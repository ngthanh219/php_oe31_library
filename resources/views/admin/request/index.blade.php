@extends('admin.layout')
@section('index')
    <div class="content-wrapper" id="formContent">
        <section class="content-header">
            <h1>{{ trans('request.requests_manager') }}</h1>
            <ol class="breadcrumb">
                <li>{{ trans('request.request') }}</li>
            </ol>
            @if (session()->has('infoMessage'))
                <div class="infoMessage">
                    <div class="box box-warning box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-bell-o"></i>
                                {{ trans('request.notifi') }}
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
                            <h3 class="box-title">{{ trans('request.request_list') }}</h3>
                            <div class="box-tools">
                                <div class="input-group input-group-sm hidden-xs">
                                    <input type="text" onkeyup="showResult(this.value)" name="search"
                                        class="form-control pull-right" placeholder="{{ trans('request.request_search') }}"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div id="livesearch"></div>
                        <div class="box-body table-responsive no-padding" id="tableContent">
                            <table class="table table-hover text-center">
                                <tbody>
                                    <tr>
                                        <th>{{ trans('request.user_name') }}</th>
                                        <th>{{ trans('request.borrowed_date') }}</th>
                                        <th>{{ trans('request.return_date') }}</th>
                                        <th>{{ trans('request.total_date') }}</th>
                                        <th>{{ trans('request.status') }}</th>
                                        <th>{{ trans('request.actions') }}</th>
                                    </tr>
                                    @foreach ($requests as $request)
                                        <tr>
                                            @php
                                                $borrowedDate = \Carbon\Carbon::parse($request->borrowed_date);
                                                $returnDate = \Carbon\Carbon::parse( $request->return_date);
                                                $totalDate = $returnDate->diffinDays($borrowedDate);
                                            @endphp
                                            <td>
                                                <b>{{ $request->user->name }}</b>
                                            </td>
                                            <td>
                                                <b>{{ date('d-m-Y', strtotime($request->borrowed_date)) }}</b>
                                            </td>
                                            <td>
                                                <b>{{ date('d-m-Y', strtotime($request->return_date)) }}</b>
                                            </td>
                                            <td>
                                                @if ($totalDate === config('request.total'))
                                                    <b>{{ trans('request.in_day') }}</b>
                                                @else 
                                                    <b>{{ $totalDate }} {{ trans('request.days') }}</b>
                                                @endif
                                            </td>
                                            <td>
                                                @switch ($request->status)
                                                    @case (config('request.pending'))
                                                        <span class="label label-warning">{{ trans('request.pending') }}</span>
                                                    @break
                                                    @case (config('request.accept'))
                                                        <span class="label label-primary">{{ trans('request.accept') }}</span>
                                                    @break
                                                    @case (config('request.reject'))
                                                        <span class="label label-danger">{{ trans('request.reject') }}</span>
                                                    @break
                                                    @case (config('request.borrow'))
                                                        <span class="label label-info">{{ trans('request.borrowing') }}</span>
                                                    @break
                                                    @case (config('request.return'))
                                                        <span class="label label-success">{{ trans('request.return') }}</span>
                                                    @break
                                                    @case (config('request.late'))
                                                        <span class="label label-danger">{{ trans('request.too_late') }}</span>
                                                    @break
                                                    @case (config('request.forget'))
                                                        <span class="label label-danger">{{ trans('request.take_book_late') }}</span>
                                                    @break
                                                    @default
                                                @endswitch
                                            </td>
                                            <td class="td general">
                                                <a href="{{ route('admin.request-detail', $request->id) }}"
                                                    title="{{ trans('request.view') }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="col-sm-12 text-right">
                                <div class="dataTables_paginate paging_simple_numbers"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{ $requests->links() }}
        </section>
    </div>
@section('script')
    <script type="text/javascript"
        src="{{ asset('bower_components/request-lte/dist/js/component/search/search_request.js') }}" defer></script>
@endsection
@endsection
