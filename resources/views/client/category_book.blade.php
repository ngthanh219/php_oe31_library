@extends('client.layout')
@section('content')
    @if ($category->books->isEmpty())
        <h4>{{ trans('book.empty_information') }}</h4>
    @else
        <section class="grid-holder features-books">
            @foreach ($category->books as $book)
                <figure class="span4 slide wapper">
                    <a href="{{ route('detail', $book->id) }}">
                        <img src="{{ asset('upload/book/' . $book->image) }}" alt="" class="pro-img" />
                    </a>
                    <span class="title wapper">
                        <a href="{{ route('detail', $book->id) }}">{{ $book->name }}</a>
                    </span>
                </figure>
            @endforeach
        </section>
        <div class="blog-footer wapper">
            <div class="pagination">
                <ul>

                </ul>
            </div>
        </div>
    @endif
@endsection
