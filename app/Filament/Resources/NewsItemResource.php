<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsItemResource\Pages;
use App\Models\NewsItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;

class NewsItemResource extends Resource
{
    protected static ?string $model = NewsItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('excerpt')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('src_link')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('feature_image')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '16:9',
                    ])
                    ->helperText('Recommended: crop to 16:9 (e.g., 1920×1080) for best hero display.')
                    ->disk('public')
                    ->visibility('public'),
                Forms\Components\TextInput::make('author')
                    ->maxLength(255),
                Forms\Components\Select::make('categories')
                    ->multiple()
                    ->relationship('categories', 'name')
                    ->preload(),
                Forms\Components\Select::make('tags')
                    ->multiple()
                    ->relationship('tags', 'name')
                    ->preload(),
                Forms\Components\Toggle::make('is_published')
                    ->required()
                    ->default(true),
                Forms\Components\Toggle::make('is_hero')
                    ->label('Show in Hero')
                    ->helperText('If enabled, this news item may appear in the homepage hero slider.')
                    ->default(false),
                Forms\Components\DateTimePicker::make('published_at')
                    ->seconds(false)
                    ->default(now())
                    ->required(fn (Get $get): bool => (bool) $get('is_published')),
                Forms\Components\Toggle::make('is_featured')
                    ->label('Show in Featured')
                    ->helperText('If enabled, this news item may appear in the featured slider shown after Expert Opinions.')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('feature_image'),
                Tables\Columns\TextColumn::make('author')
                    ->searchable(),
                Tables\Columns\TextColumn::make('categories.name')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tags.name')
                    ->badge()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_hero')
                    ->label('Hero')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_hero')
                    ->label('Show in Hero'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Show in Featured'),
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
            'index' => Pages\ListNewsItems::route('/'),
            'create' => Pages\CreateNewsItem::route('/create'),
            'edit' => Pages\EditNewsItem::route('/{record}/edit'),
        ];
    }
}
