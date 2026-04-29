<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimelineEventResource\Pages;
use App\Filament\Resources\TimelineEventResource\RelationManagers;
use App\Models\TimelineEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TimelineEventResource extends Resource
{
    protected static ?string $model = TimelineEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('icon_id')
                    ->label('Icon')
                    ->relationship('icon', 'name')
                    ->preload()
                    ->searchable()
                    ->nullable(),
                Forms\Components\DatePicker::make('event_date')
                    ->required()
                    ->displayFormat('d F Y')
                    ->native(false)
                    ->columnSpanFull(),
                
                Forms\Components\Section::make('Global Event')
                    ->schema([
                        Forms\Components\TextInput::make('global_event_title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('global_event_link')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('global_event_excerpt')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Bangladesh Impact')
                    ->schema([
                        Forms\Components\TextInput::make('impact_title')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('impact_link')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('impact_excerpt')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Toggle::make('is_published')
                    ->required()
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('icon.image')
                    ->label('Icon'),
                Tables\Columns\TextColumn::make('event_date')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('global_event_title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('impact_title')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
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
            'index' => Pages\ListTimelineEvents::route('/'),
            'create' => Pages\CreateTimelineEvent::route('/create'),
            'edit' => Pages\EditTimelineEvent::route('/{record}/edit'),
        ];
    }
}
