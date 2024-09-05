<?php if ( isset( $args['event_id'] ) ) : ?>
	<?php
	$start_date = get_field( 'event_start_date', $args['event_id'] );
	$end_date   = get_field( 'event_end_date', $args['event_id'] );
	?>
	<div class="order-dates order-dates--flights">
		<form class="order-form">        
			<div class="order-form__row align-items-end">
				<div class="order-form__way d-flex">
					<div class="order-form__field">
						<label for="departure"><?php esc_html_e( 'Departure', 'nirvana' ); ?></label>
						<input type="text" name="departure">
					</div>
					<div class="order-form__field">
						<label for="destination"><?php esc_html_e( 'Destination', 'nirvana' ); ?></label>
						<input type="text" name="destination">
					</div>
				</div>

				<div class="datepicker" data-start="<?php echo $start_date; ?>" data-end="<?php echo $end_date; ?>">
					<span class="datepicker__field order-form__field">
						<label for="date-checkin"><?php esc_html_e( 'Departing', 'nirvana' ); ?></label>
						<span class="datepicker__field-inner">
							<input type="text" name="date-checkin"/>
							<?php echo get_inline_svg( 'icons/icon-calendar.svg' ); ?>
						</span>
					</span>
					<span class="datepicker__field order-form__field">
						<label for="date-checkout"><?php esc_html_e( 'Returning', 'nirvana' ); ?></label>
						<span class="datepicker__field-inner">
							<input type="text" name="date-checkout"/>
							<?php echo get_inline_svg( 'icons/icon-calendar.svg' ); ?>
						</span>
					</span>
				</div>

				<div class="order-form__field">
					<button class="submit"><?php esc_html_e( 'Search', 'nirvana' ); ?></button>
				</div>
			</div>
		</form>
	</div>

	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<?php endif; ?>
