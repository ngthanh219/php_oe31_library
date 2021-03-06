<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorRequest;
use App\Repositories\Author\AuthorRepositoryInterface;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    protected $authorRepo, $bookRepo;

    public function __construct(
        AuthorRepositoryInterface $authorRepo
    ) {
       $this->authorRepo = $authorRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authors = $this->authorRepo->getAll();

        return view('admin.author.index', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.author.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthorRequest $request)
    {
        $image = '';
        if ($request->hasFile('image')) {
            $file = $request->image;
            $file->move('upload/author', $file->getClientOriginalName());
            $image = $file->getClientOriginalName();
        }

        $result = $this->authorRepo->create([
            'name' => $request->name,
            'address' => $request->address,
            'image' => $image,
            'date_of_born' => $request->date_of_born,
            'date_of_death' => $request->date_of_death,
        ]);

        if ($result) {
            return redirect()->route('admin.authors.index')->with('infoMessage',
                trans('message.author_create_success'));
        }

        return redirect()->route('admin.authors.index')->with('infoMessage',
            trans('message.author_create_fail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $author = $this->authorRepo->find($id);

        return view('admin.author.edit', compact('author'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AuthorRequest $request, $id)
    {
        $author = $this->authorRepo->find($id);
        $image = $author->image;
        if ($request->hasFile('image')) {
            $file = $request->image;
            $file->move('upload/author', $file->getClientOriginalName());
            $image = $file->getClientOriginalName();
        }

        $result = $this->authorRepo->update($id, [
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image,
            'date_of_born' => $request->date_of_born,
            'date_of_death' => $request->date_of_death,
        ]);

        if ($result) {
            return redirect()->route('admin.authors.index')->with('infoMessage',
                trans('message.author_update_success'));
        }

        return redirect()->route('admin.authors.index')->with('infoMessage',
            trans('message.author_update_fail'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $author = $this->authorRepo->getRelatedBook($id);

        if (!$author->books->isEmpty()) {
            return redirect()->route('admin.authors.index')->with('infoMessage',
                trans('message.author_has_books'));
        }

        $result = $this->authorRepo->destroy($id);
        
        if ($result) {
            return redirect()->route('admin.authors.index')->with('infoMessage',
                trans('message.author_delete_success'));
        }

        return redirect()->route('admin.authors.index')->with('infoMessage',
            trans('message.author_delete_fail'));
    }
}
