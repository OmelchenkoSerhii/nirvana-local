<?php 

$event_code = '';
$order = isset($args['order'])?$args['order']:false;
if (isset($args['event_code'])):
    $event_code = '?eventid='.$args['event_code'];
endif; 

$sidebarNavSearch = array(
    /*array(
        'title' => 'Dates',
        'link' => get_field('api_book_page','option').$event_code,
        'icon' => get_inline_svg('icons/icon-dates.svg'),
        'template' => 'api-templates/template-api-book.php',
    ),*/
    array(
        'title' => 'Booking Type',
        'link' => get_field('api_booking_type_page','option'),
        'icon' => get_inline_svg('icons/icon-booking-type.svg'),
        'template' => 'api-templates/template-api-booking-type.php',
    ),
);

$sidebarNavTailor = array(
    /*array(
        'title' => 'Dates',
        'link' => get_field('api_book_page','option').$event_code,
        'icon' => get_inline_svg('icons/icon-dates.svg'),
        'template' => 'api-templates/template-api-book.php',
    ),*/
    array(
        'title' => 'Booking Type',
        'link' => get_field('api_booking_type_page','option'),
        'icon' => get_inline_svg('icons/icon-booking-type.svg'),
        'template' => 'api-templates/template-api-booking-type.php',
    ),
    array(
        'title' => 'Accommodation',
        'link' => get_field('api_accommodation_page','option'),
        'icon' => get_inline_svg('icons/icon-accommodation.svg'),
        'template' => 'api-templates/template-api-accommodations.php',
    ),
    array(
        'title' => 'Event services',
        'link' => get_field('api_extras_page','option'),
        'icon' => get_inline_svg('icons/icon-extras.svg'),
        'template' => 'api-templates/template-api-extras.php',
    ),
    /*array(
        'title' => 'Flights',
        'link' => get_field('api_flights_page','option'),
        'icon' => get_inline_svg('icons/icon-flights.svg'),
    ),*/
    /*
    array(
        'title' => 'Transfers',
        'link' => get_field('api_book_page','option'),
        'icon' => get_inline_svg('icons/icon-transfers.svg'),
    ),
    array(
        'title' => 'Car Hire',
        'link' => get_field('api_book_page','option'),
        'icon' => get_inline_svg('icons/icon-car-hire.svg'),
    ),*/
    /*
    array(
        'title' => 'Comments',
        'link' => get_field('api_comments_page','option'),
        'icon' => get_inline_svg('icons/icon-comments.svg'),
        'template' => 'api-templates/template-api-comments.php',
    ),
    */
    array(
        'title' => 'Summary',
        'link' => get_field('api_summary_page','option'),
        'icon' => get_inline_svg('icons/icon-summary.svg'),
        'template' => 'api-templates/template-api-summary.php',
    ),
    array(
        'title' => 'Payment',
        'link' => get_field('api_payment_page','option'),
        'icon' => get_inline_svg('icons/icon-payment.svg'),
        'template' => 'api-templates/template-api-payment.php',
    ),
    /*
    array(
        'title' => 'Thank you',
        'link' => get_field('api_thank_you_page','option'),
        'icon' => get_inline_svg('icons/icon-thanks.svg'),
    ),
    */
);

$sidebarNavTours = array(
    /*array(
        'title' => 'Dates',
        'link' => get_field('api_book_page','option').$event_code,
        'icon' => get_inline_svg('icons/icon-dates.svg'),
        'template' => 'api-templates/template-api-book.php',
    ),*/
    array(
        'title' => 'Booking Type',
        'link' => get_field('api_booking_type_page','option'),
        'icon' => get_inline_svg('icons/icon-booking-type.svg'),
        'template' => 'api-templates/template-api-booking-type.php',
    ),
    array(
        'title' => 'Packages',
        'link' => get_field('api_packages_page','option'),
        'icon' => get_inline_svg('icons/icon-packages.svg'),
        'template' => array(
            'api-templates/template-api-packages.php',
            'api-templates/template-api-package-info.php',
        ),
    ),
    array(
        'title' => 'Accommodation',
        'link' => get_field('api_package_single_page','option'),
        'icon' => get_inline_svg('icons/icon-accommodation.svg'),
        'template' => array(
            'api-templates/template-api-package-single.php',
            'api-templates/template-api-book-package.php',
        ),
        'clickable' => false,
    ),
    array(
        'title' => 'Event services',
        'link' => get_field('api_package_extras_page','option'),
        'icon' => get_inline_svg('icons/icon-extras.svg'),
        'template' => 'api-templates/template-api-package-extras.php',
    ),
    array(
        'title' => 'Airport Transfers',
        'link' => get_field('api_package_transfers_page','option'),
        'icon' => get_inline_svg('icons/icon-transfers.svg'),
        'template' => 'api-templates/template-api-package-transfers.php',
    ),
    /*array(
        'title' => 'Flights',
        'link' => get_field('api_flights_page','option'),
        'icon' => get_inline_svg('icons/icon-flights.svg'),
    ),*/
    /*
    array(
        'title' => 'Transfers',
        'link' => get_field('api_book_page','option'),
        'icon' => get_inline_svg('icons/icon-transfers.svg'),
    ),
    array(
        'title' => 'Car Hire',
        'link' => get_field('api_book_page','option'),
        'icon' => get_inline_svg('icons/icon-car-hire.svg'),
    ),*/
    /*
    array(
        'title' => 'Comments',
        'link' => get_field('api_comments_page','option'),
        'icon' => get_inline_svg('icons/icon-comments.svg'),
        'template' => 'api-templates/template-api-comments.php',
    ),
    */
    array(
        'title' => 'Summary',
        'link' => get_field('api_summary_page','option'),
        'icon' => get_inline_svg('icons/icon-summary.svg'),
        'template' => 'api-templates/template-api-summary.php',
    ),
    array(
        'title' => 'Payment',
        'link' => get_field('api_payment_page','option'),
        'icon' => get_inline_svg('icons/icon-payment.svg'),
        'template' => 'api-templates/template-api-payment.php',
    ),
    /*
    array(
        'title' => 'Thank you',
        'link' => get_field('api_thank_you_page','option'),
        'icon' => get_inline_svg('icons/icon-thanks.svg'),
    ),
    */
);

?>

<div class="booking-sidebar">
    <ul class="booking-sidebar__nav">
        <?php 
        $currentNav = array();
        if(is_page_template('api-templates/template-api-book.php') || is_page_template('api-templates/template-api-booking-type.php')):
            $currentNav = $sidebarNavSearch;        
        else:
            $currentNav = $sidebarNavTailor; 
            foreach($sidebarNavTours as $item):
                if(is_page_template($item['template'])){
                    $currentNav = $sidebarNavTours;
                    break;
                }
            endforeach;
            if(is_page_template('api-templates/template-api-search.php') || is_page_template('api-templates/template-api-book-package.php') || is_page_template('api-templates/template-api-package-info.php') || is_page_template('api-templates/template-api-package-single.php')) $currentNav = $sidebarNavTours;
        endif;
        ?>
        <?php 
        $disabled = false;
        foreach( $currentNav as $item): ?>
            <?php 
            $active = false;
            $show = true;
            if(is_array($item['template'])):
                foreach($item['template'] as $template):
                    if(is_page_template($template)):
                        $active = true;
                        break;
                    endif;
                endforeach;
            else:
                if(is_page_template($item['template'])):
                    $active = true;
                endif;
            endif;

            if(is_page_template('api-templates/template-api-search.php') && $item['title'] == 'Packages') $active = true;

            if($order):
                $orderType = $order->getOrderType();
                $packageData = $order->getPackagesOrderData();
                if($orderType == 'tour' && $packageData && !is_page_template('api-templates/template-api-packages.php')):
                    if(!$packageData[0]['TransfersExists'] && $item['template'] == 'api-templates/template-api-package-transfers.php'):
                        $show = false;
                    endif;
                    if(!$packageData[0]['ExtrasExists'] && $item['template'] == 'api-templates/template-api-package-extras.php'):
                        $show = false;
                    endif;
                    if(!$packageData[0]['AccommodationExists'] && $item['title'] == 'Accommodation'):
                        $show = false;
                    endif;
                    if(!$packageData[0]['AccommodationExists'] && is_page_template('api-templates/template-api-book-package.php') && $item['title'] == 'Packages'):
                        $active = true;
                    endif;
                endif;
            endif;

            if($show):
                ?>
                <li>
                    <?php if($item['title'] == 'Dates'): ?>
                        <a href="<?php echo $item['link']; ?>" class="booking-sidebar__nav-link <?php echo $active?'active':'';?> <?php echo $disabled?'disabled':'';?>">
                            <span class="booking-sidebar__nav-link__icon">
                                <?php echo $item['icon']; ?>
                            </span>
                            <span clsas="booking-sidebar__nav-link__text"><?php echo $item['title']; ?></span>
                        </a>
                    <?php else: ?>
                        <span class="booking-sidebar__nav-link <?php echo $active?'active':'';?> <?php echo $disabled?'disabled':'';?>">
                            <span class="booking-sidebar__nav-link__icon">
                                <?php echo $item['icon']; ?>
                            </span>
                            <span clsas="booking-sidebar__nav-link__text"><?php echo $item['title']; ?></span>
                        </span> 
                    <?php endif; ?>
                </li>
                <?php 
            endif;
            if($active) $disabled = true;
            ?>
        <?php endforeach; ?>
    </ul>
</div>
