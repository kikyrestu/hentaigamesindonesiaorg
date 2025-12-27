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
        $details = $this->getFileDetails($path);
        
        $detector = new \League\MimeTypeDetection\ExtensionMimeTypeDetector();
        $mimeType = $detector->detectMimeTypeFromPath($path, null);

        return new FileAttributes(
            $path, 
            $details->size, 
            null, 
            strtotime($details->updatedAt), 
            $mimeType ?: 'application/octet-stream'
        ); 
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
                return $file->fileId;
            }
        }

        return null;
    }
    
    protected function getFileDetails(string $path)
    {
        $fileId = $this->getFileId($path);
        if (!$fileId) {
             throw UnableToRetrieveMetadata::create($path, 'File not found');
        }
        
        $response = $this->client->getFileDetails($fileId);
        if ($response->error) {
             throw UnableToRetrieveMetadata::create($path, $response->error->message);
        }
        
        return $response->result;
    }
    
    protected function getUrl(string $path): ?string
    {
        $fileId = $this->getFileId($path);
        if (!$fileId) return null;
        
        $response = $this->client->getFileDetails($fileId);
        return $response->result->url ?? null;
    }
}
