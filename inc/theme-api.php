<?php
/*
=====================
Theme API
=====================
 */

require get_template_directory() . '/inc/api-classes/NirvanaAPI.php';
require get_template_directory() . '/inc/api-classes/NirvanaOrder.php';


function order_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    $api = new NiravanaAPI();
?>
    <div class="wp-order-table wp-booking-table">
        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Order token : </span>
                <span><?php echo get_post_meta($object->ID, "order_token", true); ?></span>
            </div>
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Quote ID : </span>
                <span><?php print_r(get_post_meta($object->ID, "quote_id", true)); ?></span>
            </div>
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Booking ID : </span>
                <span><?php print_r(get_post_meta($object->ID, "booking_id", true)); ?></span>
            </div>
        </div>

        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Order Type : </span>
                <span><?php print_r(get_post_meta($object->ID, "booking_type", true)); ?></span>
            </div>
        </div>

        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Order Status : </span>
                <span>
                    <?php
                    $transaction_id = get_post_meta($object->ID, "transaction_id", true);
                    $rest_transaction_id = get_post_meta($object->ID, "rest_transaction_id", true);
                    $booking_id = get_post_meta($object->ID, "booking_id", true);
                    $transaction_status = get_post_meta($object->ID, "transaction_status", true);
                    $rest_transaction_status = get_post_meta($object->ID, "rest_transaction_status", true);

                    $payment_type = get_post_meta($object->ID, 'payment_type', true);
                    $billing_info = get_post_meta($object->ID, "billing_info", true);
                    $rest_billing_info = get_post_meta($object->ID, "rest_billing_info", true);

                    $rooms = get_post_meta($object->ID, "rooms", true);

                    if ($payment_type) :
                        if ($payment_type != 'due_payment') :
                            if ($transaction_id && $transaction_status && $transaction_status != 'ERROR' && !$booking_id) :
                                echo 'Payment completed , error on Booking Creation';
                            elseif ($transaction_id && $transaction_status && $transaction_status == 'ERROR') :
                                echo 'Error on Payment';
                            elseif ($transaction_id && !$transaction_status) :
                                echo 'Error on Payment';
                            elseif ($transaction_id && $transaction_status && $booking_id) :
                                echo 'Booking Created and Payment Completed';
                            else :
                                echo 'Payment not Completed';
                            endif;
                        else :
                            if ($rest_transaction_id && !$rest_transaction_status) :
                                if ($rooms) :
                                    echo 'Error on Payment';
                                else :
                                    echo 'Error on Payment through my account payment link';
                                endif;
                            elseif ($rest_transaction_id && $rest_transaction_status && $rest_transaction_status != 'ERROR') :
                                echo 'Booking Payment Completed';
                            elseif ($rest_transaction_id && $rest_transaction_status && $rest_transaction_status == 'ERROR') :
                                if ($rooms) :
                                    echo 'Error on Payment';
                                else :
                                    echo 'Error on Payment through my account payment link';
                                endif;
                            else :
                                echo 'Payment not Completed';
                            endif;
                        endif;
                    else :
                        if ($booking_id) :
                            echo 'Order created from RES Booking';
                        else :
                            echo 'Quote Created';
                        endif;
                    endif;
                    ?>
                </span>
            </div>
        </div>

        <div class="wp-order-table__row wp-booking-table__row">
            <?php
            $clientID = get_post_meta($object->ID, "client_id", true);
            $userID = get_post_meta($object->ID, "user_id", true);
            $user_info = get_userdata($userID);
            ?>
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Client ID : </span>
                <span><?php echo $clientID ? $clientID : 'No client assigned'; ?></span>
            </div>
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">WP User : </span>
                <span><?php echo $userID ? '<a href="' . get_edit_user_link($userID) . '" target="_blank">' . $user_info->first_name . ' ' . $user_info->last_name . '</a>' : 'No user assigned'; ?></span>
            </div>

        </div>

        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell">
                <?php $event_id = get_post_meta($object->ID, "event_id", true); ?>
                <span style="font-weight: 700;">Event Name : </span>
                <span><a href="<?php echo get_the_permalink($event_id); ?>" target="_blank"><?php echo get_the_title($event_id); ?></a></span>
            </div>
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Event Code : </span>
                <span><?php echo get_field("event_code", $event_id); ?></span>
            </div>
        </div>


        <div class="wp-order-table__row wp-booking-table__row" style="display: none">
            <span style="font-weight: 700;">Event ID : </span>
            <span><?php echo get_post_meta($object->ID, "event_id", true); ?></span>
        </div>

        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Check-in Date : </span>
                <span><?php echo get_post_meta($object->ID, "date-checkin", true); ?></span>
            </div>
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Check-out Date : </span>
                <span><?php echo get_post_meta($object->ID, "date-checkout", true); ?></span>
            </div>
        </div>

        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Currency : </span>
                <span><?php echo get_post_meta($object->ID, "currency", true); ?></span>
            </div>
        </div>
        <div class="wp-order-table__row wp-booking-table__row">
            <?php
            $passengers = get_post_meta($object->ID, 'passengers', true);
            $adultsQtt = 0;
            $childsQtt = 0;
            $infantsQtt = 0;
            foreach ($passengers as $passenger) :
                if ($passenger['Age'] > 11) $adultsQtt += 1;
                if ($passenger['Age'] <= 11 && $passenger['Age'] >= 2) $childsQtt += 1;
                if ($passenger['Age'] < 2) $infantsQtt += 1;
            endforeach;
            ?>
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Adults Quantity : </span>
                <span><?php echo $adultsQtt; ?></span>
            </div>
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Child Quantity : </span>
                <span><?php echo $childsQtt; ?></span>
            </div>
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Infants Quantity : </span>
                <span><?php echo $infantsQtt; ?></span>
            </div>
        </div>

        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Tour Info: </span>
                <div>
                    <?php
                    $package_data = get_post_meta($object->ID, "package_data", true);
                    if ($package_data) :
                        ?>
                        <pre><?php print_r($package_data); ?></pre>
                        <?php
                    endif; ?>
                </div>
            </div>
        </div>
        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Tour Accommodation: </span>
                <div>
                    <?php
                    $package_accommodation = get_post_meta($object->ID, "package_accommodation", true);
                    if ($package_accommodation) :
                        ?>
                        <pre><?php print_r($package_accommodation); ?></pre>
                        <?php
                    endif; ?>
                </div>
            </div>
        </div>
        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Tour Reserved Accommodation: </span>
                <div>
                    <?php
                    $package_accommodation = get_post_meta($object->ID, "package_accommodation_reserved", true);
                    if ($package_accommodation) :
                        ?>
                        <pre><?php print_r($package_accommodation); ?></pre>
                        <?php
                    endif; ?>
                </div>
            </div>
        </div>
        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Tour Reserved Extras: </span>
                <div>
                    <pre>
                    <?php
                    $package_extras = get_post_meta($object->ID, "reserved_package_extras", true);
                    if ($package_extras) :
                        print_r($package_extras);
                    endif; ?>
                    </pre>
                </div>
            </div>
        </div>
        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Tour Reserved Transfers: </span>
                <div>
                    <?php
                    $package_transfers = get_post_meta($object->ID, "reserved_package_transfers", true);
                    if ($package_transfers) :
                        print_r($package_transfers);
                    endif; ?>
                </div>
            </div>
        </div>

        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Rooms Configuration: </span>
                <div>
                    <?php
                    $rooms = get_post_meta($object->ID, "rooms", true);
                    if ($rooms) :
                        foreach ($rooms as $key => $room) :
                    ?>
                            <div class="wp-booking-table__room">
                                <span style="font-weight: 700; margin-right: 20px;">Room <?php echo $key + 1; ?></span>
                                <span>Adults : <?php echo $room['adults_qtt']; ?> ,</span>
                                <span>Children : <?php echo $room['childs_qtt']; ?></span>
                                <span>Infants : <?php echo $room['infants_qtt']; ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>


        <div class="wp-order-table__row wp-booking-table__row" style="display: block;">
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Selected Accommodation : </span>
                <span><?php print_r(get_post_meta($object->ID, "accommodation", true)); ?></span>
            </div>
        </div>

        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell">
                <div style="font-weight: 700; padding-bottom: 10px;">Reserved Accommodations : </div>
                <div>
                    <?php
                    $accoms = get_post_meta($object->ID, "reservedAccommodation", true);
                    //print_r($accoms);
                    if ($accoms) :
                        foreach ($accoms as $accom) :
                            $hotelID = $api->getHotelIDbyCode($accom['hotel']);
                            ?>
                            <div style="padding: 5px 0; border-top: 1px solid grey;">
                                <?php if($hotelID): ?>
                                    <div>Hotel : <a href="<?php echo get_edit_post_link($hotelID); ?>"><?php echo get_the_title($hotelID); ?></a></div>
                                <?php else: ?>
                                    <?php if(isset($accom['hotel_name'])): ?>
                                        <div>Hotel : <?php echo $accom['hotel_name']; ?></div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <div>Room : <?php echo $accom['name']; ?></div>
                                <div>Room Code : <?php echo $accom['id']; ?></div>
                                <div>Price : <?php echo $accom['price']; ?></div>
                                <div><span style="font-weight: 700;">Travellers : </span>
                                    <?php foreach ($accom['passengers'] as $passenger) : ?>
                                        <div><?php echo $passenger['title'] . ' ' . $passenger['name'] . ' ' . $passenger['surname'] . ', ' . $passenger['age'] . ' years'; ?></div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Passengers : </span>
                <div>
                    <?php
                    $passengers = get_post_meta($object->ID, "passengers", true);
                    print_r($passengers);
                    if ($passengers) :
                        foreach ($passengers as $passenger) :
                    ?>
                            <div><?php echo $passenger['Title'] . ' ' . $passenger['Name'] . ' ' . $passenger['Surname'] . ', ' . $passenger['Age'] . ' years, ref number : ' . $passenger['ref_number'] . (isset($passenger['Type']) ? ' , Type :' . $passenger['Type'] : ''); ?></div>
                    <?php
                        endforeach;
                    endif; ?>
                </div>
            </div>
        </div>

        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell">
                <div style="font-weight: 700; padding-bottom: 10px;">Reserved Extras : </div>
                <div>
                    <?php
                    $extras = get_post_meta($object->ID, "reservedExtras", true);
                    if ($extras) :
                        foreach ($extras as $extra) :
                    ?>
                            <div style="padding: 5px 0; border-top: 1px solid grey;">
                                <div><b>Extra Name :</b> <?php echo $extra['name']; ?></div>
                                <div><b>Price :</b> <?php echo $extra['price']; ?></div>
                                <div><span style="font-weight: 700;">Travellers : </span>
                                    <?php foreach ($extra['travellers'] as $passenger) : ?>
                                        <?php
                                        $actualPassenger = false;
                                        foreach ($passengers as $item) :
                                            if ($item['ref_number'] == $passenger['ref_number']) {
                                                $actualPassenger = $item;
                                                break;
                                            }
                                        endforeach;
                                        if ($actualPassenger) :
                                        ?>
                                            <div><?php echo $actualPassenger['Title'] . ' ' . $actualPassenger['Name'] . ' ' . $actualPassenger['Surname'] . ', ' . $actualPassenger['Age'] . ' years'; ?></div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Comment : </span>
                <span><?php echo (get_post_meta($object->ID, "comment", true)); ?></span>
            </div>
        </div>

        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell">
                <div style="font-weight: 700;">Billing Information : </div>
                <?php
                $billing_info = get_post_meta($object->ID, "billing_info", true);
                if ($billing_info) :
                ?>
                    <div>
                        <div><b>First Name :</b> <?php echo $billing_info['first-name']; ?></div>
                        <div><b>Last Name :</b> <?php echo $billing_info['last-name']; ?></div>
                        <div><b>Address 1 :</b> <?php echo $billing_info['address-1']; ?></div>
                        <div><b>Address 2 :</b> <?php echo $billing_info['address-2']; ?></div>
                        <div><b>Town :</b> <?php echo $billing_info['town']; ?></div>
                        <div><b>Postcode :</b> <?php echo $billing_info['postcode']; ?></div>
                        <div><b>Country :</b> <?php echo $billing_info['country']; ?></div>
                        <div><b>Phone :</b> <?php echo $billing_info['phone']; ?></div>
                        <div><b>Email :</b> <?php echo $billing_info['email']; ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Payment Type : </span>
                <span><?php echo get_post_meta($object->ID, "payment_type", true); ?></span>
            </div>
        </div>
        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell">
                <span style="font-weight: 700;">Custom Amount : </span>
                <span><?php echo get_post_meta($object->ID, "custom_amount", true); ?></span>
            </div>
        </div>


        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell  wp-booking-dropdown">
                <div class="wp-booking-dropdown_header" style="font-weight: 700; ">
                    <span>Payment API Info</span>
                    <span class="toggle-indicator" aria-hidden="false"></span>
                </div>
                <div class="wp-booking-dropdown_content" style="display: none">
                    <div class="wp-order-table__row wp-booking-table__row" style="padding: 10px">
                        <span style="font-weight: 700;">Transaction ID : </span>
                        <span><?php print_r(get_post_meta($object->ID, "transaction_id", true)); ?></span>
                    </div>
                    <div class="wp-order-table__row wp-booking-table__row" style="padding: 10px">
                        <span style="font-weight: 700;">Transaction Status : </span>
                        <span><?php print_r(get_post_meta($object->ID, "transaction_status", true)); ?></span>
                    </div>
                    <div class="wp-order-table__row wp-booking-table__row" style="padding: 10px">
                        <span style="font-weight: 700;">Rest Billing Information : </span>
                        <span><?php print_r(get_post_meta($object->ID, "rest_billing_info", true)); ?></span>
                    </div>
                    <div class="wp-order-table__row wp-booking-table__row" style="padding: 10px">
                        <span style="font-weight: 700;">Rest Transaction ID : </span>
                        <span><?php print_r(get_post_meta($object->ID, "rest_transaction_id", true)); ?></span>
                    </div>
                    <div class="wp-order-table__row wp-booking-table__row" style="padding: 10px">
                        <span style="font-weight: 700;">Rest Transaction Status : </span>
                        <span><?php print_r(get_post_meta($object->ID, "rest_transaction_status", true)); ?></span>
                    </div>
                    <div class="wp-order-table__row wp-booking-table__row" style="padding: 10px">
                        <span style="font-weight: 700;">Rest Transactions IDs : </span>
                        <span><?php print_r(get_post_meta($object->ID, "rest_transactions_ids", true)); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="wp-order-table__row wp-booking-table__row">
            <div class="wp-booking-table__cell  wp-booking-dropdown">
                <div class="wp-booking-dropdown_header" style="font-weight: 700; ">
                    <span>Booking API Info</span>
                    <span class="toggle-indicator" aria-hidden="false"></span>
                </div>
                <div class="wp-booking-dropdown_content" style="display: none">
                    <div class="wp-order-table__row wp-booking-table__row" style="padding: 10px">
                        <span style="font-weight: 700;">Booking ID : </span>
                        <span><?php print_r(get_post_meta($object->ID, "booking_id", true)); ?></span>
                    </div>
                    <div class="wp-order-table__row wp-booking-table__row" style="padding: 10px">
                        <span style="font-weight: 700;">Booking Creation Request : </span>
                        <span><?php print_r(get_post_meta($object->ID, "booking_creation_request", true)); ?></span>
                    </div>
                    <div class="wp-order-table__row wp-booking-table__row" style="padding: 10px">
                        <span style="font-weight: 700;">Booking Creation Result : </span>
                        <span><?php print_r(get_post_meta($object->ID, "booking_creation", true)); ?></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        jQuery('.wp-booking-dropdown').each(function() {
            let block = jQuery(this);
            let header = jQuery(this).find('.wp-booking-dropdown_header');
            let content = jQuery(this).find('.wp-booking-dropdown_content');
            header.click(function() {
                content.slideToggle();
                block.toggleClass('active');
            });
        });
    </script>
    <?php
}

function add_order_meta_box($object)
{
    add_meta_box("order-meta-box", "Booking Info", "order_meta_box_markup", "booking", "normal", "high", null);
}

add_action("add_meta_boxes", "add_order_meta_box");


function redirect_if_booking($order)
{
    $bookingID = $order->getBookingID();
    $summary_page = get_field('api_summary_page', 'option');
    if ($bookingID && $summary_page) :
        wp_redirect($summary_page);
    endif;
}

/**
 *
 * Sync events and accoms
 *
 */

 function log_RES_sync($file_name , $title , $text){
    // Define the log directory
    $log_dir = WP_CONTENT_DIR . '/sync-logs/';

    // Create the log directory if it doesn't exist
    if (!file_exists($log_dir)) {
        mkdir($log_dir, 0755, true);
    }


    // Generate a unique log filename for each booking
    $log_filename = $log_dir . $file_name . '.txt';

    $request_data = htmlspecialchars_decode($request_data);

    // Create or open the log file for writing
    $log_file = fopen($log_filename, 'a');

    if ($log_file) {
        // Format the log entry with timestamp
       // $log_entry = "[" . date("Y-m-d H:i:s") . "] " . "\n";
        $log_entry = $title.": ".$text .= "\n";

        // Write the log entry to the log file
        fwrite($log_file, $log_entry);

        // Close the log file
        fclose($log_file);
    } else {
        // Log file couldn't be opened
        //error_log("Failed to open log file: " . $log_filename);
    }
}


function api_sync_events()
{
    $api = new NiravanaAPI();
    $apiData = $api->GetStaticData();
    if($apiData):
        $events = $apiData->Products->Product;
        $hotels = $apiData->Accommodation->Hotel;
        $tours = $apiData->Tours->Tour;
        $debug = false;

        $time = date("Y-m-d H:i");

        log_RES_sync('sync_logs' , 'SYNC STARTED' , '['.$time.']');

        if ($hotels) :
            if ($debug) :
                ?>
                <pre><?php print_r($hotels); ?></pre>
                <?php
            endif;
            foreach ($hotels as $hotel) :
                $hotelCode = $hotel->Hcode;
                $hotelName = $hotel->Name;
                $hotelCity = $hotel->Resort;
                $hotelRating = $hotel->Rating;
                $hotelDesc = '';
                $hotelID = false;

                $hotelsPosts = get_posts(array(
                    'numberposts' => 1,
                    'post_type' => 'hotel',
                    'meta_key' => 'hotel_id',
                    'meta_value' => $hotelCode,
                ));
                if ($hotelsPosts) : //if hotel with provided code exists
                    $hotelPost = $hotelsPosts[0];
                    $hotelID = $hotelPost->ID;
                    $post = array(
                        'ID' => $hotelID,
                        'post_title' => $hotelName,
                        'post_status' => 'publish',
                        'post_type' => 'hotel',
                        'meta_input' => array(
                            'hotel_location' => $hotelCity,
                            'hotel_rating' => $hotelRating,
                            'hotel_description_api' => $hotelDesc,
                        ),
                    );
                    wp_update_post($post);
                    log_RES_sync('sync_logs' , 'Hotel - '.$hotelName , 'updated');
                else : //if booking with provided token doesnt exist
                    $post = array(
                        'post_title' => $hotelName,
                        'post_status' => 'publish',
                        'post_type' => 'hotel',
                        'meta_input' => array(
                            'hotel_id' => $hotelCode,
                            'hotel_location' => $hotelCity,
                            'hotel_rating' => $hotelRating,
                            'hotel_description_api' => $hotelDesc,
                        ),
                    );
                    $hotelID = wp_insert_post($post);
                    log_RES_sync('sync_logs' , 'Hotel - '.$hotelName , 'added');
                endif;

                if ($hotelID) :
                    $arrayRooms = array();
                    $arrayRoomsFromWP = get_field('hotel_rooms', $hotelID);
                    $apiRooms = false;
                    if (is_array($hotel->Room)) {
                        $apiRooms = $hotel->Room;
                    } else {
                        $apiRooms = array($hotel->Room);
                    }
                    //echo $hotelName;
                    if ($arrayRoomsFromWP) :
                        //echo '<br>Hotel Rooms exists';
                        //print_r($arrayRoomsFromWP);
                        if ($apiRooms) :
                            foreach ($apiRooms as $room) :
                                if ($room->RoomType) :
                                    $roomExistsFlag = false;
                                    foreach ($arrayRoomsFromWP as $roomWP) :
                                        if ($roomWP['id'] == $room->Hucode) :
                                            $roomWP['room_type_name'] == $room->RoomType;
                                            $roomWP['id'] = $room->Hucode;
                                            $roomWP['max_adults'] = $room->MaxOccupancy;
                                            array_push($arrayRooms, $roomWP);
                                            $roomExistsFlag = true;
                                            break;
                                        endif;
                                    endforeach;
                                    if (!$roomExistsFlag) :
                                        array_push($arrayRooms, array(
                                            'room_type_name' => $room->RoomType,
                                            'id' => $room->Hucode,
                                            'max_adults' => $room->MaxOccupancy,
                                        ));
                                    endif;
                                endif;
                            endforeach;
                            log_RES_sync('sync_logs' , 'Hotel - '.$hotelName , 'updated rooms');
                        endif;
                    else :
                        if ($apiRooms) :
                            foreach ($apiRooms as $room) :
                                if ($room->Hucode) :
                                    array_push($arrayRooms, array(
                                        'room_type_name' => $room->RoomType,
                                        'id' => $room->Hucode,
                                        'max_adults' => $room->MaxOccupancy,
                                    ));
                                endif;
                            endforeach;
                            log_RES_sync('sync_logs' , 'Hotel - '.$hotelName , 'added rooms');
                        endif;
                    endif;
                endif;
                update_field('hotel_rooms', $arrayRooms, $hotelID);
            endforeach;
        else:
            log_RES_sync('sync_logs' , 'ERROR' , 'No hotels found in the RES system.');
        endif;

        if ($events) :

            foreach ($events as $event) :
                $event_title = $event->Description;
                $event_code = $event->ProjectCode;
                $event_id = $event->ProductId;
                $event_pmcode = $event->PMCode;
                $start_date = $event->StartDate;
                $start_date_str = '';
                if ($start_date) :
                    $start_date_obj = DateTime::createFromFormat('d F Y', $start_date);
                    if ($start_date_obj) :
                        $start_date_str = $start_date_obj->format('F j, Y');
                    endif;
                endif;

                if ($debug) :
                ?>
                    <pre><?php print_r($event); ?></pre>
                    <?php
                endif;

                /**
                 * Checking if event with this title exists
                 */
                $post_id = false;

                $eventsQueryArgs = array(
                    'fields' => 'ids',
                    'meta_key' => 'event_code',
                    'meta_value' => $event_code,
                    'post_type' => 'event',
                );

                $eventQuery = new WP_Query($eventsQueryArgs);
                if ($eventQuery->have_posts()) {
                    while ($eventQuery->have_posts()) {
                        $eventQuery->the_post();
                        $post_id = get_the_ID();
                    }
                }
                wp_reset_postdata();

                /**
                 * Add / Update event
                 */

                if ($post_id) : //If event code exists

                    if (get_field('event_start_date', $post_id)) {
                        $start_date_str = get_field('event_start_date', $post_id);
                    }

                    $post = array(
                        'ID' => $post_id,
                        'post_title' => get_the_title($post_id),
                        'post_status' => 'publish',
                        'post_type' => 'event',
                        'meta_input' => array(
                            'event_id' => $event_id,
                            'event_code' => $event_code,
                            'event_pm_code' => $event_pmcode,
                            'event_start_date' => $start_date_str,
                        ),
                    );
                    wp_update_post($post);

                    log_RES_sync('sync_logs' , 'Event - '.$event_title.' - '.$event_code.' - ' , 'updated');

                else : //if event name doesnt exist

                    $post = array(
                        'post_title' => $event_title,
                        'post_status' => 'publish',
                        'post_type' => 'event',
                        'meta_input' => array(
                            'event_id' => $event_id,
                            'event_code' => $event_code,
                            'event_pm_code' => $event_pmcode,
                            'event_start_date' => $start_date_str,
                        ),
                    );
                    $post_id = wp_insert_post($post);

                    log_RES_sync('sync_logs' , 'Event - '.$event_title.' - '.$event_code.' - ' , 'added');

                endif;

                $hotels = $api->getEventHotels($post_id);
                $hotelPostsIDs = array();
                if ($hotels) :

                    if (is_array($hotels)) :
                        foreach ($hotels as $hotel) :
                            $hotelPostID = $api->getHotelIDbyCode($hotel->Hcode);
                            if ($hotelPostID) {
                                array_push($hotelPostsIDs, $hotelPostID);
                            }
                        endforeach;
                        //print_r($hotelPostsIDs);
                        //print_r($hotels[0]->Resort);
                        update_field('event_resort', $hotels[0]->Resort, $post_id);
                        update_field('event_hotels', $hotelPostsIDs, $post_id);

                        log_RES_sync('sync_logs' , 'Event Hotels - '.$event_title , 'updated hotels');
                    else :
                        $hotelPostID = $api->getHotelIDbyCode($hotels->Hcode);
                        if ($hotelPostID) {
                            array_push($hotelPostsIDs, $hotelPostID);
                        }

                        //print_r($hotelPostsIDs);
                        //print_r($hotels->Resort);
                        update_field('event_resort', $hotels->Resort, $post_id);
                        update_field('event_hotels', $hotelPostsIDs, $post_id);

                        log_RES_sync('sync_logs' , 'Event Hotels - '.$event_title , 'updated hotels');
                    endif;
                endif;
            endforeach;
        else:
            log_RES_sync('sync_logs' , 'ERROR' , 'No events found in the RES system.');
        endif;

        if ($tours) :
            foreach ($tours as $tour) :
                $TMcode = $tour->TMcode;
                $tourName = $tour->TourName;
                $TourCode = $tour->TourCode;
                $SeasonName = $tour->SeasonName;
                $country = $tour->Countries->Country->CountryCode;

                $tourID = false;
                $toursPosts = get_posts(array(
                    'numberposts' => 1,
                    'post_type' => 'tour',
                    'meta_key' => 'tmcode',
                    'meta_value' => $TMcode,
                ));
                if ($toursPosts) : //if booking with provided token exists
                    $toursPost = $toursPosts[0];
                    $tourID = $toursPost->ID;
                    $post = array(
                        'ID' => $tourID,
                        'post_title' => get_the_title($tourID),
                        'post_status' => 'publish',
                        'post_type' => 'tour',
                        'meta_input' => array(
                            'tourcode' => $TourCode,
                            'season_name' => $SeasonName,
                            'country' => $country,
                        ),
                    );
                    wp_update_post($post);
                    log_RES_sync('sync_logs' , 'Tour - '.$tourName , 'updated');
                else : //if booking with provided token doesnt exist
                    $post = array(
                        'post_title' => $tourName,
                        'post_status' => 'publish',
                        'post_type' => 'tour',
                        'meta_input' => array(
                            'tmcode' => $TMcode,
                            'tourcode' => $TourCode,
                            'season_name' => $SeasonName,
                            'country' => $country,
                        ),
                    );
                    $tourID = wp_insert_post($post);
                    log_RES_sync('sync_logs' , 'Tour - '.$tourName , 'added');
                endif;

            endforeach;
        else:
            log_RES_sync('sync_logs' , 'ERROR' , 'No tours found in the RES system.');
        endif;

        $time = date("Y-m-d H:i");

        log_RES_sync('sync_logs' , 'SYNC FINISHED' , '['.$time.']');
    else:
        $time = date("Y-m-d H:i");
        log_RES_sync('sync_logs' , 'ERROR '.'['.$time.']' , 'SOAP Error');
    endif;
}

add_action('api_sync_events', 'api_sync_events');

/**
 *
 * Ajax function for creating booking
 *
 */
function ajax_create_booking()
{
    if (isset($_POST['data'])) {
        $data = json_decode(stripslashes($_POST['data']), true);
        $order = new NiravanaOrder($data['order']);
        if (!$order->orderExists()) :
            $order->createOrderPost($data['eventid'], $data['date-checkin'], $data['date-checkout'], $data['currency'], $data['rooms'], $data['clientid'], $data['celtic-code']);
        else :
            $order->UpdateOrderPost($data);
        endif;
        return wp_send_json_success($data);
    } else {
        return false;
    }
    die();
}
add_action('wp_ajax_nopriv_ajax_create_booking', 'ajax_create_booking');
add_action('wp_ajax_ajax_create_booking', 'ajax_create_booking');

/**
 *
 * Ajax functions for add/remove rooms
 *
 */
function ajax_reserve_accom()
{

    if (isset($_POST['data'])) {

        $data = json_decode(stripslashes($_POST['data']), true);
        $order = new NiravanaOrder($data['order']);
        $result = $order->reserveAccom($data['rooms']);
        return wp_send_json_success($result);
    }
    die();
}

add_action('wp_ajax_nopriv_ajax_reserve_accom', 'ajax_reserve_accom');
add_action('wp_ajax_ajax_reserve_accom', 'ajax_reserve_accom');


/**
 *
 * Ajax functions for add package
 *
 */
function ajax_reserve_package_accom()
{

    if (isset($_POST['data'])) {

        $data = json_decode(stripslashes($_POST['data']), true);
        $order = new NiravanaOrder($data['order']);
        $result = $order->addPackage($data['hotels']);
        //$result = print_r($_POST['data'] , true);
        return wp_send_json_success($result);
    }
    die();
}

add_action('wp_ajax_nopriv_ajax_reserve_package_accom', 'ajax_reserve_package_accom');
add_action('wp_ajax_ajax_reserve_package_accom', 'ajax_reserve_package_accom');

/**
 *
 * Ajax functions for add package
 *
 */
function ajax_reserve_package()
{

    if (isset($_POST['data'])) {

        $data = json_decode(stripslashes($_POST['data']), true);
        $order = new NiravanaOrder($data['order']);
        $result = $order->reservePackage($data['rooms']);
        return wp_send_json_success($result);
    }
    die();
}

add_action('wp_ajax_nopriv_ajax_reserve_package', 'ajax_reserve_package');
add_action('wp_ajax_ajax_reserve_package', 'ajax_reserve_package');

/**
 *
 * Ajax functions for add package
 *
 */
function ajax_add_package_passengers()
{

    if (isset($_POST['data'])) {

        $data = json_decode(stripslashes($_POST['data']), true);
        $order = new NiravanaOrder($data['order']);
        $result = $order->reservePackagePassengers($data['passengers']);
        return wp_send_json_success($result);
    }
    die();
}

add_action('wp_ajax_nopriv_ajax_add_package_passengers', 'ajax_add_package_passengers');
add_action('wp_ajax_ajax_add_package_passengers', 'ajax_add_package_passengers');

/**
 *
 * Ajax functions for removing rooms
 *
 */
function ajax_remove_accom()
{

    if (isset($_POST['data'])) {
        $data = json_decode(stripslashes($_POST['data']), true);
        $order = new NiravanaOrder($data["order"]);
        $result = $order->removeAccommodationRoom($data);

        return $result;
    }
    die();
}

add_action('wp_ajax_nopriv_ajax_remove_accom', 'ajax_remove_accom');
add_action('wp_ajax_ajax_remove_accom', 'ajax_remove_accom');

/**
 *
 * Ajax functions for removing rooms from quote
 *
 */
function ajax_remove_accom_from_quote()
{

    if (isset($_POST['data'])) {
        $data = json_decode(stripslashes($_POST['data']), true);
        $order = new NiravanaOrder($data["order"]);
        $result = $order->removeAccommodation($data);

        return $result;
    }
    die();
}

add_action('wp_ajax_nopriv_ajax_remove_accom_from_quote', 'ajax_remove_accom_from_quote');
add_action('wp_ajax_ajax_remove_accom_from_quote', 'ajax_remove_accom_from_quote');


/**
 *
 * Ajax functions for add extra
 *
 */
function ajax_reserve_extras()
{

    if (isset($_POST['data'])) {

        $data = json_decode(stripslashes($_POST['data']), true);

        ob_start();

        $order = new NiravanaOrder($data['order']);
        print_r($order->reserveExtrasArray($data['data']));
        $result = ob_get_clean();
        return wp_send_json_success($result);
    }
    die();
}

add_action('wp_ajax_nopriv_ajax_reserve_extras', 'ajax_reserve_extras');
add_action('wp_ajax_ajax_reserve_extras', 'ajax_reserve_extras');


/**
 *
 * Ajax functions for add package extra
 *
 */
function ajax_reserve_packages_extras()
{

    if (isset($_POST['data'])) {

        $data = json_decode(stripslashes($_POST['data']), true);

        ob_start();

        $order = new NiravanaOrder($data['order']);
        print_r($order->reservePackageExtras($data['data']));
        $result = ob_get_clean();

        $packageData = $order->getPackagesOrderData();
        if(!$packageData[0]['TransfersExists']){
            $order->addPackageToQuote();
        }

        return wp_send_json_success($result);
    }
    die();
}

add_action('wp_ajax_nopriv_ajax_reserve_packages_extras', 'ajax_reserve_packages_extras');
add_action('wp_ajax_ajax_reserve_packages_extras', 'ajax_reserve_packages_extras');


/**
 *
 * Ajax functions for add package extra
 *
 */
function ajax_reserve_tour()
{

    if (isset($_POST['data'])) {

        $data = json_decode(stripslashes($_POST['data']), true);

        $order = new NiravanaOrder($data['order']);
        $order->addPackageTransfers($data['transfers']);
        $result = $order->addPackageToQuote();
        return wp_send_json_success($result);
    }
    die();
}

add_action('wp_ajax_nopriv_ajax_reserve_tour', 'ajax_reserve_tour');
add_action('wp_ajax_ajax_reserve_tour', 'ajax_reserve_tour');



/**
 *
 * Ajax functions for remove extra
 *
 */
function ajax_remove_extra()
{
    if (isset($_POST['data'])) {

        $data = json_decode(stripslashes($_POST['data']), true);

        $order = new NiravanaOrder($data['order']);
        $result = $order->removeExtra($data);
        return wp_send_json_success($result);
    }
    die();
}

add_action('wp_ajax_nopriv_ajax_remove_extra', 'ajax_remove_extra');
add_action('wp_ajax_ajax_remove_extra', 'ajax_remove_extra');


/**
 *
 * Ajax functions for update dates on accom
 *
 */
function ajax_update_accom()
{

    if (isset($_POST['data'])) {

        $data = json_decode(stripslashes($_POST['data']), true);

        $api = new NiravanaAPI();
        $order = new NiravanaOrder($data['order']);
        $order->UpdateOrderPost($data);
    }
    die();
}

add_action('wp_ajax_nopriv_ajax_update_accom', 'ajax_update_accom');
add_action('wp_ajax_ajax_update_accom', 'ajax_update_accom');

/**
 *
 * Ajax functions for search accoms
 *
 */
function ajax_search_accoms()
{

    if (isset($_POST['data'])) {

        $data = json_decode(stripslashes($_POST['data']), true);

        $api = new NiravanaAPI();
        $order = new NiravanaOrder($data['order']);
        $order->UpdateOrderPost($data);
        $hotels = $order->searchAccoms();
        get_template_part(
            'template-parts/api/accommodation/order-accoms',
            'data',
            array(
                'order' => $order,
                'api' => $api,
                'hotels' => $hotels,
            )
        );
        $orderEventId     = $order->getEventPostID();
		$enable_bedbanks_tab = get_field('enable_bedbanks_tab' , $orderEventId);
		if($enable_bedbanks_tab):
			$bedbanks = $order->searchBedBanks();
			if($bedbanks && $bedbanks[0]):
				get_template_part(
					'template-parts/api/bedbanks/order-bedbanks',
					'data',
					array(
						'order' => $order,
						'api' => $api,
						'bedbanks' => $bedbanks,
					)
				);
			endif;
		endif;
    }
    die();
}

add_action('wp_ajax_nopriv_ajax_search_accoms', 'ajax_search_accoms');
add_action('wp_ajax_ajax_search_accoms', 'ajax_search_accoms');

/**
 *
 * Ajax functions for search accoms
 *
 */
function ajax_search_bedbanks()
{

    if (isset($_POST['data'])) {

        $data = json_decode(stripslashes($_POST['data']), true);

        $api = new NiravanaAPI();
        $order = new NiravanaOrder($data['order']);
        $order->UpdateOrderPost($data);
       
        $orderEventId     = $order->getEventPostID();
		$enable_bedbanks_tab = get_field('enable_bedbanks_tab' , $orderEventId);
		if($enable_bedbanks_tab):
			$bedbanks = $order->searchBedBanks();
			if($bedbanks && $bedbanks[0]):
				get_template_part(
					'template-parts/api/bedbanks/order-bedbanks',
					'data-separate',
					array(
						'order' => $order,
						'api' => $api,
						'bedbanks' => $bedbanks,
					)
				);
			endif;
		endif;
    }
    die();
}

add_action('wp_ajax_nopriv_ajax_search_bedbanks', 'ajax_search_bedbanks');
add_action('wp_ajax_ajax_search_bedbanks', 'ajax_search_bedbanks');



/**
 *
 * Ajax functions for search packages
 *
 */
function ajax_search_packages()
{

    if (isset($_POST['data'])) {

        $data = json_decode(stripslashes($_POST['data']), true);

        $api = new NiravanaAPI();
        $order = new NiravanaOrder($data['order']);
        $order->UpdateOrderPost($data);
        $packages = $order->searchPackages();
        get_template_part(
            'template-parts/api/packages/order-packages',
            'data',
            array(
                'order' => $order,
                'api' => $api,
                'packages' => $packages,
            )
        );
    }
    die();
}

add_action('wp_ajax_nopriv_ajax_search_packages', 'ajax_search_packages');
add_action('wp_ajax_ajax_search_packages', 'ajax_search_packages');



/**
 *
 * Ajax functions for search accoms
 *
 */
function ajax_search_rooms()
{

    if (isset($_POST['data'])) {

        $data = json_decode(stripslashes($_POST['data']), true);

        $api = new NiravanaAPI();
        $order = new NiravanaOrder($data['order']);
        $order->UpdateOrderPost($data);

        $hotelCode = $data['hotelcode'];
        $hotelPostID = $data['hotel'];

        //Get rooms detailed info, missing prices

        if (intval($data['minNights']) <= intval($order->getNightsQtt())) :
            //Get rooms general info
            $hotels = $order->searchAccoms();
            $hotelData = array();
            $selected_rooms =  $order->getAccommodations();
            $hotelInfo = $api->GetHotelInfo($order->getEventCode(), $order->getPassengerTypeQuantity(), $hotelCode, $order->getCheckinDate(), $order->getCheckoutDate());


            if (sizeof($hotels) != 0) :
                $roomCounter = 0;
                foreach ($hotels as $roomHotels) :

                    if ($roomHotels) :
                        if (is_array($roomHotels)) :
                            foreach ($roomHotels as $hotel) :
                                if ($hotel->BasicPropertyInfo->HotelCode == $hotelCode) :
                                    $hotelData[$roomCounter] = $hotel;
                                endif;
                            endforeach;
                        else :
                            if ($roomHotels->BasicPropertyInfo->HotelCode == $hotelCode) :
                                $hotelData[$roomCounter] = $roomHotels;
                            endif;
                        endif;
                    endif;
                    $roomCounter++;
                endforeach;

                if ($hotelData) :
                    get_template_part(
                        'template-parts/api/accommodation/hotel-rooms',
                        'data',
                        array(
                            'rooms' => $hotelData,
                            'order' => $order,
                            'hotel_post_id' => $hotelPostID,
                            'selected_rooms' => $selected_rooms,
                            'hotelInfo' => $hotelInfo,
                        )
                    );
                else :
                ?>
                    <h3 class="p-1"><?php _e('No available rooms found for selected dates.', 'nirvana'); ?></h3>
                <?php
                endif;
            else :
                ?>
                <h3 class="p-1"><?php _e('No available rooms found for selected dates.', 'nirvana'); ?></h3>
            <?php
            endif;
        else :
            ?>
            <h3 class="p-1"><?php _e('Minimum of ' . $data['minNights'] . ' nights needed to reserve this hotel room. You need to change your dates to reserve it.', 'nirvana'); ?></h3>
        <?php
        endif;
    }
    die();
}

add_action('wp_ajax_nopriv_ajax_search_rooms', 'ajax_search_rooms');
add_action('wp_ajax_ajax_search_rooms', 'ajax_search_rooms');


/**
 *
 * Ajax functions for search accoms
 *
 */
function ajax_search_bedbanks_rooms()
{

    if (isset($_POST['data'])) {

        $data = json_decode(stripslashes($_POST['data']), true);

        $api = new NiravanaAPI();
        $order = new NiravanaOrder($data['order']);
        $order->UpdateOrderPost($data);

        $hotelCode = $data['hotelcode'];
        $hotelPostID = $data['hotel'];

        //Get rooms detailed info, missing prices

        if (intval($data['minNights']) <= intval($order->getNightsQtt())) :
            //Get rooms general info
            $hotels = $order->searchBedBanks();
            $hotelData = array();
            $selected_rooms =  $order->getAccommodations();
            

            if (sizeof($hotels) != 0) :
                $roomCounter = 0;
                foreach ($hotels as $roomHotels) :

                    if ($roomHotels) :
                        if (is_array($roomHotels)) :
                            foreach ($roomHotels as $hotel) :
                                if ($hotel->BasicPropertyInfo->HotelCode == $hotelCode) :
                                    $hotelData[$roomCounter] = $hotel;
                                endif;
                            endforeach;
                        else :
                            if ($roomHotels->BasicPropertyInfo->HotelCode == $hotelCode) :
                                $hotelData[$roomCounter] = $roomHotels;
                            endif;
                        endif;
                    endif;
                    $roomCounter++;
                endforeach;

        
                if ($hotelData) :
                    if (is_array($hotelData[0]->RoomRates->RoomRate)):
                        $locator = $hotelData[0]->RoomRates->RoomRate[0]->Rates->Rate->TPA_Extensions->Locator;
                    else:
                        $locator = $hotelData[0]->RoomRates->RoomRate->Rates->Rate->TPA_Extensions->Locator;
                    endif;
                    $hotelInfo = $api->GetBedBankHotelInfo($order->getEventCode(), $order->getPassengerTypeQuantity(), $locator, $order->getCheckinDate(), $order->getCheckoutDate());    
                    get_template_part(
                        'template-parts/api/bedbanks/hotel-rooms',
                        'data',
                        array(
                            'rooms' => $hotelData,
                            'order' => $order,
                            'hotel_post_id' => $hotelPostID,
                            'selected_rooms' => $selected_rooms,
                            'hotelInfo' => $hotelInfo,
                        )
                    );
                else :
                ?>
                    <h3 class="p-1"><?php _e('No available rooms found for selected dates.', 'nirvana'); ?></h3>
                <?php
                endif;
            else :
                ?>
                <h3 class="p-1"><?php _e('No available rooms found for selected dates.', 'nirvana'); ?></h3>
            <?php
            endif;
        else :
            ?>
            <h3 class="p-1"><?php _e('Minimum of ' . $data['minNights'] . ' nights needed to reserve this hotel room. You need to change your dates to reserve it.', 'nirvana'); ?></h3>
        <?php
        endif;
    }
    die();
}

add_action('wp_ajax_nopriv_ajax_search_bedbanks_rooms', 'ajax_search_bedbanks_rooms');
add_action('wp_ajax_ajax_search_bedbanks_rooms', 'ajax_search_bedbanks_rooms');


/**
 *
 * Ajax functions for Notify Me Popup
 *
 */
function ajax_notify_me()
{

    if (isset($_POST['data'])) {

        $data = json_decode(stripslashes($_POST['data']), true);
        $result = $data;

        $token_dev = '030c035e-24be-4148-872d-6239393753d9';
        $token_live = '9fec7d7b-1c2a-4f7a-a96e-091fe96b8a42';

        $base_url_dev = 'https://uat-nirvana-crm.hosting.inspiretec.com/api/external/events?token=' . $token_dev;
        $base_url_live = 'https://live-nirvana-crm.hosting.inspiretec.com/api/external/events?token=' . $token_live;

        $token = '';
        $base_url = '';
        if (get_field('api_mode', 'option') == 'live') :
            $token = $token_live;
            $base_url = $base_url_live;
        else :
            $token = $token_dev;
            $base_url = $base_url_dev;
        endif;

        $api = new NiravanaAPI();
        $client = $api->SearchClientByEmail($data['email']);
        $fullName = [
            "title" => "Mr",
            "first" => $data['first-name'],
            "last" => $data['last-name'],
        ];
        if($client['id'] == 1):
            $fullName = $client['response']['fullName'];
        endif;

        $curl = curl_init();

        $params = array(
            'token' => $token,
            'type' => 'enquired',
            'context' => 'Notify Me',
            'tags' => array(
                "title" => $data['title'],
                "enquiry" => $data['message'],
            ),
            "visitor" => array(
                "email" => $data['email'],
                "fullName" => $fullName,
                "mobileNumber" => $data['phone'],
            ),
        );

        curl_setopt_array($curl, [
            CURLOPT_URL => $base_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "content-type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $result = array(
            'client' => $client,
            'fullname' => $fullName,
        );
        if ($err) {
            $result['response'] = "cURL Error #:" . $err;
        } else {
            $result['response'] = $response;
        }

        // Fetch ACF repeater field data for email recipients.
        if (have_rows('crm_email_recepients', 'option')) {
            while (have_rows('crm_email_recepients', 'option')) {
                the_row();
                $recipient_email = get_sub_field('email');
                
                // Send email to the current recipient.
                $to = $recipient_email;
                $subject = "New Notify Me form submission - ".$data['title'];
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $body = "
                    <h2>New Notify Me form submission - {$data['title']}</h2>
                    <p><strong>First Name:</strong> {$data['first-name']}</p>
                    <p><strong>Last Name:</strong> {$data['last-name']}</p>
                    <p><strong>Email:</strong> {$data['email']}</p>
                    <p><strong>Phone:</strong> {$data['phone']}</p>
                    <p><strong>Event:</strong> {$data['title']}</p>
                    <p><strong>Message:</strong> {$data['message']}</p>
                ";
                
                wp_mail($to, $subject, $body, $headers);
            }
        }

        return wp_send_json_success($client);
    }
    die();
}

add_action('wp_ajax_nopriv_ajax_notify_me', 'ajax_notify_me');
add_action('wp_ajax_ajax_notify_me', 'ajax_notify_me');


/**Send NF submission to API */
add_action('send_api_contact_form', 'send_api_contact_form', 10, 2);
function send_api_contact_form($form_data)
{
    $form_id = $form_data['form_id'];
    $message = '';
    if ($form_id == 2) :
        $first_name = $form_data['fields_by_key']['first_name']['value'];
        $last_name = $form_data['fields_by_key']['last_name']['value'];
        $email = $form_data['fields_by_key']['email']['value'];
        $phone = $form_data['fields_by_key']['phone']['value'];
        $event_series = $form_data['fields_by_key']['event_series']['value'];
        $event = $form_data['fields_by_key']['event']['value'];
        $message = $form_data['fields_by_key']['enquiry']['value'];

        $token_dev = '030c035e-24be-4148-872d-6239393753d9';
        $token_live = '9fec7d7b-1c2a-4f7a-a96e-091fe96b8a42';

        $base_url_dev = 'https://uat-nirvana-crm.hosting.inspiretec.com/api/external/events?token=' . $token_dev;
        $base_url_live = 'https://live-nirvana-crm.hosting.inspiretec.com/api/external/events?token=' . $token_live;

        $token = '';
        $base_url = '';
        if (get_field('api_mode', 'option') == 'live') :
            $token = $token_live;
            $base_url = $base_url_live;
        else :
            $token = $token_dev;
            $base_url = $base_url_dev;
        endif;

        $api = new NiravanaAPI();
        $client = $api->SearchClientByEmail( $email);
        $fullName = [
            "first" => $first_name,
            "last" => $last_name,
        ];
        if($client['id'] == 1):
            $fullName = $client['response']['fullName'];
        endif;

        $curl = curl_init();
        
        $params = array(
            'token' => $token,
            'type' => 'enquired',
            'context' => 'Contact Us',
            'tags' => array(
                "title" => $event,
                "eventSeries" => $event_series,
                "enquiry" => $message,
            ),
            "visitor" => array(
                "email" => $email,
                "fullName" => $fullName,
                "mobileNumber" => $phone,
            ),
        );

        curl_setopt_array($curl, [
            CURLOPT_URL => $base_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "content-type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $result = "cURL Error #:" . $err;
            //error_log( $result);
        } else {
            //error_log( 'API Request Success: ' . $response);
        }
    endif;
}




/**
 *
 * Ajax function for downloading docs
 *
 */
function ajax_download_doc()
{

    if (isset($_POST['data'])) {

        $data = json_decode(stripslashes($_POST['data']), true);

        $api = new NiravanaAPI();
        $result = $api->downloadDoc($data['type'], $data['event'], $data['booking']);
        if ($result && isset($result->GetDocumentResult->Document)) {
            return wp_send_json_success($result->GetDocumentResult->Document);
        }
        return wp_send_json_success(false);
    }
    die();
}

add_action('wp_ajax_nopriv_ajax_download_doc', 'ajax_download_doc');
add_action('wp_ajax_ajax_download_doc', 'ajax_download_doc');



/**
 * 
 * Order Content Heading
 * 
 */
function order_content_header( $template_slug , $order , $api , $arguments = array())
{
    ?>
    <div class="booking-heading">
    <?php
    
    $title = isset($arguments['title'])?$arguments['title']:false;
    $orderType = 'tailor';
    if($order) $orderType = $order->getOrderType();
    $celtic = false;
    if($order) $celtic = is_celtic_active($order->getEventPostID());

    $enableBedBanks = false;
    $orderEventId     = $order->getEventPostID();
	$enable_bedbanks_tab = get_field('enable_bedbanks_tab' , $orderEventId);
    if($enable_bedbanks_tab) $enableBedBanks = true;
    if($template_slug == 'bedbanks') $orderType = 'bedbanks';

    if(
        (
        $template_slug == 'packages'
        || $template_slug == 'package'
        || $template_slug == 'book-package'
        || $template_slug == 'extras'
        || $template_slug == 'transfers'
        || $template_slug == 'accomomodations'
        || $template_slug == 'accomomodation'
        || $template_slug == 'bedbanks'
        || $template_slug == 'bedbank'
        )
        &&
        !$celtic
    ):
    ?>
        <div class="header-booking__type">
            <?php
            $booking_types = get_field('booking_types', 'option');
            if ($booking_types) :
            ?>
                <h3 class="mb-2"><?php _e('Please use the tabs below to view our range of services.' , 'nirvana'); ?></h3>
                <ul class="header-booking__type__list d-flex flex-wrap">
                    <?php foreach ($booking_types as $type) : ?>
                        <?php
                        $image = $type['image'];
                        $name = $type['name'];
                        $link = $type['link'];
                        $type_slug = $type['type'];
                        if(!$enableBedBanks && $type_slug == 'bedbanks') continue;
                        ?>
                        <li class="header-booking__type__item <?php if($orderType == $type['type']) echo 'active'; ?>">
                            <?php if ($link) : ?>
                                <a class="header-booking__type__item__link" href="<?php echo esc_url($link); ?>">
                                    <?php if ($image) : ?>
                                        <div class="header-booking__type__item__image">
                                            <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                                        </div>
                                    <?php endif; ?>
                                    <span class="header-booking__type__item__title"><?php echo esc_html($name); ?></span>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php
    endif;

    if (is_page_template('api-templates/template-api-accommodation.php') || is_page_template('api-templates/template-api-book-accommodation.php')) :

        if (isset($_GET['hotelid'])) :
            $hotelCode = $_GET['hotelid'];
        elseif (isset($_POST['hotelid'])) :
            $hotelCode = $_POST['hotelid'];
        endif;
        $hotelPostId = $api->GetAccomHotelPostID($hotelCode);
        ?>
        <div class="d-flex align-items-center header-booking__main__accom">
                <?php if (is_page_template('api-templates/template-api-accommodation.php')) : ?>
                    <a class="button button-back button--dark-transparent mr-sm-3" href="<?php echo get_field('api_accommodation_page', 'option'); ?>">
                <?php else : ?>
                    <a class="button button-back button--dark-transparent mr-sm-3" onclick="history.back()">
                <?php endif; ?>
                    <svg width="15" height="9" viewBox="0 0 15 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="14.3286" y="5" width="12" height="1" transform="rotate(-180 14.3286 5)" fill="#000C26" stroke="#000C26" stroke-width="0.75" />
                        <rect x="4.53564" y="8.03564" width="5" height="1" transform="rotate(-135 4.53564 8.03564)" fill="#000C26" stroke="#000C26" stroke-width="0.75" />
                        <rect x="5.24268" y="1.67139" width="5" height="1" transform="rotate(135 5.24268 1.67139)" fill="#000C26" stroke="#000C26" stroke-width="0.75" />
                    </svg>
                </a>
                <h1 class="h3"><?php echo get_the_title($hotelPostId); ?></h1>
                <?php the_rating(intval(get_field('hotel_rating', $hotelPostId)), 'ml-sm-1 mr-sm-1'); ?>

                <a href="#rooms" class="button button--orange text-uppercase font--weight--700 text-center ml-auto reserve-btn"><?php echo esc_html_e('Reserve', 'nirvana'); ?></a>

        </div>
    <?php elseif (is_page_template('api-templates/template-api-package-info.php')) : ?>
        
    <?php elseif (is_page_template('api-templates/template-api-package-single.php')) : ?>

    <?php elseif (is_page_template('api-templates/template-api-book-package.php')) : ?>
        <?php 
        $packageAccom = $order->getPackageAccom();
        if($packageAccom): 
            //$hotelPostID = $packageAccom['hotel_post_id'];
            if ( false ) :
                ?>
                <h1 class="h3"><?php //echo get_the_title($hotelPostID); ?></h1>
            <?php endif; ?>
        <?php endif; ?>
    <?php elseif(
        is_page_template('api-templates/template-api-package-extras.php')
        || is_page_template('api-templates/template-api-package-transfers.php')
    ): ?>
        <?php 
        $tourData = $order->getPackagesOrderData();
        $tourTitle = isset($tourData[0]['TourName'])?$tourData[0]['TourName']:get_the_title();
        ?>
        <h1 class="h3"><?php echo $tourTitle; ?></h1>
    <?php elseif (is_page_template('api-templates/template-api-bedbanks-single.php')) : ?>

    <?php else : ?>
        <?php if (is_page_template('api-templates/template-api-search.php') || is_page_template('api-templates/template-api-packages.php') || is_page_template('api-templates/template-api-accommodations.php') || is_page_template('api-templates/template-api-bedbanks.php')) : ?>
           
        <?php else : ?>
            <h1 class="header-booking__main__title"><?php echo $title ? $title : get_the_title(); ?></h1>
        <?php endif; ?>
    <?php endif;
    ?>
    </div>
    <?php
}

add_action( 'order_content_header', 'order_content_header', 10 , 3);


//Airports save to database
class AirportsEntriesTable {
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'airports';
    }

    public function create_table() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
            airport_id INT NOT NULL,
            airport_code VARCHAR(20) NOT NULL,
            name VARCHAR(255) NOT NULL,
            type VARCHAR(50) NOT NULL,
            entry_date DATETIME NOT NULL,
            PRIMARY KEY (airport_id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function insert_entry($data) {
        global $wpdb;
        $wpdb->insert(
            $this->table_name,
            $data,
            array('%d', '%s', '%s', '%s', '%s')
        );
    }

    public function get_airport_name_by_code($airport_code) {
        global $wpdb;

        $query = $wpdb->prepare(
            "SELECT name FROM $this->table_name WHERE airport_code = %s",
            $airport_code
        );

        $name = $wpdb->get_var($query);
        if($name):
            return $name;
        else:
            return $airport_code;
        endif;
    }
}


function api_sync_airports(){
    $api = new NiravanaAPI();
    
    $data = $api->getAirports();

    if (!$data && !is_array($data)) {
        return;
    }

    //print_r( $data);

    $custom_table = new AirportsEntriesTable();
    $custom_table->create_table();

    foreach ($data as $entry) {

        // Prepare entry data
        $entry_data = array(
            'airport_id' => $entry->AirportId,
            'airport_code' => $entry->AirportCode,
            'name' => $entry->Name,
            'type' => $entry->Type,
            'entry_date' => current_time('mysql')
        );

        // Insert the entry into the custom table
        $custom_table->insert_entry($entry_data);
    }
    
}
add_action('api_sync_airports', 'api_sync_airports');
