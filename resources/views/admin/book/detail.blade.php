@extends('admin.layout')
@section('index')
    <div class="content-wrapper" id="formContent">
        <section class="content-header">
            <h1>{{ trans('book.books_manager') }}</h1>
            <ol class="breadcrumb">
                <li>{{ trans('book.menu') }}</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-3">
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            @if ($book->image != '')
                                <img class="profile-user-img img-responsive img-circle"
                                    src="{{ asset('upload/book/' . $book->image) }}"
                                    title="{{ trans('book.book') }}: {{ $book->name }}">
                            @else
                                {{ trans('book.image') . ': ' . trans('book.unknow') }}
                            @endif
                            <h3 class="profile-username text-center">{{ $book->name }}</h3>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>{{ trans('book.name') }}</b> <a class="pull-right">{{ $book->name }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>{{ trans('book.in_stock') }}</b> <a class="pull-right">{{ $book->in_stock }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>{{ trans('book.total') }}</b> <a class="pull-right">{{ $book->total }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>{{ trans('book.status') }}</b> <a
                                        class="pull-right">{{ $book->status == config('book.visible') ? trans('book.visible') : trans('book.hidden') }}</a>
                                </li>
                            </ul>
                            <div class="form-group text-center">
                                <a href="{{ route('admin.books.edit', [$book->id]) }}"
                                    class="btn btn-danger"><b>{{ trans('book.edit_information') }}</b></a>
                                <a href="{{ route('admin.books.index') }}"
                                    class="btn btn-primary"><b>{{ trans('book.return') }}</b></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ trans('book.detail') }}</h3>
                        </div>
                        <div class="box-body">
                            <strong><i class="fa fa-tags margin-r-5"></i> {{ trans('category.category') }}</strong>
                            <div class="timeline-footer">
                                @foreach ($book->categories as $category)
                                    <a class="btn btn-warning btn-xs">{{ $category->name }}</a>
                                @endforeach
                            </div>
                            <hr>
                            <strong><i class="fa fa-address-book margin-r-5"></i> {{ trans('admin.author') }}</strong>
                            <p class="text-muted">{{ $book->author->name }}</p>
                            <hr>
                            <strong><i class="fa fa-building margin-r-5"></i> {{ trans('admin.publisher') }}</strong>
                            <p>
                                {{ $book->publisher->name }}
                            </p>
                            <hr>
                            <strong><i class="fa fa-file-text-o margin-r-5"></i> {{ trans('book.des') }}</strong>
                            <p>
                                {!! $book->description == '' ? trans('book.unknow') : $book->description !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
