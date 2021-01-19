<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PublishersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\PublisherRequest;
use App\Models\Publisher;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('publisher.index')) {
            $publishers = Publisher::orderBy('id', 'DESC')->paginate(config('pagination.limit_page'));

            return view('admin.publisher.index', compact('publishers'));
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->can('publisher.create')) {
            return view('admin.publisher.create');
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PublisherRequest $request)
    {
        if (Auth::user()->can('publisher.store')) {
            $publisher = new Publisher;
            $image = '';
            if ($request->hasFile('image')) {
                $file = $request->image;
                $file->move('upload/publisher', $file->getClientOriginalName());
                $image = $file->getClientOriginalName();
            }
            $result = $publisher->create([
                'image' => $image,
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'phone' => $request->phone,
                'description' => $request->description,
            ]);
            if ($result) {
                return redirect()->route('admin.publishers.index')->with('infoMessage',
                    trans('message.publisher_create_success'));
            }

            return redirect()->route('admin.publishers.index')->with('infoMessage',
                trans('message.publisher_create_fail'));
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->can('publisher.edit')) {
            $publisher = Publisher::findOrFail($id);

            return view('admin.publisher.edit', compact('publisher'));
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PublisherRequest $request, $id)
    {
        if (Auth::user()->can('publisher.update')) {
            $publisher = Publisher::findOrFail($id);
            $image = $publisher->image;
            if ($request->hasFile('image')) {
                $file = $request->image;
                $file->move('upload/publisher', $file->getClientOriginalName());
                $image = $file->getClientOriginalName();
            }
            $result = $publisher->update([
                'name' => $request->name,
                'image' => $image,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'description' => $request->description,
            ]);
            if ($result) {
                return redirect()->route('admin.publishers.index')->with('infoMessage',
                    trans('message.publisher_update_success'));
            }

            return redirect()->route('admin.publishers.index')->with('infoMessage',
                trans('message.publisher_update_fail'));
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->can('publisher.destroy')) {
            $publisher = Publisher::findOrFail($id)->load('books');
            if (!$publisher->books->isEmpty()) {
                return redirect()->route('admin.publishers.index')->with('infoMessage',
                    trans('message.publisher_has_books'));
            }
            $result = $publisher->delete();
            if ($result) {
                return redirect()->route('admin.publishers.index')->with('infoMessage',
                    trans('message.publisher_delete_success'));
            }

            return redirect()->route('admin.publishers.index')->with('infoMessage',
                trans('message.publisher_delete_fail'));
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    public function export()
    {
        if (Auth::user()->can('publisher.export')) {
            $item = new PublishersExport;
            if ($item->view()) {
                return Excel::download(new PublishersExport, 'publishers.xlsx');
            }

            return redirect()->back()->with('infoMessage',
                trans('message.publisher_no_data'));
        }

        abort(Response::HTTP_FORBIDDEN);
    }
}
