# フリマアプリ

## 概要

ログイン・会員登録から出品・購入・コメント・いいね・プロフィール編集・住所登録・マイページ機能まで実装した、Laravel製のフリマアプリです。

---

## 環境構築

### Dockerビルド手順

1. `git clone <リポジトリURL>`
2. `docker-compose up -d --build`

> ※ MySQLが立ち上がらない場合、OSに応じて `docker-compose.yml` を調整してください。

---

### Laravelセットアップ

1. `docker-compose exec php bash`
2. `composer install`
3. `.env.example` をコピーして `.env` を作成
4. `.env` の環境変数を適切に編集
5. `php artisan key:generate`
6. `php artisan migrate`
7. `php artisan db:seed` （初期データ投入が必要な場合）

---

## 使用技術

- **PHP** 8.0  
- **Laravel** 10  
- **MySQL** 8.0  
- **Docker / Docker Compose**  
- **Bladeテンプレート** / **CSS（Sanitize + カスタムCSS）**  
- **dbdiagram.io** によるER図管理

---

## 主な機能

- ユーザー登録 / ログイン / ログアウト（Fortify利用）
- 商品一覧・検索
- 商品詳細（コメント・いいね機能）
- 商品出品（画像アップロード含む）
- 商品購入（支払い方法選択、住所確認）
- マイページ（出品一覧 / 購入一覧のタブ切り替え）
- プロフィール編集（画像プレビュー、住所入力）
- いいね機能（マイリスト表示対応）
- レスポンシブ対応（一部）

---

## URL（開発用）

- フロントエンド: [http://localhost](http://localhost)  
- phpMyAdmin: [http://localhost:8080](http://localhost:8080)

---

## その他

- プロフィール画像・商品画像は Laravel の `storage/app/public` に保存され、`storage:link` により `/storage` に公開されます。
- 商品画像クリックで詳細ページへ遷移します（ルート：`/item/{id}`）。
- コメント・いいねはAjax未使用、フォームベースで実装。
- 支払い方法の変更や画像アップロードはJavaScript不使用で実現しています。
