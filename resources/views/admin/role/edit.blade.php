@extends('admin/layout')
@section('index')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ trans('role.role_edit') }}</h1>
            <ol class="breadcrumb">
                <li>{{ trans('role.role_manager') }}</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#settings" data-toggle="tab" aria-expanded="true">{{ trans('role.role_form') }}</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="settings">
                                <form class="form-horizontal" action="{{ route('admin.roles.update', $role->id) }}"
                                    method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="inputName"
                                            class="col-sm-2 control-label">{{ trans('role.name') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="name" value="{{ $role->name }}" />
                                            @if ($errors->has('name'))
                                                <div class="error">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{ trans('role.select_permissions') }}</label>
                                        <div class="col-sm-10">
                                            <select class="form-control select2" id="sel1"
                                                name="permission[]" multiple="multiple">
                                                <option value="0" disabled>-- role --</option>
                                                @foreach ($permissions as $premission)
                                                    @if ($role->permissions->contains($premission))
                                                        <option value="{{ $premission->id }}" selected>
                                                            {{ $premission->name }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $premission->id }}">{{ $premission->description }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" id="add"
                                                class="btn btn-danger">{{ trans('role.edit_submit_button') }}</button>
                                            <a href="{{ route('admin.roles.index') }}"
                                                class="btn btn-info quaylai">{{ trans('role.return') }}</a>
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
    <script src="{{ asset('bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}">
    </script>
    <script src="{{ asset('js/select2.js') }}"></script>
@endsection
@endsection
