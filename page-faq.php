<?php
/**
 * Template Name: FAQ
 * Template Post Type: page
 */
get_header();
geed_codex_page_hero( array(
	'slug' => 'faq',
	'en'   => 'FAQ',
	'ja'   => 'よくあるご質問',
	'sub'  => '入会前・利用中によくいただくご質問をまとめました。',
	'bg'   => 'interior-entrance.jpg',
) );

// キャンペーンの答えは管理画面の設定（geed_codex_campaign）から作る。ビラ・Instagram の告知と食い違わないように
$campaign = geed_codex_campaign();
$price_link = '<a href="' . esc_url( home_url( '/price/' ) ) . '">料金プランページ</a>';
if ( $campaign['show'] ) {
	$cp_items  = implode( '・', array_map( function ( $i ) { return esc_html( trim( $i[0] . ' ' . $i[1] ) ); }, $campaign['items'] ) );
	$cp_answer = '現在のキャンペーンは「' . esc_html( $campaign['title'] ) . '」です（' . $cp_items . '）。' . ( $campaign['note'] ? '<strong>' . esc_html( preg_replace( '/^※\s*/u', '', $campaign['note'] ) ) . '</strong>。' : '' ) . '最新の内容は' . $price_link . 'をご覧ください。';
} else {
	$cp_answer = '現在実施中のキャンペーンはありません。最新の内容は' . $price_link . 'をご覧ください。';
}

$faqs = array(
	'入会・お試しについて' => array(
		array( '体験は無料ですか？', '<strong>無料の体験ご案内</strong>を受け付けています。所要時間はおよそ60分、館内・マシン説明込みです。手ぶらでお越しください（シューズ・タオルはレンタル可）。' ),
		array( '見学・体験は予約が必要ですか？', '初めての方の<strong>見学・体験、パーソナルトレーニングは事前予約制</strong>です。LINEから希望日時をお送りいただければ、スタッフが日程を調整いたします。' ),
		array( '入会キャンペーンの条件は？', $cp_answer ),
		array( 'クレジットカードは使えますか？', 'はい、月額プランはクレジットカード・口座振替に対応しています。店頭でのお支払いは現金・主要クレジットカード・電子マネー（PayPay等）に対応。' ),
		array( '入会に必要な持ち物は？', '身分証明書、通帳など口座情報がわかるもの（口座振替の場合）またはクレジットカード（カード決済の場合）、初月会費をお持ちください。' ),
	),
	'利用について' => array(
		array( '運動が苦手でも大丈夫ですか？', 'もちろん大丈夫です。スタッフがマシンの使い方を丁寧にご説明します。会員さまの多くも「ジム初体験」からのスタートです。' ),
		array( '持ち物は何が必要ですか？', '動きやすい服装とお飲み物だけでOK。レンタルウェア・タオル・シューズは全プラン無料でご利用いただけます。' ),
		array( '駐車場はありますか？', '提携駐車場のご案内がございます。詳細は店頭または公式LINEよりお問い合わせください。' ),
		array( 'シャワーは使えますか？', 'はい、シャワー完備です。ボディソープ・シャンプー備え付け。タオルはレンタル可能です。' ),
		array( 'ロッカーはありますか？', 'はい、無料の鍵付きロッカーをご用意しています。貴重品もお預けいただけます。' ),
		array( '休館日はありますか？', '毎月15日がメンテナンス休館日です。営業時間は 07:00〜23:00。' ),
	),
	'女性のお客さまから' => array(
		array( '女性ひとりでも安心して通えますか？', '会員さまの<strong>約6割が女性</strong>です。安心して通っていただけるよう配慮しています。混雑が気になる方は <a href="' . esc_url( home_url( '/facility/#crowd' ) ) . '">空いている時間の目安</a> もご参考ください。' ),
		array( 'ナンパや勧誘されることは？', '<strong>勧誘・ナンパは一切ありません</strong>。トラブル発生時はスタッフがすぐに対応します。女性が安心して通える環境づくりを最優先しています。' ),
		array( '生理中でも利用できますか？', 'ご無理のない範囲でご利用ください。BE-BEは生理中ご使用いただけません。スタッフにご相談いただければ、別メニューもご提案します。' ),
		array( '産後ダイエットでも通えますか？', '産後3〜6ヶ月以降、医師の許可があれば通っていただけます。パーソナルプランで安全に進めることをおすすめします。' ),
	),
	'パーソナルについて' => array(
		array( 'パーソナル料金プランは？', '月4回 ¥19,800 / 月8回 ¥36,800 / 都度払い ¥6,600・1回60分です。ジム使い放題＋パーソナル月2回付きの<strong>プレミアムプラン（月額¥15,400）</strong>もあります。詳しくは <a href="' . esc_url( home_url( '/personal/' ) ) . '">パーソナルページ</a> をご覧ください。' ),
		array( '月額プランと併用できますか？', 'はい、併用可能です。月額プラン+パーソナルプランで、通い放題＋週1〜2回のセッションが組めます。' ),
		array( '食事指導はどんな内容？', 'LINEで毎日の食事を送っていただき、トレーナーがフィードバックする形です。極端な制限は一切しません。' ),
	),
	'退会・休会について' => array(
		array( '退会方法は？', '退会希望月の<strong>前月9日まで</strong>に、店頭または所定のフォームよりお手続きください。引き止め・無理な勧誘は一切いたしません。' ),
		array( '休会制度はありますか？', '月額¥1,100で休会可能です。期間は最長6ヶ月、復帰時に再入会金は不要です。' ),
		array( '退会後に再入会する場合は？', '退会後6ヶ月以内の再入会なら、入会金は半額になります。お気軽にご相談ください。' ),
	),
);
?>

<?php $n = 0; foreach ( $faqs as $cat => $items ) : $n++; $sid = 'faq-cat-' . $n; ?>
<section class="g-sec<?php echo 0 === $n % 2 ? ' g-sec--surface' : ''; ?>" aria-labelledby="<?php echo esc_attr( $sid ); ?>">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span><?php echo esc_html( sprintf( '%02d', $n ) ); ?></span>FAQ</p>
			<h2 id="<?php echo esc_attr( $sid ); ?>"><?php echo esc_html( $cat ); ?></h2>
		</header>
		<div class="g-sec__body">
			<div class="g-faq">
				<?php foreach ( $items as $f ) : ?>
					<details>
						<summary><?php echo esc_html( $f[0] ); ?></summary>
						<p><?php echo wp_kses_post( $f[1] ); ?></p>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
<?php endforeach; ?>

<?php
// FAQPage 構造化データ: 表示中の Q&A と同じ配列から作る（本文と食い違わない）
$faq_ld = array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array() );
foreach ( $faqs as $items ) {
	foreach ( $items as $f ) {
		$faq_ld['mainEntity'][] = array(
			'@type'          => 'Question',
			'name'           => $f[0],
			'acceptedAnswer' => array( '@type' => 'Answer', 'text' => trim( wp_strip_all_tags( $f[1] ) ) ),
		);
	}
}
echo '<script type="application/ld+json">' . wp_json_encode( $faq_ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
?>

<?php geed_codex_page_cta(); ?>
<?php get_footer();
