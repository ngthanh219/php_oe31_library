@extends('admin/layout')
@section('index')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ trans('publisher.publisher_create') }}</h1>
            <ol class="breadcrumb">
                <li>{{ trans('publisher.publishers_manager') }}</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#settings" data-toggle="tab"
                                    aria-expanded="true">{{ trans('publisher.publisher_form') }}</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="settings">
                                <form class="form-horizontal" action="{{ route('admin.publishers.update', $publisher->id) }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="inputName"
                                            class="col-sm-2 control-label">{{ trans('publisher.name') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="name"
                                                value="{{ $publisher->name }}" />
                                            @if ($errors->has('name'))
                                                <div class="error">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="image"
                                            class="col-sm-2 control-label">{{ trans('publisher.image') }}</label>
                                        <div class="col-sm-10">
                                            <input type="file" class="form-control-file" id="image" name="image">
                                            <img src="{{ asset('public/publisher/' . $publisher->image) }}" alt="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName"
                                            class="col-sm-2 control-label">{{ trans('publisher.email') }}</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" name="email"
                                                value="{{ $publisher->email }}" />
                                            @if ($errors->has('email'))
                                                <div class="error">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName"
                                            class="col-sm-2 control-label">{{ trans('publisher.phone') }}</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" name="phone"
                                                value="{{ $publisher->phone }}" />
                                            @if ($errors->has('phone'))
                                                <div class="error">{{ $errors->first('phone') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName"
                                            class="col-sm-2 control-label">{{ trans('publisher.address') }}</label>
                                        <div class="col-sm-10">
                                            <textarea type="text" class="form-control" name="address">{{ $publisher->address }}</textarea>
                                            @if ($errors->has('address'))
                                                <div class="error">{{ $errors->first('address') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName"
                                            class="col-sm-2 control-label">{{ trans('publisher.description') }}</label>
                                        <div class="col-sm-10">
                                            <textarea type="text" class="form-control" name="description">{{ $publisher->description }}</textarea>
                                            @if ($errors->has('description'))
                                                <div class="error">{{ $errors->first('description') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" id="add"
                                                class="btn btn-danger">{{ trans('publisher.edit_submit_button') }}</button>
                                            <a href="{{ route('admin.publishers.index') }}"
                                                class="btn btn-info quaylai">{{ trans('publisher.return') }}</a>
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
@endsection
