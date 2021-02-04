<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Auth;
use App\Repositories\Comment\CommentRepositoryInterface;

class CommentController extends Controller
{
    protected $commentRepo;

    public function __construct(CommentRepositoryInterface $commentRepo)
    {
        $this->commentRepo = $commentRepo;
    }

    public function store(CommentRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $this->commentRepo->create($data);

        return response()->json([
            'user_name' => Auth::user()->name,
            'comment' => $data['comment'],
            'status' => trans('comment.success'),
        ]);
    }
}
