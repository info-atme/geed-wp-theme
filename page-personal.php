<?php
/**
 * Template Name: パーソナル
 * Template Post Type: page
 */
get_header();
geed_codex_page_hero( array(
	'slug' => 'personal',
	'en'   => 'PERSONAL',
	'ja'   => 'パーソナルトレーニング',
	'sub'  => 'マンツーマンで、あなたの理想のカラダへ。',
	'bg'   => 'interior-main.jpg',
) );
$cta = geed_codex_cta();
?>

<section class="g-sec">
	<div class="g-container">
		<p class="g-lead g-narrow">
			「ひとりだと続かない」「効率よく結果を出したい」<br>
			そんな方には、経験豊富なトレーナーがマンツーマンでサポート。<br>
			あなた専用のメニューと食事プランで、最短ルートで目標へ。
		</p>
	</div>
</section>

<section class="g-sec g-sec--surface" aria-labelledby="forwhom-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>01</span>FOR WHOM</p>
			<h2 id="forwhom-title">こんな方におすすめ</h2>
		</header>
		<div class="g-sec__body">
			<ul class="g-list">
				<li>結婚式・イベント・夏に向けて短期で結果を出したい</li>
				<li>自己流トレーニングで停滞している</li>
				<li>食事のとり方を相談できる人が欲しい</li>
				<li>正しいフォームを最初に身につけたい</li>
				<li>ひとりだとサボってしまう</li>
				<li>産後ダイエットを安全に進めたい</li>
			</ul>
		</div>
	</div>
</section>

<section class="g-sec" aria-labelledby="trainers-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>02</span>TRAINERS</p>
			<h2 id="trainers-title">スタッフ紹介</h2>
		</header>
		<div class="g-sec__body">
			<article class="p-personal-staff">
				<figure class="p-personal-staff__photo">
					<img src="<?php echo geed_codex_asset( 'img/staff/miyamoto-025.jpg' ); ?>"<?php echo geed_codex_img_size( 'img/staff/miyamoto-025.jpg' ); ?> decoding="async" alt="宮本達矢 トレーナー" loading="lazy" data-reveal="clip">
				</figure>
				<div class="p-personal-staff__text">
					<p class="p-personal-staff__role">代表トレーナー / 元プロボクサー</p>
					<h3>宮本 達矢 <small>みやもと たつや</small></h3>
					<p class="p-personal-staff__quote">「あなたの身体が1番大事」</p>
					<p>熊本生まれ、大分育ち。映画ロッキーに憧れ高校でボクシングを始め、2006年に沖縄へ。プロボクサーとして10年で28戦をこなし、29歳で引退。2020年からパーソナルトレーニング業を開始し、<strong>2026年5月、那覇市壺屋GEEDを拠点にフリーランスのパーソナルトレーナーとして再始動</strong>。</p>
					<p>自身の競技人生で「身体のコンディション」がいかに大切かを痛感。運動だけでなく、調理師免許や筋膜リリースの知識を活かし、食事・心身両面からあなたに寄り添うオリジナルメニューをご提案します。</p>
				</div>
			</article>

			<ul class="p-personal-thumbs">
				<li><img src="<?php echo geed_codex_asset( 'img/staff/miyamoto-020.jpg' ); ?>"<?php echo geed_codex_img_size( 'img/staff/miyamoto-020.jpg' ); ?> decoding="async" alt="" loading="lazy" data-reveal="clip"></li>
				<li><img src="<?php echo geed_codex_asset( 'img/staff/miyamoto-024.jpg' ); ?>"<?php echo geed_codex_img_size( 'img/staff/miyamoto-024.jpg' ); ?> decoding="async" alt="" loading="lazy" data-reveal="clip"></li>
				<li><img src="<?php echo geed_codex_asset( 'img/staff/miyamoto-026.jpg' ); ?>"<?php echo geed_codex_img_size( 'img/staff/miyamoto-026.jpg' ); ?> decoding="async" alt="" loading="lazy" data-reveal="clip"></li>
			</ul>

			<dl class="g-table p-personal-meta">
				<div><dt>生年月日</dt><dd>1987年12月8日</dd></div>
				<div><dt>出身</dt><dd>熊本県 → 大分県 → 沖縄県</dd></div>
				<div><dt>競技歴</dt><dd>プロボクサーA級ライセンス（10年・通算28戦12勝15敗1分）</dd></div>
			</dl>

			<h3 class="p-personal-sub">保有資格</h3>
			<ul class="g-list">
				<li>加圧ジャパン認定インストラクター</li>
				<li>調理師免許</li>
				<li>食育インストラクター3級</li>
				<li>元JBCトレーナーライセンス</li>
				<li>FCEカッピング筋膜リリーストレーナー</li>
				<li>オンラインチャイルドコーチング</li>
				<li>アスリート栄養管理アドバイザー</li>
				<li>HARELU腸活講習（2024年修了）</li>
			</ul>

			<div class="g-callout">「最近疲れやすい」「寝付きが悪い」「過食してしまう」 — 心と身体は繋がっています。失敗を重ねた自分だからこそ、あなたに寄り添ったプランをご用意します。一緒に解決していきましょう。</div>
		</div>
	</div>
</section>

<section class="g-sec g-sec--surface" aria-labelledby="pflow-title">
	<div class="g-container g-sec__grid g-sec__grid--stack">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>03</span>FLOW</p>
			<h2 id="pflow-title">パーソナルの流れ</h2>
		</header>
		<div class="g-sec__body">
			<ol class="g-steps p-personal-steps">
				<li><span class="g-steps__no" lang="en" aria-hidden="true">01</span><h3>カウンセリング</h3><p>目標・現状・運動歴・食生活などを伺います。InBody測定もここで実施。</p></li>
				<li><span class="g-steps__no" lang="en" aria-hidden="true">02</span><h3>専用プログラム作成</h3><p>あなたの目標・体力・スケジュールに合わせて、トレーナーが個別プログラムを設計。</p></li>
				<li><span class="g-steps__no" lang="en" aria-hidden="true">03</span><h3>トレーニング・食事サポート</h3><p>週1〜2回のセッション＋食事相談。LINEで日々の食事もチェックします。</p></li>
				<li><span class="g-steps__no" lang="en" aria-hidden="true">04</span><h3>定期測定・プラン更新</h3><p>月1回のInBody測定で変化を可視化。結果に応じてプログラムを微調整します。</p></li>
			</ol>
		</div>
	</div>
</section>

<section class="g-sec" aria-labelledby="pprice-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>04</span>PRICE</p>
			<h2 id="pprice-title">パーソナル料金</h2>
		</header>
		<div class="g-sec__body">
			<dl class="g-table p-personal-price">
				<?php foreach ( geed_codex_personal_plans() as $pp ) : ?>
					<div><dt><?php echo esc_html( $pp[0] ); ?><?php if ( $pp[4] ) : ?><em><?php echo esc_html( $pp[4] ); ?></em><?php endif; ?></dt><dd><b><?php echo esc_html( $pp[1] ); ?><span> / <?php echo esc_html( $pp[2] ); ?></span></b><?php echo esc_html( $pp[3] ); ?></dd></div>
				<?php endforeach; ?>
			</dl>
			<p class="g-note">※ 月額プランとパーソナルプランは併用可能です（料金は加算）。<br>※ 価格はすべて税込です。<br>※ ジム使い放題＋パーソナル月2回付きの<strong>プレミアムプラン（月額 ¥15,400 税込）</strong>もあります。詳しくは <a href="<?php echo esc_url( home_url( '/price/' ) ); ?>">料金プラン</a> をご覧ください。</p>
		</div>
	</div>
</section>

<section class="g-sec g-sec--surface" aria-labelledby="support-title">
	<div class="g-container g-sec__grid g-sec__grid--stack">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>05</span>SUPPORT</p>
			<h2 id="support-title">サポート内容</h2>
		</header>
		<div class="g-sec__body">
			<ul class="g-points g-points--3">
				<li><span class="g-points__no" lang="en">01</span><h3>食事・栄養アドバイス</h3><p>LINEで日々の食事を送ってもらい、トレーナーがフィードバック。「がまん」より「賢く選ぶ」を学べます。</p></li>
				<li><span class="g-points__no" lang="en">02</span><h3>フォームチェック</h3><p>動画撮影してその場で確認。正しいフォームを身につければ、ジムを卒業しても結果が続きます。</p></li>
				<li><span class="g-points__no" lang="en">03</span><h3>モチベーション維持</h3><p>セッション以外もLINEで気軽に質問OK。「今日サボりたい…」もそのまま送ってください。</p></li>
			</ul>
		</div>
	</div>
</section>

<?php geed_codex_page_cta(); ?>
<?php get_footer();
