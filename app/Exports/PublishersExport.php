<?php

namespace App\Exports;

use App\Models\Publisher;
use Maatwebsite\Excel\Concerns\FromCollection;

class PublishersExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $publishers = Publisher::all(['name', 'email', 'phone', 'address', 'description']);
        if ($publishers->isEmpty()) {
            return false;
        }

        return $publishers;
    }
}
