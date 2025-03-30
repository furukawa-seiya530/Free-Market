<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🏠 トップページ（ログイン不要）
Route::get('/', [MarketController::class, 'index'])->name('index');

// 🛒 マイページ関連（ログイン必須）
Route::middleware(['auth'])->group(function () {

    // 🔸 マイページ
    Route::get('/mypage', [MarketController::class, 'mypage'])->name('mypage');

    // 🔸 プロフィール編集
    Route::get('/mypage/profile', [MarketController::class, 'editProfile'])->name('profile.edit');
    Route::post('/mypage/profile', [MarketController::class, 'updateProfile'])->name('profile.update');

    // 🔸 購入ページ
    Route::get('/purchase/address/{item_id}', [MarketController::class, 'editAddress'])->name('purchase.address');
    Route::post('/purchase/address/{item_id}', [MarketController::class, 'updateAddress'])->name('purchase.address.update');
    Route::get('/purchase/{item_id}', [MarketController::class, 'showPurchasePage'])->name('purchase.show');
    Route::post('/purchase/{item_id}', [MarketController::class, 'processPurchase'])->name('purchase.process');

    // 🔸 商品出品
    Route::get('/sell', [MarketController::class, 'showSellPage'])->name('sell.show');
    Route::post('/sell', [MarketController::class, 'storeSell'])->name('sell.store');
});

// 🔍 商品詳細（ログイン不要）
Route::get('/item/{item_id}', [MarketController::class, 'showItem'])->name('item.show');

// 💬 コメント投稿（ログイン不要）
Route::post('/item/{item_id}/comment', [MarketController::class, 'storeComment'])->name('comment.store');

// ❤️ いいね機能（ログイン必須）
Route::post('/item/{item_id}/like', [MarketController::class, 'toggleLike'])
    ->name('like.toggle')
    ->middleware('auth');

// 🚪 Fortify の認証ビュー（ログイン・登録）
Route::get('/register', fn () => view('register'))->name('register');
Route::get('/login', fn () => view('login'))->name('login');

// 🚪 ログアウト（POST）
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

