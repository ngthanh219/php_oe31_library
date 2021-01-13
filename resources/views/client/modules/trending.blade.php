<section class="container-fluid footer-top2">
    <section class="social-ico-bar">
        <section class="container">
            <section class="row-fluid">
                <div id="socialicons" class="hidden-phone">
                    <a id="social_linkedin" class="social_active" href="#" title="Visit Google Plus page">
                        <span></span>
                    </a>
                    <a id="social_facebook" class="social_active" href="#" title="Visit Facebook page">
                        <span></span>
                    </a>
                    <a id="social_twitter" class="social_active" href="#" title="Visit Twitter page">
                        <span></span>
                    </a>
                    <a id="social_youtube" class="social_active" href="#" title="Visit Youtube">
                        <span></span>
                    </a>
                    <a id="social_vimeo" class="social_active" href="#" title="Visit Vimeo">
                        <span></span>
                    </a>
                    <a id="social_trumblr" class="social_active" href="#" title="Visit Vimeo">
                        <span></span>
                    </a>
                    <a id="social_google_plus" class="social_active" href="#" title="Visit Vimeo">
                        <span></span>
                    </a>
                    <a id="social_dribbble" class="social_active" href="#" title="Visit Vimeo">
                        <span></span>
                    </a>
                    <a id="social_pinterest" class="social_active" href="#" title="Visit Vimeo">
                        <span></span>
                    </a>
                </div>
                <ul class="footer2-link">
                    <li><a href="#">{{ trans('client.about_us') }}</a></li>
                </ul>
            </section>
        </section>
    </section>
    <section class="container">
        <section class="row-fluid">
            <figure class="span4">
                <h4>{{ trans('client.new_books') }}</h4>
                <ul class="f2-img-list">
                    @foreach ($newBooks as $newBook)
                        <li>
                            <div class="left">
                                <a href="book-detail.html">
                                    <img src="{{ $newBook->image ? asset('upload/book/' . $newBook->image) : asset('bower_components/book-client-lte/images/image35.jpg') }}"
                                        alt="" />
                                </a>
                            </div>
                            <div class="right">
                                <strong class="title">
                                    <a href="#">{{ $newBook->name }}</a>
                                </strong>
                                <span class="by-author">{{ trans('client.by') }} {{ $newBook->author->name }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </figure>
            <figure class="span4">
                <h4>{{ trans('client.top_like_book') }}</h4>
                <ul class="f2-img-list">
                    @foreach ($likeBooks as $likeBook)
                        <li>
                            <div class="left">
                                <a href="book-detail.html">
                                    <img src="{{ asset('bower_components/book-client-lte/images/image35.jpg') }}" />
                                </a>
                            </div>
                            <div class="right">
                                <strong class="title">
                                    <a href="#">{{ $likeBook->name }}</a>
                                </strong>
                                <span class="by-author">{{ trans('client.like') }}: {{ $likeBook->likes_count }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </figure>
            <figure class="span4">
                <h4>{{ trans('client.top_rate_book') }}</h4>
                <ul class="f2-img-list">
                    <li>
                        <div class="left">
                            <a href="#">
                                <img src="{{ asset('bower_components/book-client-lte/images/image35.jpg') }}" />
                            </a>
                        </div>
                        <div class="right">
                            <strong class="title">
                                <a href="#">{{ trans('client.name_book') }}</a>
                            </strong>
                            <span class="by-author">{{ trans('client.by_author') }}</span>
                            <span class="rating-bar">
                                <img src="{{ asset('bower_components/book-client-lte/images/rating-star.png') }}" />
                            </span>
                        </div>
                    </li>
                </ul>
            </figure>
        </section>
    </section>
</section>
<footer id="main-footer">
    <section class="social-ico-bar">
        <section class="container">
            <section class="row-fluid">
                <article class="span6">
                    <p>{{ trans('client.resources') }}</p>
                </article>
                <article class="span6 copy-right">
                    <p>{{ trans('client.design_by') }}<a href="#">{{ trans('client.name_websites') }}</a></p>
                </article>
            </section>
        </section>
    </section>
</footer>
