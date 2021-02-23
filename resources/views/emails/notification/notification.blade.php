@component('mail::message')
@component('mail::panel')
    <img src="{{ asset('/bower_components/book-client-lte/images/logo.png') }}">
@endcomponent
@lang('client.hello') {{ $user->name }}
@php
    $today = \Carbon\Carbon::today();
    $borrowDate = \Carbon\Carbon::parse($request->borrowed_date);
    $returnDate = \Carbon\Carbon::parse($request->return_date);
    $lastDate = $today->diffinDays($returnDate);
    $totalDate = $returnDate->diffinDays($borrowDate);
@endphp
<br>
@lang('request.you_have') **{{ $lastDate }}** @lang('request.days') @lang('request.mess') **{{ date('d-m-Y', strtotime($request->borrowed_date)) }}**
<br>
**@lang('request.borrowed_date')** : {{ date('d-m-Y', strtotime($request->borrowed_date)) }}
<br>
**@lang('request.return_date')** : {{ date('d-m-Y', strtotime($request->return_date)) }}
<br>
**@lang('request.total_date')** : {{ $totalDate }} @lang('request.days')
<br>
**@lang('request.days_remaining')** : {{ $lastDate }} @lang('request.days')

@component('mail::table')
| Book Name | Image |
|:---------: |:-----: |
@foreach ($request->books as $book)
**{{ $book->name }}** |<img src="{{ asset('upload/book/' . $book->image) }}" alt="{{ $book->name }}" width="200px">
@endforeach
@endcomponent

**@Lang('request.thanks')**, {{ $user->name }}
<br>
**{{ config('app.name') }}**
@endcomponent
