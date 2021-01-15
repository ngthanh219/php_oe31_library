@if ($books->isEmpty())
    <div class="box-data">
        <a class="item-box-filter">{{ trans('user.empty_information') }}</a>
    </div>
@else
    @foreach ($books as $book)
        <div class="box-data">
            <a href="{{ route('detail', $book->id) }}" class="item-box-filter">
                <div class="content-data">
                    {{ $book->name }}
                </div>
            </a>
        </div>
    @endforeach
@endif
