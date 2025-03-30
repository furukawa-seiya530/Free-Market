<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>住所の変更</title>
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
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
                <a href="{{ route('mypage') }}">マイページ</a>
                <a href="{{ route('sell.show') }}" class="btn-sell">出品</a>
            @else
                <a href="{{ route('login') }}">ログイン</a>
            @endauth
        </nav>
    </header>

    <main class="address-container">
        <h2 class="title">住所の変更</h2>

        @if ($errors->any())
            <div class="form-errors" style="color: red; margin-bottom: 1em;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('purchase.address.update', ['item_id' => $item_id]) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="postal_code">郵便番号</label>
                <input type="text" id="postal_code" name="postal_code"
                    value="{{ old('postal_code', $address->postal_code ?? '') }}">
                @error('postal_code')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="address">住所</label>
                <input type="text" id="address" name="address"
                    value="{{ old('address', $address->address ?? '') }}">
                @error('address')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="building">建物名</label>
                <input type="text" id="building" name="building"
                    value="{{ old('building', $address->building ?? '') }}">
                @error('building')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-submit">更新する</button>
        </form>
    </main>
</body>
</html>

