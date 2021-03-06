@extends('admin/layout')
@section('index')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ trans('category.category_create') }}</h1>
            <ol class="breadcrumb">
                <li>{{ trans('category.categories_manager') }}</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#settings" data-toggle="tab"
                                    aria-expanded="true">{{ trans('category.category_form') }}</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="settings">
                                <form class="form-horizontal" action="{{ route('admin.categories.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="inputName"
                                            class="col-sm-2 control-label">{{ trans('category.category_name') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="name" />
                                            @if ($errors->has('name'))
                                                <div class="error">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{ trans('category.select_list') }}</label>
                                        <div class="col-sm-10">
                                            <select class="form-control select2 select-category" id="sel1" name="parent_id">
                                                <option value="{{ config('category.value_parent') }}">-- Category --
                                                </option>
                                                @foreach ($categoryParents as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" id="add"
                                                class="btn btn-danger">{{ trans('category.add_submit_button') }}</button>
                                            <a href="{{ route('admin.categories.index') }}"
                                                class="btn btn-info quaylai">{{ trans('category.return') }}</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@section('script')
    <script src="{{ asset('js/select2.js') }}"></script>
@endsection
@endsection
