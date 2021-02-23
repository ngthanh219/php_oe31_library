@extends('admin.layout')
@section('index')
    <div class="content-wrapper" id="formContent">
        <section class="content-header">
            <h1>{{ trans('dashboard.dashboard') }}</h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <i class="fa fa-bar-chart-o"></i>
                            <h3 class="box-title">{{ trans('dashboard.name') }}</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="dashboard-general" id="bar-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script src="{{ asset('bower_components/admin-lte/bower_components/Flot/jquery.flot.js') }}" defer></script>
    <script src="{{ asset('bower_components/admin-lte/bower_components/Flot/jquery.flot.resize.js') }} " defer></script>
    <script src="{{ asset('bower_components/admin-lte/bower_components/Flot/jquery.flot.pie.js') }}" defer></script>
    <script src="{{ asset('bower_components/admin-lte/bower_components/Flot/jquery.flot.categories.js') }}" defer></script>
    <script src="{{ asset('js/chart.js') }}" defer></script>
    <script src="{{ asset('bower_components/admin-lte/dist/js/component/search/search.js') }}" defer>
    </script>
    <script src="{{ asset('bower_components/admin-lte/dist/js/component/general.js') }}" defer>
    </script>
@endsection
