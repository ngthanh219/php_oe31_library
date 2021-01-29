<?php

namespace Tests\Unit\Notifications;

use App\Models\User;
use App\Notifications\PasswordReset;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    protected $token, $notification;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->token = 'tokentest';
        $this->user = factory(User::class)->make();
        $this->notification = new PasswordReset($this->token);
    }

    public function tearDown(): void
    {
        unset($this->token);
        unset($this->notification);
        unset($this->user);

        parent::tearDown();
    }

    public function test_via_mail()
    {
        $this->assertEquals(['mail'], $this->notification->via(User::class));
    }

    public function test_to_mail()
    {
        Notification::fake();
        $this->user->notify(new PasswordReset($this->token));
        $this->notification->toMail(User::class);
        Notification::assertSentTo(
            [$this->user], PasswordReset::class
        );
    }
}
