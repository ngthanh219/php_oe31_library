<?php

namespace App\Console\Commands;

use App\Jobs\ProcessCheckDate;
use App\Models\Request;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckDateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check date and update status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $statusRequest = [
            config('request.reject'),
            config('request.return'),
            config('request.late'),
            config('request.forget'),
        ];
        $requests = Request::with('user')->whereNotIn('status', $statusRequest)->get();
        foreach ($requests as $request) {
            $returnDate = Carbon::parse($request->return_date);
            $today = Carbon::today();
            $date = $returnDate->lt($today);

            if ($date) {
                ProcessCheckDate::dispatch($request);
            }
        }
    }
}
