<?php

namespace App\Extensions;

use ImageKit\ImageKit;
use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToCheckExistence;
use League\Flysystem\UnableToCopyFile;
use League\Flysystem\UnableToCreateDirectory;
use League\Flysystem\UnableToDeleteDirectory;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToMoveFile;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToRetrieveMetadata;
use League\Flysystem\UnableToWriteFile;

class ImageKitAdapter implements FilesystemAdapter
{
    /**
     * @var ImageKit
     */
    protected $client;

    public function __construct(ImageKit $client)
    {
        $this->client = $client;
    }

    public function fileExists(string $path): bool
    {
        try {
            return $this->getFileId($path) !== null;
        } catch (\Throwable $exception) {
            throw UnableToCheckExistence::forLocation($path, $exception);
        }
    }

    public function directoryExists(string $path): bool
    {
        // ImageKit does not have explicit directories, but we can check if any file exists in the path
        try {
            $response = $this->client->listFiles([
                'path' => $path,
                'limit' => 1
            ]);

            return !empty($response->result) && count($response->result) > 0;
        } catch (\Throwable $exception) {
            throw UnableToCheckExistence::forLocation($path, $exception);
        }
    }

    public function write(string $path, string $contents, Config $config): void
    {
        $this->upload($path, $contents, $config);
    }

    public function writeStream(string $path, $resource, Config $config): void
    {
        $contents = stream_get_contents($resource);
        $this->upload($path, $contents, $config);
    }

    protected function upload(string $path, string $contents, Config $config): void
    {
        $dirname = dirname($path);
        $filename = basename($path);
        
        // ImageKit expects folder to start with /
        $folder = $dirname === '.' ? '/' : $dirname;
        if (!str_starts_with($folder, '/')) {
            $folder = '/' . $folder;
        }

        $options = [
            'file' => $contents,
            'fileName' => $filename,
            'folder' => $folder,
            'useUniqueFileName' => false,
            'overwriteFile' => true
        ];

        if ($visibility = $config->get('visibility')) {
            $options['isPrivateFile'] = $visibility === 'private';
        }

        $response = $this->client->uploadFile($options);
        
        if ($response->error) {
            throw UnableToWriteFile::atLocation($path, $response->error->message);
        }

        // CACHE THE RESULT to handle eventual consistency
        if ($response->result) {
            $cacheKey = 'imagekit_file_' . md5($path);
            // Cache for 10 minutes
            \Illuminate\Support\Facades\Cache::put($cacheKey, $response->result, 600);
            \Illuminate\Support\Facades\Log::info("DEBUG_IMAGEKIT: Cached file details for path: $path");
        }
    }

    public function read(string $path): string
    {
        $url = $this->getUrl($path);
        if (!$url) {
             throw UnableToReadFile::fromLocation($path, 'File not found');
        }
        
        $contents = file_get_contents($url);
        if ($contents === false) {
            throw UnableToReadFile::fromLocation($path, 'Could not download file');
        }
        
        return $contents;
    }

    public function readStream(string $path)
    {
        $contents = $this->read($path);
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $contents);
        rewind($stream);
        return $stream;
    }

    public function delete(string $path): void
    {
        $fileId = $this->getFileId($path);
        if ($fileId) {
            $response = $this->client->deleteFile($fileId);
            if ($response->error) {
                throw UnableToDeleteFile::atLocation($path, $response->error->message);
            }
            // Clear Cache
            $cacheKey = 'imagekit_file_' . md5($path);
            \Illuminate\Support\Facades\Cache::forget($cacheKey);
        }
    }

    public function deleteDirectory(string $path): void
    {
        // To delete a directory, we would need to delete all files in it.
        // This is a potentially heavy operation.
        // For now, we can try to list files and delete them.
        
        $response = $this->client->listFiles([
            'path' => $path
        ]);

        if ($response->error) {
            throw UnableToDeleteDirectory::atLocation($path, $response->error->message);
        }

        foreach ($response->result as $file) {
            $this->client->deleteFile($file->fileId);
        }
    }

    public function createDirectory(string $path, Config $config): void
    {
        // ImageKit creates directories implicitly
    }

    public function setVisibility(string $path, string $visibility): void
    {
        throw FilesystemException::fromLocation($path, 'Not supported');
    }

    public function visibility(string $path): FileAttributes
    {
        $details = $this->getFileDetails($path);
        $isPrivate = $details->isPrivateFile ?? false;
        return new FileAttributes($path, null, $isPrivate ? 'private' : 'public');
    }

    public function mimeType(string $path): FileAttributes
    {
        \Illuminate\Support\Facades\Log::info("DEBUG_IMAGEKIT: Checking mimeType for path: " . $path);

        try {
            $details = $this->getFileDetails($path);
            \Illuminate\Support\Facades\Log::info("DEBUG_IMAGEKIT: Details from ImageKit: " . json_encode($details));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("DEBUG_IMAGEKIT: Failed to get details: " . $e->getMessage());
            // If we can't get details, we can't do much, but let's try extension fallback anyway if possible
            $details = (object)['size' => 0, 'updatedAt' => date('Y-m-d H:i:s')];
        }
        
        // 1. Try Extension (Fastest & Most Reliable for Uploads)
        $detector = new \League\MimeTypeDetection\ExtensionMimeTypeDetector();
        $mimeType = $detector->detectMimeTypeFromPath($path, null);
        \Illuminate\Support\Facades\Log::info("DEBUG_IMAGEKIT: Extension detector result: " . ($mimeType ?? 'null'));

        // 2. Try ImageKit Metadata (If extension fails)
        if (!$mimeType || $mimeType === 'application/octet-stream') {
            $mimeType = $details->mime ?? null;
            \Illuminate\Support\Facades\Log::info("DEBUG_IMAGEKIT: ImageKit metadata result: " . ($mimeType ?? 'null'));
        }

        // 3. Fallback to generic image if we know it's an image extension but detection failed
        if ((!$mimeType || $mimeType === 'application/octet-stream') && $this->hasImageExtension($path)) {
             $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
             $mimeType = 'image/' . ($ext === 'jpg' ? 'jpeg' : $ext);
             \Illuminate\Support\Facades\Log::info("DEBUG_IMAGEKIT: Hard fallback result: " . $mimeType);
        }

        \Illuminate\Support\Facades\Log::info("DEBUG_IMAGEKIT: Final MimeType returned: " . ($mimeType ?: 'application/octet-stream'));

        return new FileAttributes(
            $path, 
            $details->size ?? 0, 
            null, 
            strtotime($details->updatedAt ?? 'now'), 
            $mimeType ?: 'application/octet-stream'
        ); 
    }

    protected function hasImageExtension(string $path): bool
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp']);
    }

    public function lastModified(string $path): FileAttributes
    {
        $details = $this->getFileDetails($path);
        return new FileAttributes($path, null, null, strtotime($details->updatedAt));
    }

    public function fileSize(string $path): FileAttributes
    {
        $details = $this->getFileDetails($path);
        return new FileAttributes($path, $details->size);
    }

    public function listContents(string $path, bool $deep): iterable
    {
        $folder = $path === '' ? '/' : $path;
        if (!str_starts_with($folder, '/')) {
            $folder = '/' . $folder;
        }

        $response = $this->client->listFiles([
            'path' => $folder
        ]);

        if ($response->error) {
            return [];
        }

        foreach ($response->result as $item) {
            // ImageKit returns files. It doesn't explicitly return subdirectories in listFiles unless we scan for them.
            // But for Flysystem, we usually just return files.
            // If we need directories, we'd have to infer them.
            
            yield new FileAttributes(
                $item->filePath,
                $item->size,
                null,
                strtotime($item->updatedAt)
            );
        }
    }

    public function move(string $source, string $destination, Config $config): void
    {
         $this->copy($source, $destination, $config);
         $this->delete($source);
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        $contents = $this->read($source);
        $this->write($destination, $contents, $config);
    }

    protected function getFileId(string $path): ?string
    {
        // Check Cache First
        $cacheKey = 'imagekit_file_' . md5($path);
        $cachedDetails = \Illuminate\Support\Facades\Cache::get($cacheKey);
        if ($cachedDetails && isset($cachedDetails->fileId)) {
            \Illuminate\Support\Facades\Log::info("DEBUG_IMAGEKIT: Cache HIT for getFileId: $path");
            return $cachedDetails->fileId;
        }

        \Illuminate\Support\Facades\Log::info("DEBUG_IMAGEKIT: Cache MISS for getFileId: $path. Querying API...");

        $dirname = dirname($path);
        $filename = basename($path);
        
        $folder = $dirname === '.' ? '/' : $dirname;
        if (!str_starts_with($folder, '/')) {
            $folder = '/' . $folder;
        }

        $response = $this->client->listFiles([
            'name' => $filename,
            'path' => $folder
        ]);

        if ($response->error || empty($response->result)) {
            return null;
        }

        foreach ($response->result as $file) {
            if ($file->name === $filename) {
                // Cache it for future use
                \Illuminate\Support\Facades\Cache::put($cacheKey, $file, 600);
                return $file->fileId;
            }
        }

        return null;
    }
    
    protected function getFileDetails(string $path)
    {
        // Check Cache First
        $cacheKey = 'imagekit_file_' . md5($path);
        $cachedDetails = \Illuminate\Support\Facades\Cache::get($cacheKey);
        if ($cachedDetails) {
            \Illuminate\Support\Facades\Log::info("DEBUG_IMAGEKIT: Cache HIT for getFileDetails: $path");
            return $cachedDetails;
        }

        $fileId = $this->getFileId($path);
        if (!$fileId) {
             throw UnableToRetrieveMetadata::create($path, 'File not found');
        }
        
        $response = $this->client->getFileDetails($fileId);
        if ($response->error) {
             throw UnableToRetrieveMetadata::create($path, $response->error->message);
        }
        
        // Cache it
        \Illuminate\Support\Facades\Cache::put($cacheKey, $response->result, 600);

        return $response->result;
    }
    
    public function getUrl(string $path): ?string
    {
        // Check Cache First
        $cacheKey = 'imagekit_file_' . md5($path);
        $cachedDetails = \Illuminate\Support\Facades\Cache::get($cacheKey);
        
        if ($cachedDetails && isset($cachedDetails->url)) {
            \Illuminate\Support\Facades\Log::info("DEBUG_IMAGEKIT: Cache HIT for getUrl: $path");
            return $cachedDetails->url;
        }

        $fileId = $this->getFileId($path);
        if (!$fileId) return null;
        
        $response = $this->client->getFileDetails($fileId);
        
        if ($response->result) {
             // Cache it
             \Illuminate\Support\Facades\Cache::put($cacheKey, $response->result, 600);
             return $response->result->url ?? null;
        }

        return null;
    }
}
