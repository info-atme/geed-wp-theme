# GEED Codex Renewal — WordPress テーマ

沖縄のボディメイクジム **GEED Body Make Up Gym** の公式サイト用に作った自作 WordPress テーマ。
既存テーマの子テーマではなく、テンプレート階層・カスタム投稿タイプ・問い合わせフォームまで独自実装している。

- WordPress 6.0 以上 / PHP 7.4 以上
- ビルドツール不使用。素の PHP + CSS + バニラ JS で構成
- Google Fonts 以外の外部依存なし
- 写真素材（`assets/img/`）は権利の都合でリポジトリに含めていない

## 構成

| 種別 | ファイル |
|------|---------|
| 共通 | `header.php` / `footer.php` / `index.php` / `home.php` / `single.php` / `page.php` / `404.php` |
| トップ | `front-page.php` |
| 固定ページ | `page-about` / `page-price` / `page-personal` / `page-facility` / `page-machine` / `page-first` / `page-freeday` / `page-online` / `page-corporate` / `page-faq` / `page-contact` / `page-privacypolicy` |
| メニュー | `archive-geed_menu.php` / `single-geed_menu.php` / `taxonomy-menu_purpose.php` |
| 部品 | `template-parts/price-table.php` / `template-parts/trial-actions.php` |
| ロジック | `inc/content-structure.php`（メニューCPT・目的別ハブ）/ `inc/article.php`（記事の構造化データ）/ `inc/contact-handler.php`（問い合わせ） |
| アセット | `assets/css/v2/`（tokens → base → layout → components → sections → pages の層構成）/ `assets/js/main.js` / `assets/js/motion.js` / `assets/icons/*.svg` |

## 実装している機能

- **カスタム投稿タイプ 3種** — `geed_menu`（トレーニングメニュー。タクソノミー `menu_purpose` で目的別ハブを生成）、
  `news`（お知らせ）、`voice`（お客様の声）。メタボックスは nonce 検証のうえサニタイズして保存する。
- **テーマカスタマイザー** — 店舗情報・料金・キャンペーン・問い合わせ通知先を管理画面から編集でき、
  テンプレートにハードコードしない。キャンペーンは終了日を過ぎると自動で非表示になる。
- **問い合わせフォーム** — `admin_post` / `admin_post_nopriv` で受ける自作フォーム。nonce・サーバー側検証・
  二重送信防止トークン・自動返信メール。プラグインに依存しない。
- **SEO / 構造化データ** — 固定ページの title / description をテーマで一元管理（AIOSEO フィルター）、
  FAQPage・記事・店舗の JSON-LD を出力。
- **CSS 設計** — デザイントークン（`tokens.css`）から積み上げる層構成。`motion.js` は
  `prefers-reduced-motion` を考慮したスクロール演出。

## セットアップ

1. `wp-content/themes/` に配置してテーマを有効化する。
2. パーマリンク設定を保存して、カスタム投稿タイプのリライトルールを反映させる。
3. 固定ページを各テンプレートのスラッグ（`about` / `price` / `personal` / `facility` /
   `first` / `freeday` / `corporate` / `faq` / `contact` / `privacypolicy`）で作成する。
   トップ用の固定ページを作り「設定 → 表示設定」でホームページに指定する。
4. 外観 → カスタマイズ から店舗情報・料金・問い合わせ通知先を設定する。
5. `news` / `voice` は投稿から登録する。

## 画像素材について

このリポジトリには **`assets/img/` の画像を含めていない**（`.gitignore` で除外）。
人物写真・ロゴ・キャンペーンバナーは肖像権と素材ライセンスの対象のため、
コードのみを公開している。テーマを動かす場合は同じパス構成で画像を配置する。
`assets/icons/` の SVG はテーマ用に作成したものなので同梱している。

## ライセンス

GPL-2.0-or-later（WordPress テーマの慣例に従う）。店舗名・ロゴ・写真等の素材は
GEED に帰属し、ライセンスの対象外。
