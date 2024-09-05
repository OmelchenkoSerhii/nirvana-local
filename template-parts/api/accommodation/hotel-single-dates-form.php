<?php
$date     = $args['date'];
$date_format = $args['date_format'];
$hotel_link = $args['hotel_link'];


$start_date = get_field('event_start_date', $args['event_id']);
$end_date = get_field('event_end_date', $args['event_id']);
$currency = get_field('event_default_currency', $args['event_id']);

?>

<div class="order-dates">
    <form method="GET" action="<?php echo $hotel_link; ?>" id="order-hotel-search" class="order-form">
        <h2 class="booking__title order-form__title"><?php esc_html_e('Please select your dates, number of travellers and chosen currency', 'nirvana', 'nirvana'); ?></h2>
        
        <div class="book-accommodation__form-loading" style="display: none;">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/loader.svg" alt="">
        </div>

        <div class="order-form__row align-items-end">
            <div  class="datepicker datepicker--not-selected" data-start="<?php echo $date ; ?>" data-end="<?php echo $date; ?>">
                <span class="datepicker__field order-form__field order-form__field--checkin">
                    <label for="date-checkin"><?php esc_html_e('Check-in', 'nirvana'); ?></label>
                    <span class="datepicker__field-inner">
                        <input type="text" name="date-checkin" required readonly value="<?php echo $date_format; ?>"/>
                        <?php echo get_inline_svg('icons/icon-calendar.svg'); ?>
                    </span>
                </span>
                <span class="datepicker__field order-form__field order-form__field--checkout">
                    <label for="date-checkout"><?php esc_html_e('Check-out', 'nirvana'); ?></label>
                    <span class="datepicker__field-inner">
                        <input type="text" name="date-checkout" required readonly value="<?php echo $date_format; ?>"/>
                        <?php echo get_inline_svg('icons/icon-calendar.svg'); ?>
                    </span>
                </span>

                <span class="datepicker__nights" style="display: none;"><?php _e('Number of nights:','nirvana'); ?> <span class="js-night"></span></span>
            </div>
            <div class="order-form__field">
                <label for="people-field"><?php esc_html_e('Travellers', 'nirvana'); ?></label>
                <div class="people-field dropdown-field">
                    <div class="dropdown-field__header">
                        <span><span data-name="adults_qtt">1</span> <span data-name="adults_qtt_label" data-single="Adult" data-plural="Adults"><?php esc_html_e('Adult', 'nirvana'); ?></span></span>
                        <span><span data-name="childs_qtt">0</span> <span data-name="childs_qtt_label" data-single="Child" data-plural="Children"><?php esc_html_e('Children', 'nirvana'); ?></span></span>
                        <span><span data-name="rooms_qtt">1</span> <span data-name="rooms_qtt_label" data-single="Room" data-plural="Rooms"><?php esc_html_e('Room', 'nirvana'); ?></span></span>
                        <span class="dropdown-field__header-icon"><?php echo get_inline_svg('dropdown-arrow.svg'); ?></span>
                    </div>
                    <div class="dropdown-field__content">
                        <div class="dropdown-field__content-inner">
                            <div class="people-field__rooms">
                                <div class="people-field__room">
                                    <span class="h4 people-field__room__title">Room 1</span>
                                    <div class="people-field__qtt">
                                        <div class="people-field__qtt-inner">
                                            <span class="people-field__qtt__label">
                                                <?php esc_html_e('Adults', 'nirvana'); ?>
                                                <span class="small"><?php esc_html_e('Ages 12+', 'nirvana'); ?></span>
                                            </span>
                                            <span class="people-field__qtt__select">
                                                <span class="minus">-</span>
                                                <input type="number" name="adults_qtt" value="1" min="0" max="10" data-label="Adult" required>
                                                <span class="plus">+</span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="people-field__qtt">
                                        <div class="people-field__qtt-inner">
                                            <span class="people-field__qtt__label">
                                                <?php esc_html_e('Children', 'nirvana'); ?>
                                                <span class="small"><?php esc_html_e('Ages 2-11', 'nirvana'); ?></span>
                                            </span>
                                            <span class="people-field__qtt__select">
                                                <span class="minus disabled">-</span>
                                                <input type="number" name="childs_qtt" value="0" min="0" max="10" data-label="Child" required>
                                                <span class="plus">+</span>
                                            </span>
                                        </div>
                                        <ul class="people-field__qtt__ages people-field__qtt__ages--child">
                                        
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <span class="room-content d-none">
                                <div class="people-field__room js-extra-room mt-2">
                                    <span class="h4 d-block people-field__room__title">Room <span class="js-room-number"></span></span>
                                    <span class="js-remove-room text--size--12 font--weight--700 text-uppercase"><?php esc_html_e('Remove Room', 'nirvana'); ?></span>
                                    <div class="people-field__qtt">
                                        <div class="people-field__qtt-inner">
                                            <span class="people-field__qtt__label">
                                                <?php esc_html_e('Adults', 'nirvana'); ?>
                                                <span class="small"><?php esc_html_e('Ages 12+', 'nirvana'); ?></span>
                                            </span>
                                            <span class="people-field__qtt__select">
                                                <span class="minus disabled">-</span>
                                                <input type="number" class="js-adults" name="adults_qtt" value="0" min="0" max="10" data-label="Adult" required>
                                                <span class="plus">+</span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="people-field__qtt">
                                        <div class="people-field__qtt-inner">
                                            <span class="people-field__qtt__label">
                                                <?php esc_html_e('Children', 'nirvana'); ?>
                                                <span class="small"><?php esc_html_e('Ages 2-11', 'nirvana'); ?></span>
                                            </span>
                                            <span class="people-field__qtt__select">
                                                <span class="minus disabled">-</span>
                                                <input type="number" class="js-childs" name="childs_qtt" value="0" min="0" max="10" data-label="Child" required>
                                                <span class="plus">+</span>
                                            </span>
                                        </div>
                                        <ul class="people-field__qtt__ages people-field__qtt__ages--child">
                                        
                                        </ul>
                                    </div>
                                </div>
                            </span>
                            <div class="d-flex justify-content-between">
                                <span class="button button--orange mt-2 js-add-room px-1"><?php esc_html_e('+ Add room', 'nirvana'); ?></span>
                                <span class="button button--orange mt-2 js-close px-2"><?php esc_html_e('Save', 'nirvana'); ?></span>
                            </div>
                        </div>
                    </div>        
                </div>
            </div>
            <div class="order-form__field currency-field-wrapper">
                <label for="currency-field"><?php esc_html_e('Currency', 'nirvana'); ?></label>
                <div class="custom-select">
                    <select name="currency" id="currency" class="select" required>
                        <option value="GBP" <?php if($currency == 'GBP') echo 'selected'; ?> data-img="<?php echo get_template_directory_uri().'/assets/images/flags/flag-uk.png'; ?>"><?php esc_html_e('GBP', 'nirvana'); ?></option>
                        <option value="USD" <?php if($currency == 'USD') echo 'selected'; ?> data-img="<?php echo get_template_directory_uri().'/assets/images/flags/flag-us.png'; ?>"><?php esc_html_e('USD', 'nirvana'); ?></option>
                        <option value="EUR" <?php if($currency == 'EUR') echo 'selected'; ?> data-img="<?php echo get_template_directory_uri().'/assets/images/flags/flag-europe.png'; ?>"><?php esc_html_e('EUR', 'nirvana'); ?></option>
                        <option value="AUD" <?php if($currency == 'AUD') echo 'selected'; ?> data-img="<?php echo get_template_directory_uri().'/assets/images/flags/flag-aud.png'; ?>"><?php esc_html_e('AUD', 'nirvana'); ?></option>
                        <option value="CAD" <?php if($currency == 'CAD') echo 'selected'; ?> data-img="<?php echo get_template_directory_uri().'/assets/images/flags/flag-canada.png'; ?>"><?php esc_html_e('CAD', 'nirvana'); ?></option>
                        <option value="DKK" <?php if($currency == 'DKK') echo 'selected'; ?> data-img="<?php echo get_template_directory_uri().'/assets/images/flags/flag-dkk.png'; ?>"><?php esc_html_e('DKK', 'nirvana'); ?></option>
                        <option value="CHF" <?php if($currency == 'CHF') echo 'selected'; ?> data-img="<?php echo get_template_directory_uri().'/assets/images/flags/flag-chf.png'; ?>"><?php esc_html_e('CHF', 'nirvana'); ?></option>
                        <option value="NZD" <?php if($currency == 'NZD') echo 'selected'; ?> data-img="<?php echo get_template_directory_uri().'/assets/images/flags/flag-nzd.png'; ?>"><?php esc_html_e('NZD', 'nirvana'); ?></option>
                        <option value="SEK" <?php if($currency == 'SEK') echo 'selected'; ?> data-img="<?php echo get_template_directory_uri().'/assets/images/flags/flag-sweden.png'; ?>"><?php esc_html_e('SEK', 'nirvana'); ?></option>
                    </select>
                </div>
            </div>
            <div class="order-form__field">
                <button class="submit" type="submit"><?php esc_html_e('Search', 'nirvana'); ?></button>
            </div>
        </div>

        <div class="order-form__errors pt-2" style="display: none;"></div>
        
        <?php 
        $order_token = wp_generate_uuid4();
        $clientID = 0;
        if ( is_user_logged_in() ) {
            $current_user = wp_get_current_user();
            $clientID = get_user_meta( $current_user->ID, 'api_client_id', true );
        }
        ?>
        <input type="hidden" name="clientid" value="<?php echo $clientID; ?>">
        <input type="hidden" name="eventid" value="<?php echo $args['event_id']; ?>">
        <input type="hidden" name="order_token" value="<?php echo $order_token; ?>">
        <input type="hidden" name="redirect_url" value="<?php echo $hotel_link; ?>&order_token=<?php echo $order_token; ?>">
    </form>
</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
