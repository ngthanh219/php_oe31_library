@extends('client.layout')
@section('content')
    <section class="grid-holder features-books">
        @foreach ($books as $book)
            <figure class="span4 slide wapper">
                <a href="{{ route('detail', $book->id) }}">
                    @if ($book->image)
                        <img class="pro-img" src="{{ asset('upload/book/' . $book->image) }}"
                            alt="{{ trans('book.book') }}: {{ $book->name }}" />
                    @else
                        {{ trans('book.image') . ': ' . trans('book.unknow') }}
                    @endif
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
                @if ($books->lastPage() > config('pagination.total_page'))
                    @for ($i = 1; $i <= $books->lastPage(); $i++)
                        @if (isset($page) && $page == $i)
                            <li class="active">
                                <a href="{{ $books->url($i) }}">{{ $i }}</a>
                            </li>
                        @else
                            <li>
                                <a href="{{ $books->url($i) }}">{{ $i }}</a>
                            </li>
                        @endif
                    @endfor
                @endif
            </ul>
        </div>
    </div>
@endsection
