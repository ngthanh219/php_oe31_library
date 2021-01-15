@extends('admin.layout')
@section('index')
    <div class="content-wrapper" id="formContent">
        <section class="content-header">
            <h1>{{ trans('book.books_manager') }}</h1>
            <div class="timeline-footer general">
                <a href="{{ route('admin.books.create') }}" class="btn btn-primary btn general">
                    <i class="fa fa-plus-square general"></i> {{ trans('book.add_button') }}
                </a>
            </div>
            <ol class="breadcrumb">
                <li>{{ trans('book.menu') }}</li>
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
                            <h3 class="box-title">{{ trans('book.list') }}</h3>
                            <div class="box-tools">
                                <div class="input-group input-group-sm hidden-xs">
                                    <input type="text" name="search-book" id="search" class="form-control pull-right"
                                        placeholder="{{ trans('book.filter') }}" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div id="livesearch"></div>
                        <div class="box-body table-responsive no-padding" id="tableContent">
                            <table class="table table-hover text-center">
                                <tbody>
                                    <tr>
                                        <th>{{ trans('book.name') }}</th>
                                        <th>{{ trans('book.image') }}</th>
                                        <th>{{ trans('book.status') }}</th>
                                        <th>{{ trans('book.actions') }}</th>
                                    </tr>
                                    @foreach ($books as $book)
                                        <tr>
                                            <td>{{ $book->name }}</td>
                                            <td>
                                                @if ($book->image)
                                                    <img class="image" src="{{ asset('upload/book/' . $book->image) }}"
                                                        title="{{ trans('book.book') }}: {{ $book->name }}">
                                                @else
                                                    {{ trans('book.image') . ': ' . trans('book.unknow') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($book->status == config('book.visible'))
                                                    <p class="success-order">{{ trans('book.visible') }}</p>
                                                @else
                                                    <p class="waiting-order">{{ trans('book.hidden') }}</p>
                                                @endif
                                            </td>
                                            <td class="td general">
                                                <a href="{{ route('admin.books.show', [$book->id]) }}"
                                                    title="{{ trans('book.detail') }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.books.edit', $book->id) }}"
                                                    title="{{ trans('book.edit_button') }}"><i class="fa fa-pencil"></i></a>
                                                <form id="delete_{{ $book->id }}"
                                                    action="{{ route('admin.books.destroy', $book->id) }}" method="POST"
                                                    class="delete-form general delete">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" title="{{ trans('book.delete_button') }}">
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
                                    {{ $books->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@section('script')
    <script type="text/javascript" src="{{ asset('bower_components/admin-lte/dist/js/component/search/search.js') }}" defer>
    </script>
    <script type="text/javascript" src="{{ asset('bower_components/admin-lte/dist/js/component/general.js') }}" defer>
    </script>
@endsection
@endsection
