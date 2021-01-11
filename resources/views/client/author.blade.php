<strong class="title">{{ trans('author.author') }}</strong>
<ul class="side-list">
    @foreach ($authors as $author)
        <li><a>{{ $author->name }}</a></li>
    @endforeach
</ul>
