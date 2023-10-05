# laravel-domestic-expenses

## Over View
家計簿WEBアプリケーション

## composition
- Nginx
- Laravel
- Breeze
- Inertia
- MySQL
- nodeJS
- Docker
- Redis

## install and Usage
### 1. コンテナビルド
```shell
docker compose build
```

### 2. 初期設定のためにコンテナ起動
```shell
docker compose up -d app db 
docker ps
docker compose ps
```

### 3. 初期設定(アプリケーション環境)
```shell
docker compose exec app bash
composer install
cp -p /var/www/app/.env.example /var/www/app/.env
php artisan key:generate
npm install
npm run build
exit
```
※フロントアプリケーションのインストールは[後続作業](#7.-全コンテナ起動)でnodeコンテナ起動時に自動で実行されるため割愛

### 4. 初期設定(データベース設定)
```shell
docker compose exec db bash
mysql -u root -proot -e "CREATE USER 'laravelUser' IDENTIFIED BY 'password000'"
mysql -u root -proot -e "GRANT all ON *.* TO 'laravelUser'"
mysql -u root -proot -e "FLUSH PRIVILEGES"
mysql -u root -proot -e "CREATE DATABASE laravel_sample"
exit
```
```shell
docker compose exec app bash
php artisan migrate:fresh --seed
exit
```

### 5. hosts設定
hosts に下記エントリーを追加
```shell
127.0.0.1 localhost.app.sample.jp
127.0.0.1 localhost.app-node.sample.jp
```

### 6. コンテナ停止
```shell
docker compose down
```

### 7. 全コンテナ起動
```shell
docker compose build
docker compose up -d
```

### 8. コンテナ確認
```shell
docker ps
docker compose ps
```

### 9. ログイン
```
http://localhost.app.sample.jp/login
```
```
Email : test@test.com
Password : password123
```