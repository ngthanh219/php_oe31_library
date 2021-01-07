@extends('admin/layout')

@section('index')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ trans('user.edit_button') }}</h1>
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
                                <form class="form-horizontal" action="{{ route('admin.users.update', $user->id) }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">{{ trans('user.name') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="name" value="{{ $user->name }}" />
                                            @if ($errors->has('name'))
                                                <div class="error">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">{{ trans('user.address') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="address" value="{{ $user->address }}" />
                                            @if ($errors->has('address'))
                                                <div class="error">{{ $errors->first('address') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">{{ trans('user.phone') }}</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" name="phone" value="{{ $user->phone }}" />
                                            @if ($errors->has('phone'))
                                                <div class="error">{{ $errors->first('phone') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">{{ trans('user.email') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control none-event" name="email" value="{{ $user->email }}" />
                                            @if ($errors->has('email'))
                                                <div class="error">{{ $errors->first('email') }}</div>
                                            @endif
                                            @if (session()->has('checkIssetEmail'))
                                                <div class="error">{{ session()->get('checkIssetEmail') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">{{ trans('user.password') }}</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" name="password" />
                                            @if ($errors->has('password'))
                                                <div class="error">{{ $errors->first('password') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" id="add" class="btn btn-danger">{{ trans('user.update_submit_button') }}</button>
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
