<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\GameResource\Pages;
use App\Filament\Admin\Resources\GameResource\RelationManagers;
use App\Models\Game;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GameResource extends Resource
{
    protected static ?string $model = Game::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Info')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('author')
                            ->label('Uploader/Author')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('developer')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Details')
                    ->schema([
                        Forms\Components\TextInput::make('version')
                            ->maxLength(255),
                        Forms\Components\Select::make('censorship')
                            ->options([
                                'Censored' => 'Censored',
                                'Uncensored' => 'Uncensored',
                            ]),
                        Forms\Components\TextInput::make('language')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('platform')
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('release_date'),
                        Forms\Components\Toggle::make('is_hot')
                            ->label('Hot Game')
                            ->inline(false),
                    ])->columns(3),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('cover_image')
                            ->image()
                            ->directory('games/covers'),
                        Forms\Components\FileUpload::make('thumbnail_image')
                            ->image()
                            ->directory('games/thumbnails'),
                        Forms\Components\FileUpload::make('gallery_images')
                            ->image()
                            ->multiple()
                            ->directory('games/gallery')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Content')
                    ->schema([
                        Forms\Components\RichEditor::make('description')
                            ->label('General Description (Info Tab)')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('system_requirements')
                            ->label('System Requirements (Desktop Tab)')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('installation_guide')
                            ->label('Installation Guide (Question Tab)')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('password')
                            ->default('kimochi.info')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('download_content')
                            ->label('Download Section Content (Download Tab)')
                            ->helperText('Use this to add download buttons, mirrors, etc.')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('download_link')
                            ->label('Main Download Link (Fallback)')
                            ->url()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('buy_link')
                            ->url()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->helperText('Link to official store (Steam, Dlsite, etc.)'),
                    ]),

                Forms\Components\Section::make('SEO Settings')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->maxLength(255)
                            ->placeholder('Custom Meta Title (Optional)'),
                        Forms\Components\Textarea::make('meta_description')
                            ->rows(3)
                            ->placeholder('Custom Meta Description (Optional)'),
                        Forms\Components\Textarea::make('meta_keywords')
                            ->rows(2)
                            ->placeholder('keyword1, keyword2, keyword3'),
                    ])->collapsed(),

                Forms\Components\Section::make('Relationships')
                    ->schema([
                        Forms\Components\Select::make('categories')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Forms\Set $set, $state) => $set('slug', \Illuminate\Support\Str::slug($state))),
                                Forms\Components\TextInput::make('slug')->required(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_image')
                    ->disk('imagekit'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('author')
                    ->searchable(),
                Tables\Columns\TextColumn::make('version'),
                Tables\Columns\TextColumn::make('release_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('categories.name')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_hot')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGames::route('/'),
            'create' => Pages\CreateGame::route('/create'),
            'edit' => Pages\EditGame::route('/{record}/edit'),
        ];
    }
}
