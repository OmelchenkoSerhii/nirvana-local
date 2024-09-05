<?php
$flight = $args['flight'];
$order  = $args['order'];

?>

<li class="flights-list__card d-flex align-items-center">
	<a href="#popup-select-flight" class="d-flex justify-content-between align-items-center w-100">
		<?php the_image( $flight['airline_logo'], false, 'w-100 h-100', '', '', true ); ?>
		<div class="d-flex g-30">
			<p class="p2 text-center"><?php echo esc_html( $flight['departure']['airport'] ); ?> <span class="d-block mt-1 text--size--25 font--weight--700"><?php echo esc_html( $flight['departure']['time'] ); ?></span></p>
			<div class="d-flex flex-column align-items-center">
				<span class="text--size--16 text--opacity"><?php esc_html_e( 'Direct', 'nirvana' ); ?></span>
				<svg width="184" height="18" viewBox="0 0 184 18" fill="none" xmlns="http://www.w3.org/2000/svg">
					<rect opacity="0.6" y="8" width="160" height="2" fill="#F47920"/>
					<path opacity="0.6" d="M169.925 17.5H171.559L176.78 10.4473C178.156 10.4688 179.549 10.4671 180.968 10.4238C182.645 10.3449 184 9.69771 184 8.99998C184 8.30249 182.645 7.65532 180.968 7.57645C179.549 7.5332 178.156 7.5314 176.78 7.55293L171.559 0.5L169.925 0.5L172.888 7.66798C170.68 7.75798 168.492 7.86801 166.291 7.94718L165.079 5.76499H164L164.566 8.99998L164 12.2348H165.079L166.291 10.0531C168.492 10.132 170.68 10.2417 172.888 10.3323L169.925 17.5Z" fill="black"/>
				</svg>
				<span class="text--size--16 text--opacity"><?php the_direct_time( $flight['direct_time'] ); ?></span>
			</div>
			<p class="p2 text-center"><?php echo esc_html( $flight['destination']['airport'] ); ?> <span class="d-block mt-1 text--size--25 font--weight--700"><?php echo esc_html( $flight['destination']['time'] ); ?></span></p>
		</div>
		<p class="text--size--12"><span class="d-block text-right text--size--25 font--weight--700 mb-1"><?php echo $order->getCurrencySymbol(); ?><?php echo esc_html( $flight['price'] ); ?></span> <?php esc_html_e( 'per traveller', 'nirvana' ); ?></p>
	</a>
</li>
