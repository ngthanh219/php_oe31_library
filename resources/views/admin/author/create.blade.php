@extends('admin/layout')
@section('index')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ trans('author.author_create') }}</h1>
            <ol class="breadcrumb">
                <li>{{ trans('author.authors_manager') }}</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#settings" data-toggle="tab"
                                    aria-expanded="true">{{ trans('author.author_form') }}</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="settings">
                                <form class="form-horizontal" action="{{ route('admin.authors.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="inputName"
                                            class="col-sm-2 control-label">{{ trans('author.name') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="name" />
                                            @if ($errors->has('name'))
                                                <div class="error">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="image" class="col-sm-2 control-label">{{ trans('author.image') }}</label>
                                        <div class="col-sm-10">
                                            <input type="file" class="form-control-file" id="image" name="image">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName"
                                            class="col-sm-2 control-label">{{ trans('author.description') }}</label>
                                        <div class="col-sm-10">
                                            <textarea type="text" class="form-control" name="description"></textarea>
                                            @if ($errors->has('description'))
                                                <div class="error">{{ $errors->first('description') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName"
                                            class="col-sm-2 control-label">{{ trans('author.date_of_born') }}</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" name="date_of_born" />
                                            @if ($errors->has('date_of_born'))
                                                <div class="error">{{ $errors->first('date_of_born') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName"
                                            class="col-sm-2 control-label">{{ trans('author.date_of_death') }}</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" name="date_of_death" />
                                            @if ($errors->has('date_of_death'))
                                                <div class="error">{{ $errors->first('date_of_death') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" id="add"
                                                class="btn btn-danger">{{ trans('author.add_submit_button') }}</button>
                                            <a href="{{ route('admin.authors.index') }}"
                                                class="btn btn-info quaylai">{{ trans('author.return') }}</a>
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
@endsection
