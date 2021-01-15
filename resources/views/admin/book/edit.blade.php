@extends('admin.layout')
@section('index')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ trans('book.books_manager') }}</h1>
            <ol class="breadcrumb">
                <li>{{ trans('book.menu') }}</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#settings" data-toggle="tab" aria-expanded="true">{{ trans('book.name_form') }}</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="settings">
                                <form class="form-horizontal" action="{{ route('admin.books.update', $book->id) }}"
                                    method="post" enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf
                                    <div class="col-md-12">
                                        <div class="col-md-9 col-9-form general">
                                            <div class="form-group">
                                                <label class="label-general">
                                                    {{ trans('book.status') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select name="status" class="form-control select2 general">
                                                    @if ($book->status == config('book.visible'))
                                                        <option value="{{ config('book.visible') }}" selected>
                                                            {{ trans('book.visible') }}</option>
                                                        <option value="{{ config('book.hidden') }}">
                                                            {{ trans('book.hidden') }}</option>
                                                    @else
                                                        <option value="{{ config('book.visible') }}">
                                                            {{ trans('book.visible') }}</option>
                                                        <option value="{{ config('book.hidden') }}" selected>
                                                            {{ trans('book.hidden') }}</option>
                                                    @endif
                                                </select>
                                                @if ($errors->has('status'))
                                                    <div class="error">{{ $errors->first('status') }}</div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>
                                                    {{ trans('book.name') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" name="name"
                                                    value="{{ $book->name }}">
                                                @if ($errors->has('name'))
                                                    <div class="error">{{ $errors->first('name') }}</div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>
                                                    {{ trans('category.category') }}
                                                    <span class="text-danger">*</span>
                                                    <a id="category-form" class="cus">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </label>
                                                <select name="category_id[]" id="category_id" class="form-control select2"
                                                    multiple="multiple">
                                                    @foreach ($categories as $category)
                                                        @if ($category->books->contains($book))
                                                            <option value="{{ $category->id }}" selected>
                                                                {{ $category->name }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $category->id }}">
                                                                {{ $category->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('category_id'))
                                                    <div class="error">{{ $errors->first('category_id') }}</div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="inputName">
                                                    {{ trans('admin.author') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select name="author_id" class="form-control select2">
                                                    @foreach ($authors as $author)
                                                        @if ($author->books->contains($book))
                                                            <option value="{{ $author->id }}" selected>{{ $author->name }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $author->id }}">{{ $author->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('author_id'))
                                                    <div class="error">{{ $errors->first('author_id') }}</div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="inputName">
                                                    {{ trans('admin.publisher') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select name="publisher_id" class="form-control select2">
                                                    @foreach ($publishers as $publisher)
                                                        @if ($publisher->books->contains($book))
                                                            <option value="{{ $publisher->id }}" selected>
                                                                {{ $publisher->name }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $publisher->id }}">{{ $publisher->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('publisher_id'))
                                                    <div class="error">{{ $errors->first('publisher_id') }}</div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>{{ trans('book.des') }}</label>
                                                <textarea id="editor1" rows="10" cols="80" name="description"
                                                    class="form-control">{{ $book->description }}</textarea>
                                                @if ($errors->has('description'))
                                                    <div class="error">{{ $errors->first('description') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>
                                                    {{ trans('book.image') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="file" id="images" name="image">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            @if ($book->image)
                                                                <img class="img-responsive"
                                                                    src="{{ asset('upload/book/' . $book->image) }}">
                                                            @else
                                                                {{ trans('book.image') . ': ' . trans('book.unknow') }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                @if ($errors->has('image'))
                                                    <div class="error">{{ $errors->first('image') }}</div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>
                                                    {{ trans('book.in_stock') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input name="in_stock" class="form-control" type="number"
                                                    value="{{ $book->in_stock }}" min="0" max="100">
                                                @if ($errors->has('in_stock'))
                                                    <div class="error">{{ $errors->first('in_stock') }}</div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>
                                                    {{ trans('book.total') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input name="total" class="form-control" type="number"
                                                    value="{{ $book->total }}" min="0" max="100">
                                                @if ($errors->has('total'))
                                                    <div class="error">{{ $errors->first('total') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <button type="submit" id="add"
                                                class="btn btn-danger">{{ trans('book.edit_button') }}</button>
                                            <a href="{{ route('admin.books.index') }}"
                                                class="btn btn-info quaylai">{{ trans('book.return') }}</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="cafe-f" id="cate-f"></div>
    </div>
@section('script')
    <script src="{{ asset('js/cate_popup.js') }}"></script>
    <script src="{{ asset('bower_components/admin-lte/bower_components/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}">
    </script>
    <script src="{{ asset('js/select2.js') }}"></script>
    <script src="{{ asset('js/editor.js') }}"></script>
@endsection
@endsection
