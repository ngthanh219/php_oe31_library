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
                                <form class="form-horizontal" action="{{ route('admin.books.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-md-12">
                                        <div class="col-md-9 col-9-form general">
                                            <div class="form-group">
                                                <label class="label-general">{{ trans('book.status') }}</label>
                                                <select name="status" class="form-control select2 general">
                                                    <option value="{{ config('book.visible') }}">{{ trans('book.visible') }}
                                                    </option>
                                                    <option value="{{ config('book.hidden') }}">{{ trans('book.hidden') }}
                                                    </option>
                                                </select>
                                                @if ($errors->has('status'))
                                                    <div class="error">{{ $errors->first('status') }}</div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>{{ trans('book.name') }}</label>
                                                <input type="text" class="form-control" name="name">
                                                @if ($errors->has('name'))
                                                    <div class="error">{{ $errors->first('name') }}</div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>
                                                    {{ trans('category.category') }}
                                                    <a id="category-form" class="cus">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </label>
                                                <select name="category_id[]" id="category_id" class="form-control select2"
                                                    multiple="multiple">
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('category_id'))
                                                    <div class="error">{{ $errors->first('category_id') }}</div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="inputName">{{ trans('admin.author') }}</label>
                                                <select name="author_id" class="form-control select2">
                                                    @foreach ($authors as $author)
                                                        <option value="{{ $author->id }}">{{ $author->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('author_id'))
                                                    <div class="error">{{ $errors->first('author_id') }}</div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="inputName">{{ trans('admin.publisher') }}</label>
                                                <select name="publisher_id" class="form-control select2">
                                                    @foreach ($publishers as $publisher)
                                                        <option value="{{ $publisher->id }}">{{ $publisher->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('publisher_id'))
                                                    <div class="error">{{ $errors->first('publisher_id') }}</div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>{{ trans('book.des') }}</label>
                                                <textarea id="editor1" rows="10" cols="80" name="description"
                                                    class="form-control"></textarea>
                                                @if ($errors->has('description'))
                                                    <div class="error">{{ $errors->first('description') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ trans('book.image') }}</label>
                                                <input type="file" id="images" name="image">
                                                @if ($errors->has('image'))
                                                    <div class="error">{{ $errors->first('image') }}</div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>{{ trans('book.in_stock') }}</label>
                                                <input name="in_stock" class="form-control" type="number" value="1" min="0"
                                                    max="100">
                                                @if ($errors->has('in_stock'))
                                                    <div class="error">{{ $errors->first('in_stock') }}</div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>{{ trans('book.total') }}</label>
                                                <input name="total" class="form-control" type="number" value="1" min="0"
                                                    max="100">
                                                @if ($errors->has('total'))
                                                    <div class="error">{{ $errors->first('total') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <button type="submit" id="add"
                                                class="btn btn-danger">{{ trans('book.add_button') }}</button>
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
    <script src="{{ asset('js/cate_popup.js') }}" defer></script>
    <script src="{{ asset('bower_components/admin-lte/bower_components/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}">
    </script>
    <script src="{{ asset('js/select2.js') }}"></script>
    <script src="{{ asset('js/editor.js') }}"></script>
@endsection
@endsection
