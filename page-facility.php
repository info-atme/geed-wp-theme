<?php
/**
 * Template Name: 施設紹介
 * Template Post Type: page
 */
get_header();
geed_codex_page_hero( array(
	'slug' => 'facility',
	'en'   => 'FACILITY',
	'ja'   => '施設紹介',
	'sub'  => '那覇市壺屋・地域密着のフィットネスジムが誇る、設備とマシン。',
	'bg'   => 'interior-main.jpg',
) );

$all_machines = array(
	array( 'S__57958402.jpg', 'Booty Builder Platinum',     '美尻特化マシン' ),
	array( 'S__57958415.jpg', 'リアデルト＆ベックフライ',   '背中・胸の両面トレーニング' ),
	array( 'S__57958413.jpg', 'アブダクター＆アダクター',   '内もも・外ももを集中強化' ),
	array( 'S__57958412.jpg', 'ラットプル＆ロングプル',     '広背筋を効率よく鍛える' ),
	array( 'S__57958411.jpg', 'デュアルアジャスタブルプーリー', '可変ケーブルで全身対応' ),
	array( 'S__57958410.jpg', 'ハーフラックプロ',           'フリーウェイトの中核設備' ),
	array( 'S__57958409.jpg', 'トレッドミル',               'ウォーキング〜ランニングに対応' ),
	array( 'S__57958408.jpg', 'スピニングバイク',           '有酸素と脂肪燃焼に' ),
	array( 'S__57958407.jpg', 'アジャスタブルデクラインベンチ', '角度を変えて多彩な刺激' ),
	array( 'S__57958406.jpg', 'バックエクステンション',     '腰・背中のコア強化' ),
	array( 'S__57958405.jpg', 'アングルドレッグプレス',     '下半身を安全に高負荷で' ),
	array( 'S__57958404.jpg', 'スミスマシン',               '初心者でも安心のフリー系' ),
);
?>

<section class="g-sec">
	<div class="g-container">
		<p class="g-lead g-narrow">
			GEEDは、那覇市壺屋にある約100坪のフィットネススタジオ。<br>
			BE-BE・InBody270・Booty Builder Platinum・タンニングマシン VEGA39 Plus など、
			他のジムではなかなか出会えない高品質マシンをご用意しています。
		</p>
	</div>
</section>

<section class="g-sec g-sec--surface" aria-labelledby="machines-title">
	<div class="g-container g-sec__grid g-sec__grid--stack">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>01</span>MACHINES</p>
			<h2 id="machines-title">主要マシン紹介</h2>
		</header>
		<div class="g-sec__body">
			<ul class="g-media p-facility-media">
				<li>
					<img src="<?php echo geed_codex_asset( 'img/facility-bebe.jpg' ); ?>"<?php echo geed_codex_img_size( 'img/facility-bebe.jpg' ); ?> decoding="async" alt="BE-BE" loading="lazy" data-reveal="clip">
					<div>
						<span class="g-media__tag">BEAUTY</span>
						<h3>BE-BE <small>セルフ痩身エステマシン</small></h3>
						<p>ESTHETIC WIRED「BEST BEAUTY ITEM」受賞のセルフ痩身マシン。トレーニング後のクールダウンと痩身ケアを同時に行えます。GEEDなら追加料金なしで使い放題。</p>
						<ul class="g-list">
						<li>キャビテーション・ラジオ波・吸引などの機能を搭載</li>
						<li>気になる部位を自分のペースで集中ケア</li>
						<li>追加料金なしで使い放題</li>
						</ul>
					</div>
				</li>
				<li>
					<img src="<?php echo geed_codex_asset( 'img/facility-vega39.jpg' ); ?>"<?php echo geed_codex_img_size( 'img/facility-vega39.jpg' ); ?> decoding="async" alt="タンニング VEGA39 Plus" loading="lazy" data-reveal="clip">
					<div>
						<span class="g-media__tag">TANNING</span>
						<h3>タンニング VEGA39 Plus <small>イタリア製・縦型タンニング</small></h3>
						<p>イタリアSportarredo社の縦型タンニングマシン。全プランに含まれ、追加料金はかかりません。使うときはスタッフが時間や準備をご案内します。</p>
						<ul class="g-list">
						<li>1回あたり約10〜15分の短時間ケア</li>
						<li>ビタミンD合成と気分転換に</li>
						</ul>
					</div>
				</li>
				<li>
					<img src="<?php echo geed_codex_asset( 'img/facility-inbody.jpg' ); ?>"<?php echo geed_codex_img_size( 'img/facility-inbody.jpg' ); ?> decoding="async" alt="InBody270" loading="lazy" data-reveal="clip">
					<div>
						<span class="g-media__tag">DATA</span>
						<h3>InBody270 <small>高精度体組成計</small></h3>
						<p>世界中のフィットネス施設・医療機関で採用されている体組成計。体脂肪率・筋肉量・水分量・部位別バランスまで、正確に数値化できます。</p>
						<ul class="g-list">
						<li>測定時間 約60秒</li>
						<li>結果はプリントアウトで持ち帰り可能</li>
						<li>月1回の測定で、変化を客観的に把握</li>
						</ul>
					</div>
				</li>
				<li>
					<img src="<?php echo geed_codex_asset( 'img/facility-booty.jpg' ); ?>"<?php echo geed_codex_img_size( 'img/facility-booty.jpg' ); ?> decoding="async" alt="Booty Builder Platinum" loading="lazy" data-reveal="clip">
					<div>
						<span class="g-media__tag">SPECIAL</span>
						<h3>Booty Builder Platinum <small>美尻特化マシン</small></h3>
						<p>ヒップスラスト専用のマシン。お尻まわりの筋肉を集中して鍛えられます。</p>
						<ul class="g-list">
						<li>美尻づくりに特化した独自設計</li>
						<li>女性でも扱いやすい安定感</li>
						<li>沖縄県内でも稀少な設備</li>
						</ul>
					</div>
				</li>
				<li>
					<img src="<?php echo geed_codex_asset( 'img/interior-main.jpg' ); ?>"<?php echo geed_codex_img_size( 'img/interior-main.jpg' ); ?> decoding="async" alt="その他マシン多数" loading="lazy" data-reveal="clip">
					<div>
						<span class="g-media__tag">GENERAL</span>
						<h3>その他マシン多数 <small>有酸素・フリーウェイト・ストレッチ</small></h3>
						<p>トレッドミル・エアロバイク・各種ウェイトマシン・フリーウェイトエリア・ストレッチコーナーまで一通り揃えています。広いフロアでのびのびトレーニング。</p>
						<ul class="g-list">
						<li>有酸素：トレッドミル / エアロバイク / クロストレーナー</li>
						<li>フリーウェイト：ダンベル / バーベル / ベンチ</li>
						<li>ストレッチ：ヨガマット・フォームローラー貸出</li>
						</ul>
					</div>
				</li>
			</ul>
		</div>
	</div>
</section>

<section class="g-sec" aria-labelledby="allm-title">
	<div class="g-container g-sec__grid g-sec__grid--stack">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>02</span>ALL MACHINES</p>
			<h2 id="allm-title">導入マシン一覧</h2>
			<p class="g-head__lead">有酸素から美尻特化マシンまで、目的別に12種を完備。</p>
		</header>
		<div class="g-sec__body">
			<ul class="p-facility-all">
				<?php foreach ( $all_machines as $i => $m ) : ?>
					<li>
						<img src="<?php echo geed_codex_asset( 'img/machines/' . $m[0] ); ?>"<?php echo geed_codex_img_size( 'img/machines/' . $m[0] ); ?> decoding="async" alt="<?php echo esc_attr( $m[1] ); ?>" loading="lazy" data-reveal="clip">
						<p class="g-gallery__cap"><span lang="en"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><b><?php echo esc_html( $m[1] ); ?></b><?php echo esc_html( $m[2] ); ?></p>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>

<?php $geed_machine_pages = geed_cs_machine_pages(); ?>
<?php if ( $geed_machine_pages ) : ?>
<section class="g-sec g-sec--surface" aria-labelledby="guide-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>03</span>GUIDE</p>
			<h2 id="guide-title">マシン別の使い方ガイド</h2>
			<p class="g-head__lead">マシンごとに、使い方・向いている人・そのマシンを使うメニューをまとめています。</p>
		</header>
		<div class="g-sec__body">
			<ul class="g-list">
				<?php foreach ( $geed_machine_pages as $p ) : ?>
					<li><a class="g-link" href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="g-sec" aria-labelledby="tour-title">
	<div class="g-container g-sec__grid g-sec__grid--stack">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>04</span>TOUR</p>
			<h2 id="tour-title">館内ツアー</h2>
			<p class="g-head__lead">エントランスからメインフロア、設備までを写真でご案内。</p>
		</header>
		<div class="g-sec__body">
			<ul class="g-gallery p-facility-tour">
				<li><img src="<?php echo geed_codex_asset( 'img/interior-entrance.jpg' ); ?>"<?php echo geed_codex_img_size( 'img/interior-entrance.jpg' ); ?> decoding="async" alt="エントランス・受付" loading="lazy" data-reveal="clip"><p class="g-gallery__cap"><span lang="en">01</span><b>エントランス・受付</b></p></li>
				<li><img src="<?php echo geed_codex_asset( 'img/interior-main.jpg' ); ?>"<?php echo geed_codex_img_size( 'img/interior-main.jpg' ); ?> decoding="async" alt="メインフロア" loading="lazy" data-reveal="clip"><p class="g-gallery__cap"><span lang="en">02</span><b>メインフロア（大理石壁・フリーウェイト）</b></p></li>
				<li><img src="<?php echo geed_codex_asset( 'img/interior-treadmill.jpg' ); ?>"<?php echo geed_codex_img_size( 'img/interior-treadmill.jpg' ); ?> decoding="async" alt="有酸素マシンエリア" loading="lazy" data-reveal="clip"><p class="g-gallery__cap"><span lang="en">03</span><b>有酸素マシンエリア（窓側）</b></p></li>
				<li><img src="<?php echo geed_codex_asset( 'img/interior-inbody.jpg' ); ?>"<?php echo geed_codex_img_size( 'img/interior-inbody.jpg' ); ?> decoding="async" alt="InBodyコーナー" loading="lazy" data-reveal="clip"><p class="g-gallery__cap"><span lang="en">04</span><b>InBody測定コーナー＋ロッカー</b></p></li>
			</ul>
		</div>
	</div>
</section>

<section class="g-sec g-sec--surface" id="crowd" aria-labelledby="crowd-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>05</span>CROWD</p>
			<h2 id="crowd-title">空いている時間の目安</h2>
			<p class="g-head__lead">「人多すぎは苦手…」という方へ。時間帯ごとの目安をお伝えします。</p>
		</header>
		<div class="g-sec__body">
			<dl class="g-table p-facility-crowd">
				<div class="p-facility-crowd--low"><dt lang="en">07:00 - 10:00</dt><dd><b>★☆☆ ゆったり</b>朝のひと汗派におすすめ。集中したい方・人見知りの方にぴったり。</dd></div>
				<div class="p-facility-crowd--low"><dt lang="en">11:00 - 15:00</dt><dd><b>★☆☆ ゆったり</b>女性会員が多めの落ち着いた時間帯。お買い物のついでにも。</dd></div>
				<div class="p-facility-crowd--mid"><dt lang="en">15:00 - 18:00</dt><dd><b>★★☆ ふつう</b>学生さん・主婦の方が増える時間帯。マシンの空きはあります。</dd></div>
				<div class="p-facility-crowd--high"><dt lang="en">18:00 - 21:00</dt><dd><b>★★★ 混みやすい</b>平日仕事帰りで混雑しやすい時間。短時間でサクッと使うのがコツ。</dd></div>
				<div class="p-facility-crowd--mid"><dt lang="en">21:00 - 23:00</dt><dd><b>★★☆ ふつう</b>夜遅め派におすすめ。比較的落ち着いて使える時間帯です。</dd></div>
			</dl>
			<p class="g-note">※ 混雑状況の目安は時期により変動します。リアルタイムの状況はLINEでお気軽にお問い合わせください。</p>
		</div>
	</div>
</section>

<?php geed_codex_page_cta(); ?>
<?php get_footer();
