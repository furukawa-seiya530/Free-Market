<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
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

    <main class="mypage-content">
        <section class="user-info">
            <div class="user-info-left">
                <div class="user-image">
                    @if ($profile && $profile->image)
                        <img src="{{ asset('storage/avatar/' . $profile->image) }}" alt="プロフィール画像" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    @else
                        <div style="width: 100%; height: 100%; background-color: #ddd; border-radius: 50%;"></div>
                    @endif
                </div>
                <h2 class="username">{{ $profile->name ?? 'ユーザー名未設定' }}</h2>
            </div>

            <a href="{{ route('profile.edit') }}" class="btn-edit-profile">プロフィールを編集</a>
        </section>

        <div class="tab-container">
            <nav class="tab-menu">
                <a href="{{ route('mypage', ['tab' => 'sell']) }}" class="tab {{ $tab === 'sell' ? 'active' : '' }}">出品した商品</a>
                <a href="{{ route('mypage', ['tab' => 'buy']) }}" class="tab {{ $tab === 'buy' ? 'active' : '' }}">購入した商品</a>
            </nav>
        </div>

        <section class="product-list">
            @foreach ($products as $product)
                <div class="product">
                    <div class="product-image">
                        <img src="{{ asset($product->image) }}" alt="商品画像">
                    </div>
                    <p class="product-name">{{ $product->name }}</p>
                </div>
            @endforeach
        </section>
    </main>
</body>
</html>


