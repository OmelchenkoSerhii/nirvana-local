<?php 
$order = $args['order'];
$api = $args['api'];

?>

<a class="button button--dark button--arrowback mt-2" onclick="history.back()">Go Back</a>

<?php if(is_user_logged_in()): ?>
    <?php if(get_user_meta(get_current_user_id() , 'api_client_id' , true)): ?>
        <div class="order-payment">
            <form method="POST" action="<?php echo get_field('api_payment_card_page','option'); ?>" class="order-form">
                <h4 class="order-form__title mb-2">Billing information</h4>
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
                    <label for="first-name">Address line 1</label>
                    <input type="text" name="address-1" class="w-100">
                </div>
                <div class="order-form__field mb-1">
                    <label for="first-name">Address line 2</label>
                    <input type="text" name="address-2" class="w-100">
                </div>
                <div class="order-form__row">
                    <div class="order-form__field mb-1">
                        <label for="town">Town</label>
                        <input type="text" name="town" class="w-100" required>
                    </div>
                    <div class="order-form__field mb-1">
                        <label for="postcode">Postcode</label>
                        <input type="text" name="postcode" class="w-100" required>
                    </div>
                </div>
                <div class="order-form__field mb-1">
                    <label for="country">Country</label>
                    <input type="text" name="country" class="w-50" required>
                </div>
                <div class="order-form__field mb-1">
                    <label for="phone">Contact number</label>
                    <input type="text" name="phone" class="w-100" required>
                </div>
                <div class="order-form__field mb-1">
                    <label for="email">Email address</label>
                    <input type="text" name="email" class="w-100" required>
                </div>
                
                <?php 
                $quote = $order->getQuoteFromRes();

                $booking_id = $order->getBookingID();
                $payment_type = $order->getPaymentType();
                $order_type = $order->getOrderType();
                ?>
                <div class="order-form__field mb-1">
                    <h3 class="pb-2">Payment Options</h3>
                    <?php if($booking_id ): ?>
                        <?php 
                        $bookingData = $api->GetBookingData($booking_id);
                        $currency = 'GBP';
                        if($bookingData): //IF BOOKING EXISTS
                            $currency = $bookingData->BookingManagementResult->Booking->CurrencyCode;
                            $amountDue = floatval($bookingData->BookingManagementResult->Booking->TotalDue);
                            $depositAmount = floatval($bookingData->BookingManagementResult->Booking->Deposit);
                            $amountPaid = floatval($bookingData->BookingManagementResult->Booking->TotalPaid);
                            ?>
                            <div class="order-form__radio">
                                <label class="checkbox-style py-1 d-flex align-items-center">
                                    <input type="radio" name="payment_option" value="due_payment" checked>
                                    <span class="checkbox-style__pseudo mr-1"></span>
                                    Pay remaining amount: <?php echo $order->getCurrencySymbolByCode($currency); ?><?php echo ($amountDue); ?>
                                </label>
                            </div>
                            <?php if($depositAmount && $amountPaid==0 && $depositAmount != $amountDue): //IF DEPOSIT NOT PAID?>
                                <div class="order-form__radio">
                                    <label class="checkbox-style py-1 d-flex align-items-center">
                                        <input type="radio" name="payment_option" value="deposit">
                                        <span class="checkbox-style__pseudo mr-1"></span>
                                        Pay deposit: <?php echo $order->getCurrencySymbolByCode($currency); ?><?php echo ($depositAmount); ?>
                                    </label>
                                </div>
                                <label class="checkbox-style py-1 d-flex align-items-center">
                                    <input type="radio" name="payment_option" value="custom_amount">
                                    <span class="checkbox-style__pseudo mr-1"></span>
                                    Pay custom amount: <?php echo $order->getCurrencySymbol(); ?>
                                    <input type="number" class="number" min="<?php echo ($depositAmount); ?>" max="<?php echo ($amountDue); ?>" name="custom_amount" value="<?php echo ($depositAmount); ?>">
                                </label>
                            <?php else: ?>
                                <label class="checkbox-style py-1 d-flex align-items-center">
                                    <input type="radio" name="payment_option" value="custom_amount_due">
                                    <span class="checkbox-style__pseudo mr-1"></span>
                                    Pay custom amount: <?php echo $order->getCurrencySymbol(); ?>
                                    <input type="number" class="number" step="0.01" min="1" max="<?php echo ($amountDue); ?>" name="custom_amount" value="<?php echo intval($amountDue/2); ?>">
                                </label>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: //IF BOOKING DOESNT EXIST ?>
                        <?php 
                        $hideDeposit = false;
                        if(isset($quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay)):
                            if(is_array($quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay)):
                                foreach($quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay as $room):
                                    if(isset($room->Nonrefundable) && $room->Nonrefundable==1):
                                        $hideDeposit = true;
                                    endif;
                                endforeach;
                            else: 
                                if(isset($quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay->Nonrefundable) && $quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay->Nonrefundable==1):
                                    $hideDeposit = true;
                                endif;
                            endif;
                        endif;
                        ?>
                        <div class="order-form__radio">
                            <label class="checkbox-style py-1 d-flex align-items-center">
                                <input type="radio" name="payment_option" value="full_quote" checked>
                                <span class="checkbox-style__pseudo mr-1"></span>
                                Pay full quote: <?php echo $order->getCurrencySymbol(); ?><?php echo number_format($quote->OTA_ViewQuoteResult->QuoteInfo->TotalFare->Amount,2); ?>
                            </label>
                            <?php if(!$hideDeposit && ($quote->OTA_ViewQuoteResult->QuoteInfo->Deposit != 0 && $quote->OTA_ViewQuoteResult->QuoteInfo->Deposit != $quote->OTA_ViewQuoteResult->QuoteInfo->TotalFare->Amount )): ?>
                                <label class="checkbox-style py-1 d-flex align-items-center">
                                    <input type="radio" name="payment_option" value="deposit">
                                    <span class="checkbox-style__pseudo mr-1"></span>
                                    Pay deposit: <?php echo $order->getCurrencySymbol(); ?><?php echo $quote->OTA_ViewQuoteResult->QuoteInfo->Deposit; ?>
                                </label>
                                <label class="checkbox-style py-1 d-flex align-items-center">
                                    <input type="radio" name="payment_option" value="custom_amount">
                                    <span class="checkbox-style__pseudo mr-1"></span>
                                    Pay custom amount: <?php echo $order->getCurrencySymbol(); ?>
                                    <input type="number" class="number" step="0.01" min="<?php echo $quote->OTA_ViewQuoteResult->QuoteInfo->Deposit; ?>" max="<?php echo $quote->OTA_ViewQuoteResult->QuoteInfo->TotalFare->Amount; ?>" name="custom_amount" value="<?php echo $quote->OTA_ViewQuoteResult->QuoteInfo->Deposit; ?>">
                                </label>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="package__buttons-bar d-flex" style="background: none;">
                    <?php if(!$order->checkIfCreateFromBooking()): ?>
                        <div class="package__buttons-bar__item">
                            <a  onclick="history.back()" class="button button--dark-white">Go Back</a>
                        </div>
                    <?php endif; ?>
                    <div class="package__buttons-bar__item">
                        <button class="submit button button--orange" type="submit">Submit and pay</button>
                    </div>
                </div> 
            </form>
        </div>
    <?php else: //IF CLIENT ID IS EMPTY; ?>
        <div class="order-account">
            <h3 class="mb-4" >Error: Client ID not linked correctly. Please <a style="text-decoration: underline; color: #f47920;" href="<?php the_field( 'api_contact_page', 'option' ); ?>">contact us</a> for assistance. </h3>
        </div>
    <?php endif; ?>
<?php else: ?>

    <div class="order-account">
        <div class="py-2">
            <h3 class="mb-1"><?php _e('Returning customer'); ?></h3>
            <?php echo do_shortcode('[theme-my-login action="login" show_title=0 redirect_to="'.get_field('api_payment_page','option').'"]'); ?>
        </div>

        <div class="py-2">
            <h3 class="mb-1"><?php _e('New customer'); ?></h3>
            <?php echo do_shortcode(get_field('payment_registration_form','option')); ?>
        </div>
    </div>

<?php endif; ?>
