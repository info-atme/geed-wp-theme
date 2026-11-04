<?php
/**
 * 無料体験の申込ボタン2本（Web=金 / LINE=緑・同格。記憶 geed-cta-dual）
 *
 * get_template_part( 'template-parts/trial-actions', null, array(
 *   'where' => 'price',   // data-cta の接頭辞（GA4 の link_location になる）
 *   'note'  => true,      // 予約できる日の注記を出す（文字列ならその文言）
 * ) );
 */
$cta   = geed_codex_cta();
$where = isset( $args['where'] ) ? $args['where'] : 'other';
$note  = isset( $args['note'] ) ? $args['note'] : true;
if ( true === $note ) {
	$note = '体験予約：日曜・毎月15日を除く。勧誘はしません。';
}
?>
<div class="g-actions">
	<a class="g-btn g-btn--form" href="<?php echo esc_url( $cta['trial'] ); ?>" data-cta="<?php echo esc_attr( $where ); ?>-form">無料体験をWebで予約<small>日時はスタッフから連絡</small></a>
	<a class="g-btn g-btn--line" href="<?php echo esc_url( $cta['line'] ); ?>" data-cta="<?php echo esc_attr( $where ); ?>-line">無料体験をLINEで予約<small>友だち追加後、日時を送信</small></a>
</div>
<?php if ( $note ) : ?>
	<p class="g-actions__note"><?php echo esc_html( $note ); ?></p>
<?php endif; ?>
