@extends('admin/layout')
@section('index')
    <div class="content-wrapper" id="formContent">
        <section class="content-header">
            <h1>{{ trans('role.role_manager') }}</h1>
            <div class="timeline-footer general">
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn general">
                    <i class="fa fa-plus-square general"></i>
                    {{ trans('role.add_submit_button') }}
                </a>
            </div>
            <ol class="breadcrumb">
                <li>{{ trans('role.role') }}</li>
            </ol>
            @if (session()->has('infoMessage'))
                <div class="infoMessage">
                    <div class="box box-warning box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-bell-o"></i>
                                {{ trans('role.notifi') }}
                            </h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            {{ session()->get('infoMessage') }}
                        </div>
                    </div>
                </div>
            @endif
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">{{ trans('role.role_list') }}</h3>
                            <div class="box-tools">
                                <div class="input-group input-group-sm hidden-xs">
                                    <input type="text" onkeyup="showResult(this.value)" name="search"
                                        class="form-control pull-right" placeholder="{{ trans('role.role_search') }}"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div id="livesearch"></div>
                        <div class="box-body table-responsive no-padding" id="tableContent">
                            <table class="table table-hover text-center">
                                <tbody>
                                    <tr>
                                        <th>{{ trans('role.role_name') }}</th>
                                        <th>{{ trans('role.permissions') }}</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>{{ $role->name }}</b>
                                        </td>
                                        <td class="td general">
                                            <div class="d-flex">
                                                @foreach ($role->permissions as $permission)
                                                    <div class="label label-primary">{{ $permission->description }}</div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="col-sm-12 text-right">
                                <div class="dataTables_paginate paging_simple_numbers"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
