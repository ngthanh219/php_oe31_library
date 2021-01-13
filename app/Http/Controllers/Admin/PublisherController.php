<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PublishersExport;
use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use App\Http\Requests\PublisherRequest;
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
        $publishers = Publisher::orderBy('id', 'DESC')->get();

        return view('admin.publisher.index', compact('publishers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.publisher.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PublisherRequest $request)
    {
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $publisher = Publisher::findOrFail($id);

        return view('admin.publisher.edit', compact('publisher'));
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
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

    public function export()
    {
        $item = new PublishersExport;
        if ($item->view()) {
            return Excel::download(new PublishersExport, 'publishers.xlsx');
        }

        return redirect()->back()->with('infoMessage',
            trans('message.publisher_no_data'));
    }
}
