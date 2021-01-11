<div class="box-body table-responsive no-padding">
    <table class="table table-hover text-center">
        @if ($books->isEmpty())
            <tr>
                <th>{{ trans('user.empty_information') }}</th>
            </tr>
        @else
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
                            <a href="{{ route('admin.books.show', [$book->id]) }}" title="{{ trans('book.detail') }}">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.books.edit', $book->id) }}"
                                title="{{ trans('book.edit_button') }}"><i class="fa fa-pencil"></i></a>
                            <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST"
                                onclick="return confirm('{{ trans('book.dialog_confirm_delete') }}')"
                                class="delete-form general">
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
        @endif
    </table>
</div>
