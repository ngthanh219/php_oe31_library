<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Rate;
use Auth;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function react($bookId)
    {
        $user = Auth::user();
        $likes = Like::where('user_id', $user->id)->where('book_id', $bookId)->first();
        $countOfLikeInBook = Like::where('book_id', $bookId)->get()->count();
        if (!$likes) {
            $item = Like::create([
                'user_id' => $user->id,
                'book_id' => $bookId,
                'status' => config('like.liked'),
            ]);

            return response()->json([
                'count' => $countOfLikeInBook + config('like.liked'),
                'like' => true,
            ]);
        } else {
            if ($likes->status == config('like.liked')) {
                $likes->update([
                    'user_id' => $user->id,
                    'book_id' => $bookId,
                    'status' => null,
                ]);

                return response()->json([
                    'count' => $countOfLikeInBook,
                    'like' => false,
                ]);
            } else if ($likes->status == null) {
                $likes->update([
                    'user_id' => $user->id,
                    'book_id' => $bookId,
                    'status' => config('like.liked'),
                ]);

                return response()->json([
                    'count' => $countOfLikeInBook,
                    'like' => true,
                ]);
            }
        }
    }

    public function vote(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $votes = Rate::where('user_id', $data['user_id'])->where('book_id', $data['book_id'])->first();
        if (!$votes) {
            Rate::create($data);

            return response()->json([
                'message' => trans('rate.voted_success'),
            ]);
        } else {
            $votes->update($data);

            return response()->json([
                'message' => trans('rate.voted_success'),
            ]);
        }
    }
}
