<?php
/**
 * コンテンツ構造（content/サイト設計.md §3〜§4）
 *
 * - CPT geed_menu（/menu/<slug>/）: ユーザー像 × 目的 × GEEDの実機 のトレーニングメニュー
 * - タクソノミー menu_purpose（/menu/purpose/<目的>/）: 目的別ハブ
 * - マシン別ページ: /facility/ の子ページ＋テンプレート page-machine.php（スラッグ = マシンキー）
 * - コラム: 投稿ページ /column/（home.php）
 * - 自動の内部リンク・ExercisePlan JSON-LD・LINEクリック計測用のページ種別
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * 定義（キーは原稿mdの front matter と geed_post.py で共通）
 * ---------------------------------------------------------------------- */

function geed_cs_personas() {
	return array(
		'U1' => '運動未経験の女性',
		'U2' => '美尻・ボディラインを作りたい女性',
		'U3' => '産後ママ',
		'U4' => 'ダイエットしたい会社員',
		'U5' => '短期で結果を出したい人',
		'U6' => 'シニア（60歳以上）',
		'U7' => '自己流で停滞している中級者',
	);
}

/** マシンキー => 表示名。キーは /facility/<key>/ の子ページのスラッグ */
function geed_cs_machines() {
	return array(
		'inbody'        => 'InBody270',
		'booty-builder' => 'Booty Builder Platinum',
		'bebe'          => 'BE-BE',
		'vega39'        => 'VEGA39 Plus',
		'cardio'        => '有酸素エリア',
		'free-weight'   => 'フリーウェイト・ストレッチ',
	);
}

/** プランキー => 表示名・URL。価格は /price/ を正にするのでここには持たない */
function geed_cs_plans() {
	return array(
		'standard' => array( 'スタンダード', '/price/' ),
		'premium'  => array( 'プレミアム（パーソナル月2回付き）', '/price/' ),
		'personal' => array( 'パーソナルトレーニング', '/personal/' ),
		'senior'   => array( 'シニアプラン', '/price/' ),
		'day'      => array( 'デイプラン', '/price/' ),
		'student'  => array( '学生プラン', '/price/' ),
		'online'   => array( 'オンライン', '/online/' ),
		'corporate' => array( '法人プラン', '/corporate/' ),
	);
}

/** 監修者。資格は /personal/ の表記に合わせる */
function geed_cs_supervisors() {
	return array(
		'miyamoto' => array(
			'name'     => '宮本達矢',
			'job'      => 'GEED トレーナー',
			'creds'    => array( '加圧ジャパン認定インストラクター', '元JBCトレーナーライセンス', 'アスリート栄養管理アドバイザー' ),
			'url'      => '/personal/',
		),
	);
}

/** コラムのカテゴリ => 関連づける目的（関連メニューの自動補完に使う） */
function geed_cs_category_purpose() {
	return array(
		'diet'     => 'diet',
		'women'    => 'hip',
		'health'   => 'health',
		'beginner' => '',
		'habit'    => '',
		'home'     => '',
	);
}

/* -------------------------------------------------------------------------
 * 登録
 * ---------------------------------------------------------------------- */

add_action( 'init', function () {
	// タクソノミーを先に登録し、/menu/purpose/<slug>/ の書き換えルールを /menu/<slug>/ より優先させる
	register_taxonomy( 'menu_purpose', 'geed_menu', array(
		'labels'            => array(
			'name'          => '目的',
			'singular_name' => '目的',
			'all_items'     => '目的一覧',
			'edit_item'     => '目的を編集',
			'add_new_item'  => '目的を追加',
		),
		'public'            => true,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'menu/purpose', 'with_front' => false ),
	) );

	register_post_type( 'geed_menu', array(
		'labels'        => array(
			'name'          => 'トレーニングメニュー',
			'singular_name' => 'メニュー',
			'add_new'       => '新規メニュー',
			'add_new_item'  => 'メニューを追加',
			'edit_item'     => 'メニューを編集',
			'all_items'     => '一覧',
		),
		'public'        => true,
		'has_archive'   => 'menu',
		'menu_icon'     => 'dashicons-universal-access',
		'menu_position' => 8,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
		'rewrite'       => array( 'slug' => 'menu', 'with_front' => false ),
		'show_in_rest'  => true,
		'taxonomies'    => array( 'menu_purpose' ),
	) );

	$menu_meta = array(
		'geed_persona', 'geed_goal', 'geed_duration', 'geed_frequency', 'geed_machines',
		'geed_cautions', 'geed_plan', 'geed_plan_reason', 'geed_supervisor',
	);
	foreach ( $menu_meta as $key ) {
		register_post_meta( 'geed_menu', $key, array( 'type' => 'string', 'single' => true, 'show_in_rest' => true ) );
	}
	foreach ( array( 'geed_related_menus', 'geed_related_machines', 'geed_plan' ) as $key ) {
		register_post_meta( 'post', $key, array( 'type' => 'string', 'single' => true, 'show_in_rest' => true ) );
	}
} );

/* -------------------------------------------------------------------------
 * 管理画面の入力欄
 * ---------------------------------------------------------------------- */

function geed_cs_menu_fields() {
	return array(
		'geed_persona'     => array( 'ユーザー像（U1〜U7）', 'text', 'U2' ),
		'geed_goal'        => array( 'ゴール', 'text', '4週間でヒップスラストのフォームを固める' ),
		'geed_duration'    => array( '所要時間（分）', 'number', '45' ),
		'geed_frequency'   => array( '頻度', 'text', '週2回' ),
		'geed_machines'    => array( '使う実機（マシンキーか名称をカンマ区切り・順番どおり）', 'text', 'booty-builder, アングルドレッグプレス, bebe' ),
		'geed_cautions'    => array( '注意（1行1項目）', 'textarea', '腰を反らさない' ),
		'geed_plan'        => array( '合うプラン（キー）', 'text', 'premium' ),
		'geed_plan_reason' => array( '合うプランの理由', 'text', 'トレーナーがフォームを確認できるため' ),
		'geed_supervisor'  => array( '監修者（キー）', 'text', 'miyamoto' ),
	);
}

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'geed_cs_menu', 'メニューの項目', 'geed_cs_menu_meta_box', 'geed_menu', 'normal', 'high' );
	add_meta_box( 'geed_cs_post', '関連リンク（メニュー・マシン・プラン）', 'geed_cs_post_meta_box', 'post', 'side' );
} );

function geed_cs_menu_meta_box( $post ) {
	wp_nonce_field( 'geed_cs_meta', 'geed_cs_nonce' );
	echo '<p>マシンキー: ' . esc_html( implode( ' / ', array_keys( geed_cs_machines() ) ) ) . '　プランキー: ' . esc_html( implode( ' / ', array_keys( geed_cs_plans() ) ) ) . '</p>';
	foreach ( geed_cs_menu_fields() as $key => $f ) {
		$v = get_post_meta( $post->ID, $key, true );
		echo '<p><label><strong>' . esc_html( $f[0] ) . '</strong><br>';
		if ( 'textarea' === $f[1] ) {
			echo '<textarea name="' . esc_attr( $key ) . '" rows="4" class="widefat" placeholder="' . esc_attr( $f[2] ) . '">' . esc_textarea( $v ) . '</textarea>';
		} else {
			echo '<input type="' . esc_attr( $f[1] ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $v ) . '" class="widefat" placeholder="' . esc_attr( $f[2] ) . '">';
		}
		echo '</label></p>';
	}
}

function geed_cs_post_meta_box( $post ) {
	wp_nonce_field( 'geed_cs_meta', 'geed_cs_nonce' );
	$fields = array(
		'geed_related_menus'    => 'メニューのスラッグ（カンマ区切り）',
		'geed_related_machines' => 'マシンキー（カンマ区切り）',
		'geed_plan'             => '合うプラン（キー）',
		'geed_supervisor'       => '監修者（キー。空なら miyamoto）',
	);
	foreach ( $fields as $key => $label ) {
		echo '<p><label>' . esc_html( $label ) . '<br><input type="text" name="' . esc_attr( $key ) . '" value="' . esc_attr( get_post_meta( $post->ID, $key, true ) ) . '" class="widefat"></label></p>';
	}
}

add_action( 'save_post', function ( $post_id, $post ) {
	if ( ! isset( $_POST['geed_cs_nonce'] ) || ! wp_verify_nonce( $_POST['geed_cs_nonce'], 'geed_cs_meta' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	$keys = 'geed_menu' === $post->post_type
		? array_keys( geed_cs_menu_fields() )
		: array( 'geed_related_menus', 'geed_related_machines', 'geed_plan', 'geed_supervisor' );
	foreach ( $keys as $k ) {
		if ( ! isset( $_POST[ $k ] ) ) continue;
		$v = wp_unslash( $_POST[ $k ] );
		update_post_meta( $post_id, $k, 'geed_cautions' === $k ? sanitize_textarea_field( $v ) : sanitize_text_field( $v ) );
	}
}, 10, 2 );

/* -------------------------------------------------------------------------
 * 取得ヘルパー
 * ---------------------------------------------------------------------- */

function geed_cs_split( $value ) {
	return array_values( array_filter( array_map( 'trim', preg_split( '/[,、\n]/u', (string) $value ) ) ) );
}

/** 公開済みのマシン別ページ（キー => WP_Post） */
function geed_cs_machine_pages() {
	static $pages = null;
	if ( null !== $pages ) return $pages;
	$pages  = array();
	$parent = get_page_by_path( 'facility' );
	if ( ! $parent ) return $pages;
	foreach ( get_pages( array( 'parent' => $parent->ID, 'post_status' => 'publish' ) ) as $p ) {
		if ( isset( geed_cs_machines()[ $p->post_name ] ) ) $pages[ $p->post_name ] = $p;
	}
	return $pages;
}

/** マシン（キーか自由記述）を表示用に解決する: array( label, url|null ) */
function geed_cs_machine( $key_or_label ) {
	$machines = geed_cs_machines();
	if ( ! isset( $machines[ $key_or_label ] ) ) return array( $key_or_label, null );
	$pages = geed_cs_machine_pages();
	return array( $machines[ $key_or_label ], isset( $pages[ $key_or_label ] ) ? get_permalink( $pages[ $key_or_label ] ) : null );
}

function geed_cs_plan( $key ) {
	$plans = geed_cs_plans();
	if ( ! isset( $plans[ $key ] ) ) return null;
	if ( 'online' === $key && ! get_page_by_path( 'online' ) ) {
		return array( $plans[ $key ][0], geed_codex_cta()['line'] );
	}
	return array( $plans[ $key ][0], home_url( $plans[ $key ][1] ) );
}

/** 指定マシンを使う公開メニュー */
function geed_cs_menus_by_machine( $machine_key, $limit = 12 ) {
	return get_posts( array(
		'post_type'      => 'geed_menu',
		'posts_per_page' => $limit,
		'meta_query'     => array( array( 'key' => 'geed_machines', 'value' => $machine_key, 'compare' => 'LIKE' ) ),
	) );
}

function geed_cs_menu_duration_text( $post_id ) {
	$min  = (int) get_post_meta( $post_id, 'geed_duration', true );
	$freq = get_post_meta( $post_id, 'geed_frequency', true );
	return trim( ( $min ? $min . '分' : '' ) . ( $min && $freq ? '・' : '' ) . $freq );
}

/* -------------------------------------------------------------------------
 * 表示パーツ
 * ---------------------------------------------------------------------- */

function geed_cs_menu_card( $post ) {
	$persona  = get_post_meta( $post->ID, 'geed_persona', true );
	$personas = geed_cs_personas();
	$meta     = geed_cs_menu_duration_text( $post->ID );
	?>
	<li class="menu-card">
		<a href="<?php echo esc_url( get_permalink( $post ) ); ?>">
			<?php if ( isset( $personas[ $persona ] ) ) : ?><span class="menu-card__persona"><?php echo esc_html( $personas[ $persona ] ); ?></span><?php endif; ?>
			<span class="menu-card__title"><?php echo esc_html( get_the_title( $post ) ); ?></span>
			<?php if ( $meta ) : ?><span class="menu-card__meta"><?php echo esc_html( $meta ); ?></span><?php endif; ?>
		</a>
	</li>
	<?php
}

function geed_cs_menu_list( $posts, $heading = '', $class = '' ) {
	if ( ! $posts ) return;
	echo '<section class="menu-list ' . esc_attr( $class ) . '">';
	if ( $heading ) echo '<h2 class="menu-list__head">' . esc_html( $heading ) . '</h2>';
	echo '<ul class="menu-list__items">';
	foreach ( $posts as $p ) geed_cs_menu_card( $p );
	echo '</ul></section>';
}

function geed_cs_machine_links( $keys, $heading = '関連するマシン' ) {
	$items = array();
	foreach ( $keys as $k ) {
		list( $label, $url ) = geed_cs_machine( $k );
		if ( $url ) $items[] = '<li><a class="cat-chip" href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
	}
	if ( ! $items ) return;
	echo '<section class="machine-links"><h2 class="menu-list__head">' . esc_html( $heading ) . '</h2><ul class="machine-links__items">' . implode( '', $items ) . '</ul></section>';
}

function geed_cs_plan_box( $plan_key, $reason = '' ) {
	$plan = geed_cs_plan( $plan_key );
	if ( ! $plan ) return;
	$is_line = false !== strpos( $plan[1], 'line.me' );
	?>
	<aside class="plan-box">
		<p class="plan-box__label">このメニューに合うプラン</p>
		<p class="plan-box__name"><?php echo esc_html( $plan[0] ); ?></p>
		<?php if ( $reason ) : ?><p class="plan-box__reason"><?php echo esc_html( $reason ); ?></p><?php endif; ?>
		<a class="btn <?php echo $is_line ? 'btn--line' : 'btn--gold'; ?>" href="<?php echo esc_url( $plan[1] ); ?>" data-cta="plan"<?php echo $is_line ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo $is_line ? 'LINEで相談する' : '料金・内容を見る'; ?></a>
	</aside>
	<?php
}

/** メニュー詳細の「4週間完全版はLINEで」CTA（サイト設計 §5-2: 受け渡しは当面スタッフ手動） */
/** アイキャッチのキャプション（AI生成画像の「※画像はイメージです」等） */
function geed_cs_eyecatch_caption() {
	$cap = wp_get_attachment_caption( get_post_thumbnail_id() );
	if ( $cap ) echo '<p class="content-page__caption">' . esc_html( $cap ) . '</p>';
}

function geed_cs_line_offer() {
	$cta = geed_codex_cta();
	?>
	<aside class="line-offer">
		<p class="line-offer__title">このメニューの「4週間版」をLINEでお送りします</p>
		<p class="line-offer__sub">ここで紹介しているのは1週目です。LINEに「4週間メニュー希望」と送っていただければ、2〜4週目の進め方をスタッフからお届けします。</p>
		<a class="btn btn--line" href="<?php echo esc_url( $cta['line'] ); ?>" target="_blank" rel="noopener" data-cta="menu_offer">LINEで4週間版を受け取る</a>
	</aside>
	<?php
}

/** コラム記事末尾の関連ブロック（single.php から呼ぶ） */
function geed_cs_post_related( $post_id ) {
	$menus = array();
	foreach ( geed_cs_split( get_post_meta( $post_id, 'geed_related_menus', true ) ) as $slug ) {
		$m = get_page_by_path( $slug, OBJECT, 'geed_menu' );
		if ( $m && 'publish' === $m->post_status ) $menus[] = $m;
	}
	if ( ! $menus ) {
		// 未指定ならカテゴリから目的を引いて補う
		$map = geed_cs_category_purpose();
		foreach ( get_the_category( $post_id ) as $c ) {
			if ( ! empty( $map[ $c->slug ] ) ) {
				$menus = get_posts( array(
					'post_type'      => 'geed_menu',
					'posts_per_page' => 2,
					'tax_query'      => array( array( 'taxonomy' => 'menu_purpose', 'field' => 'slug', 'terms' => $map[ $c->slug ] ) ),
				) );
				break;
			}
		}
	}
	geed_cs_menu_list( $menus, 'この記事に関連するトレーニングメニュー', 'menu-list--related' );
	geed_cs_machine_links( geed_cs_split( get_post_meta( $post_id, 'geed_related_machines', true ) ) );
	$plan = get_post_meta( $post_id, 'geed_plan', true );
	if ( $plan ) geed_cs_plan_box( $plan );
}

/* -------------------------------------------------------------------------
 * クエリ・URL
 * ---------------------------------------------------------------------- */

// コラム一覧から旧キャンペーン投稿（未分類）を外す。メニュー一覧は全件
add_action( 'pre_get_posts', function ( $q ) {
	if ( is_admin() || ! $q->is_main_query() ) return;
	if ( $q->is_home() ) {
		$q->set( 'category__not_in', array( (int) get_option( 'default_category' ) ) );
		$q->set( 'posts_per_page', 12 );
	}
	if ( $q->is_post_type_archive( 'geed_menu' ) || $q->is_tax( 'menu_purpose' ) ) {
		$q->set( 'posts_per_page', -1 );
		$q->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'ASC' ) );
	}
} );

function geed_cs_column_url() {
	$id = (int) get_option( 'page_for_posts' );
	return $id ? get_permalink( $id ) : home_url( '/column/' );
}

/* -------------------------------------------------------------------------
 * SEO: アーカイブの title/description（AIOSEO はアーカイブを投稿単位で持てない）
 * ---------------------------------------------------------------------- */

add_filter( 'geed_codex_seo_meta_extra', function ( $meta ) {
	if ( is_post_type_archive( 'geed_menu' ) ) {
		return array(
			'title'       => 'トレーニングメニュー｜目的・タイプ別 | GEED 那覇市壺屋',
			'description' => 'GEED（那覇市壺屋）の実機を使ったトレーニングメニュー。ダイエット・美尻・産後・健康づくりなど、目的とタイプ別に手順・時間・注意点をトレーナー監修でまとめています。',
		);
	}
	if ( is_tax( 'menu_purpose' ) ) {
		$term = get_queried_object();
		return array(
			'title'       => $term->name . 'のトレーニングメニュー | GEED 那覇市壺屋',
			'description' => $term->description ? wp_trim_words( wp_strip_all_tags( $term->description ), 110, '' ) : 'GEED（那覇市壺屋）の' . $term->name . '向けトレーニングメニュー一覧。実機の使い方と手順をトレーナー監修で紹介します。',
		);
	}
	return $meta;
} );

/* -------------------------------------------------------------------------
 * 構造化データ: ExercisePlan（メニュー詳細）
 * ---------------------------------------------------------------------- */

add_action( 'wp_head', function () {
	if ( ! is_singular( 'geed_menu' ) ) return;
	$id       = get_queried_object_id();
	$personas = geed_cs_personas();
	$persona  = get_post_meta( $id, 'geed_persona', true );
	$min      = (int) get_post_meta( $id, 'geed_duration', true );
	$sup_key  = get_post_meta( $id, 'geed_supervisor', true );
	$sups     = geed_cs_supervisors();
	$purposes = wp_list_pluck( get_the_terms( $id, 'menu_purpose' ) ?: array(), 'name' );

	$data = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'ExercisePlan',
		'name'        => get_the_title( $id ),
		'description' => wp_strip_all_tags( get_the_excerpt( $id ) ),
		'url'         => get_permalink( $id ),
		'inLanguage'  => 'ja',
		'publisher'   => array( '@id' => home_url( '/' ) ),
	);
	if ( $purposes ) $data['exerciseType'] = implode( '・', $purposes );
	if ( $min ) $data['activityDuration'] = 'PT' . $min . 'M';
	if ( $f = get_post_meta( $id, 'geed_frequency', true ) ) $data['activityFrequency'] = $f;
	if ( isset( $personas[ $persona ] ) ) $data['audience'] = array( '@type' => 'PeopleAudience', 'audienceType' => $personas[ $persona ] );
	if ( isset( $sups[ $sup_key ] ) ) {
		$s = $sups[ $sup_key ];
		$data['author'] = array(
			'@type'          => 'Person',
			'name'           => $s['name'],
			'jobTitle'       => $s['job'],
			'hasCredential'  => array_map( function ( $c ) { return array( '@type' => 'EducationalOccupationalCredential', 'name' => $c ); }, $s['creds'] ),
			'worksFor'       => array( '@id' => home_url( '/' ) ),
			'url'            => home_url( $s['url'] ),
		);
	}
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
} );

/* -------------------------------------------------------------------------
 * LINEクリック計測: body に pt-<種別> を付け、main.js が GA4 に line_click を送る
 * ---------------------------------------------------------------------- */

function geed_cs_page_type() {
	if ( is_front_page() ) return 'top';
	if ( is_singular( 'geed_menu' ) ) return 'menu';
	if ( is_post_type_archive( 'geed_menu' ) || is_tax( 'menu_purpose' ) ) return 'menu_hub';
	if ( is_page_template( 'page-machine.php' ) ) return 'machine';
	if ( is_singular( 'post' ) ) return 'column';
	if ( is_home() || is_category() ) return 'column_hub';
	if ( is_page() ) return 'page_' . get_post_field( 'post_name', get_queried_object_id() );
	return 'other';
}

add_filter( 'body_class', function ( $classes ) {
	$classes[] = 'pt-' . sanitize_html_class( geed_cs_page_type() );
	return $classes;
} );
