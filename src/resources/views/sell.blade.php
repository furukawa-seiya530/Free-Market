<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品の出品</title>
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
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

    <main class="sell-container">
        <h1 class="title">商品の出品</h1>

        <form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <section class="image-upload">
                <label class="image-box" for="image">
                    <input type="file" name="image" id="image" class="file-input">
                    <img src="{{ asset('images/select-image.png') }}" alt="画像を選択する" class="preview-image">
                </label>
                @error('image')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </section>

            <section class="product-details">
                <h2 class="section-title">商品の詳細</h2>

                <label class="label">カテゴリー</label>
                <div class="category-list">
                    @php
                        $categories = [
                            'ファッション', '家電', 'インテリア', 'レディース', 'メンズ',
                            'コスメ', '本', 'ゲーム', 'スポーツ', 'キッチン',
                            'ハンドメイド', 'アクセサリー', 'おもちゃ', 'ベビー・キッズ'
                        ];
                        $oldCategories = old('category', []);
                    @endphp

                    @foreach ($categories as $cat)
                        <input
                            type="checkbox"
                            id="cat-{{ $loop->index }}"
                            name="category[]"
                            value="{{ $cat }}"
                            class="category-checkbox"
                            {{ in_array($cat, $oldCategories) ? 'checked' : '' }}
                        >
                        <label for="cat-{{ $loop->index }}" class="category-btn">{{ $cat }}</label>
                    @endforeach
                </div>
                @error('category')
                    <p class="form-error">{{ $message }}</p>
                @enderror

                <label class="label">商品の状態</label>
                <select name="status" class="select-box">
                    <option value="">選択してください</option>
                    <option {{ old('status') === '良好' ? 'selected' : '' }}>良好</option>
                    <option {{ old('status') === '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                    <option {{ old('status') === 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                    <option {{ old('status') === '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
                </select>
                @error('status')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </section>

            <section class="product-info">
                <h2 class="section-title">商品名と説明</h2>

                <label class="label">商品名</label>
                <input type="text" name="name" class="input-box" value="{{ old('name') }}">
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror

                <label class="label">ブランド名</label>
                <input type="text" name="brand" class="input-box" value="{{ old('brand') }}">

                <label class="label">商品の説明</label>
                <textarea name="description" class="textarea-box">{{ old('description') }}</textarea>
                @error('description')
                    <p class="form-error">{{ $message }}</p>
                @enderror

                <label class="label">販売価格</label>
                <input type="number" name="price" class="input-box" placeholder="¥" value="{{ old('price') }}">
                @error('price')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </section>

            <button type="submit" class="btn-submit">出品する</button>
        </form>
    </main>
</body>
</html>




