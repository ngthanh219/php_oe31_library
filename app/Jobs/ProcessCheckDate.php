<?php

namespace App\Jobs;

use App\Models\Request;
use App\Repositories\Book\BookRepositoryInterface;
use App\Repositories\Request\RequestRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCheckDate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(RequestRepositoryInterface $requestRepo, BookRepositoryInterface $bookRepo, UserRepositoryInterface $userRepo)
    {
        if ($this->request->status === config('request.pending') || $this->request->status == config('request.accept')) {
            $req = $requestRepo->withFind($this->request->id, ['books']);
            foreach ($req->books as $book) {    
                $result = $bookRepo->update($book->id, [
                    'in_stock' => $book->in_stock + config('book.book'),
                ]);
            }
            $result = $requestRepo->update($this->request->id, [
                'status' => config('request.forget'),
            ]);
        } else {
            $result = $userRepo->update($this->request->user->id, [
                'status' => $this->request->user->status + config('user.add_status'),
            ]);
            $result = $requestRepo->update($this->request->id, [
                'status' => config('request.late'),
            ]);
        }
        
        return $result;
    }
}
