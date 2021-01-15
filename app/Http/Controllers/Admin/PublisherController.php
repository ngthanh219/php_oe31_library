<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PublishersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\PublisherRequest;
use App\Models\Publisher;
use App\Repositories\Publisher\PublisherRepositoryInterface;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PublisherController extends Controller
{
    protected $publisherRepository;

    public function __construct(PublisherRepositoryInterface $publisherRepository)
    {
        $this->publisherRepository = $publisherRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('publisher.index')) {
            abort(Response::HTTP_FORBIDDEN);
        }
        $publishers = $this->publisherRepository->getAll();

        return view('admin.publisher.index', compact('publishers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('publisher.create')) {
            abort(Response::HTTP_FORBIDDEN);
        }

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
        if (!Auth::user()->can('publisher.store')) {
            abort(Response::HTTP_FORBIDDEN);
        }
        $publisher = new Publisher;
        $image = '';
        if ($request->hasFile('image')) {
            $file = $request->image;
            $file->move('upload/publisher', $file->getClientOriginalName());
            $image = $file->getClientOriginalName();
        }

        $result = $this->publisherRepository->create([
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
        if (!Auth::user()->can('publisher.edit')) {
            abort(Response::HTTP_FORBIDDEN);
        }
        $publisher = $this->publisherRepository->find($id);

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
        if (!Auth::user()->can('publisher.update')) {
            abort(Response::HTTP_FORBIDDEN);
        }
        $publisher = $this->publisherRepository->find($id);
        $image = $publisher->image;
        if ($request->hasFile('image')) {
            $file = $request->image;
            $file->move('upload/publisher', $file->getClientOriginalName());
            $image = $file->getClientOriginalName();
        }
        $result = $this->publisherRepository->update($id, [
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
        if (!Auth::user()->can('publisher.destroy')) {
            abort(Response::HTTP_FORBIDDEN);
        }
        $publisher = $this->publisherRepository->loadBook($id);
        if (!$publisher->books->isEmpty()) {
            return redirect()->route('admin.publishers.index')->with('infoMessage',
                trans('message.publisher_has_books'));
        }
        $result = $this->publisherRepository->destroy($id);
        if ($result) {
            return redirect()->route('admin.publishers.index')->with('infoMessage',
                trans('message.publisher_delete_success'));
        }

        return redirect()->route('admin.publishers.index')->with('infoMessage',
            trans('message.publisher_delete_fail'));
    }

    public function export()
    {
        if (!Auth::user()->can('publisher.export')) {
            abort(Response::HTTP_FORBIDDEN);
        }
        $item = new PublishersExport;
        if ($item->view()) {
            return $this->publisherRepository->export($item, 'publishers.xlsx');
        }

        return redirect()->back()->with('infoMessage',
            trans('message.publisher_no_data'));
    }
}
