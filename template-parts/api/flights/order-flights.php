<?php
/**
 * Template part for displaying flights list
 */

session_start();

$api = new NiravanaAPI();
$order = false;
$session_id = isset( $_COOKIE['my_session_id'] ) ? $_COOKIE['my_session_id'] : uniqid();
$order_token = get_transient( 'order_token_' . $session_id );
if ( $order_token ) {
    $order = new NiravanaOrder($order_token);
}

$flights = $order->searchFlights();

$flights = array(
	array(
		'airline_logo' => get_template_directory_uri() . '/assets/images/logo-british.png',
		'price'        => 244,
		'direct_time'  => '2:40',
		'departure'    => array(
			'country' => 'London',
			'airport' => 'LHR',
			'time'    => '19:50',
		),
		'destination'  => array(
			'country' => 'Berlin',
			'airport' => 'BER',
			'time'    => '22:50',
		),
		'fares'        => array(
			array(
				'id'              => 'basic',
				'price'           => '244',
				'tag'             => 'Basic',
				'cabin'           => 'Economy',
				'features'        => array(
					'Seat choice',
					'Cancellation',
					'Changes <span>£50</span>',
					'Personal iItem',
				),
				'carry-on'        => 'Incl. up to 23 kg',
				'1st-checked-bag' => '£50 up to 23 kg',
				'2nd-checked-bag' => '£50 up to 23 kg',
			),
			array(
				'id'              => 'plus',
				'price'           => '262',
				'tag'             => 'Plus',
				'cabin'           => 'Economy',
				'features'        => array(
					'Seat choice',
					'Cancellation',
					'Changes <span>£50</span>',
					'Personal iItem',
				),
				'carry-on'        => 'Incl. up to 23 kg',
				'1st-checked-bag' => 'Incl. up to 23 kg',
				'2nd-checked-bag' => '£50 up to 23 kg',
			),
			array(
				'id'              => 'plus',
				'price'           => '262',
				'tag'             => 'Plus',
				'cabin'           => 'Economy',
				'features'        => array(
					'Seat choice',
					'Cancellation',
					'Changes <span>£50</span>',
					'Personal iItem',
				),
				'carry-on'        => 'Incl. up to 23 kg',
				'1st-checked-bag' => 'Incl. up to 23 kg',
				'2nd-checked-bag' => '£50 up to 23 kg',
			),
		),
	),
	array(
		'airline_logo' => get_template_directory_uri() . '/assets/images/logo-british.png',
		'price'        => 275,
		'direct_time'  => '2',
		'departure'    => array(
			'country' => 'London',
			'airport' => 'LHR',
			'time'    => '14:50',
		),
		'destination'  => array(
			'country' => 'Berlin',
			'airport' => 'BER',
			'time'    => '17:50',
		),
		'fares'        => array(
			array(
				'id'              => 'basic',
				'price'           => '244',
				'tag'             => 'Basic',
				'cabin'           => 'Economy',
				'features'        => array(
					'Seat choice',
					'Cancellation',
					'Changes <span>£50</span>',
					'Personal iItem',
				),
				'carry-on'        => 'Incl. up to 23 kg',
				'1st-checked-bag' => '£50 up to 23 kg',
				'2nd-checked-bag' => '£50 up to 23 kg',
			),
			array(
				'id'              => 'plus',
				'price'           => '262',
				'tag'             => 'Plus',
				'cabin'           => 'Economy',
				'features'        => array(
					'Seat choice',
					'Cancellation',
					'Changes <span>£50</span>',
					'Personal iItem',
				),
				'carry-on'        => 'Incl. up to 23 kg',
				'1st-checked-bag' => 'Incl. up to 23 kg',
				'2nd-checked-bag' => '£50 up to 23 kg',
			),
			array(
				'id'              => 'plus',
				'price'           => '262',
				'tag'             => 'Plus',
				'cabin'           => 'Economy',
				'features'        => array(
					'Seat choice',
					'Cancellation',
					'Changes <span>£50</span>',
					'Personal iItem',
				),
				'carry-on'        => 'Incl. up to 23 kg',
				'1st-checked-bag' => 'Incl. up to 23 kg',
				'2nd-checked-bag' => '£50 up to 23 kg',
			),
		),
	),
	array(
		'airline_logo' => get_template_directory_uri() . '/assets/images/logo-british.png',
		'price'        => 300,
		'direct_time'  => '0:40',
		'departure'    => array(
			'country' => 'London',
			'airport' => 'LHR',
			'time'    => '08:50',
		),
		'destination'  => array(
			'country' => 'Berlin',
			'airport' => 'BER',
			'time'    => '11:50',
		),
		'fares'        => array(
			array(
				'id'              => 'basic',
				'price'           => '244',
				'tag'             => 'Basic',
				'cabin'           => 'Economy',
				'features'        => array(
					'Seat choice',
					'Cancellation',
					'Changes <span>£50</span>',
					'Personal iItem',
				),
				'carry-on'        => 'Incl. up to 23 kg',
				'1st-checked-bag' => '£50 up to 23 kg',
				'2nd-checked-bag' => '£50 up to 23 kg',
			),
			array(
				'id'              => 'plus',
				'price'           => '262',
				'tag'             => 'Plus',
				'cabin'           => 'Economy',
				'features'        => array(
					'Seat choice',
					'Cancellation',
					'Changes <span>£50</span>',
					'Personal iItem',
				),
				'carry-on'        => 'Incl. up to 23 kg',
				'1st-checked-bag' => 'Incl. up to 23 kg',
				'2nd-checked-bag' => '£50 up to 23 kg',
			),
			array(
				'id'              => 'plus',
				'price'           => '262',
				'tag'             => 'Plus',
				'cabin'           => 'Economy',
				'features'        => array(
					'Seat choice',
					'Cancellation',
					'Changes <span>£50</span>',
					'Personal iItem',
				),
				'carry-on'        => 'Incl. up to 23 kg',
				'1st-checked-bag' => 'Incl. up to 23 kg',
				'2nd-checked-bag' => '£50 up to 23 kg',
			),
		),
	),
	array(
		'airline_logo' => get_template_directory_uri() . '/assets/images/logo-british.png',
		'price'        => 323,
		'direct_time'  => '0:05',
		'departure'    => array(
			'country' => 'London',
			'airport' => 'LHR',
			'time'    => '19:50',
		),
		'destination'  => array(
			'country' => 'Berlin',
			'airport' => 'BER',
			'time'    => '22:50',
		),
		'fares'        => array(
			array(
				'id'              => 'basic',
				'price'           => '244',
				'tag'             => 'Basic',
				'cabin'           => 'Economy',
				'features'        => array(
					'Seat choice',
					'Cancellation',
					'Changes <span>£50</span>',
					'Personal iItem',
				),
				'carry-on'        => 'Incl. up to 23 kg',
				'1st-checked-bag' => '£50 up to 23 kg',
				'2nd-checked-bag' => '£50 up to 23 kg',
			),
			array(
				'id'              => 'plus',
				'price'           => '262',
				'tag'             => 'Plus',
				'cabin'           => 'Economy',
				'features'        => array(
					'Seat choice',
					'Cancellation',
					'Changes <span>£50</span>',
					'Personal iItem',
				),
				'carry-on'        => 'Incl. up to 23 kg',
				'1st-checked-bag' => 'Incl. up to 23 kg',
				'2nd-checked-bag' => '£50 up to 23 kg',
			),
			array(
				'id'              => 'plus',
				'price'           => '262',
				'tag'             => 'Plus',
				'cabin'           => 'Economy',
				'features'        => array(
					'Seat choice',
					'Cancellation',
					'Changes <span>£50</span>',
					'Personal iItem',
				),
				'carry-on'        => 'Incl. up to 23 kg',
				'1st-checked-bag' => 'Incl. up to 23 kg',
				'2nd-checked-bag' => '£50 up to 23 kg',
			),
		),
	),
);
?>

<div class="flights">
	<div>
		<?php
		get_template_part(
			'template-parts/api/flights/order',
			'dates-select',
			array( 'event_id' => $order->getOrderEventID() )
		);
		?>
	</div>

	<!-- TODO: ALEX -->
	<!-- IF Your selected flight dates do not match -->
	<div class="warning-message my-2">
		<p class="p1 font--weight--700 position-relative">
			<?php esc_html_e( 'Your selected flight dates do not match your accomodation stay.', 'nirvana' ); ?>
			<button href="#popup-special-flight" class="button--clean text--underline p1"><?php esc_html_e( 'Click here to adjust your dates.', 'nirvana' ); ?></button>
		</p>
		<button class="button--clean btn--warning-message--close" onclick="jQuery(this).parent().slideUp();">
			<svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect x="3.25879" y="4.67285" width="2" height="10" rx="0.5" transform="rotate(-45 3.25879 4.67285)" fill="#F47920"/>
				<rect x="10.3296" y="3.25879" width="2" height="10" rx="0.5" transform="rotate(45 10.3296 3.25879)" fill="#F47920"/>
			</svg>
		</button>
	</div>
	<hr class="my-2">
	<!-- ENDIF -->

	<div class="flights-nav">
		<ol class="flights-nav__inner">
			<li class="flights-nav__item active">
				<?php esc_html_e( 'Choose departing flight' ); ?>
			</li>
			<li class="flights-nav__item">
				<?php esc_html_e( 'Choose returning flight' ); ?>
			</li>
		</ol>
	</div>

	<!-- TODO ALEX -->
	<!-- I EXPECT THAT YOU WILL GET data-key FROM <LI> THEN PASTE IT INTO -->
	<!-- HIDDEN <INPUT> AND REPLACE TEXT IN <h5> => <span> !!! -->
	<div class="flights-sorting mt-2">
		<form action="" class="d-flex">
			<div class="flights-sorting__field">
				<h5><span><?php esc_html_e( 'Sort by', 'nirvana' ); ?></span></h5>
				<div class="flights-sorting__select">
					<ul>
						<li data-key="">
							<span><?php esc_html_e( 'Cost', 'nirvana' ); ?></span>
							<?php esc_html_e( 'Cost Low to high', 'nirvana' ); ?>
						</li>
						<li data-key="">
							<span><?php esc_html_e( 'Cost', 'nirvana' ); ?></span>
							<?php esc_html_e( 'High to low', 'nirvana' ); ?>
						</li>
						<li data-key="">
							<span><?php esc_html_e( 'Duration', 'nirvana' ); ?></span>
							<?php esc_html_e( 'Shortest to longest', 'nirvana' ); ?>
						</li>
						<li data-key="">
							<span><?php esc_html_e( 'Duration', 'nirvana' ); ?></span>
							<?php esc_html_e( 'Longest to shortest', 'nirvana' ); ?>
						</li>
						<li data-key="">
							<span><?php esc_html_e( 'Arrival', 'nirvana' ); ?></span>
							<?php esc_html_e( 'Earliest to latest', 'nirvana' ); ?>
						</li>
						<li data-key="">
							<span><?php esc_html_e( 'Arrival', 'nirvana' ); ?></span>
							<?php esc_html_e( 'Latest to earliest', 'nirvana' ); ?>
						</li>
					</ul>
				</div>
				<input type="hidden" id="sort-by">
			</div>

			<div class="flights-sorting__field ml-auto">
				<h5>
					<?php esc_html_e( 'Stops: ', 'nirvana' ); ?>
					<span><?php esc_html_e( 'Direct', 'nirvana' ); ?></span>
				</h5>
				<div class="flights-sorting__select">
					<ul>
						<li data-key="">
							<?php esc_html_e( 'Direct', 'nirvana' ); ?>
						</li>
					</ul>
				</div>
				<input type="hidden" id="stops">
			</div>

			<div class="flights-sorting__field">
				<h5>
					<?php esc_html_e( 'Flight time: ', 'nirvana' ); ?>
					<span><?php esc_html_e( 'Anytime', 'nirvana' ); ?></span>
				</h5>
				<div class="flights-sorting__select">
					<ul>
						<li data-key="">
							<?php esc_html_e( 'Anytime', 'nirvana' ); ?>
						</li>
					</ul>
				</div>
				<input type="hidden" id="flight-time">
			</div>

			<div class="flights-sorting__field">
				<h5>
					<?php esc_html_e( 'Airline: ', 'nirvana' ); ?>
					<span><?php esc_html_e( 'Any', 'nirvana' ); ?></span>
				</h5>
				<div class="flights-sorting__select">
					<ul>
						<li data-key="">
							<?php esc_html_e( 'Any', 'nirvana' ); ?>
						</li>
					</ul>
				</div>
				<input type="hidden" id="airline">
			</div>

			<div class="flights-sorting__field">
				<h5>
					<?php esc_html_e( 'Cabin: ', 'nirvana' ); ?>
					<span><?php esc_html_e( 'Any', 'nirvana' ); ?></span>
				</h5>
				<div class="flights-sorting__select">
					<ul>
						<li data-key="">
							<?php esc_html_e( 'Any', 'nirvana' ); ?>
						</li>
					</ul>
				</div>
				<input type="hidden" id="cabin">
			</div>
		</form>
	</div>

	<div class="flights-list mt-2">
		<ul class="flights-list__inner d-flex flex-column g-15">
			<?php foreach ( $flights as $flight ) : ?>
				<?php
				get_template_part(
					'template-parts/api/flights/flight',
					'card',
					array(
						'flight' => $flight,
						'order'  => $order,
					)
				);
				?>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
