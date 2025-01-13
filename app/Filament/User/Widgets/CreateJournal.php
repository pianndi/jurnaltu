<?php

namespace App\Filament\User\Widgets;

use Filament\Widgets\Widget;

class CreateJournal extends Widget
{
    protected static string $view = 'filament.user.widgets.create-journal';

    public function __construct()
    {
        $this->viewData([
            'title' => 'Buat Jurnal',
            'description' => 'Buat jurnal baru',
        ]);
    }
}
