<!DOCTYPE html>
<html>

<head>
    <title>{{ trans('client.book_store') }}</title>
    @include('client.modules.header')
</head>

<body>
    <div class="wrapper">
        <div class="notification-client"></div>
        @include('client.modules.top_nav_bar')
        @include('client.modules.nav_bar')
        <section id="content-holder" class="container-fluid container">
            <section class="row-fluid">
                <section class="span9 first wapper">
                    @yield('content')
                </section>
                @if (Route::current()->getName() != 'cart' && Route::current()->getName()!= 'request' && Route::current()->getName()!= 'request-detail')
                    <section class="span3">
                        <div class="side-holder">
                            <article class="shop-by-list">
                                <h2>{{ trans('client.shop_by') }}</h2>
                                <div class="side-inner-holder">
                                    @include('client.category')
                                    @include('client.author')
                                </div>
                            </article>
                        </div>
                    </section>
                @endif
            </section>
        </section>
        @include('client.modules.trending')
    </div>
    @yield('script')
    @include('client.modules.footer')
</body>

</html>
