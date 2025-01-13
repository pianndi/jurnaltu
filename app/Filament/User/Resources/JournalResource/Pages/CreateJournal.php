<?php

namespace App\Filament\User\Resources\JournalResource\Pages;

use App\Filament\User\Resources\JournalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJournal extends CreateRecord
{
    protected static string $resource = JournalResource::class;
}
