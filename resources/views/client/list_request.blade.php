@extends('client.layout')
@section('content')
    @if (session()->has('mess'))
        <div class="notification-client">
            <div class="content-message">
                {{ session()->get('mess') }}
            </div>
        </div>
    @endif
    <section id="content-holder" class="container-fluid container">
        <section class="row-fluid">
            <section class="span12 cart-holder">
                <div class="heading-bar">
                    <h2>{{ trans('client.list_request') }}</h2>
                    <span class="h-line"></span>
                </div>
                <div class="cart-table-holder">
                    <table class="cart-table-general" border="0" cellpadding="10">
                        <tr>
                            <th class="col" align="left">{{ trans('request.borrowed_book') }}</th>
                            <th class="col" align="left">{{ trans('request.return_book') }}</th>
                            <th class="col" align="left">{{ trans('request.total_date') }}</th>
                            <th class="col" align="left">{{ trans('request.status') }}</th>
                            <th class="col" align="left">{{ trans('request.actions') }}</th>
                        </tr>
                        @foreach ($requests as $request)
                            <tr bgcolor="#FFFFFF" class=" product-detail">
                                @php
                                    $startTime = \Carbon\Carbon::parse($request->borrowed_date);
                                    $finishTime = \Carbon\Carbon::parse($request->return_date);
                                    $totalDate = $finishTime->diffinDays($startTime);
                                @endphp
                                <td valign="top hige-name">{{ date('d-m-Y', strtotime($request->borrowed_date)) }}</td>
                                <td valign="top hige-name">{{ date('d-m-Y', strtotime($request->return_date)) }}</td>
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
                                <td>
                                    <a href="{{ route('request-detail', $request->id) }}"><i class="icon-eye-open"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    <div class="pagination">
                        <ul>
                            @if ($requests->lastPage())
                                @for ($i = 1; $i <= $requests->lastPage(); $i++)
                                    @if (isset($page) && $page == $i)
                                        <li class="active">
                                            <a href="{{ $requests->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{ $requests->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor
                            @endif
                        </ul>
                    </div>
                </div>
            </section>
        </section>
    </section>
@endsection
