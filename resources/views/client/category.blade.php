<strong class="title">Category</strong>
<ul class="side-list">
    @foreach ($categories as $category)
        <li class="hover-wapper" id="hover-wapper" data-key="{{ $category->id }}">
            <a>{{ $category->name }}</a>
            <div class="overlay" id="overlay-{{ $category->id }}"></div>
            <div class="box-wapper" id="box-wapper-{{ $category->id }}">
                @foreach ($category->children as $child)
                    <div class="item-wapper">
                        <a href="{{ route('category-book', $child->id) }}" class="child">{{ $child->name }}</a>
                    </div>
                @endforeach
            </div>
        </li>
    @endforeach
</ul>
