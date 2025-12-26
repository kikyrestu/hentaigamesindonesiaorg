<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->disabled()
                    ->maxLength(255),
                
                Forms\Components\TextInput::make('value_text')
                    ->label('Value')
                    ->statePath('value')
                    ->visible(fn ($record) => $record?->type === 'text' || $record?->type === null),

                Forms\Components\Textarea::make('value_textarea')
                    ->label('Value')
                    ->statePath('value')
                    ->visible(fn ($record) => $record?->type === 'textarea'),

                Forms\Components\FileUpload::make('value_image')
                    ->label('Image')
                    ->statePath('value')
                    ->image()
                    ->directory('settings')
                    ->visible(fn ($record) => $record?->type === 'image'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', ucfirst($state))),
                Tables\Columns\TextColumn::make('value')
                    ->limit(50),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('sitemap')
                    ->label('View Sitemap')
                    ->url(fn () => route('sitemap'))
                    ->openUrlInNewTab(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // No delete for settings
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteSettings::route('/'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
