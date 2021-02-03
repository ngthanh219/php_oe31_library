<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\PopupCategoryRequest;
use App\Repositories\Category\CategoryRepositoryInterface;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    protected $categoryRepo;

    public function __construct(
        CategoryRepositoryInterface $categoryRepo
    ) {
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('category.index')) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $categories = $this->categoryRepo->getParentOrderBy();

        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('category.create')) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $categoryParents = $this->categoryRepo->getParentOrderBy();

        return view('admin.category.create', compact('categoryParents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        if (!Auth::user()->can('category.store')) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $parentId = (isset($request->parent_id)) ? $request->parent_id : config('category.parent_id');
        $result = $this->categoryRepo->create([
            'name' => $request->name,
            'parent_id' => $parentId,
        ]);

        if ($result) {
            return redirect()->route('admin.categories.index')->with('infoMessage',
                trans('message.category_create_success'));
        }

        return redirect()->route('admin.categories.index')->with('infoMessage',
            trans('message.category_create_fail'));
    }

    public function apiStore(PopupCategoryRequest $request)
    {
        if (!Auth::user()->can('category.apiStore')) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $parent = $this->categoryRepo->create([
            'name' => $request->parent_name,
            'parent_id' => config('category.parent_id'),
        ]);
        $child = $this->categoryRepo->create([
            'name' => $request->child_name,
            'parent_id' => $parent['id'],
        ]);

        return response()->json([
            'dataChild' => $child,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::user()->can('category.show')) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $category = $this->categoryRepo->withFind($id, ['children']);

        if (!$category) {
            return redirect()->route('admin.categories.index')->with('infoMessage',
                trans('message.category_not_valid'));
        }

        return view('admin.category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('category.edit')) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $category = $this->categoryRepo->find($id);
        $categories = $this->categoryRepo->getAll();

        return view('admin.category.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        if (!Auth::user()->can('category.update')) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $category = $this->categoryRepo->find($id);
        $parentId = (isset($request->parent_id)) ? $request->parent_id : config('category.parent_id');
        $result = $this->categoryRepo->update($id, [
            'name' => $request->name,
            'parent_id' => $parentId,
        ]);
        $parent = $this->categoryRepo->load($category, 'parent');

        if ($result) {
            if (isset($parent->parent)) {
                return redirect()->route('admin.categories.show', $parent->parent['id'])->with('infoMessage',
                    trans('message.category_update_success'));
            }

            return redirect()->route('admin.categories.index')->with('infoMessage',
                trans('message.category_update_success'));
        }

        return redirect()->back()->with('infoMessage',
            trans('message.category_update__fail'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->can('category.destroy')) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $category = $this->categoryRepo->find($id);

        if ($category->parent_id === config('category.parent_id')) {
            $children = $this->categoryRepo->load($category, 'children');
            if (!$children->children->isEmpty()) {
                return redirect()->back()->with('infoMessage',
                    trans('message.category_has_children'));
            }
        }

        $result = $this->categoryRepo->destroy($id);

        if ($result) {
            return redirect()->back()->with('infoMessage',
                trans('message.category_delete_success'));
        }

        return redirect()->route('admin.categories.index')->with('infoMessage',
            trans('message.category_delete__fail'));
    }
}
