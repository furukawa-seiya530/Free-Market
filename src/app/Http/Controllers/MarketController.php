<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sell;
use App\Models\User;
use App\Models\Profile;
use App\Models\Address;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Order;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ExhibitionRequest;

class MarketController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isMyList = $request->query('page') === 'mylist';
        $keyword = $request->query('keyword');

        if ($isMyList && $user) {
            $products = $user->likes()->with('item')->get()->pluck('item');
            if ($keyword) {
                $products = $products->filter(fn($item) => str_contains($item->name, $keyword));
            }
        } elseif ($user) {
            $query = Sell::where('user_id', '!=', $user->id);
            if ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            }
            $products = $query->orderBy('created_at', 'desc')->get();
        } else {
            $query = Sell::query();
            if ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            }
            $products = $query->orderBy('created_at', 'desc')->get();
        }

        return view('index', compact('products'));
    }

    public function showRegister() { return view('register'); }
    public function showLogin() { return view('login'); }

    public function mypage(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;
        $tab = $request->query('tab', 'sell');

        $products = $tab === 'sell'
            ? Sell::where('user_id', $user->id)->latest()->get()
            : Sell::whereIn('id', Order::where('user_id', $user->id)->pluck('sell_id'))->latest()->get();

        return view('mypage', compact('profile', 'products', 'tab'));
    }

    public function editProfile()
    {
        $user = auth()->user();
        $profile = $user->profile;
        $address = $user->address;

        return view('mypage-edit', compact('profile', 'address'));
    }

    public function updateProfile(AddressRequest $request)
    {
        $user = auth()->user();
        $updateType = $request->input('update_type', 'full');

        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            ['name' => $request->name]
        );

        Address::updateOrCreate(
            ['user_id' => $user->id],
            [
                'postal_code' => $request->postal_code,
                'address'     => $request->address,
                'building'    => $request->building,
            ]
        );

        if ($request->hasFile('image')) {
            $filename = $request->file('image')->store('avatar', 'public');
            $profile->image = basename($filename);
            $profile->save();
        }

        return $updateType === 'image'
            ? redirect()->route('profile.edit')->with('success', 'プロフィール画像を更新しました。')
            : redirect()->route('mypage')->with('success', 'プロフィール情報を更新しました。');
    }

    public function editAddress($item_id)
    {
        $user = auth()->user();
        $address = $user->address;

        return view('address', compact('item_id', 'address'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        Address::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'postal_code' => $request->postal_code,
                'address'     => $request->address,
                'building'    => $request->building,
            ]
        );

        return redirect()->route('purchase.show', ['item_id' => $item_id])
                        ->with('success', '住所が更新されました');
    }

    public function showPurchasePage(Request $request, $item_id)
    {
        $item = Sell::findOrFail($item_id);
        $user = auth()->user();
        $user->load('address');

        $address = Address::where('user_id', $user->id)->first();

        $itemData = [
            'id' => $item->id,
            'name' => $item->name,
            'price' => $item->price,
            'image' => $item->image,
            'payment_method' => $request->payment_method ?? 'コンビニ払い',
            'postal_code' => $address->postal_code ?? '未登録',
            'address' => ($address->address ?? '') . ' ' . ($address->building ?? ''),
        ];

        return view('purchase', compact('itemData'));
    }

    public function processPurchase(PurchaseRequest $request, $item_id)
    {
        if (Order::where('sell_id', $item_id)->exists()) {
            return back()->with('error', 'すでに購入されています');
        }

        $user = auth()->user();
        $address = $user->address;

        Order::create([
            'user_id' => $user->id,
            'sell_id' => $item_id,
            'payment_method' => $request->payment_method,
            'postal_code' => $address->postal_code,
            'address' => $address->address,
            'building' => $address->building,
        ]);

        return redirect()->route('index')->with('success', '購入が完了しました！');
    }

    public function showItem($item_id)
    {
        $item = Sell::findOrFail($item_id);
        $comments = Comment::where('item_id', $item_id)
            ->with('user.profile')
            ->latest()
            ->get();

        return view('item', compact('item', 'comments'));
    }

    public function showSellPage()
    {
        return view('sell');
    }

    public function storeSell(ExhibitionRequest $request)
    {
        $imagePath = $request->file('image')->store('items', 'public');

        Sell::create([
            'user_id' => auth()->id(),
            'image' => 'storage/' . $imagePath,
            'category' => is_array($request->category) ? implode(',', $request->category) : $request->category,
            'status' => $request->status,
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return redirect()->route('index')->with('success', '商品を出品しました！');
    }

    public function storeComment(CommentRequest $request, $item_id)
    {
        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $item_id,
            'comment' => $request->comment,
        ]);

        return redirect()->route('item.show', ['item_id' => $item_id])
                        ->with('success', 'コメントを投稿しました。');
    }

    public function toggleLike($item_id)
    {
        $user = auth()->user();
        $like = $user->likes()->where('sell_id', $item_id)->first();

        if ($like) {
            $like->delete();
        } else {
            $user->likes()->create([
                'sell_id' => $item_id,
                'user_id' => $user->id,
            ]);
        }

        return back();
    }
}
