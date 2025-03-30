<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入ページ</title>
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
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
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-button">ログアウト</button>
                </form>
            @else
                <a href="{{ route('login') }}" style="color: #fff;">ログイン</a>
            @endauth

            <a href="{{ auth()->check() ? route('mypage') : route('login') }}">マイページ</a>
            <a href="{{ auth()->check() ? route('sell.show') : route('login') }}" class="btn-sell">出品</a>
        </nav>
    </header>

    <main class="purchase-container">
        <section class="purchase-content">
            <!-- 左カラム -->
            <div class="left-column">
                <div class="product-info">
                    <img src="{{ asset($itemData['image']) }}" alt="商品画像" class="product-image">

                    <div class="product-details">
                        <h2 class="product-name">{{ $itemData['name'] }}</h2>
                        <p class="product-price">¥{{ number_format($itemData['price']) }}</p>
                    </div>
                </div>

                <hr class="divider">

                <!-- 支払い方法フォーム -->
                <form action="{{ route('purchase.show', ['item_id' => $itemData['id']]) }}" method="GET">
                    <div class="form-group">
                        <label for="payment_method">支払い方法</label>
                        <select name="payment_method" onchange="this.form.submit()" class="payment-select">
                            <option value="コンビニ払い" {{ $itemData['payment_method'] === 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                            <option value="カード払い" {{ $itemData['payment_method'] === 'カード払い' ? 'selected' : '' }}>カード払い</option>
                        </select>
                    </div>
                </form>

                <hr class="divider-thin">

                <!-- 配送先 -->
                <div class="address-info">
                    <p class="address-title">配送先</p>
                    <p class="address-details">
                        〒{{ $itemData['postal_code'] }}<br>
                        {{ $itemData['address'] }}
                    </p>
                    <a href="{{ route('purchase.address', ['item_id' => $itemData['id']]) }}" class="change-address">変更する</a>
                </div>

                <hr class="divider">
            </div>

            <!-- 右カラム -->
            <div class="right-column">
                <section class="summary">
                    <div class="summary-box">
                        <p>商品代金</p>
                        <p>¥{{ number_format($itemData['price']) }}</p>
                    </div>
                    <div class="summary-box">
                        <p>支払い方法</p>
                        <p>{{ $itemData['payment_method'] }}</p>
                    </div>
                </section>

                <!-- 購入処理フォーム -->
                <form action="{{ route('purchase.process', ['item_id' => $itemData['id']]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="{{ $itemData['payment_method'] }}">
                    <input type="hidden" name="delivery_method" value="user-address">

                    @if ($errors->any())
                        <div class="error-message" style="color: red; margin-bottom: 10px;">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <button type="submit" class="btn-purchase">購入する</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>

