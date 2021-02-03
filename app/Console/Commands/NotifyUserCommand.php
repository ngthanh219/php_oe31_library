<?php

namespace App\Console\Commands;

use App\Jobs\ProcessMailNotifyUser;
use App\Models\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:notifyUser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Mail Notify about return book before 3 days';

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
        $requests = Request::with('user', 'books')->whereNotIn('status', $statusRequest)->get();
        $users = [];
        foreach ($requests as $request) {
            $returnDate = Carbon::parse($request->return_date);
            $today = Carbon::today();
            $date = $returnDate->gt($today);
            $dayNotify = $today->diff($returnDate);

            if ($date && $dayNotify->days == config('request.notification')) {
                ProcessMailNotifyUser::dispatch($request->user->email, $request);
            }
        }
    }
}
