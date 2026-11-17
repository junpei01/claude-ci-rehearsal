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

```
composer test
```

## 未対応

- JSON 出力
- 目次
