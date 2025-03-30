<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細ページ</title>
    <link rel="stylesheet" href="{{ asset('css/item.css') }}">
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

    <main class="item-container">
        <div class="item-content">
            <!-- 左側 商品画像 -->
            <div class="left-column">
                <div class="product-image">
                    <img src="{{ asset($item->image) }}" alt="商品画像">
                </div>
            </div>

            <!-- 右側 商品詳細 -->
            <div class="right-column">
                <h1 class="product-title">{{ $item->name }}</h1>
                <p class="brand-name">{{ $item->brand }}</p>
                <p class="product-price">¥{{ number_format($item->price) }} <span class="tax">(税込)</span></p>

                <div class="product-icons">
                    <span class="icon">💬 {{ count($comments) }}</span>

                    @auth
                        <form action="{{ route('like.toggle', ['item_id' => $item->id]) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" style="background: none; border: none; cursor: pointer;">
                                @if(auth()->user()->likes->contains('sell_id', $item->id))
                                    ❤️ いいね済み
                                @else
                                    🤍 いいね
                                @endif
                            </button>
                        </form>
                    @endauth
                </div>

                <a href="{{ route('purchase.show', ['item_id' => $item->id]) }}" class="btn-purchase">購入手続きへ</a>

                <hr class="divider">

                <section class="product-description">
                    <h2>商品説明</h2>
                    <p>カラー：{{ $item->color ?? '指定なし' }}</p>
                    <p>{{ $item->description }}</p>
                </section>

                <section class="product-info">
                    <h2>商品の情報</h2>
                    <div class="product-tags">
                        <span class="tag">{{ $item->category }}</span>
                        @if (!empty($item->gender))
                            <span class="tag">{{ $item->gender }}</span>
                        @endif
                    </div>
                    <p>商品の状態： <strong>{{ $item->status }}</strong></p>
                </section>

                <hr class="divider">


                <section class="comments">
                    <h2>コメント({{ count($comments) }})</h2>

                    @foreach($comments as $comment)
                    <div class="comment">
                        <div class="comment-user">
                            <img src="{{ asset(optional(optional($comment->user)->profile)->image ? 'storage/avatar/' . optional(optional($comment->user)->profile)->image : 'images/user-icon.png') }}" alt="User Icon">
                            <span class="comment-name">{{ $comment->user->name }}</span>
                        </div>
                        <p class="comment-text">{{ $comment->comment }}</p>
                    </div>
                    @endforeach

                    @auth
                    <form action="{{ route('comment.store', ['item_id' => $item->id]) }}" method="POST">
                        @csrf
                        <textarea name="comment" class="comment-input" placeholder="商品へのコメント"></textarea>
                        @error('comment')
                            <p style="color: red;">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="btn-comment">コメントを送信する</button>
                    </form>
                    @endauth
                </section>
            </div>
        </div>
    </main>
</body>
</html>
