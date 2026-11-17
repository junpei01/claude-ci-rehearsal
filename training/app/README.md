# manual-converter

製品マニュアルの XML を HTML に変換します。

作成: 有限会社サンプルソフト（架空）

## 使い方

```
composer install
php bin/convert ../spec/samples/kt-200.xml > out.html
```

HTTP で使う場合:

```
php -S localhost:8000 -t public
curl -X POST --data-binary @../spec/samples/kt-200.xml http://localhost:8000/convert
```

## テスト

テストには PHPUnit 10.5 を使います。PHP 8.1 以上が必要です。

```
cd training/app
composer install      # 初回のみ（PHPUnit を含む開発用依存も入ります）
composer test         # 全テストを実行
```

特定のテストだけ実行する場合:

```
vendor/bin/phpunit tests/ManualParserTest.php
vendor/bin/phpunit --filter <テストメソッド名>
```

テストは `tests/` 配下にあります（設定は `phpunit.xml`）。

## 未対応

- JSON 出力
- 目次
