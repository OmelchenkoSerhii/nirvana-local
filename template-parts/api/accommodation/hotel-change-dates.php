<?php
$order     = $args['order'];
$hotelCode = $args['hotelCode'];

$start_date = $order->getCheckinDateVal();
$end_date   = $order->getCheckoutDateVal();

if ( $start_date ) :
	$start_date = $start_date->format( 'F j, Y' );
endif;

if ( $end_date ) :
	$end_date = $end_date->format( 'F j, Y' );
endif;

$rooms = $order->getRooms();
$passengers = $order->getPassengers();

$link      = get_field( 'api_accommodation_single_page', 'option' );
$hotelLink = $link . '?hotelid=' . $hotelCode.'&reserve=true';
?>
<div class="order-dates js-change-dates">
	<form id="change-accom-dates" class="order-form" method="POST" action="<?php echo $hotelLink; ?>" data-order="<?php echo $order->getOrderNumber(); ?>">

		<div class="book-accommodation__form-loading" style="display: none;">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/loader.svg" alt="">
		</div>
		<div class="order-form__row align-items-end">
            <div class="datepicker" data-start="<?php echo $start_date; ?>" data-end="<?php echo $end_date; ?>">
                <span class="datepicker__field order-form__field order-form__field--checkin">
                    <label for="date-checkin">Check-in</label>
                    <span class="datepicker__field-inner">
                        <input type="text" name="date-checkin" readonly/>
                        <?php echo get_inline_svg('icons/icon-calendar.svg'); ?>
                    </span>
                </span>
                <span class="datepicker__field order-form__field order-form__field--checkout">
                    <label for="date-checkout">Check-out</label>
                    <span class="datepicker__field-inner">
                        <input type="text" name="date-checkout" readonly/>
                        <?php echo get_inline_svg('icons/icon-calendar.svg'); ?>
                    </span>
                </span>
                <span class="datepicker__nights"><?php _e('Number of nights:','nirvana'); ?> <span class="js-night"></span></span>
            </div>
            <div class="order-form__field">
                <label for="people-field"><?php esc_html_e('Travellers', 'nirvana'); ?></label>
                <div class="people-field dropdown-field">
                    <div class="dropdown-field__header">
                        <span><span data-name="adults_qtt"><?php echo $order->getAdultsQtt(); ?></span> <?php echo $order->getAdultsQtt()==1?__('Adult', 'nirvana'):__('Adults', 'nirvana'); ?></span>
                        <span><span data-name="childs_qtt"><?php echo $order->getChildQtt(); ?></span> <?php echo ($order->getChildQtt())==1?__('Child', 'nirvana'):__('Children', 'nirvana'); ?></span>
                        <span><span data-name="rooms_qtt"><?php echo sizeof($rooms); ?></span> <?php echo sizeof($rooms)>1?__('Rooms', 'nirvana'):__('Room', 'nirvana'); ?></span>
                        <span class="dropdown-field__header-icon"><?php echo get_inline_svg('dropdown-arrow.svg'); ?></span>
                    </div>
                    <div class="dropdown-field__content">
                        <div class="dropdown-field__content-inner">
                            <div class="people-field__rooms">
                                <?php if($rooms): ?>
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
                                                    <input type="number" name="adults_qtt" value="<?php echo $rooms[0]['adults_qtt']; ?>" min="0" max="10" data-label="Adult" required>
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
                                                    <span class="minus">-</span>
                                                    <input type="number" name="childs_qtt" value="<?php echo $rooms[0]['childs_qtt']; ?>" min="0" max="10" data-label="Child" required>
                                                    <span class="plus">+</span>
                                                </span>
                                            </div>
                                            <ul class="people-field__qtt__ages people-field__qtt__ages--child">
                                                <?php foreach($rooms[0]['ages'] as $age): ?>
                                                    <li data-age="<?php echo $age; ?>"></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                        <div class="people-field__qtt">
                                            <div class="people-field__qtt-inner">
                                                <span class="people-field__qtt__label">
                                                    <?php esc_html_e('Infants', 'nirvana'); ?>
                                                    <span class="small"><?php esc_html_e('Ages 0-1', 'nirvana'); ?></span>
                                                </span>
                                                <span class="people-field__qtt__select">
                                                    <span class="minus">-</span>
                                                    <input type="number" name="infants_qtt" value="<?php echo $rooms[0]['infants_qtt']; ?>" min="0" max="10" data-label="Infant" required>
                                                    <span class="plus">+</span>
                                                </span>
                                            </div>
                                            <ul class="people-field__qtt__ages people-field__qtt__ages--infant">
                                                <?php foreach($rooms[0]['infant_ages'] as $age): ?>
                                                    <li data-age="<?php echo $age; ?>"></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php for($i = 0; $i < sizeof($rooms) ; $i++): ?>
                                        <?php if($i == 0) continue;?>
                                        <div class="people-field__room js-extra-room mt-2">
                                            <span class="h4 d-block people-field__room__title">Room <span class="js-room-number"><?php echo $i+1; ?></span></span>
                                            <span class="js-remove-room text--size--12 font--weight--700 text-uppercase"><?php esc_html_e('Remove Room', 'nirvana'); ?></span>
                                            <div class="people-field__qtt">
                                                <div class="people-field__qtt-inner">
                                                    <span class="people-field__qtt__label">
                                                        <?php esc_html_e('Adults', 'nirvana'); ?>
                                                        <span class="small"><?php esc_html_e('Ages 12+', 'nirvana'); ?></span>
                                                    </span>
                                                    <span class="people-field__qtt__select">
                                                        <span class="minus">-</span>
                                                        <input type="number" class="js-adults" name="adults_qtt" value="<?php echo $rooms[$i]['adults_qtt']; ?>" min="0" max="10" data-label="Adult" required>
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
                                                        <span class="minus">-</span>
                                                        <input type="number" class="js-childs" name="childs_qtt" value="<?php echo $rooms[$i]['childs_qtt']; ?>" min="0" max="10" data-label="Child" required>
                                                        <span class="plus">+</span>
                                                    </span>
                                                </div>
                                                <ul class="people-field__qtt__ages people-field__qtt__ages--child">
                                                    <?php foreach($rooms[$i]['ages'] as $age): ?>
                                                        <li data-age="<?php echo $age; ?>"></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                            <div class="people-field__qtt">
                                                <div class="people-field__qtt-inner">
                                                    <span class="people-field__qtt__label">
                                                        <?php esc_html_e('Infants', 'nirvana'); ?>
                                                        <span class="small"><?php esc_html_e('Ages 0-1', 'nirvana'); ?></span>
                                                    </span>
                                                    <span class="people-field__qtt__select">
                                                        <span class="minus">-</span>
                                                        <input type="number" class="js-infants" name="infants_qtt" value="<?php echo $rooms[$i]['infants_qtt']; ?>" min="0" max="10" data-label="Infant" required>
                                                        <span class="plus">+</span>
                                                    </span>
                                                </div>
                                                <ul class="people-field__qtt__ages people-field__qtt__ages--infant">
                                                    <?php foreach($rooms[$i]['infant_ages'] as $age): ?>
                                                        <li data-age="<?php echo $age; ?>"></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                <?php endif; ?>
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
                                    <div class="people-field__qtt">
                                        <div class="people-field__qtt-inner">
                                            <span class="people-field__qtt__label">
                                                <?php esc_html_e('Infants', 'nirvana'); ?>
                                                <span class="small"><?php esc_html_e('Ages 0-1', 'nirvana'); ?></span>
                                            </span>
                                            <span class="people-field__qtt__select">
                                                <span class="minus disabled">-</span>
                                                <input type="number" class="js-infants" name="infants_qtt" value="0" min="0" max="10" data-label="Infant" required>
                                                <span class="plus">+</span>
                                            </span>
                                        </div>
                                        <ul class="people-field__qtt__ages people-field__qtt__ages--infant">
                                        
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
            <div class="order-form__field">
                <button class="submit">Search</button>
            </div>
        </div>
	</form>
</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
