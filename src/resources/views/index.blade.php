<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
</head>
<body>
    <header class="header">
        <a href="{{ route('index') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="COACHTECHロゴ" class="logo">
        </a>

        <form method="GET" action="{{ route('index') }}">
            <input
                type="text"
                name="keyword"
                class="search-box"
                placeholder="なにをお探しですか？"
                value="{{ request('keyword') }}"
            >
        </form>

        <nav class="nav-links">
            @auth
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: #fff; font-size: 20px; cursor: pointer;">
                        ログアウト
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" style="color: #fff; font-size: 20px;">ログイン</a>
            @endauth

            <a href="{{ auth()->check() ? route('mypage') : route('login') }}">マイページ</a>
            <a href="{{ auth()->check() ? route('sell.show') : route('login') }}" class="btn-sell">出品</a>
        </nav>
    </header>

    <main class="content">
        <nav class="tab-menu">
            <a href="{{ url('/') }}{{ request('keyword') ? '?keyword=' . urlencode(request('keyword')) : '' }}"
            class="tab {{ request()->query('page') !== 'mylist' ? 'active' : '' }}">
                おすすめ
            </a>
            <a href="{{ auth()->check() ? url('/?page=mylist') . (request('keyword') ? '&keyword=' . urlencode(request('keyword')) : '') : route('login') }}"
            class="tab {{ request()->query('page') === 'mylist' ? 'active' : '' }}">
                マイリスト
            </a>
        </nav>

        @if(request('keyword'))
            <p class="search-result">「{{ request('keyword') }}」の検索結果</p>
        @endif

        <section class="product-list">
            @forelse ($products as $product)
                <div class="product">
                    <a href="{{ route('item.show', ['item_id' => $product->id]) }}">
                        <div class="product-image">
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                            @if ($product->is_sold ?? false)
                                <div class="sold-label">SOLD</div>
                            @endif
                        </div>
                        <p class="product-name">{{ $product->name }}</p>
                        <p class="product-price">¥{{ number_format($product->price) }}</p>
                    </a>
                </div>
            @empty
                <p class="no-result">商品が見つかりませんでした。</p>
            @endforelse
        </section>
    </main>
</body>
</html>



