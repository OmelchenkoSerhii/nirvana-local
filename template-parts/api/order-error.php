<div class="order-confirmation">
    <div class="text-center">
        <img class="order-confirmation__icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/payment-unsucessful.svg" alt="">
        <h3 class="order-confirmation__title"><?php _e('Please select an event.', 'nirvana'); ?></h3>
        <a class="button button--orange mt-3" href="<?php echo get_field('api_events_page','option'); ?>"><?php _e('Events'); ?></a>
    </div>
</div>