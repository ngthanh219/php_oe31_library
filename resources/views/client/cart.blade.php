@extends('client.layout')
@section('content')
    @if (session()->has('mess'))
        <div class="notification-client">
            <div class="content-message">
                {{ session()->get('mess') }}
            </div>
        </div>
    @endif
    <section id="content-holder" class="container-fluid container">
        <section class="row-fluid">
            <section class="span12 cart-holder">
                <div class="heading-bar">
                    <h2>{{ trans('client.name_cart') }}</h2>
                    <span class="h-line"></span>
                </div>
                <div class="cart-table-holder">
                    @if (!session('cart'))
                        <tr>
                            <td colspan="6">
                                <h4>{{ trans('book.empty_information') }}</h4>
                            </td>
                        </tr>
                    @else
                        <form action="{{ route('request') }}" method="POST">
                            <table class="cart-table-general" border="0" cellpadding="10">
                                <tr>
                                    <th class="col-first-table">{{ trans('client.image_book') }}</th>
                                    <th class="col-second-table" align="left">{{ trans('client.name_book') }}</th>
                                    <th class="col-third-table">{{ trans('client.delete_item_in_cart') }}</th>
                                </tr>
                                @foreach ($cart as $item)
                                    <tr bgcolor="#FFFFFF" class=" product-detail">
                                        <td valign="top">
                                            <img src="{{ $item['image'] ? asset('upload/book/' . $item['image']) : '' }}"
                                                alt="{{ $item['name'] }}" />
                                        </td>
                                        <td valign="top hige-name">{{ $item['name'] }}</td>
                                        <td align="center">
                                            <a href="{{ $item['id'] }}" class="remove-item">
                                                <i class="icon-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <div class="box-rule">
                                <div class="cart-option-box">
                                    @csrf
                                    <p>{{ trans('client.borrow_data') }}</p>
                                    <input type="date" id="inputDiscount" name="borrowed_date"
                                        value="{{ old('borrowed_date') }}">
                                    @if ($errors->has('borrowed_date'))
                                        <span class="red">{{ $errors->first('borrowed_date') }}</span>
                                    @endif
                                    <p>{{ trans('client.return_data') }}</p>
                                    <input type="date" id="inputDiscount" name="return_date"
                                        value="{{ old('return_date') }}">
                                    @if ($errors->has('borrowed_date'))
                                        <span class="red">{{ $errors->first('borrowed_date') }}</span>
                                    @endif
                                    <br class="clearfix">
                                    <textarea name="note" class="textarea">{{ old('note') }}</textarea>
                                    <br class="clearfix">
                                    <button type="submit" class="more-btn">{{ trans('client.request_cart') }}</button>
                                </div>
                                <div class="rule">
                                    <br>
                                    <p class="high-l-rule">
                                        <i class="icon-check-sign"></i>
                                        {{ trans('client.rule1') }}
                                    </p>
                                    <p class="high-l-rule">
                                        <i class="icon-check-sign"></i>
                                        {{ trans('client.rule2') }}
                                    </p>
                                    <p class="high-l-rule">
                                        <i class="icon-check-sign"></i>
                                        {{ trans('client.rule3') }}
                                    </p>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </section>
        </section>
    </section>
@section('script')
    <script src=" {{ asset('js/remove_cart.js') }} " defer></script>
@endsection
@endsection
