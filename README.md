# claude-ci-rehearsal

Claude Code の GitHub Actions と、マージの前の確認を試すためのリハーサル用リポジトリです。

- `training/app/` は、製品マニュアルの XML を HTML に変換する PHP のプログラムです。会社名・製品名を含め、すべて架空です
- `training/spec/` は、その架空の仕様書とサンプルです
- `.github/workflows/` に、テスト・`@claude`・模擬の展開の3つのワークフローがあります

## テストの実行

```text
cd training/app
composer install
vendor/bin/phpunit
```
