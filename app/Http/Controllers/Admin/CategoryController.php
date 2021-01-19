<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\PopupCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('category.index')) {
            $categories = Category::where('parent_id', config('category.parent_id'))->orderBy('id', 'DESC')->paginate(config('pagination.limit_page'));

            return view('admin.category.index', compact('categories'));
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
        if (Auth::user()->can('category.create')) {
            $categoryParents = Category::where('parent_id', config('category.parent_id'))->get();

            return view('admin.category.create', compact('categoryParents'));
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        if (Auth::user()->can('category.store')) {
            $category = new Category;
            $parentId = (isset($request->parent_id)) ? $request->parent_id : config('category.parent_id');
            $result = $category->create([
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

        abort(Response::HTTP_FORBIDDEN);
    }

    public function apiStore(PopupCategoryRequest $request)
    {
        if (Auth::user()->can('category.apiStore')) {
            $category = new Category;
            $parent = $category->create([
                'name' => $request->parent_name,
                'parent_id' => config('category.parent_id'),
            ]);
            $child = $category->create([
                'name' => $request->child_name,
                'parent_id' => $parent['id'],
            ]);

            return response()->json([
                'dataChild' => $child,
            ]);
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Auth::user()->can('category.show')) {
            $category = Category::findOrFail($id);
            if ($category->parent_id === config('category.parent_id')) {
                $category->load('children');

                return view('admin.category.show', compact('category'));
            }
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
        if (Auth::user()->can('category.edit')) {
            $category = Category::findOrFail($id);
            $categories = Category::where('parent_id', config('category.parent_id'))->with('children')->get();

            return view('admin.category.edit', compact('category', 'categories'));
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
    public function update(CategoryRequest $request, $id)
    {
        if (Auth::user()->can('category.update')) {
            $category = Category::findOrFail($id);
            $parentId = (isset($request->parent_id)) ? $request->parent_id : config('category.parent_id');
            $result = $category->update([
                'name' => $request->name,
                'parent_id' => $parentId,
            ]);
            $parent = $category->load('parent');
            if ($result) {
                if (isset($parent->parent)) {
                    return redirect()->route('admin.categories.show', $parent['parent']->id)->with('infoMessage',
                        trans('message.category_update_success'));
                }

                return redirect()->route('admin.categories.index')->with('infoMessage',
                    trans('message.category_update_success'));
            }

            return redirect()->back()->with('infoMessage',
                trans('message.category_update__fail'));
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
        if (Auth::user()->can('category.destroy')) {
            $category = Category::findOrFail($id);
            if ($category->parent_id === config('category.parent_id')) {
                $children = $category->load('children');
                if (!$children->children->isEmpty()) {
                    return redirect()->back()->with('infoMessage',
                        trans('message.category_has_children'));
                }
            }
            $result = $category->delete();
            if ($result) {
                return redirect()->back()->with('infoMessage',
                    trans('message.category_delete_success'));
            }

            return redirect()->route('admin.categories.index')->with('infoMessage',
                trans('message.category_delete__fail'));
        }

        abort(Response::HTTP_FORBIDDEN);
    }
}
