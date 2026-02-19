<?php

namespace App\Filament\Resources\Visits\Pages;

use App\Filament\Resources\Visits\VisitResource;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Attributes\On;

class CreateVisit extends CreateRecord
{
    protected static string $resource = VisitResource::class;

    #[On('set-image-location')]
    public function setImageLocation($latitude, $longitude): void
    {
        $this->data['image_latitude'] = $latitude;
        $this->data['image_longitude'] = $longitude;
    }
}
