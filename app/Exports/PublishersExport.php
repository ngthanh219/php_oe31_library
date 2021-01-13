<?php

namespace App\Exports;

use App\Models\Publisher;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PublishersExport implements FromView
{
    public function view(): View
    {
        $publishers = Publisher::all(['name', 'email', 'phone', 'address', 'description']);
        if ($publishers->isEmpty()) {
            return false;
        }

        return view('admin.export.publishers', [
            'publishers' => $publishers,
        ]);
    }

}
