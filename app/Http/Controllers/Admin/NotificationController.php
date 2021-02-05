<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Notification\NotificationRepositoryInterface;
use Auth;
use Carbon\Carbon;

class NotificationController extends Controller
{
    protected $notificationRepo;

    public function __construct(NotificationRepositoryInterface $notificationRepo)
    {
        $this->notificationRepo = $notificationRepo;
    }

    public function index()
    {
        $notification = $this->notificationRepo->getNotificationByDB();

        return response()->json([
            'currentUser' => Auth::user()->id,
            'data' => $notification,
        ]);
    }

    public function detailNotification($id)
    {
        $notification = $this->notificationRepo->find($id);
        $request = json_decode($notification->data, true);
        $notification->update([
            'read_at' => Carbon::now(),
        ]);

        return redirect()->route('admin.request-detail', $request['request_id']);
    }

    public function apiGetUser()
    {
        return response()->json([
            'user_id' => Auth::id(),
        ]);
    }
}
