<?php

namespace Tests\Unit\Jobs;

use App\Jobs\ProcessMailNotifyUser;
use App\Mail\UserNotification;
use App\Models\Book;
use App\Models\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProcessMailNotifyUserTest extends TestCase
{
    protected $user, $request, $book;
    protected $job;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->make();
        $this->request = factory(Request::class)->make();
        $this->book = factory(Book::class)->make();
        $this->request->setRelation('books', $this->book);
        $this->request->setRelation('user', $this->user);
        $this->job = new ProcessMailNotifyUser($this->user->email, $this->request);
    }

    public function tearDown(): void
    {
        unset($this->user);
        unset($this->request);
        unset($this->book);
        unset($this->request);
        unset($this->job);

        parent::tearDown();
    }

    public function test_handle_send_mail()
    {
        Queue::fake();
        Mail::fake();
        Queue::push(UserNotification::class);
        Mail::to($this->user->email)->send(new UserNotification($this->request->user, $this->request));
        $this->job->handle();
        Mail::assertSent(UserNotification::class, function ($mail) {
            return $mail->hasTo($this->user->email, $this->request);
        });
        Queue::assertPushed(UserNotification::class, 1);
    }
}
