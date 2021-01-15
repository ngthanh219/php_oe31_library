@extends('admin.layout')
@section('index')
    <div class="content-wrapper" id="formContent">
        <section class="content-header">
            <h1>{{ trans('author.authors_manager') }}</h1>
            <div class="timeline-footer general">
                <a href="{{ route('admin.authors.create') }}" class="btn btn-primary btn general">
                    <i class="fa fa-plus-square general"></i> {{ trans('author.add_submit_button') }}
                </a>
            </div>
            <ol class="breadcrumb">
                <li>{{ trans('author.author') }}</li>
            </ol>
            @if (session()->has('infoMessage'))
                <div class="infoMessage">
                    <div class="box box-warning box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-bell-o"></i>
                                {{ trans('author.notifi') }}
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
                            <h3 class="box-title">{{ trans('author.author_list') }}</h3>
                            <div class="box-tools">
                                <div class="input-group input-group-sm hidden-xs">
                                    <input type="text" onkeyup="showResult(this.value)" name="search"
                                        class="form-control pull-right" placeholder="{{ trans('author.author_filter') }}"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div id="livesearch"></div>
                        <div class="box-body table-responsive no-padding" id="tableContent">
                            <table class="table table-hover text-center">
                                <tbody>
                                    <tr>
                                        <th>{{ trans('author.author_name') }}</th>
                                        <th>{{ trans('author.avatar') }}</th>
                                        <th>{{ trans('author.description') }}</th>
                                        <th>{{ trans('author.date_of_born') }}</th>
                                        <th>{{ trans('author.date_of_death') }}</th>
                                        <th>{{ trans('author.actions') }}</th>
                                    </tr>
                                    @foreach ($authors as $author)
                                        <tr>
                                            <td>{{ $author->name }}</td>
                                            <td>
                                                @if ($author->image)
                                                    <img class="image-avatar"
                                                        src="{{ asset('upload/author/' . $author->image) }}"
                                                        title="{{ trans('author.author') }}: {{ $author->name }}">
                                                @else
                                                    {{ trans('author.unknow') }}
                                                @endif
                                            </td>
                                            <td>{{ $author->description ? $author->description : trans('author.unknow') }}
                                            </td>
                                            <td>{{ $author->date_of_born ? $author->date_of_born : trans('author.unknow') }}
                                            </td>
                                            <td>{{ $author->date_of_death ? $author->date_of_death : trans('author.unknow') }}
                                            </td>
                                            <td class="td general">
                                                <a href="{{ route('admin.authors.edit', $author->id) }}"><i
                                                        class="fa fa-pencil"></i></a>
                                                <form action="{{ route('admin.authors.destroy', $author->id) }}"
                                                    method="POST" class="delete delete-form general"
                                                    id="delete_{{ $author->id }}">
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
                                    {{ $authors->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
