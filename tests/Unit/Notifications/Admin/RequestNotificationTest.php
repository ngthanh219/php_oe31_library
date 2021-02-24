<?php

namespace Tests\Unit\Notifications\Admin;

use PHPUnit\Framework\TestCase;
use App\Notifications\Admin\RequestNotification;
use Illuminate\Support\Str;
use App\Models\User;

class RequestNotificationTest extends TestCase
{
    protected $notification, $data;

    public function setUp(): void
    {
        parent::setUp();
        $this->data = [
            'request_id' => 1,
            'nameUser' => 'admin',
            'content' => 'yeu cau muon sach'
        ];
        $this->notification = new RequestNotification($this->data);
    }

    public function tearDown(): void
    {
        unset($this->data);
        unset($this->notification);
        parent::tearDown();
    }

    public function test_via_method()
    {
        $this->assertEquals(['database'], $this->notification->via(User::class));
    }

    public function test_toArray_method()
    {
        $this->assertEquals($this->data, $this->notification->toArray(User::class));
    }
}
