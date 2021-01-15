@extends('admin/layout')

@section('index')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ trans('user.add_button') }}</h1>
            <ol class="breadcrumb">
                <li>{{ trans('user.users_manager') }}</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#settings" data-toggle="tab" aria-expanded="true">{{ trans('user.name_form') }}</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="settings">
                                <form class="form-horizontal" action="{{ route('admin.users.store') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">
                                            {{ trans('user.name') }}
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
                                        <label for="inputName" class="col-sm-2 control-label">
                                            {{ trans('user.address') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="address" />
                                            @if ($errors->has('address'))
                                                <div class="error">{{ $errors->first('address') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">
                                            {{ trans('user.phone') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" name="phone" />
                                            @if ($errors->has('phone'))
                                                <div class="error">{{ $errors->first('phone') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">
                                            {{ trans('user.email') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="email" />
                                            @if ($errors->has('email'))
                                                <div class="error">{{ $errors->first('email') }}</div>
                                            @endif
                                            @if (session()->has('checkIssetEmail'))
                                                <div class="error">{{ session()->get('checkIssetEmail') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">
                                            {{ trans('user.password') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" name="password" />
                                            @if ($errors->has('password'))
                                                <div class="error">{{ $errors->first('password') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" id="add" class="btn btn-danger">{{ trans('user.add_submit_button') }}</button>
                                            <a href="{{ route('admin.users.index') }}" class="btn btn-info quaylai">{{ trans('user.return') }}</a>
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
