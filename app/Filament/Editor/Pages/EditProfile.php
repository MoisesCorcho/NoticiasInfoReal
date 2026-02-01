<?php

namespace App\Filament\Editor\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                FileUpload::make('image')
                    ->label('Imagen de Perfil')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                    ->directory('users/images')
                    ->avatar()
                    ->imageEditor()
                    ->circleCropper(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
