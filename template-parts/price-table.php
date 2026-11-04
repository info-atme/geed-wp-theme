<?php
/**
 * 月額料金表。データはカスタマイザー「【料金ページ】プラン詳細」(geed_price_plans) を正とし、TOPと料金ページで共用する。
 * スタンダードを先頭・強調（renewal_spec.md §4）。価格が数字でないプラン（法人など）は 'with_quote' => true のときだけ出す。
 *
 * get_template_part( 'template-parts/price-table', null, array( 'with_quote' => false ) );
 */
$with_quote = ! empty( $args['with_quote'] );
$plans      = geed_codex_price_plans();

// スタンダードを先頭へ（他は元の並び）
usort( $plans, function ( $a, $b ) {
	return (int) ( 0 !== strpos( $a[0], 'スタンダード' ) ) - (int) ( 0 !== strpos( $b[0], 'スタンダード' ) );
} );
?>
<table class="g-price">
	<caption>通常の月額料金（税込）</caption>
	<tbody>
		<?php foreach ( $plans as $plan ) :
			$is_yen = 0 === strpos( $plan[1], '¥' );
			if ( ! $is_yen && ! $with_quote ) {
				continue;
			}
			$is_main = 0 === strpos( $plan[0], 'スタンダード' );
		?>
			<tr<?php echo $is_main ? ' class="is-main"' : ''; ?>>
				<th scope="row">
					<?php echo esc_html( $plan[0] ); ?><?php if ( $is_main ) : ?><span class="g-price__tag">迷ったらこちら</span><?php endif; ?>
					<?php if ( '' !== $plan[3] ) : ?><small><?php echo esc_html( $plan[3] ); ?></small><?php endif; ?>
				</th>
				<td><strong><?php echo esc_html( $plan[1] ); ?></strong><?php if ( $is_yen ) : ?><small>/月</small><?php endif; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
