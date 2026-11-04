<?php
/**
 * GEED Codex Renewal theme functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GEED_CODEX_VERSION', '1.5.5-a' );

require_once get_template_directory() . '/inc/content-structure.php';
require_once get_template_directory() . '/inc/article.php';
require_once get_template_directory() . '/inc/contact-handler.php';

function geed_codex_setup() {
	load_theme_textdomain( 'geed-codex-renewal', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 64,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'geed-codex-renewal' ),
		'footer'  => __( 'Footer Menu', 'geed-codex-renewal' ),
	) );
}
add_action( 'after_setup_theme', 'geed_codex_setup' );

function geed_codex_assets() {
	wp_enqueue_style(
		'geed-codex-fonts',
		'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&family=Noto+Serif+JP:wght@500;600;700&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'geed-codex-main', get_theme_file_uri( '/assets/css/main.css' ), array( 'geed-codex-fonts' ), GEED_CODEX_VERSION );
	// v2（renewal_spec.md §6）: 旧 main.css の後に読む。header/footer は全ページ v2、本文は body.geed-v2 のページだけ
	$deps = array( 'geed-codex-main' );
	foreach ( array( 'tokens', 'base', 'layout', 'components', 'motion' ) as $part ) {
		wp_enqueue_style( 'geed-v2-' . $part, get_theme_file_uri( '/assets/css/v2/' . $part . '.css' ), $deps, GEED_CODEX_VERSION );
		$deps = array( 'geed-v2-' . $part );
	}
	// 英字・数字の見出し用。使う文字だけに絞って読み込む（renewal_spec.md 2026-10-04 追加分）
	wp_enqueue_style( 'geed-v2-en-font', 'https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600&display=swap&text=' . rawurlencode( 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.,:/-–—' ), array(), null );
	wp_enqueue_style( 'geed-v2-sections', get_theme_file_uri( '/assets/css/v2/sections.css' ), array_merge( $deps, array( 'geed-v2-en-font' ) ), GEED_CODEX_VERSION );
	// 下層ページ固有の追加分: assets/css/v2/pages/<テンプレート名>.css があれば読む（例 page-first.css）
	$tpl = basename( (string) get_page_template_slug(), '.php' );
	if ( is_singular( array( 'post', 'geed_menu' ) ) ) {
		$tpl = 'single-article'; // コラムとトレーニングメニューは同じ枠
	} elseif ( ! $tpl && is_singular() ) {
		$tpl = 'single-' . get_post_type();
	}
	if ( $tpl && file_exists( get_theme_file_path( '/assets/css/v2/pages/' . $tpl . '.css' ) ) ) {
		wp_enqueue_style( 'geed-v2-page', get_theme_file_uri( '/assets/css/v2/pages/' . $tpl . '.css' ), array( 'geed-v2-sections' ), GEED_CODEX_VERSION );
	}
	// 記事内の図解（原稿の [[FIG 名前]]）。記事・メニュー・マシン紹介のどれにも出るので投稿ページ全部で読む
	if ( is_singular() ) {
		wp_enqueue_style( 'geed-v2-fig', get_theme_file_uri( '/assets/css/v2/fig.css' ), array( 'geed-v2-sections' ), GEED_CODEX_VERSION );
	}
	if ( is_front_page() ) {
		wp_enqueue_style( 'geed-v2-top', get_theme_file_uri( '/assets/css/v2/top.css' ), array( 'geed-v2-sections' ), GEED_CODEX_VERSION );
	}
	wp_enqueue_script( 'geed-codex-main', get_theme_file_uri( '/assets/js/main.js' ), array(), GEED_CODEX_VERSION, true );
	wp_enqueue_script( 'geed-v2-motion', get_theme_file_uri( '/assets/js/motion.js' ), array(), GEED_CODEX_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'geed_codex_assets' );

/**
 * v2 デザインに移行済みのページか。移行が済んだページをここに足していく（段階2: TOP・price・first・faq）
 */
function geed_codex_is_v2() {
	return (bool) apply_filters( 'geed_codex_is_v2', ! is_singular( array( 'post', 'geed_menu' ) ) ); // 記事は本文の旧スタイルに依存しているので別途移行
}

add_filter( 'body_class', function ( $classes ) {
	if ( geed_codex_is_v2() ) {
		$classes[] = 'geed-v2';
	}
	// SP固定バーの分だけフッター下に余白を取る（/contact/ はバーを出さない）
	if ( ! is_page_template( 'page-contact.php' ) && ! is_page( 'contact' ) ) {
		$classes[] = 'has-fixbar';
	}
	return $classes;
} );

/**
 * テーマ同梱アセットのURL。jpg/png は隣に .webp があればそちらを返す（変換は ../tools/webp.py）
 */
function geed_codex_asset( $path ) {
	$path = ltrim( $path, '/' );
	if ( preg_match( '/\.(jpe?g|png)$/i', $path ) ) {
		$webp = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $path );
		if ( file_exists( get_theme_file_path( '/assets/' . $webp ) ) ) {
			$path = $webp;
		}
	}
	return esc_url( get_theme_file_uri( '/assets/' . $path ) );
}

/**
 * <img> の width/height 属性（CLS 対策）。実際に配信するファイルの寸法を返す
 */
function geed_codex_img_size( $path ) {
	static $cache = array();
	$path = ltrim( $path, '/' );
	if ( ! isset( $cache[ $path ] ) ) {
		$file = get_theme_file_path( '/assets/' . $path );
		$webp = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $file );
		$size = @getimagesize( file_exists( $webp ) ? $webp : $file );
		$cache[ $path ] = $size ? sprintf( ' width="%d" height="%d"', $size[0], $size[1] ) : '';
	}
	return $cache[ $path ];
}

function geed_codex_cta() {
	return array(
		'line'      => get_theme_mod( 'geed_cta_line', 'https://line.me/R/ti/p/@594zfdro' ),
		'trial'     => get_theme_mod( 'geed_cta_trial', home_url( '/contact/' ) ),
		'tel'       => get_theme_mod( 'geed_cta_tel', '098-855-1777' ),
		'tel_link'  => 'tel:' . preg_replace( '/[^0-9+]/', '', get_theme_mod( 'geed_cta_tel', '098-855-1777' ) ),
		'instagram' => get_theme_mod( 'geed_cta_instagram', 'https://www.instagram.com/geed_style/' ),
		'address'   => get_theme_mod( 'geed_cta_address', '〒902-0065 沖縄県那覇市壺屋2丁目26番地11号 1F' ),
		'hours'     => get_theme_mod( 'geed_cta_hours', '07:00〜23:00' ),
		'closed'    => get_theme_mod( 'geed_cta_closed', 'メンテナンス休館日：毎月15日' ),
	);
}

function geed_codex_news() {
	return array(
		'label' => get_theme_mod( 'geed_news_label', 'お知らせ' ),
		'text'  => get_theme_mod( 'geed_news_text', '6周年記念キャンペーン実施中' ),
		'url'   => get_theme_mod( 'geed_news_url', home_url( '/price/' ) ),
		'video' => get_theme_mod( 'geed_news_video', '' ),
		'image' => geed_codex_mod_image_url( 'geed_news_image' ),
	);
}

/**
 * カスタマイザー画像の取得（未設定なら空文字を返す）
 */
function geed_codex_mod_image_url( $key ) {
	$val = get_theme_mod( $key, '' );
	if ( empty( $val ) ) {
		return '';
	}
	if ( is_numeric( $val ) ) {
		$url = wp_get_attachment_image_url( $val, 'large' ); // 原寸のビラは数MBあるので表示用サイズで出す
		return $url ?: '';
	}
	return $val;
}

/**
 * 入会キャンペーンの共通データ（TOPのヒーロー直下と料金ページで共用）
 * ビラ・Instagram のキャンペーンを見て来た人が、同じ内容をすぐ見つけられるようにする。管理画面「外観 > カスタマイズ」で入れ替える。
 * items: 1行 = ラベル | 強調テキスト
 * show: 「表示する」がオン かつ 終了日（空なら無期限）を過ぎていない
 */
function geed_codex_campaign() {
	$end  = trim( (string) get_theme_mod( 'geed_join_end', '' ) );
	$show = (bool) get_theme_mod( 'geed_join_show', true ) && ( '' === $end || current_time( 'Y-m-d' ) <= $end );
	return array(
		'show'   => $show,
		'period' => geed_codex_mod( 'geed_join_period', '' ),
		'image'  => geed_codex_mod_image_url( 'geed_join_image' ),
		'title'  => geed_codex_mod( 'geed_join_title', '6周年記念キャンペーン実施中！' ),
		'items' => geed_codex_lines( 'geed_join_items', array(
			array( '入会金 ¥11,000 →', '無料' ),
			array( '月会費 6ヶ月間', '半額' ),
		), 2 ),
		'note'   => geed_codex_mod( 'geed_join_note', '※キャンペーンの適用は6ヶ月間のご利用が条件となります' ),
	);
}

/**
 * 区切り文字 | で複数行に分けたデータをパースして二次元配列に。
 * 各行: フィールド1 | フィールド2 | フィールド3 ...
 * 空行はスキップ。
 *
 * @param string $key       theme_mod キー
 * @param array  $defaults  デフォルト値（[ [field1,field2,...], ... ]）
 * @param int    $field_count 1行あたりのフィールド数
 * @return array
 */
function geed_codex_lines( $key, $defaults = array(), $field_count = 0 ) {
	$raw = trim( (string) get_theme_mod( $key, '' ) );
	if ( $raw === '' ) {
		return $defaults;
	}
	$lines  = preg_split( '/\r\n|\r|\n/', $raw );
	$result = array();
	foreach ( $lines as $line ) {
		$line = trim( $line );
		if ( $line === '' ) {
			continue;
		}
		$fields = array_map( 'trim', explode( '|', $line ) );
		if ( $field_count > 0 ) {
			while ( count( $fields ) < $field_count ) {
				$fields[] = '';
			}
		}
		$result[] = $fields;
	}
	return empty( $result ) ? $defaults : $result;
}

/**
 * テキスト/URL/テキストエリアの単純取得ヘルパー。
 */
function geed_codex_mod( $key, $default = '' ) {
	$val = get_theme_mod( $key, $default );
	return $val === '' ? $default : $val;
}

/**
 * カスタマイザー画像の取得（添付ID or デフォルトasset path）
 */
function geed_codex_mod_image( $key, $default_asset_path ) {
	$attachment_id = get_theme_mod( $key, '' );
	if ( ! empty( $attachment_id ) ) {
		// theme_mod が attachment id or URL の両方を許容
		if ( is_numeric( $attachment_id ) ) {
			$url = wp_get_attachment_image_url( $attachment_id, 'full' );
			if ( $url ) {
				return $url;
			}
		} else {
			return $attachment_id; // already URL
		}
	}
	return geed_codex_asset( $default_asset_path );
}

function geed_codex_default_menu( $class = 'site-nav__list' ) {
	$home  = home_url( '/' );
	$front = is_front_page();

	// 下層ページからもアクセスできるよう、専用ページがある項目は固定URLに。
	// トップページ内アンカーで完結する項目は、下層からはトップへ戻ってジャンプ。
	$items = array(
		array( $front ? '#top'      : $home,                        'TOP' ),
		array( home_url( '/about/' ),                               'GEEDとは' ),
		array( home_url( '/facility/' ),                            '設備紹介' ),
		array( home_url( '/price/' ),                               '料金プラン' ),
		array( home_url( '/personal/' ),                            'パーソナル' ),
		array( home_url( '/freeday/' ),                             '無料開放DAY' ),
		array( $front ? '#access'   : $home . '#access',            'アクセス' ),
		array( home_url( '/faq/' ),                                 'FAQ' ),
	);

	echo '<ul class="' . esc_attr( $class ) . '">';
	foreach ( $items as $item ) {
		echo '<li><a href="' . esc_url( $item[0] ) . '">' . esc_html( $item[1] ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * 固定ページのtitle/descriptionをテーマで一元管理する（SEO_STRATEGY.md / MARKETING_STRATEGY.md）。
 * All in One SEO が有効ならそのフィルターで上書きし、無効ならテーマが自前で出力する。
 * 地図にない投稿・お知らせは AIOSEO 側（投稿ごとの設定）に任せる。
 */
function geed_codex_seo_meta() {
	$map = array(
		'front'     => array(
			'那覇市壺屋のジム｜初心者・女性歓迎 GEED Body Make Up Gym',
			'那覇市壺屋のフィットネスジムGEED。初心者・女性ひとりでも安心、勧誘なし。7:00〜23:00営業、パーソナルトレーニングも。無料開放DAYの日程はLINEで確認できます。',
		),
		'first'     => array(
			'初めての方へ｜ジム初心者の通い方・持ち物 | GEED 那覇',
			'ジムが初めての方へ。来店から入会までの流れ、持ち物、初日の過ごし方をまとめました。那覇市壺屋のGEEDは初心者・女性ひとりでも安心して通えるジムです。',
		),
		'price'     => array(
			'料金プラン｜那覇市壺屋のジム GEED',
			'GEED（那覇市壺屋）の月会費・各種プラン・入会時に必要なものをご案内します。キャンペーン情報もこちらから。',
		),
		'personal'  => array(
			'パーソナルトレーニング｜那覇市壺屋 GEED',
			'那覇市壺屋のGEEDのパーソナルトレーニング。ダイエット・ボディメイク・運動不足解消を、初心者にも無理のないペースでトレーナーがサポートします。',
		),
		'facility'  => array(
			'施設・マシン紹介｜InBody・Booty Builder | GEED 那覇',
			'GEED（那覇市壺屋）の館内とマシンを紹介。InBody270、Booty Builder Platinum、BE-BE、VEGA39 Plusなど、沖縄でも珍しい設備を初心者でも使えるようにご案内します。',
		),
		'freeday'   => array(
			'無料開放DAY｜那覇のジムを無料で体験 | GEED',
			'GEED（那覇市壺屋）の無料開放DAY。入会前にジムの雰囲気やマシンを無料で体験できます。勧誘はありません。開催日程はLINEでお知らせしています。',
		),
		'faq'       => array(
			'よくある質問｜那覇市壺屋のジム GEED',
			'GEED（那覇市壺屋）によくある質問。初心者でも大丈夫？予約は必要？持ち物は？営業時間は？など、入会前の疑問にお答えします。',
		),
		'corporate' => array(
			'法人プラン｜福利厚生・健康経営に | GEED 那覇',
			'那覇市壺屋のジムGEEDの法人プラン。従業員のジム利用を福利厚生として法人が負担でき、月締めの請求書払いに対応。健康経営の取り組みにもご活用ください。',
		),
		'about'     => array(
			'GEEDについて｜那覇市壺屋のボディメイクジム',
			'那覇市壺屋のボディメイクアップジムGEEDのコンセプト。勧誘ゼロ、会員の約6割が女性、朝7時〜夜23時。初心者が続けられるジムを目指しています。',
		),
		'column'    => array(
			'コラム｜ジム初心者・女性のためのトレーニング情報 | GEED 那覇',
			'那覇市壺屋のジムGEEDのコラム。ジムが初めての方、女性、ダイエット中の方に向けて、通い方・トレーニング・続けるコツをトレーナー目線でまとめています。',
		),
		'contact'   => array(
			'お問い合わせ・見学予約｜GEED 那覇市壺屋',
			'GEED（那覇市壺屋）へのお問い合わせ・見学予約はこちら。LINEからも気軽にご相談いただけます。',
		),
	);

	if ( is_front_page() ) {
		$key = 'front';
	} elseif ( is_page() ) {
		$key = get_post_field( 'post_name', get_queried_object_id() );
	} elseif ( is_home() ) {
		$key = 'column';
	} else {
		return apply_filters( 'geed_codex_seo_meta_extra', null );
	}
	return isset( $map[ $key ] ) ? array( 'title' => $map[ $key ][0], 'description' => $map[ $key ][1] ) : null;
}

add_filter( 'aioseo_title', function ( $title ) {
	$meta = geed_codex_seo_meta();
	return $meta ? $meta['title'] : $title;
} );

add_filter( 'aioseo_description', function ( $description ) {
	$meta = geed_codex_seo_meta();
	return $meta ? $meta['description'] : $description;
} );

add_filter( 'aioseo_facebook_tags', function ( $tags ) {
	$meta = geed_codex_seo_meta();
	if ( $meta ) {
		$tags['og:title']       = $meta['title'];
		$tags['og:description'] = $meta['description'];
	}
	$tags['og:site_name'] = 'GEED Body Make Up Gym';
	if ( is_front_page() ) {
		$tags['og:type'] = 'website';
	}
	if ( empty( $tags['og:image'] ) ) {
		$tags['og:image'] = get_theme_file_uri( '/assets/img/ogp.png' );
	}
	return $tags;
} );

add_filter( 'aioseo_twitter_tags', function ( $tags ) {
	$meta = geed_codex_seo_meta();
	if ( $meta ) {
		$tags['twitter:title']       = $meta['title'];
		$tags['twitter:description'] = $meta['description'];
	}
	return $tags;
} );

// AIOSEO 無効時のフォールバック（有効時は AIOSEO が title/description/OGP を出すので重複させない）
add_filter( 'pre_get_document_title', function ( $title ) {
	if ( defined( 'AIOSEO_VERSION' ) ) {
		return $title;
	}
	$meta = geed_codex_seo_meta();
	return $meta ? $meta['title'] : $title;
} );

function geed_codex_head_meta() {
	if ( defined( 'AIOSEO_VERSION' ) ) {
		// AIOSEO は本文が空のテンプレートページで description を出さない（フィルターも通らない）ので、その時だけ補う
		$meta = geed_codex_seo_meta();
		if ( $meta && function_exists( 'aioseo' ) && '' === (string) aioseo()->meta->description->getDescription() ) {
			echo '<meta name="description" content="' . esc_attr( $meta['description'] ) . '">' . "\n";
		}
		return;
	}
	$meta        = geed_codex_seo_meta();
	$description = $meta ? $meta['description'] : '那覇市壺屋の地域密着フィットネスジム GEED。初心者歓迎、手ぶらOK、女性も安心。LINEから見学・体験予約受付中。';
	?>
	<meta name="description" content="<?php echo esc_attr( $description ); ?>">
	<meta property="og:site_name" content="GEED Body Make Up Gym">
	<meta property="og:type" content="<?php echo is_front_page() ? 'website' : 'article'; ?>">
	<meta property="og:image" content="<?php echo esc_url( get_theme_file_uri( '/assets/img/ogp.png' ) ); ?>">
	<meta name="twitter:card" content="summary_large_image">
	<?php
}
add_action( 'wp_head', 'geed_codex_head_meta', 5 );

remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
// 使っていない head 出力を止める（絵文字スクリプト・短縮URL・oEmbed 検出・REST API の link）
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'rest_output_link_wp_head' );

// Google Fonts へ先に接続しておく
add_filter( 'wp_resource_hints', function ( $urls, $relation ) {
	if ( 'preconnect' === $relation ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}, 10, 2 );

// テーマの JS は描画を止めないよう defer
add_filter( 'script_loader_tag', function ( $tag, $handle ) {
	if ( in_array( $handle, array( 'geed-codex-main', 'geed-v2-motion' ), true ) && false === strpos( $tag, ' defer' ) ) {
		$tag = str_replace( ' src=', ' defer src=', $tag );
	}
	return $tag;
}, 10, 2 );

add_filter( 'excerpt_more', function () {
	return '...';
} );

/**
 * カスタム投稿: news（お知らせ） / voice（会員の声）
 */
add_action( 'init', function () {
	register_post_type( 'news', array(
		'label'        => 'お知らせ',
		'labels'       => array(
			'name'          => 'お知らせ',
			'singular_name' => 'お知らせ',
			'add_new'       => '新規お知らせ',
			'add_new_item'  => 'お知らせを追加',
			'edit_item'     => 'お知らせを編集',
			'all_items'     => '一覧',
		),
		'public'        => true,
		'has_archive'   => true,
		'menu_icon'     => 'dashicons-megaphone',
		'menu_position' => 6,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'rewrite'       => array( 'slug' => 'news' ),
		'show_in_rest'  => true,
	) );
	register_taxonomy( 'news_cat', 'news', array(
		'label'        => 'お知らせカテゴリー',
		'public'       => true,
		'hierarchical' => true,
		'rewrite'      => array( 'slug' => 'news-cat' ),
		'show_in_rest' => true,
	) );

	register_post_type( 'voice', array(
		'label'        => '会員の声',
		'labels'       => array(
			'name'          => '会員の声',
			'singular_name' => '会員の声',
			'add_new'       => '新規会員の声',
			'add_new_item'  => '会員の声を追加',
			'edit_item'     => '会員の声を編集',
			'all_items'     => '一覧',
		),
		'public'        => true,
		'has_archive'   => true,
		'menu_icon'     => 'dashicons-format-quote',
		'menu_position' => 7,
		'supports'      => array( 'title', 'editor', 'thumbnail' ),
		'rewrite'       => array( 'slug' => 'voice' ),
		'show_in_rest'  => true,
	) );
} );

/**
 * voice 投稿の追加メタフィールド
 */
add_action( 'add_meta_boxes', function () {
	add_meta_box( 'geed_codex_voice_meta', '会員プロフィール', 'geed_codex_voice_meta_box', 'voice', 'side' );
} );
function geed_codex_voice_meta_box( $post ) {
	wp_nonce_field( 'geed_codex_voice_meta', 'geed_codex_voice_nonce' );
	$g = get_post_meta( $post->ID, 'voice_gender', true );
	$a = get_post_meta( $post->ID, 'voice_age', true );
	$y = get_post_meta( $post->ID, 'voice_years', true );
	$o = get_post_meta( $post->ID, 'voice_occupation', true );
	?>
	<p><label>性別<br><input type="text" name="voice_gender" value="<?php echo esc_attr( $g ); ?>" placeholder="女性 / 男性" class="widefat"></label></p>
	<p><label>年代<br><input type="text" name="voice_age" value="<?php echo esc_attr( $a ); ?>" placeholder="30代" class="widefat"></label></p>
	<p><label>通っている期間<br><input type="text" name="voice_years" value="<?php echo esc_attr( $y ); ?>" placeholder="1年6ヶ月" class="widefat"></label></p>
	<p><label>職業・属性<br><input type="text" name="voice_occupation" value="<?php echo esc_attr( $o ); ?>" placeholder="会社員 / 主婦 など" class="widefat"></label></p>
	<?php
}
add_action( 'save_post_voice', function ( $post_id ) {
	if ( ! isset( $_POST['geed_codex_voice_nonce'] ) || ! wp_verify_nonce( $_POST['geed_codex_voice_nonce'], 'geed_codex_voice_meta' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	foreach ( array( 'voice_gender', 'voice_age', 'voice_years', 'voice_occupation' ) as $k ) {
		if ( isset( $_POST[ $k ] ) ) update_post_meta( $post_id, $k, sanitize_text_field( $_POST[ $k ] ) );
	}
} );

/**
 * news 投稿の追加メタフィールド（動画URL）
 * 設定するとTOPのお知らせ一覧でサムネイルクリック時に動画をモーダル再生、
 * 詳細ページでは記事上部に動画を埋め込み表示する。
 */
add_action( 'add_meta_boxes', function () {
	add_meta_box( 'geed_codex_news_video', '動画URL（YouTube）', 'geed_codex_news_video_meta_box', 'news', 'side' );
} );
function geed_codex_news_video_meta_box( $post ) {
	wp_nonce_field( 'geed_codex_news_video_meta', 'geed_codex_news_video_nonce' );
	$v = get_post_meta( $post->ID, 'geed_video_url', true );
	?>
	<p><label>YouTubeのURLを入力すると、TOPページのサムネイルクリックで動画が再生されます。<br>
	<input type="url" name="geed_video_url" value="<?php echo esc_attr( $v ); ?>" placeholder="https://www.youtube.com/watch?v=..." class="widefat"></label></p>
	<?php
}
add_action( 'save_post_news', function ( $post_id ) {
	if ( ! isset( $_POST['geed_codex_news_video_nonce'] ) || ! wp_verify_nonce( $_POST['geed_codex_news_video_nonce'], 'geed_codex_news_video_meta' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( isset( $_POST['geed_video_url'] ) ) update_post_meta( $post_id, 'geed_video_url', esc_url_raw( wp_unslash( $_POST['geed_video_url'] ) ) );
} );

/**
 * パンくず
 */
function geed_codex_breadcrumb() {
	$home   = '<a href="' . esc_url( home_url( '/' ) ) . '">TOP</a>';
	$crumbs = array( $home );

	if ( is_singular( 'news' ) || is_post_type_archive( 'news' ) ) {
		$crumbs[] = is_singular( 'news' )
			? '<a href="' . esc_url( get_post_type_archive_link( 'news' ) ) . '">お知らせ</a>'
			: 'お知らせ';
		if ( is_singular( 'news' ) ) $crumbs[] = get_the_title();
	} elseif ( is_singular( 'voice' ) || is_post_type_archive( 'voice' ) ) {
		$crumbs[] = is_singular( 'voice' )
			? '<a href="' . esc_url( get_post_type_archive_link( 'voice' ) ) . '">会員の声</a>'
			: '会員の声';
		if ( is_singular( 'voice' ) ) $crumbs[] = get_the_title();
	} elseif ( is_singular( 'geed_menu' ) || is_post_type_archive( 'geed_menu' ) || is_tax( 'menu_purpose' ) ) {
		$crumbs[] = is_post_type_archive( 'geed_menu' )
			? 'トレーニングメニュー'
			: '<a href="' . esc_url( get_post_type_archive_link( 'geed_menu' ) ) . '">トレーニングメニュー</a>';
		if ( is_tax( 'menu_purpose' ) ) $crumbs[] = single_term_title( '', false );
		if ( is_singular( 'geed_menu' ) ) $crumbs[] = get_the_title();
	} elseif ( is_singular( 'post' ) ) {
		$crumbs[] = '<a href="' . esc_url( geed_cs_column_url() ) . '">コラム</a>';
		$crumbs[] = get_the_title();
	} elseif ( is_home() ) {
		$crumbs[] = 'コラム';
	} elseif ( is_page() ) {
		foreach ( array_reverse( get_post_ancestors( get_queried_object_id() ) ) as $anc ) {
			$crumbs[] = '<a href="' . esc_url( get_permalink( $anc ) ) . '">' . esc_html( get_the_title( $anc ) ) . '</a>';
		}
		$crumbs[] = get_the_title();
	} elseif ( is_category() ) {
		$crumbs[] = '<a href="' . esc_url( geed_cs_column_url() ) . '">コラム</a>';
		$crumbs[] = single_cat_title( '', false );
	} elseif ( is_archive() ) {
		$crumbs[] = wp_kses_post( get_the_archive_title() );
	} elseif ( is_search() ) {
		$crumbs[] = '検索結果';
	} elseif ( is_404() ) {
		$crumbs[] = 'ページが見つかりません';
	}

	echo '<nav class="crumb" aria-label="パンくず"><ol class="crumb__list">';
	foreach ( $crumbs as $c ) echo '<li class="crumb__item">' . $c . '</li>';
	echo '</ol></nav>';
}

/**
 * 下層ヘッダー（v2: 左に墨のパネル＝パンくず・英字・H1・説明、右に写真。SPは写真が上）
 */
function geed_codex_page_hero( $args = array() ) {
	$d = array(
		'slug' => '',
		'en'   => '',
		'ja'   => '',
		'sub'  => '',
		'bg'   => 'interior-main.jpg',
	);
	$a = array_merge( $d, $args );

	// slug 指定があればカスタマイザー設定を優先する
	$bg_url  = geed_codex_asset( 'img/' . $a['bg'] );
	$bg_size = geed_codex_img_size( 'img/' . $a['bg'] );
	if ( ! empty( $a['slug'] ) ) {
		$a['en']  = geed_codex_mod( 'geed_page_' . $a['slug'] . '_en', $a['en'] );
		$a['ja']  = geed_codex_mod( 'geed_page_' . $a['slug'] . '_title', $a['ja'] );
		$a['sub'] = geed_codex_mod( 'geed_page_' . $a['slug'] . '_sub', $a['sub'] );
		$bg_mod   = get_theme_mod( 'geed_page_' . $a['slug'] . '_bg', '' );
		if ( ! empty( $bg_mod ) ) {
			$bg_url  = is_numeric( $bg_mod ) ? wp_get_attachment_image_url( $bg_mod, 'large' ) : $bg_mod;
			$bg_size = '';
		}
	}
	?>
	<section class="g-pagehead" aria-labelledby="page-title">
		<div class="g-pagehead__grid">
			<figure class="g-pagehead__photo"><img src="<?php echo esc_url( $bg_url ); ?>" alt=""<?php echo $bg_size; ?> fetchpriority="high" decoding="async"></figure>
			<div class="g-pagehead__body">
				<?php geed_codex_breadcrumb(); ?>
				<?php if ( $a['en'] ) : ?><p class="g-head__en" lang="en" aria-hidden="true"><?php echo esc_html( $a['en'] ); ?></p><?php endif; ?>
				<?php if ( $a['ja'] ) : ?><h1 id="page-title"><?php echo esc_html( $a['ja'] ); ?></h1><?php endif; ?>
				<?php if ( $a['sub'] ) : ?><p class="g-pagehead__sub"><?php echo wp_kses( $a['sub'], array( 'br' => array() ) ); ?></p><?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * 下層ページ末尾の共通CTA
 */
function geed_codex_page_cta( $where = 'page_end', $title = 'まずは、約60分の<br>無料体験から。' ) {
	$GLOBALS['geed_has_page_cta'] = true; // フッターの申込帯と重複させない
	?>
	<section class="g-final" aria-labelledby="final-title">
		<div class="g-container">
			<p class="g-head__en" lang="en" aria-hidden="true">FREE TRIAL</p>
			<h2 id="final-title"><?php echo wp_kses( $title, array( 'br' => array() ) ); ?></h2>
			<p class="g-final__lead">ウェア・シューズ・タオルは無料で借りられます。見学だけでも大丈夫です。勧誘はしません。</p>
			<div class="g-final__cta"><?php get_template_part( 'template-parts/trial-actions', null, array( 'where' => $where, 'note' => '体験予約：日曜・毎月15日を除く。' ) ); ?></div>
		</div>
	</section>
	<?php
}

/**
 * /llms.txt（AI 検索向けの事実まとめ）。料金・連絡先・キャンペーンは表示と同じ設定値から作る
 */
add_action( 'template_redirect', function () {
	if ( '/llms.txt' !== wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ) ) {
		return;
	}
	$cta      = geed_codex_cta();
	$campaign = geed_codex_campaign();
	$plans    = array();
	foreach ( geed_codex_price_plans() as $p ) {
		$plans[] = '- ' . $p[0] . '：' . $p[1] . ( 0 === strpos( $p[1], '¥' ) ? '／月（税込）' : '' ) . ( $p[3] ? '。' . $p[3] : '' );
	}
	$cp = '';
	if ( $campaign['show'] ) {
		$cp = "\n## 実施中のキャンペーン\n- " . $campaign['title'] . '：' . implode( '・', array_map( function ( $i ) { return trim( $i[0] . ' ' . $i[1] ); }, $campaign['items'] ) ) . ( $campaign['period'] ? '（' . $campaign['period'] . '）' : '' ) . "\n";
	}
	$u = function ( $path ) { return home_url( $path ); };

	status_header( 200 );
	header( 'Content-Type: text/plain; charset=utf-8' );
	echo "# GEED Body Make Up Gym\n\n";
	echo "> 沖縄県那覇市壺屋のトレーニングジム。ジムが初めての方向けに、約60分の無料体験でスタッフが館内とマシンの使い方を案内する。\n\n";
	echo "## 基本情報\n";
	echo '- 住所：' . $cta['address'] . "\n";
	echo '- 電話：' . $cta['tel'] . "\n";
	echo '- 営業時間：' . $cta['hours'] . "\n";
	echo '- ' . $cta['closed'] . "\n";
	echo "- 日曜はスタッフ不在のため、見学・体験・パーソナルの予約は受け付けていない\n";
	echo "- 提携駐車場：無料・10台\n";
	echo "- ウェア・シューズ・タオルのレンタルは無料\n";
	echo '- 予約・問い合わせ：LINE ' . $cta['line'] . ' ／ フォーム ' . $cta['trial'] . "\n";
	echo '- Instagram：' . $cta['instagram'] . "\n\n";
	echo "## 月額料金（税込）\n" . implode( "\n", $plans ) . "\n\n";
	echo "## パーソナルトレーニング（税込・1回60分）\n";
	foreach ( geed_codex_personal_plans() as $pp ) {
		echo '- ' . $pp[0] . '：' . $pp[1] . '／' . $pp[2] . "\n";
	}
	echo "- 月額プランと併用可（料金は加算）\n";
	echo $cp . "\n";
	echo "## 主なページ\n";
	foreach ( array(
		'/first/'     => 'はじめての方へ（無料体験の流れ）',
		'/price/'     => '料金プラン',
		'/facility/'  => '設備・マシン',
		'/personal/'  => 'パーソナルトレーニング',
		'/faq/'       => 'よくある質問',
		'/about/'     => 'ジムについて・アクセス',
		'/corporate/' => '法人プラン',
		'/contact/'   => '無料体験の申し込み',
	) as $path => $label ) {
		echo '- [' . $label . '](' . $u( $path ) . ")\n";
	}
	exit;
}, 0 );

/**
 * パーソナル料金（/personal/ の料金表と llms.txt で共用）。名前・価格・単位・説明・バッジ
 */
function geed_codex_personal_plans() {
	return array(
		array( '月4回プラン', '¥19,800', '月', '週1回ペース・60分セッション', '' ),
		array( '月8回プラン', '¥36,800', '月', '週2回ペース・60分セッション', '人気' ),
		array( '都度払い', '¥6,600', '回', '60分セッション・予約制', '' ),
	);
}

/**
 * 月額プラン（料金表・構造化データで共用）。カスタマイザー「【料金ページ】プラン詳細」(geed_price_plans) が正
 */
function geed_codex_price_plans() {
	return geed_codex_lines( 'geed_price_plans', array(
		array( 'プレミアムプラン', '¥15,400', '/ 月（税込）', 'パーソナルトレーニング月2回付きのプランです', '人気' ),
		array( 'スタンダードプラン', '¥9,900', '/ 月（税込）', '迷ったらまずはこちらの標準プラン', '' ),
		array( 'シニアプラン', '¥7,700', '/ 月（税込）', '60歳以上の方向けの特別価格です', '' ),
		array( 'デイプラン', '¥7,700', '/ 月（税込）', '平日7時〜17時までご利用可能なプランです', '' ),
		array( '学生プラン', '¥6,600', '/ 月（税込）', '24歳以下の学生の方向けの特別価格です', '' ),
		array( '法人プラン', 'お見積り', '', '福利厚生・従業員の健康促進に', '' ),
	), 5 );
}

/**
 * 構造化データ（全ページ: HealthClub＋WebSite / 記事: BlogPosting / 下層: BreadcrumbList）
 * AIOSEO の schema と @id が衝突しないよう、テーマ側は #gym / #website を使う
 */
add_action( 'wp_head', function () {
	$cta  = geed_codex_cta();
	$home = home_url( '/' );

	// 料金表と同じデータから Offer を作る（価格が数字のものだけ）
	$offers = array();
	foreach ( geed_codex_price_plans() as $plan ) {
		$price = preg_replace( '/[^0-9]/', '', $plan[1] );
		if ( 0 !== strpos( $plan[1], '¥' ) || '' === $price ) {
			continue;
		}
		$offers[] = array(
			'@type'              => 'Offer',
			'name'               => $plan[0],
			'description'        => $plan[3],
			'price'              => $price,
			'priceCurrency'      => 'JPY',
			'priceSpecification' => array( '@type' => 'UnitPriceSpecification', 'price' => $price, 'priceCurrency' => 'JPY', 'unitText' => '月', 'valueAddedTaxIncluded' => true ),
			'url'                => home_url( '/price/' ),
		);
	}

	// 毎月15日はメンテナンス休館。向こう12か月分の日付を出す
	$closed = array();
	$y      = (int) wp_date( 'Y' );
	$m      = (int) wp_date( 'n' ) + ( (int) wp_date( 'j' ) > 15 ? 1 : 0 );
	for ( $i = 0; $i < 12; $i++ ) {
		$d        = gmdate( 'Y-m-d', gmmktime( 0, 0, 0, $m + $i, 15, $y ) );
		$closed[] = array( '@type' => 'OpeningHoursSpecification', 'validFrom' => $d, 'validThrough' => $d, 'opens' => '00:00', 'closes' => '00:00' );
	}

	$data = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'HealthClub',
		'@id'         => $home . '#gym',
		'name'        => 'GEED Body Make Up Gym',
		'alternateName' => 'GEED',
		'description' => '那覇市壺屋のトレーニングジム。営業 7:00〜23:00、毎月15日はメンテナンス休館。日曜はスタッフ不在のため、見学・体験・パーソナルの予約は受け付けていません。無料体験は約60分でスタッフが館内とマシンの使い方を案内します。',
		'image'       => array(
			get_theme_file_uri( '/assets/img/interior-main.jpg' ),
			get_theme_file_uri( '/assets/img/interior-entrance.jpg' ),
			get_theme_file_uri( '/assets/img/ogp.png' ),
		),
		'logo'        => get_theme_file_uri( '/assets/img/logo.png' ),
		'url'         => $home,
		'telephone'   => $cta['tel'],
		'priceRange'  => '¥6,600〜',
		'hasMap'      => 'https://www.google.com/maps/search/?api=1&query=GEED%20Body%20Make%20Up%20Gym',
		'address'    => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => '壺屋2-26-11 1F',
			'addressLocality' => '那覇市',
			'addressRegion'   => '沖縄県',
			'postalCode'      => '902-0065',
			'addressCountry'  => 'JP',
		),
		'openingHoursSpecification' => array(
			array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array( 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday' ),
				'opens'     => '07:00',
				'closes'    => '23:00',
			),
		),
		'specialOpeningHoursSpecification' => $closed,
		'hasOfferCatalog' => array(
			'@type'           => 'OfferCatalog',
			'name'            => '月額プラン（税込）',
			'itemListElement' => $offers,
		),
		'sameAs' => array( $cta['instagram'] ),
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
} );

/**
 * カスタマイザー登録（外観 → カスタマイズ から編集可能）
 *
 * リスト系（複数カードを並べる項目）は textarea で1行=1レコード、フィールドは「 | 」区切り。
 * 空にするとデフォルト値が使われる。
 */
function geed_codex_customize_register( $wp_customize ) {
	// ============================================================
	// 共通ヘルパークロージャ
	// ============================================================
	$add_text = function( $section, $key, $label, $default, $type = 'text' ) use ( $wp_customize ) {
		$wp_customize->add_setting( $key, array(
			'default'           => $default,
			'sanitize_callback' => in_array( $type, array( 'url' ), true ) ? 'esc_url_raw' : 'sanitize_text_field',
		) );
		$wp_customize->add_control( $key, array(
			'label'   => $label,
			'section' => $section,
			'type'    => $type,
		) );
	};
	$add_textarea = function( $section, $key, $label, $default, $description = '' ) use ( $wp_customize ) {
		$wp_customize->add_setting( $key, array(
			'default'           => $default,
			'sanitize_callback' => 'wp_kses_post',
		) );
		$wp_customize->add_control( $key, array(
			'label'       => $label,
			'description' => $description,
			'section'     => $section,
			'type'        => 'textarea',
		) );
	};
	$add_image = function( $section, $key, $label ) use ( $wp_customize ) {
		$wp_customize->add_setting( $key, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control(
			new WP_Customize_Image_Control( $wp_customize, $key, array(
				'label'   => $label,
				'section' => $section,
			) )
		);
	};

	// ============================================================
	// 1. お知らせバナー
	// ============================================================
	$wp_customize->add_section( 'geed_news', array(
		'title'    => '【共通】お知らせバナー',
		'priority' => 30,
	) );
	$add_text( 'geed_news', 'geed_news_label', 'ラベル（例: お知らせ / メディア出演 / キャンペーン）', 'お知らせ' );
	$add_text( 'geed_news', 'geed_news_text', 'お知らせ本文', '6周年記念キャンペーン実施中' );
	$add_text( 'geed_news', 'geed_news_url', 'リンク先URL（キャンペーン/LINE等）', home_url( '/price/' ), 'url' );
	$add_text( 'geed_news', 'geed_news_video', '動画URL（YouTube・設定するとクリックで動画再生）', '', 'url' );
	$add_image( 'geed_news', 'geed_news_image', 'サムネイル画像（設定するとメインビジュアル上にカード表示）' );
	$add_text( 'geed_news', 'geed_topnews_title', 'TOPお知らせ一覧 見出し', 'お知らせ・メディア情報' );
	$add_text( 'geed_news', 'geed_topnews_count', 'TOPお知らせ一覧 表示件数', '3' );

	// ============================================================
	// 2. CTA・連絡先
	// ============================================================
	$wp_customize->add_section( 'geed_cta', array(
		'title'    => '【共通】CTA / 連絡先',
		'priority' => 31,
	) );
	$add_text( 'geed_cta', 'geed_cta_line', 'LINE URL', 'https://line.me/R/ti/p/@594zfdro', 'url' );
	$add_text( 'geed_cta', 'geed_cta_trial', '体験予約フォームURL', home_url( '/contact/' ), 'url' );
	$add_text( 'geed_cta', 'geed_cta_tel', '電話番号', '098-855-1777' );
	$add_text( 'geed_cta', 'geed_cta_instagram', 'Instagram URL', 'https://www.instagram.com/geed_style/', 'url' );
	$add_text( 'geed_cta', 'geed_cta_address', '住所', '〒902-0065 沖縄県那覇市壺屋2丁目26番地11号 1F' );
	$add_text( 'geed_cta', 'geed_cta_hours', '営業時間', '07:00〜23:00' );
	$add_text( 'geed_cta', 'geed_cta_closed', '休館日', 'メンテナンス休館日：毎月15日' );

	// ============================================================
	// 3. TOP - ヒーロー
	// ============================================================
	$wp_customize->add_section( 'geed_top_hero', array(
		'title'    => 'TOP - ヒーロー',
		'priority' => 40,
	) );
	$add_text( 'geed_top_hero', 'geed_hero_eyebrow', '見出し上の小タイトル', '那覇市壺屋・地域密着フィットネスジム' );
	$add_text( 'geed_top_hero', 'geed_hero_title_l1', 'メインコピー 1行目', '通えるジムが、' );
	$add_text( 'geed_top_hero', 'geed_hero_title_l2', 'メインコピー 2行目', '結局いちばん変われる。' );
	$add_textarea( 'geed_top_hero', 'geed_hero_lead', 'リード文', '初心者も、女性も、安心して選べる。生活に運動を戻すための、那覇のフィットネスジム。' );
	$add_image( 'geed_top_hero', 'geed_hero_image', 'メイン画像' );
	$add_textarea(
		'geed_top_hero', 'geed_hero_benefits', '特徴アイコン（最大4個）',
		"beginner.svg | 初心者歓迎 | サポート完備で安心\nempty-hand.svg | 手ぶらOK | 気軽に立ち寄れる\nclock.svg | 07:00-23:00 | 仕事前後にも便利\nwomen-safe.svg | 女性も安心 | 清潔で落ち着いた空間",
		'1行に1つ: アイコンファイル名 | タイトル | サブテキスト'
	);
	$add_text( 'geed_top_hero', 'geed_hero_cta1_text', 'CTA1 メインテキスト', '見学・体験を予約する' );
	$add_text( 'geed_top_hero', 'geed_hero_cta1_sub', 'CTA1 吹き出しテキスト', 'フォームで予約' );
	$add_text( 'geed_top_hero', 'geed_hero_cta1_url', 'CTA1 リンク先（空欄で体験予約URL）', '', 'url' );
	$add_text( 'geed_top_hero', 'geed_hero_cta2_text', 'CTA2 メインテキスト', 'LINEで開放DAYの日程を聞く' );
	$add_text( 'geed_top_hero', 'geed_hero_cta2_sub', 'CTA2 吹き出しテキスト', '登録だけでもOK' );
	$add_text( 'geed_top_hero', 'geed_hero_cta2_url', 'CTA2 リンク先（空欄でLINE URL）', '', 'url' );

	// ============================================================
	// 4. TOP - お悩み
	// ============================================================
	$wp_customize->add_section( 'geed_top_worry', array(
		'title'    => 'TOP - お悩み',
		'priority' => 41,
	) );
	$add_text( 'geed_top_worry', 'geed_worry_title', '見出し', 'こんなお悩みありませんか？' );
	$add_textarea(
		'geed_top_worry', 'geed_worries', 'お悩みカード（最大4個）',
		"worry-unknown.svg | ジムは初めてで不安... | 何をすればいいかわからない / マシンの使い方が不安 / 周りの目が気になる | pink\nworry-continue.svg | 続けられるか心配... | 仕事や予定で不規則 / 三日坊主になりそう / 自分のペースで通いたい | blue\nworry-confused.svg | 何をすればいいかわからない... | 目的に合うメニューが不安 / 正しいフォームを知りたい / 体の状態を確認したい | teal\nworry-crowd.svg | ムキムキの人ばかりで行きづらい... | 初心者でも浮かないか不安 / 一人で行っても大丈夫か心配 / 雰囲気になじめるか不安 | orange",
		'1行に1つ: アイコン | タイトル | 箇条書き（/ 区切り） | 色（pink/blue/teal/orange）'
	);

	// ============================================================
	// 5. TOP - 中CTA（初心者相談）
	// ============================================================
	$wp_customize->add_section( 'geed_top_mid_cta', array(
		'title'    => 'TOP - 中段CTA（初心者相談）',
		'priority' => 42,
	) );
	$add_text( 'geed_top_mid_cta', 'geed_mid_cta_eyebrow', '小見出し', '自分に合う通い方がわからない方へ' );
	$add_text( 'geed_top_mid_cta', 'geed_mid_cta_title', '見出し', 'まずはLINEで無料開放DAYの日程を確認できます。' );
	$add_text( 'geed_top_mid_cta', 'geed_mid_cta_btn1_text', 'ボタン1 テキスト', 'LINEで日程を聞く' );
	$add_text( 'geed_top_mid_cta', 'geed_mid_cta_btn2_text', 'ボタン2 テキスト', '見学を予約して相談する' );

	// ============================================================
	// 6. TOP - 初心者が安心できる理由
	// ============================================================
	$wp_customize->add_section( 'geed_top_reasons', array(
		'title'    => 'TOP - 初心者が安心できる理由',
		'priority' => 43,
	) );
	$add_text( 'geed_top_reasons', 'geed_reasons_title', '見出し', '初心者が安心できる理由' );
	$add_textarea(
		'geed_top_reasons', 'geed_reasons', '理由カード',
		"beginner.svg | reason-staff.jpg | マシンの使い方をスタッフがサポート | 初めてでも迷わず始めやすい\nclock.svg | reason-flexible.jpg | 朝7時〜夜23時まで通える | 生活リズムに合わせて続けやすい\nquality.svg | reason-equipment.jpg | 目的に合わせて選べる設備 | 体力づくりからボディメイクまで対応\nwomen-safe.svg | reason-women.jpg | 女性一人でも通いやすい空間 | 清潔感と落ち着きを大切にしています\nno-pushy.svg | reason-no-pressure.jpg | 無理な勧誘なし | 見学だけ、相談だけでも安心\nlocal.svg | reason-local.jpg | 那覇市壺屋で通いやすい | 近くで続けられる地域密着ジム",
		'1行に1つ: アイコン | 写真 | タイトル | 説明'
	);

	// ============================================================
	// 7. TOP - 通い始めて変わること
	// ============================================================
	$wp_customize->add_section( 'geed_top_changes', array(
		'title'    => 'TOP - 通い始めて変わること',
		'priority' => 44,
	) );
	$add_text( 'geed_top_changes', 'geed_changes_title', '見出し', '通い始めて変わること' );
	$add_textarea(
		'geed_top_changes', 'geed_changes', '変化カード（最大4個）',
		"clock.svg | change-habit.png | 運動習慣がつく | 週1回からでも、生活の中に運動を戻しやすく。 | pink\nbeginner.svg | change-stamina.png | 体力がつく | 階段や日常動作が少しラクになる変化を目指します。 | teal\nwomen-safe.svg | change-mood.png | 気分が前向きに | 汗をかく時間が、気持ちを切り替えるきっかけに。 | orange\nquality.svg | change-data.png | 体の状態がわかる | InBodyで変化を見ながら、自分に合うペースで継続。 | green",
		'1行に1つ: アイコン | 写真 | タイトル | 説明 | 色'
	);

	// ============================================================
	// 8. TOP - キャンペーン
	// ============================================================
	$wp_customize->add_section( 'geed_top_campaign', array(
		'title'    => 'TOP - キャンペーン（ヒーロー直下・料金ページ共通） / 無料開放DAY',
		'description' => 'ビラ・Instagramで告知中のキャンペーンと同じ内容にしてください。終了日を入れると、その翌日から自動で非表示になります。',
		'priority' => 45,
	) );
	$wp_customize->add_setting( 'geed_join_show', array(
		'default'           => true,
		'sanitize_callback' => function ( $v ) { return (bool) $v; },
	) );
	$wp_customize->add_control( 'geed_join_show', array(
		'label'   => 'キャンペーンを表示する',
		'section' => 'geed_top_campaign',
		'type'    => 'checkbox',
	) );
	$add_text( 'geed_top_campaign', 'geed_join_period', '期間の表示（例: 2026年10月31日まで）', '' );
	$add_text( 'geed_top_campaign', 'geed_join_end', '終了日（この日の翌日から自動で非表示。空なら無期限）', '', 'date' );
	$add_image( 'geed_top_campaign', 'geed_join_image', 'キャンペーン画像（ビラ・Instagramと同じ画像。任意）' );
	$add_text( 'geed_top_campaign', 'geed_join_title', '入会キャンペーン 見出し（TOP・料金ページ共通）', '6周年記念キャンペーン実施中！' );
	$add_textarea(
		'geed_top_campaign', 'geed_join_items', 'キャンペーン特典（TOP・料金ページ共通）',
		"入会金 ¥11,000 → | 無料\n月会費 6ヶ月間 | 半額",
		'1行に1つ: ラベル | 強調テキスト'
	);
	$add_text( 'geed_top_campaign', 'geed_join_note', '注釈（TOP・料金ページ共通）', '※キャンペーンの適用は6ヶ月間のご利用が条件となります' );
	$add_text( 'geed_top_campaign', 'geed_join_btn', 'ボタンテキスト', '料金プランを見る' );
	$add_text( 'geed_top_campaign', 'geed_free_title', '無料開放DAY 見出し', '無料開放DAY' );
	$add_text( 'geed_top_campaign', 'geed_free_sub', '無料開放DAY 説明', '見学だけでもOK！お気軽にお越しください' );
	$add_textarea( 'geed_top_campaign', 'geed_free_points', '無料開放DAYのポイント（1行1つ）', "見学だけOK\nマシンの使い方も体験\n初心者大歓迎" );
	$add_text( 'geed_top_campaign', 'geed_free_btn1', 'ボタン1（LINE）', 'LINEで日程を聞く' );
	$add_text( 'geed_top_campaign', 'geed_free_btn2', 'ボタン2（体験）', '見学・体験を予約する' );

	// ============================================================
	// 9. TOP - 黒帯CTA（見学だけOK）
	// ============================================================
	$wp_customize->add_section( 'geed_top_dark_cta', array(
		'title'    => 'TOP - 黒帯CTA（見学だけOK）',
		'priority' => 46,
	) );
	$add_text( 'geed_top_dark_cta', 'geed_dark_cta_eyebrow', '小見出し', '入会前に相談・体験できます' );
	$add_text( 'geed_top_dark_cta', 'geed_dark_cta_title', '見出し', '見学だけでもOK。無料開放DAYの日程をLINEで確認できます。' );
	$add_text( 'geed_top_dark_cta', 'geed_dark_cta_btn1', 'ボタン1', 'LINEで開放DAYの日程を聞く' );
	$add_text( 'geed_top_dark_cta', 'geed_dark_cta_btn2', 'ボタン2', '見学・体験を予約する' );

	// ============================================================
	// 10. TOP - 初回体験の流れ
	// ============================================================
	$wp_customize->add_section( 'geed_top_flow', array(
		'title'    => 'TOP - 初回体験の流れ',
		'priority' => 47,
	) );
	$add_text( 'geed_top_flow', 'geed_flow_title', '見出し', '初回体験の流れ' );
	$add_text( 'geed_top_flow', 'geed_flow_lead', 'リード', '何をするかわかると、最初の一歩はずっと軽くなります。' );
	$add_textarea(
		'geed_top_flow', 'geed_flow_steps', 'ステップ',
		"01 | LINE予約 | 希望日時を送るだけ。見学だけでもOKです。\n02 | 来店 | 那覇市壺屋の店舗へ。スタッフが受付します。\n03 | 館内案内 | ロッカー、マシン、利用方法をひと通りご案内。\n04 | マシン説明 | 初心者でも使いやすいように、基本操作を確認。\n05 | 体験スタート | 無理のない範囲で実際に動いてみます。所要時間は約60分。",
		'1行に1つ: 番号 | タイトル | 説明'
	);
	$add_text( 'geed_top_flow', 'geed_flow_note', '注釈', '所要時間：約60分 / 見学のみも相談できます' );

	// ============================================================
	// 11. TOP - 雰囲気を確認（口コミ / Instagram）
	// ============================================================
	$wp_customize->add_section( 'geed_top_social', array(
		'title'    => 'TOP - 来店前に雰囲気を確認',
		'priority' => 48,
	) );
	$add_text( 'geed_top_social', 'geed_social_title', '見出し', '来店前に雰囲気を確認' );
	$add_text( 'geed_top_social', 'geed_social_google_title', 'Googleカード タイトル', 'Google口コミを見る' );
	$add_textarea( 'geed_top_social', 'geed_social_google_text', 'Googleカード 説明', '地域ジムは実際の口コミも大切。来店前にGoogleマップで雰囲気を確認できます。' );
	$add_text( 'geed_top_social', 'geed_social_google_btn', 'Googleカード ボタンテキスト', 'Google口コミを確認' );
	$add_text( 'geed_top_social', 'geed_social_google_url', 'Googleカード リンク先', 'https://www.google.com/maps/search/?api=1&query=GEED%20Body%20Make%20Up%20Gym', 'url' );
	$add_text( 'geed_top_social', 'geed_social_ig_title', 'Instagramカード タイトル', 'Instagramで最新情報を見る' );
	$add_textarea( 'geed_top_social', 'geed_social_ig_text', 'Instagramカード 説明', 'キャンペーンや無料開放DAYなど、リアルタイムの情報はInstagramでも発信しています。' );
	$add_text( 'geed_top_social', 'geed_social_ig_btn', 'Instagramカード ボタンテキスト', 'Instagramを見る' );

	// ============================================================
	// 12. TOP - 設備マシン
	// ============================================================
	$wp_customize->add_section( 'geed_top_facility', array(
		'title'    => 'TOP - 充実の設備・マシン',
		'priority' => 49,
	) );
	$add_text( 'geed_top_facility', 'geed_facility_title', '見出し', '充実の設備・マシン' );
	$add_textarea(
		'geed_top_facility', 'geed_facilities', '設備カード',
		"facility-bebe.jpg | BE-BE | セルフ痩身エステマシン | パーソナルケアを気軽に取り入れられる人気設備。\nfacility-vega39.jpg | タンニングマシン | VEGA39 Plus | 短時間で全身を焼ける人気マシン。\nfacility-inbody.jpg | InBody270 | 高精度体組成計 | 体の状態を数値で可視化し、変化を追いやすく。\nfacility-booty.jpg | Booty Builder Platinum | 美尻特化マシン | お尻と脚まわりを集中して鍛えるマシン。\ninterior-main.jpg | その他マシン多数 | 充実のトレーニング環境 | 初心者から上級者まで満足できる設備を用意。",
		'1行に1つ: 画像 | タイトル | サブ | 説明'
	);

	// ============================================================
	// 13. TOP - 一人で不安な方も + パーソナル
	// ============================================================
	$wp_customize->add_section( 'geed_top_staff', array(
		'title'    => 'TOP - 一人で不安な方も + パーソナル誘導',
		'priority' => 50,
	) );
	$add_text( 'geed_top_staff', 'geed_staff_title', '見出し（スタッフ）', '一人で不安な方も、最初に相談できます' );
	$add_textarea( 'geed_top_staff', 'geed_staff_text', '説明', 'スタッフが在席している時間帯は、目的や不安に合わせて通い方をご提案します。マシンの使い方や、無理のない通い方の相談だけでも歓迎です。' );
	$add_text( 'geed_top_staff', 'geed_staff_btn', 'ボタン', '見学を予約して相談する' );
	$add_text( 'geed_top_staff', 'geed_personal_title', '見出し（パーソナル）', 'パーソナルトレーニングで理想のカラダへ' );
	$add_textarea( 'geed_top_staff', 'geed_personal_text', '説明', 'マンツーマンで、目的や体の状態に合わせたメニューを設計します。食事の相談や、フォームのチェックまで含めてサポート。' );
	$add_text( 'geed_top_staff', 'geed_personal_btn', 'ボタン', 'パーソナルの詳細を見る' );

	// ============================================================
	// 14. TOP - パーソナル後CTA
	// ============================================================
	$wp_customize->add_section( 'geed_top_after_personal_cta', array(
		'title'    => 'TOP - パーソナル後CTA',
		'priority' => 51,
	) );
	$add_text( 'geed_top_after_personal_cta', 'geed_apc_eyebrow', '小見出し', 'まず話を聞いてみたい方へ' );
	$add_text( 'geed_top_after_personal_cta', 'geed_apc_title', '見出し', 'あなたに合う通い方をLINEまたは体験で相談できます。' );
	$add_text( 'geed_top_after_personal_cta', 'geed_apc_btn1', 'ボタン1（LINE）', 'LINEで相談する' );
	$add_text( 'geed_top_after_personal_cta', 'geed_apc_btn2', 'ボタン2（体験）', '見学・体験を予約する' );

	// ============================================================
	// 15. TOP - 料金プラン
	// ============================================================
	$wp_customize->add_section( 'geed_top_price', array(
		'title'    => 'TOP - 料金プラン',
		'priority' => 52,
	) );
	$add_text( 'geed_top_price', 'geed_price_title', '見出し', '料金プラン' );
	$add_textarea(
		'geed_top_price', 'geed_plans', 'プランカード',
		"プレミアムプラン | パーソナル月2回付き | ¥15,400 / 月（税込） | マシン使い放題 / パーソナルトレーニング月2回 / レンタル・水・InBody無料\nスタンダードプラン | 迷ったらこちら | ¥9,900 / 月（税込） | マシン使い放題 / 07:00〜23:00利用可 / レンタル・水・InBody無料\nシニアプラン | 60歳以上の方 | ¥7,700 / 月（税込） | 60歳以上の方向けの特別価格 / マシン使い放題 / レンタル・水・InBody無料\nデイプラン | 平日おトクに | ¥7,700 / 月（税込） | 平日7時〜17時まで利用可能 / マシン使い放題 / レンタル・水・InBody無料\n学生プラン | 24歳以下の学生の方 | ¥6,600 / 月（税込） | 24歳以下の学生向け特別価格 / マシン使い放題 / レンタル・水・InBody無料",
		'1行に1つ: プラン名 | サブ | 金額 | 特徴（/ 区切り）'
	);
	$add_text( 'geed_top_price', 'geed_price_btn1', 'ボタン1', '料金一覧を見る' );
	$add_text( 'geed_top_price', 'geed_price_btn2', 'ボタン2', 'LINEで相談する' );

	// ============================================================
	// 15b. 料金ページ - プラン詳細
	// ============================================================
	$wp_customize->add_section( 'geed_price_page', array(
		'title'    => '【料金ページ】プラン詳細',
		'priority' => 53,
	) );
	$add_textarea(
		'geed_price_page', 'geed_price_plans', '料金プラン一覧',
		"プレミアムプラン | ¥15,400 | / 月（税込） | パーソナルトレーニング月2回付きのプランです | 人気\nスタンダードプラン | ¥9,900 | / 月（税込） | 迷ったらまずはこちらの標準プラン |\nシニアプラン | ¥7,700 | / 月（税込） | 60歳以上の方向けの特別価格です |\nデイプラン | ¥7,700 | / 月（税込） | 平日7時〜17時までご利用可能なプランです |\n学生プラン | ¥6,600 | / 月（税込） | 24歳以下の学生の方向けの特別価格です |\n法人プラン | お見積り |  | 福利厚生・従業員の健康促進に |",
		'1行に1つ: プラン名 | 金額 | 単位 | 説明 | タグ（人気など・省略可）'
	);
	$add_textarea(
		'geed_price_page', 'geed_price_common', '全プラン共通特典（1行1つ）',
		"トレーニングマシン使い放題\nミネラルウォーター飲み放題\nレンタルウェア・タオル・シューズ無料\n体成分分析装置【InBody】利用可"
	);

	// ============================================================
	// 16. TOP - FAQ + アクセス
	// ============================================================
	$wp_customize->add_section( 'geed_top_faq_access', array(
		'title'    => 'TOP - FAQ / アクセス',
		'priority' => 53,
	) );
	$add_text( 'geed_top_faq_access', 'geed_faq_title', 'FAQ 見出し', 'よくあるご質問' );
	$add_textarea(
		'geed_top_faq_access', 'geed_faqs', 'FAQ（最大10件）',
		"本当に初心者でも大丈夫ですか？ | 大丈夫です。マシンの使い方や通い方を、目的に合わせて丁寧にご案内します。\n一人で来る人は多いですか？ | お一人で来店される方も多いです。見学だけ、相談だけでも気軽にお越しください。\n運動経験ゼロでも平気ですか？ | 問題ありません。まずは無理なく続けられるメニューから始められます。\n体験・見学は予約が必要ですか？ | 初めての方の見学・体験、パーソナルトレーニングは事前予約制です。LINEから希望日時をお送りください。\n何を持って行けばいいですか？ | 動きやすい服装とお飲み物だけでOK。ウェア・シューズ・タオルは無料レンタルをご用意しています。\nどれくらいで慣れますか？ | 最初の数回で流れをつかむ方が多いです。不安な方は初回体験で館内案内から始められます。",
		'1行に1つ: 質問 | 回答'
	);
	$add_text( 'geed_top_faq_access', 'geed_access_title', 'アクセス 見出し', 'アクセス' );
	$add_text( 'geed_top_faq_access', 'geed_access_name', 'ジム名', 'GEED Body Make Up Gym' );

	// ============================================================
	// 17. TOP - Instagram帯
	// ============================================================
	$wp_customize->add_section( 'geed_top_instagram', array(
		'title'    => 'TOP - Instagram帯',
		'priority' => 54,
	) );
	$add_text( 'geed_top_instagram', 'geed_ig_title', 'Instagram 見出し', 'Instagram' );
	$add_textarea( 'geed_top_instagram', 'geed_ig_text', '説明', 'キャンペーン情報や、新マシン・スタッフからの一言などをタイムリーに発信中。' );
	$add_text( 'geed_top_instagram', 'geed_ig_btn', 'ボタン', 'Instagramをフォロー' );

	// ============================================================
	// 18-26. 下層ページ - hero とリード文だけ最低限編集可能に
	// ============================================================
	$pages = array(
		'about'     => array( 'GEEDとは',           '【下層】GEEDとは',           'ABOUT',     '通えるジムが、結局いちばん変われる。',                                  '初心者も女性も安心して通える、那覇のフィットネスジム。' ),
		'facility'  => array( '設備・マシン',       '【下層】設備・マシン',       'FACILITY',  '本気で取り組める、充実のマシンと環境。',                                 '初心者から上級者まで、目的に合わせて選べる設備をご用意。' ),
		'price'     => array( '料金プラン',         '【下層】料金プラン',         'PRICE',     '通いやすさを最優先にした料金。',                                         '通い方・年齢に合わせて選べる5つの月額プランをご用意しています。' ),
		'personal'  => array( 'パーソナル',         '【下層】パーソナル',         'PERSONAL',  'マンツーマンで理想のカラダへ。',                                         'プロのトレーナーが、目的・体の状態に合わせて寄り添います。' ),
		'freeday'   => array( '無料開放DAY',        '【下層】無料開放DAY',        'FREE DAY',  '見学だけでもOKの無料開放DAY。',                                          '入会前に雰囲気・マシン・スタッフを実際に確かめられます。' ),
		'first'     => array( '初めての方へ',       '【下層】初めての方へ',       'FIRST',     '初心者でも安心して始められます。',                                       '体験の流れ、持ち物、よくある不安まで、最初の一歩をやさしくご案内。' ),
		'faq'       => array( 'FAQ',                '【下層】FAQ',                'FAQ',       'よくあるご質問',                                                         '入会、料金、利用方法など、お問い合わせの多い内容をまとめました。' ),
		'contact'   => array( 'お問い合わせ',       '【下層】お問い合わせ',       'CONTACT',   'お気軽にご相談ください。',                                               'LINEまたはフォームから、見学・体験予約や質問を受け付けています。' ),
		'corporate' => array( '法人契約',           '【下層】法人契約',           'CORPORATE', '従業員の健康を、福利厚生で支える。',                                     '柔軟なプラン設計で、企業の健康促進をサポートいたします。' ),
	);
	$priority = 60;
	foreach ( $pages as $slug => $info ) {
		$section = 'geed_page_' . $slug;
		$wp_customize->add_section( $section, array(
			'title'    => $info[1],
			'priority' => $priority++,
		) );
		$add_text( $section, 'geed_page_' . $slug . '_en', '英語見出し（小）', $info[2] );
		$add_text( $section, 'geed_page_' . $slug . '_title', 'ヒーロータイトル', $info[3] );
		$add_textarea( $section, 'geed_page_' . $slug . '_sub', 'ヒーローサブ', $info[4] );
		$add_image( $section, 'geed_page_' . $slug . '_bg', 'ヒーロー背景画像' );
	}

	// ============================================================
	// 27. ヘッダー / フッター
	// ============================================================
	$wp_customize->add_section( 'geed_header_footer', array(
		'title'    => '【共通】ヘッダー / フッター',
		'priority' => 32,
	) );
	$add_image( 'geed_header_footer', 'geed_logo_image', 'ロゴ画像' );
	$add_text( 'geed_header_footer', 'geed_header_cta1', 'ヘッダーCTA1（体験予約）見出し', '無料体験' );
	$add_text( 'geed_header_footer', 'geed_header_cta1_sub', 'ヘッダーCTA1 サブ', 'Webで予約' );
	$add_text( 'geed_header_footer', 'geed_header_cta2', 'ヘッダーCTA2（LINE）見出し', '無料体験' );
	$add_text( 'geed_header_footer', 'geed_header_cta2_sub', 'ヘッダーCTA2 サブ', 'LINEで予約' );
	$add_text( 'geed_header_footer', 'geed_footer_text', 'フッター下部テキスト', '© GEED Body Make Up Gym. All Rights Reserved.' );
	$add_text( 'geed_header_footer', 'geed_fixed_cta1', '固定CTA1 テキスト', 'Webで体験予約' );
	$add_text( 'geed_header_footer', 'geed_fixed_cta2', '固定CTA2 テキスト', 'LINEで体験予約' );
}
add_action( 'customize_register', 'geed_codex_customize_register' );
