<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Repositories\Like\LikeRepositoryInterface;
use App\Repositories\Rate\RateRepositoryInterface;
use Auth;
use Illuminate\Http\Request;
use Session;

class ReactionController extends Controller
{
    protected $likeRepo, $rateRepo;

    public function __construct(
        LikeRepositoryInterface $likeRepo,
        RateRepositoryInterface $rateRepo
    ) {
        $this->likeRepo = $likeRepo;
        $this->rateRepo = $rateRepo;
    }

    public function react($bookId)
    {
        $user = Auth::user();
        $likes = $this->likeRepo->getLikeForUser($user->id, $bookId);
        $countOfLikeInBook = $this->likeRepo->countOfLikeInBook($user->id, $bookId);

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
        $data['user_id'] = Auth::id();
        $votes = $this->rateRepo->getRateForUser($data['user_id'], $data['book_id']);

        if (!$votes) {
            $this->rateRepo->create($data);

            return response()->json([
                'message' => trans('rate.voted_success'),
            ]);
        } else {
            $this->rateRepo->update($votes->id, $data);

            return response()->json([
                'message' => trans('rate.voted_success'),
            ]);
        }
    }

    public function changeLanguage(Request $request)
    {
        $lang = $request->language;
        if ($lang != 'en' && $lang != 'vi') {
            $lang = config('app.locale');
        }
        Session::put('language', $lang);

        return redirect()->back();
    }
}
