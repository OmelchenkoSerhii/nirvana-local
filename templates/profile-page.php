<?php
/*
Template Name: Profile Page
*/
?>
<?php
global $current_user;
wp_get_current_user();

$api = new NiravanaAPI();

$logout_icon = get_field('logout_icon', 'option');

$help_link = get_field('help_link', 'option');
$help_icon = get_field('help_icon', 'option');

$doc_link = get_field('doc_link', 'option');
$doc_icon = get_field('doc_icon', 'option');


?>


<?php get_header(); ?>
<section class="profile-page__heading pt-19 pb-5" style="background-color: #000C26">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-12 ml-auto mr-auto  profile-page__heading__wrapper d-flex justufy-content-center justify-content-md-between" style="flex-wrap: wrap;">
                    <div class="col-md-auto profile-page__heading__title">
                        <h5>Welcome Back</h5>
                        <h1>
                            <?php
                                echo $current_user->user_firstname . ' ' . $current_user->user_lastname;
                            ?>
                        </h1>
                    </div>
                <ul class="col-md-3 col-12 profile-page__heading__links__list d-flex mx-n1 align-items-center">
                    <?php
                    $logout_link_url = wp_logout_url(get_home_url());
                    $logout_link_title = __('Logout');
                    ?>
                    <li class="profile-page__heading__links__item text-center px-1">
                        <a href="<?php echo esc_url($logout_link_url); ?>">
                            <?php if (!empty($logout_icon)) : ?>
                                <img src="<?php echo esc_url($logout_icon['url']); ?>" alt="<?php echo esc_attr($logout_icon['alt']); ?>" />
                            <?php endif; ?>
                            <?php echo esc_html($logout_link_title); ?>
                        </a>
                    </li>

                    <?php if ($help_link) :
                        $help_link_url = $help_link['url'];
                        $help_link_title = $help_link['title'];
                        $help_link_target = $help_link['target'] ? $help_link['target'] : '_self';
                    ?>
                        <li class="profile-page__heading__links__item text-center px-1">
                            <a href="<?php echo esc_url($help_link_url); ?>" target="<?php echo esc_attr($help_link_target); ?>">
                                <?php if (!empty($help_icon)) : ?>
                                    <img src="<?php echo esc_url($help_icon['url']); ?>" alt="<?php echo esc_attr($help_icon['alt']); ?>" />
                                <?php endif; ?>
                                <?php echo esc_html($help_link_title); ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (false && $doc_link) :
                        $doc_link_url = $doc_link['url'];
                        $doc_link_title = $doc_link['title'];
                        $doc_link_target = $doc_link['target'] ? $doc_link['target'] : '_self';
                    ?>
                        <li class="profile-page__heading__links__item text-center px-1">
                            <a href="<?php echo esc_url($doc_link_url); ?>" target="<?php echo esc_attr($doc_link_target); ?>">
                                <?php if (!empty($doc_icon)) : ?>
                                    <img src="<?php echo esc_url($doc_icon['url']); ?>" alt="<?php echo esc_attr($doc_icon['alt']); ?>" />
                                <?php endif; ?>
                                <?php echo esc_html($doc_link_title); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

        </div>

    </div>
</section>
<section class="section text-dark" style="background-color: #F2F2F4">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-12 ml-auto mr-auto ">
                <h2>Events</h2>

                <?php if(get_field('account_page_notice', 'option')): ?>
                    <div class="content-block content-block--dark mt-3 mb-3"><?php the_field('account_page_notice', 'option'); ?></div>
                <?php endif; ?>


                <div class="book-accommodation__form-loading" style="display: none;">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/loader.svg" alt="">
                </div>
                <?php
                $clientID = get_user_meta($current_user->ID, 'api_client_id', true);
                if ($clientID) :
                    $args = array(
                        'post_type' => 'booking',
                        'meta_query' => array(
                            array(
                                'key' => 'client_id',
                                'value' => $clientID,
                                'compare' => '='
                            )
                        )
                    );

                    $events_qtt = 0;

                    $webBookings = array();
                    $query = new WP_Query($args);
                    if ($query->have_posts()) {
                        while ($query->have_posts()) {
                            $query->the_post();

                            $order_token = get_post_meta( get_the_ID() , 'order_token', true );
                            $event_id = get_post_meta( get_the_ID() , 'event_id', true );
                            $transaction_status =  get_post_meta( get_the_ID() , 'transaction_status', true );
                            $transaction_due_status =  get_post_meta( get_the_ID() , 'rest_transaction_status', true );
                            $start_date = DateTime::createFromFormat('j F Y', get_post_meta(get_the_ID() , 'date-checkin', true));
                            $title = get_the_title($event_id);
                            $image = get_the_post_thumbnail_url($event_id);
                            $category = wp_get_post_terms( $event_id, 'event_category' );
                            $date = get_field('event_start_date' , $event_id);
                            $order = new NiravanaOrder($order_token);
                            
                            $booking_id = $order->getBookingID();

                            if(!in_array($booking_id, $webBookings)):
                                if($booking_id):
                                    array_push($webBookings , $booking_id);
                                ?>
                                <div class="event-card d-flex flex-column flex-md-row" style="padding-bottom: 5px; padding-top: 5px;">
                                    <?php $dateFormat = DateTime::createFromFormat('Ymd', $date); ?>
                                    <?php if($start_date): ?>
                                        <time style="background-color: #000C26;color: #fff;padding: 25px 40px; width: 160px;" class="event-card__dated-flex flex-column d-flex text-center z-1 justify-content-center" datetime="<?php echo $date; ?>">
                                            <span class="p1 text-uppercase"><?php echo $start_date->format('M')?></span>
                                            <span class="h2"><?php echo $start_date->format('j')?></span>
                                            <span class="p1"><?php echo $start_date->format('Y')?></span>
                                        </time>
                                    <?php endif; ?>
                                    <div class="event-card__main-content text--color--dark p-2 p-sm-3 pr-md-3" style="width: 100%;padding-right: 30px;    border: 1px solid darkgrey;">
                                        <?php if($booking_id): ?>
                                            <div>
                                                <h3><?php echo __('Booking reference:', 'nirvana').' '.$booking_id; ?></h3>
                                            </div>
                                        <?php endif; ?>
                                        <div class="row d-flex align-items-center  justify-content-between">
                                            <div class="event-booked-card__cat col-md-3">
                                                <div class="text--size--16">Event Series</div>
                                                <?php
                                                if ($category):
                                                    foreach ($category as $term) {
                                                        echo $term->name;
                                                    }
                                                endif; 
                                                ?>
                                            </div>
                                            <div class="event-booked-card__cat col-md-4">
                                                <div class="text--size--16">Event Name</div>
                                                <?php echo $title; ?>
                                            </div>
                                            
                                            <?php if($booking_id): ?>
                                                <?php
                                                $bookingData = $api->GetBookingData($booking_id);
                                                $currency = 'GBP';
                                                if($bookingData):
                                                    $currency = $bookingData->BookingManagementResult->Booking->CurrencyCode;
                                                    $amountPaid = number_format(floatval($bookingData->BookingManagementResult->Booking->TotalPaid), 2, '.', '');
                                                    $amountDue = number_format(floatval($bookingData->BookingManagementResult->Booking->TotalDue), 2, '.', '');
                                                    $totalCost = number_format(floatval($bookingData->BookingManagementResult->Booking->TotalPrice), 2, '.', '');
                                                    
                                                    ?> 
                                                    <?php if($amountDue > 0): ?>
                                        
                                                        
                                                        <div class="event-booked-card__cat col-md-2">
                                                            <div class="text--size--16">Balance Due</div>
                                                            <?php echo $order->getCurrencySymbol(); ?><?php echo ($amountDue); ?>
                                                        </div>
                                                        <?php if($totalCost - $amountPaid != 0): ?>
                                                            <div class="col-md-3 text-right">
                                                                <a class="button button--orange" href="<?php echo get_field('api_payment_page', 'option'); ?>?order_token=<?php echo $order_token; ?>" target="_blank">make payment</a> 
                                                            </div>
                                                        <?php endif; ?>
                                                    
                
                                                    <?php else: ?>
                                                        <div class="col-md-3 text-right">
                                                            <span class="button button--dark ml-auto">PAID <?php echo $order->getCurrencySymbolByCode($currency); ?><?php echo $totalCost; ?></span>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="col ml-auto text-right py-1">
                                                        <span class="button button--orange js-download-doc ml-auto" data-type="Invoice" data-booking="<?php echo $order->getBookingID(); ?>" data-event="<?php echo $order->getEventCode();?>"> <?php _e('Download invoice' , 'nirvana'); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    
                                    </div>
                                </div>
                            <?php
                            endif;
                            $events_qtt++;
                            endif;
                        }
                        /* Restore original Post Data */
                        wp_reset_postdata();
                    } else {
                        // no posts found
                    }

                    /**
                     * 
                     * Bookings from RES
                     * 
                     */

                    $bookingsFromAPI = $api->GetBookingsByClient($clientID);
                    if(isset($bookingsFromAPI->BookingSearchResult->Bookings->Booking)):
                        if(is_array( $bookingsFromAPI->BookingSearchResult->Bookings->Booking )):
                            $bookings = $bookingsFromAPI->BookingSearchResult->Bookings->Booking;
                        else: 
                            $bookings = array($bookingsFromAPI->BookingSearchResult->Bookings->Booking);
                        endif;

                        foreach($bookings as $booking):
                            $booking_id = $booking->Bcode;
                            if(!in_array($booking_id, $webBookings)):
                                $product = $booking->ProductCode;
                                $date = $booking->DepartureDate;
                                $amountPaid = $booking->AmountPaid;
                                $totalCost = $booking->TotalValue;
                                $events_qtt++;
                                $bookingData = $api->GetBookingData($booking_id);
                                $currency = 'GBP';
                                $amountDue = 0;
                                if($bookingData):
                                    $currency = $bookingData->BookingManagementResult->Booking->CurrencyCode;
                                    $amountDue = floatval($bookingData->BookingManagementResult->Booking->TotalDue);
                                endif;
                                ?>
                                <?php if(false): ?>
                                <pre>
                                    <?php print_r( $booking);?>
                                    <hr>
                                    <?php print_r( $bookingData->BookingManagementResult);?>
                                </pre>
                                <?php endif; ?>
                                <div class="event-card d-flex flex-column flex-md-row" style="padding-bottom: 5px; padding-top: 5px;">
                                    <?php $dateFormat = strtotime($date); ?>
                                    <?php if($dateFormat): ?>
                                        <time style="background-color: #000C26;color: #fff;padding: 25px 40px; width: 160px;" class="event-card__dated-flex flex-column d-flex text-center z-1 justify-content-center" datetime="<?php echo $date; ?>">
                                            <span class="p1 text-uppercase"><?php echo date('M',$dateFormat); ?></span>
                                            <span class="h2"><?php echo date('j',$dateFormat);?></span>
                                            <span class="p1"><?php echo date('Y',$dateFormat);?></span>
                                        </time>
                                    <?php endif; ?>
                                    <div class="event-card__main-content text--color--dark p-2 p-sm-3 pr-md-3" style="width: 100%;padding-right: 30px;    border: 1px solid darkgrey;">
                                        <?php if($booking_id): ?>
                                            <div>
                                                <h3><?php echo __('Booking reference:', 'nirvana').' '.$booking_id; ?></h3>
                                            </div>
                                        <?php endif; ?>
                                        <div class="row d-flex align-items-center  justify-content-between">
                                            
                                            <div class="event-booked-card__cat col-md-4">
                                                <div class="text--size--16">Event Name</div>
                                                <?php echo $product; ?>
                                            </div>
                                            

                                                <?php if($amountDue != 0 ): ?>
                                                    <div class="event-booked-card__cat col-md-2">
                                                        <div class="text--size--16">Balance Due</div>
                                                        <?php echo $api->getCurrencySymbolByCode($currency); ?><?php echo number_format($amountDue, 2, '.', ''); ?>
                                                    </div>
                                                    <div class="col-md-3 text-right">
                                                        <a class="button button--orange" href="<?php echo get_field('api_payment_page', 'option'); ?>?booking_id=<?php echo $booking_id; ?>&create_order=1" target="_blank">make payment</a> 
                                                    </div>
                                                <?php elseif($amountDue == 0): ?>
                                                    <div class="col-md-3 text-right">
                                                        <span class="button button--dark ml-auto">PAID <?php echo $api->getCurrencySymbolByCode($currency); ?><?php echo number_format($amountPaid, 2, '.', ''); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="col ml-auto text-right py-1">
                                                    <span class="button button--orange js-download-doc ml-auto" data-type="Invoice" data-booking="<?php echo $booking_id; ?>" data-event="<?php echo ''; ?>"> <?php _e('Download invoice' , 'nirvana'); ?></span>
                                                </div>
                                            
                                        </div>
                                    
                                    </div>
                                </div>
                                <?php
                            endif; 
                        endforeach;
                    endif;

                    if($events_qtt == 0):
                        ?>
                        <h3 class="mt-4">No bookings found. <a style="color: #f47920;" href="https://nirvanaevents2.wpengine.com/events/">Browse events here</a></h3>
                        <?php
                    endif;

                else :
                ?>
                    <h3 class="mt-4">No client found. <a style="color: #f47920;" href="<?php the_field( 'api_contact_page', 'option' ); ?>">Please contact us</a></h3>
                <?php
                endif;
                ?>

                

            </div>
        </div>
</section>
<?php get_footer(); ?>