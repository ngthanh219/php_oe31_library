<?php

namespace App\Repositories\Notification;

use App\Repositories\BaseRepository;
use Auth;
use App\Models\Notification;
use DB;

class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{
    public function getModel()
    {
        return Notification::class;
    }

    public function getNotificationByDB()
    {
        return DB::table('notifications')
            ->where('notifiable_id', Auth::user()->id)
            ->where('read_at', null)
            ->orderBy('updated_at', 'asc')
            ->get();
    }
}
