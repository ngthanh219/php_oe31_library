<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Auth;

class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        Comment::create($data);

        return response()->json([
            'user_name' => Auth::user()->name,
            'comment' => $data['comment'],
            'status' => trans('comment.success'),
        ]);
    }
}
