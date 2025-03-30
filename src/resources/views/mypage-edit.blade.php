<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール設定</title>
    <link rel="stylesheet" href="{{ asset('css/mypage-edit.css') }}">
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

    <main class="profile-form">
        <h2 class="title">プロフィール設定</h2>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <input type="hidden" name="update_type" value="full" id="updateType">

            <div class="profile-image-container">
                <div class="profile-image">
                    @if ($profile && $profile->image)
                        <img src="{{ asset('storage/avatar/' . $profile->image) }}" alt="プロフィール画像">
                    @endif
                </div>
                <input
                    type="file"
                    name="image"
                    accept=".jpg,.jpeg,.png"
                    id="image"
                    style="display: none;"
                    onchange="document.getElementById('updateType').value='image'; this.form.submit();"
                >
                <label for="image" class="btn-upload">画像を選択する</label>
            </div>

            <!-- ユーザー名 -->
            <div class="form-group">
                <label for="name">ユーザー名</label>
                <input type="text" id="name" name="name" value="{{ old('name', $profile->name ?? '') }}">
                @error('name')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- 郵便番号 -->
            <div class="form-group">
                <label for="postal-code">郵便番号</label>
                <input type="text" id="postal-code" name="postal_code" value="{{ old('postal_code', $address->postal_code ?? '') }}">
                @error('postal_code')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- 住所 -->
            <div class="form-group">
                <label for="address">住所</label>
                <input type="text" id="address" name="address" value="{{ old('address', $address->address ?? '') }}">
                @error('address')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- 建物名 -->
            <div class="form-group">
                <label for="building">建物名</label>
                <input type="text" id="building" name="building" value="{{ old('building', $address->building ?? '') }}">
                @error('building')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-submit">更新する</button>
        </form>
    </main>
</body>
</html>


