<div id="popup-notify" class="popup-block popup-notify">
	<div class="popup-block__background"></div>
	<div class="popup-block__inner">
		<div class="container popup-block__container position-relative">
			<div class="popup-block__content text--color--dark p-2">
				<span id="popupClose" class="popup-block__close"></span>
				
				<form id="notify-form" class="order-form notify-me-form">
					<h4 class="order-form__title mb-2">Notify me - <span class="order-form__title-event"><?php echo get_the_title(); ?></span></h4>
					<div class="order-form__inner">
						<div class="order-form__row">
							<div class="order-form__field mb-1">
								<label for="first-name">First name</label>
								<input type="text" name="first-name" class="w-100" required>
							</div>
							<div class="order-form__field mb-1">
								<label for="last-name">Last name</label>
								<input type="text" name="last-name" class="w-100" required>
							</div>
						</div>
						<div class="order-form__field mb-1">
							<label for="email">Email address</label>
							<input type="text" name="email" class="w-100" required>
						</div>
						<div class="order-form__field mb-1">
							<label for="phone">Contact number</label>
							<input type="text" name="phone" class="w-100" required>
						</div>
						<div class="order-form__field mb-1">
							<label for="message">Message</label>
							<input type="textarea" name="message" class="w-100" required>
						</div>
						<input type="hidden" name="destination" value="<?php echo esc_html( get_event_country_by_taxonomy() ); ?>">
						<input type="hidden" name="title" value="<?php echo esc_html( get_the_title() ); ?>">
						<?php
						$event_start_date = get_field( 'event_start_date' );
						if ( $event_start_date ) :
							$dateFormat = DateTime::createFromFormat( 'Ymd', $event_start_date );
							?>
							<input type="hidden" name="year" value="<?php echo $dateFormat->format( 'Y' ); ?>">
							<input type="hidden" name="month" value="<?php echo $dateFormat->format( 'F' ); ?>">
						<?php endif; ?>
						<p class="text--size--12 text--opacity">By submitting this form I agree to my details being used in sole connection with the intended enquiry. Please check our <a class="text--underline text--color--light-orange" href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>">privacy policy</a> to see how we protect and manage your submitted data.</p>
						<div class="order-form__field mt-3 text-center">
							<button class="submit" type="submit">Submit</button>
						</div>

						<p class="error mt-2" style="display:none;"><?php _e( 'Sorry, we were unable to process your request. Please try again.', 'nirvana' ); ?></p>
					</div>	
					<p class="success mt-2 h4 py-5 text-center" style="display:none;"><?php echo "Thank you for messaging us regarding <span class='event'>".get_the_title()."</span>, we will be in touch when we go on sale."; ?></p>			
				</form>


			</div>

		</div>
	</div>
</div>
