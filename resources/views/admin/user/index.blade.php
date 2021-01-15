@extends('admin.layout')
@section('index')
    <div class="content-wrapper" id="formContent">
        <section class="content-header">
            <h1>{{ trans('user.users_manager') }}</h1>
            <div class="timeline-footer general">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn general">
                    <i class="fa fa-plus-square general"></i> {{ trans('user.add_submit_button') }}
                </a>
            </div>
            <ol class="breadcrumb">
                <li>{{ trans('user.menu') }}</li>
            </ol>
            @if (session()->has('infoMessage'))
                <div class="col-md-3 infoMessage">
                    <div class="box box-warning box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-bell-o"></i>
                                {{ trans('user.notifi') }}
                            </h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
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
                            <h3 class="box-title">{{ trans('user.list') }}</h3>
                            <div class="box-tools">
                                <div class="input-group input-group-sm hidden-xs">
                                    <input type="text" name="search-user" id="search" class="form-control pull-right"
                                        placeholder="{{ trans('user.filter') }}" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div id="livesearch"></div>
                        <div class="box-body table-responsive no-padding" id="tableContent">
                            <table class="table table-hover text-center">
                                <tbody>
                                    <tr>
                                        <th>{{ trans('user.name') }}</th>
                                        <th>{{ trans('user.email') }}</th>
                                        <th>{{ trans('user.phone') }}</th>
                                        <th>{{ trans('user.role_id') }}</th>
                                        <th>{{ trans('user.status') }}</th>
                                        <th>{{ trans('user.actions') }}</th>
                                    </tr>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>{{ !isset($user->role->name) ? trans('user.no_role') : $user->role->name }}
                                            </td>
                                            <td>{{ $user->status == config('status.on') ? trans('user.on') : trans('user.off') }}
                                            </td>
                                            <td class="td general">
                                                <a href="{{ route('admin.users.edit', $user->id) }}">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <form id="delete_{{ $user->id }}"
                                                    action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                    class="delete-form general delete">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="col-sm-12 text-right">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    {{ $users->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @section('script')
    <script type="text/javascript" src="{{ asset('bower_components/admin-lte/dist/js/component/search/search.js') }}"
        defer>
    </script>
    <script type="text/javascript" src="{{ asset('bower_components/admin-lte/dist/js/component/general.js') }}" defer>
    </script>
    @endsection
@endsection
