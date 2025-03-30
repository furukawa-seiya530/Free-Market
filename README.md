# フリマアプリ

## 概要

ログイン・会員登録から出品・購入・コメント・いいね・プロフィール編集・住所登録・マイページ機能まで実装した、Laravel製のフリマアプリです。

---

## 環境構築

### Dockerビルド手順

1. `git clone git@github.com:furukawa-seiya530/Free-Market.git`
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
8. `php artisan storage:link` (storage中の画像を使えるように)


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

## その他(重要)

- プロフィール画像・商品画像は Laravel の `storage/app/public` に保存され、`storage:link` により `/storage` に公開されます。
  
- ※出品画面ではJavaScriptを使用していないため、選択した画像のプレビューが表示されず、ユーザーが画像を選択できているか把握しにくい仕様となっている。
  
- ※マイページ編集機能は正常に動作しているが、画像プレビュー時にページのリロードが発生するため、ユーザー名などの必須項目が未入力だとバリデーションエラーが表示されることがあり、全項目を入力した後に画像を選択することでスムーズに登録が行える。
  
- ※シーディングを行う際は、まず `php artisan migrate:fresh` を実行してデータベースを初期化し、その後に1人分のユーザーをユーザー登録ページで作成してからシーディングを実行してください（※商品一覧にダミーデータを表示するためには、出品者となるユーザーが必要なためです）。
  　↑`php artisan migrate:fresh`を実行しないとクローンした際にマイグレーションのデータが入っているので商品画像が適切に表示されないエラーが発生します。
  
- ※envファイルを編集する際には以下の様に変更してください。(データベース接続エラーが起きます。)
　DB_CONNECTION=mysql
　DB_HOST=mysql
　DB_PORT=3306
　DB_DATABASE=laravel_db
　DB_USERNAME=root
　DB_PASSWORD=root
