<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use App\Models\Setting as SettingModel;
use Filament\Notifications\Notification;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.settings';

    protected static ?string $navigationGroup = 'System';
    protected static ?int $navigationSort = 10;

    public ?array $data = [];

    public function mount(): void
    {
        $setting = SettingModel::firstOrCreate([], [
            'site_name' => 'BD Energy Transition',
            'theme_color' => '#5b21b6',
        ]);
        $this->form->fill($setting->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Information')
                    ->description('Manage your site identity and theme.')
                    ->schema([
                        TextInput::make('site_name')
                            ->required()
                            ->maxLength(255),
                        ColorPicker::make('theme_color')
                            ->required(),
                        FileUpload::make('favicon')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios(['1:1'])
                            ->maxSize(1024)
                            ->helperText('Upload a square image. Recommended size: 32x32px or 64x64px. Max size: 1MB.')
                            ->disk('public')
                            ->visibility('public')
                            ->directory('settings'),
                        FileUpload::make('logo')
                            ->image()
                            ->imageEditor()
                            ->maxSize(2048)
                            ->helperText('Upload your site logo. Recommended format: PNG with transparent background. Max size: 2MB.')
                            ->disk('public')
                            ->visibility('public')
                            ->directory('settings'),
                        Textarea::make('footer_text')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $setting = SettingModel::firstOrCreate([], [
            'site_name' => 'BD Energy Transition',
            'theme_color' => '#5b21b6',
        ]);
        $setting->update($this->form->getState());

        Notification::make()
            ->success()
            ->title('Settings saved successfully')
            ->send();
    }
}
