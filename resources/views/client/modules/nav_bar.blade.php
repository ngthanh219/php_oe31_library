<header id="main-header">
    <section class="container-fluid container">
        <section class="row-fluid">
            <section class="span4">
                <h1 id="logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('bower_components/book-client-lte/images/logo.png') }}" />
                    </a>
                </h1>
            </section>
            <section class="span8">
                <ul class="top-nav2">
                    <li><a href="{{ route('request') }}">{{ trans('client.list_request')}}</a></li>
                    <li><a href="{{ route('cart')}}">{{ trans('client.cart') }}</a></li>
                </ul>
                <div class="search-bar">
                    <input name="" type="text" value="{{ trans('client.filter_input') }}" />
                    <input name="" type="button" value="{{ trans('client.search') }}" />
                </div>
            </section>
        </section>
    </section>
    <nav id="nav">
        <div class="navbar navbar-inverse">
            <div class="navbar-inner">
                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
                </button>
                <div class="nav-collapse collapse">
                    <ul class="nav">
                        <li> <a href="{{ route('home') }}">{{ trans('book.menu') }}</a> </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
