<?php

namespace Tests\Unit\Mail;

use App\Mail\UserNotification;
use App\Models\Book;
use App\Models\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserNotificationTest extends TestCase
{
    protected $user, $request, $book;
    protected $mail;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->make();
        $this->request = factory(Request::class)->make();
        $this->book = factory(Book::class)->make();
        $this->request->setRelation('books', $this->book);
        $this->mail = new UserNotification($this->user, $this->request);
    }

    public function tearDown(): void
    {
        unset($this->user);
        unset($this->request);
        unset($this->book);
        unset($this->request);
        unset($this->mail);

        parent::tearDown();
    }

    public function test_build_send_mail_markdown()
    {
        Mail::fake();
        Mail::send($this->mail);
        Mail::assertSent(UserNotification::class, function ($mail) {
            $mail->build();
            $this->assertEquals($mail->viewData['user'], $this->user);
            $this->assertEquals($mail->viewData['request'], $this->request);
            $this->assertEquals($mail->viewData['url'], route('home'));

            return true;
        });
    }
}
