@extends('client.layout')
@section('content')
    <section id="content-holder" class="container-fluid container">
        <section class="row-fluid">
            <section class="span12 cart-holder">
                <div class="heading-bar">
                    <h2>{{ trans('client.detail_request') }}</h2>
                    <span class="h-line"></span>
                </div>
                <div class="cart-table-holder">
                    <div class="row">
                        <section class="span4">
                            <ul class="list-group list-group-unbordered info">
                                <li class="li">
                                    <b>{{ trans('user.email') }}</b><b class="pull-right">{{ $request->user->email }}</b>
                                </li>
                                <li class="li">
                                    <b>{{ trans('user.phone') }}</b><b
                                        class="pull-right">{{ $request->user->phone ? $request->user->phone : trans('author.unknow') }}</b>
                                </li class="li">
                                <li class="li">
                                    <b>{{ trans('user.address') }}</b> <b
                                        class="pull-right">{{ $request->user->address }}</b>
                                </li>
                                <li class="li">
                                    <b>{{ trans('request.borrowed_date') }}</b><b
                                        class="pull-right">{{ date('d-m-Y', strtotime($request->borrowed_date)) }}</b>
                                </li>
                                <li class="li">
                                    <b>{{ trans('request.return_date') }}</b><b
                                        class="pull-right">{{ date('d-m-Y', strtotime($request->return_date)) }}</b>
                                </li>
                                <li class="li">
                                    <b>{{ trans('request.total_date') }}</b><b class="pull-right">
                                        @php
                                            $startTime = \Carbon\Carbon::parse($request->borrowed_date);
                                            $finishTime = \Carbon\Carbon::parse($request->return_date);
                                            $totalDate = $finishTime->diffinDays($startTime);
                                        @endphp
                                        @if ($totalDate === config('request.total'))
                                            <b>{{ trans('request.in_day') }}</b>
                                        @else
                                            <b>{{ $totalDate }} {{ trans('request.days') }}</b>
                                        @endif
                                    </b>
                                </li>
                                <li class="li">
                                    <b>{{ trans('request.status') }}
                                        <b class="pull-right">
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
                                        </b>
                                    </b>
                                </li>
                            </ul>
                        </section>
                        <section class="span8">
                            <table class="cart-table-general" border="0" cellpadding="10">
                                <tr>
                                    <th class="col-first-table">{{ trans('book.image') }}</th>
                                    <th class="col-second-table" align="left">{{ trans('book.book') }}</th>
                                </tr>
                                @foreach ($request->books as $book)
                                    <tr bgcolor="#FFFFFF" class=" product-detail">
                                        <td valign="top"><img src="{{ asset(config('book.url') . $book->image) }}" /></td>
                                        <td valign="top">{{ $book->name }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </section>
                    </div>
                </div>
            </section>
        </section>
    </section>
@endsection
