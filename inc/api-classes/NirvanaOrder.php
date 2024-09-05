<?php

class NiravanaOrder
{
    private $orderObject;

    private $orderNumber;
    private $orderPostID;

    private $api;

    private $orderEventPostID;
    private $orderEventID;
    private $orderEventCode;

    private $orderType;

    private $resort;
    private $resort_badbanks;
    private $region;
    private $country;
    private $dateCheckIn;
    private $dateCheckOut;
    private $currency;
    private $rooms;
    private $passengers;
    private $adultsQtt;
    private $childsQtt;
    private $infantsQtt;

    private $price;
    private $quoteID;
    private $bookingID;
    private $clientID;
    private $transactionID;
    private $transactionIDRest;
    private $paymentType;

    private $orderPeople;

    public function __construct($order_token)
    {
        $this->orderNumber = $order_token;
        $this->api = new NiravanaAPI($order_token);
        $this->orderExists();
    }

    public function orderExists()
    {
        $orders = get_posts(
            array(
                'numberposts' => 1,
                'post_type' => 'booking',
                'meta_key' => 'order_token',
                'meta_value' => $this->orderNumber,
            )
        );
        if ($orders) {
            $this->orderObject = $orders[0];
            $this->orderPostID = $this->orderObject->ID;
            $this->setupOrderData();
            return true;
        }

        return false;
    }

    public function setupOrderData()
    {
        $this->orderEventPostID = get_post_meta($this->orderPostID, 'event_id', true);
        $this->orderEventID = get_field('event_id', $this->orderEventPostID);
        $this->orderEventCode = get_field('event_code', $this->orderEventPostID);
        $this->orderType = get_post_meta($this->orderPostID, 'booking_type', true);
        $this->resort = get_field('event_resort', $this->orderEventPostID);
        $this->resort_badbanks = get_field('event_badbanks_resort', $this->orderEventPostID);
        $this->region = get_field('event_api_region', $this->orderEventPostID);
        $this->country = get_field('event_api_country', $this->orderEventPostID);
        $this->dateCheckIn = DateTime::createFromFormat('j F Y', get_post_meta($this->orderPostID, 'date-checkin', true));
        $this->dateCheckOut = DateTime::createFromFormat('j F Y', get_post_meta($this->orderPostID, 'date-checkout', true));
        $this->currency = get_post_meta($this->orderPostID, 'currency', true);
        $this->rooms = get_post_meta($this->orderPostID, 'rooms', true);
        $this->passengers = get_post_meta($this->orderPostID, 'passengers', true);
        $this->adultsQtt = 0;
        $this->childsQtt = 0;
        $this->infantsQtt = 0;

        $this->resort_badbanks =  $this->resort_badbanks? $this->resort_badbanks : $this->resort;

        if ($this->passengers):
            foreach ($this->passengers as $passenger):
                if ($passenger['Age'] > 11) {
                    $this->adultsQtt += 1;
                }

                if ($passenger['Age'] <= 11 && $passenger['Age'] >= 2) {
                    $this->childsQtt += 1;
                }

                if ($passenger['Age'] < 2) {
                    $this->infantsQtt += 1;
                }

            endforeach;
        endif;

        $this->price = get_post_meta($this->orderPostID, 'price', true);
        $this->quoteID = get_post_meta($this->orderPostID, 'quote_id', true);
        $this->clientID = get_post_meta($this->orderPostID, 'client_id', true);
        $this->transactionID = get_post_meta($this->orderPostID, 'transaction_id', true);
        $this->transactionIDRest = get_post_meta($this->orderPostID, 'rest_transaction_id', true);
        $this->paymentType = get_post_meta($this->orderPostID, 'payment_type', true);

        $this->bookingID = get_post_meta($this->orderPostID, 'booking_id', true);
    }

    public function outputOrderData()
    {
        echo $this->orderNumber . '<br>';
        echo $this->orderEventPostID . '<br>';
        echo $this->orderEventID . '<br>';
        echo $this->orderEventCode . '<br>';
        echo $this->resort . '<br>';
        echo $this->dateCheckIn->format('Y-m-d') . '<br>';
        echo $this->dateCheckOut->format('Y-m-d') . '<br>';
        echo $this->currency . '<br>';
        print_r($this->passengers) . '<br>';
    }

    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    public function getOrderID()
    {
        return $this->orderPostID;
    }

    public function getQuoteID()
    {
        return $this->quoteID;
    }

    public function getBookingID()
    {
        return $this->bookingID;
    }

    /**
     *
     * Create order
     *
     */
    public function createOrderPost($event_id, $date_checkin, $date_checkout, $currency, $rooms, $clientID = '')
    {

        $orders = get_posts(
            array(
                'numberposts' => 1,
                'post_type' => 'booking',
                'meta_key' => 'order_token',
                'meta_value' => $this->orderNumber,
            )
        );

        $passengers = array();

        foreach ($rooms as $roomIndex => $room):
            $adults_qtt = $room['adults_qtt'];
            $childs_qtt = $room['childs_qtt'];
            $infants_qtt = $room['infants_qtt'];
            if ($adults_qtt != 0):
                for ($i = 0; $i < $adults_qtt; $i++):
                    $passenger = array(
                        'Age' => 25,
                        'AgeCode' => 10,
                        'Name' => 'TBD',
                        'Surname' => 'TBD',
                        'full-name' => 'TBD',
                        'room' => $roomIndex,
                    );
                    array_push($passengers, $passenger);
                endfor;
            endif;
            $child_ages_index = 0;
            if ($childs_qtt != 0):
                for ($i = 0; $i < $childs_qtt; $i++):
                    $passenger = array(
                        'Age' => $room['ages'][$i],
                        'AgeCode' => 8,
                        'Name' => 'TBD',
                        'Surname' => 'TBD',
                        'full-name' => 'TBD',
                        'room' => $roomIndex,
                    );
                    $child_ages_index++;
                    array_push($passengers, $passenger);
                endfor;
            endif;
            if ($infants_qtt != 0):
                for ($i = 0; $i < $infants_qtt; $i++):
                    $passenger = array(
                        'Age' => $room['infant_ages'][$i],
                        'AgeCode' => 7,
                        'Name' => 'TBD',
                        'Surname' => 'TBD',
                        'full-name' => 'TBD',
                        'room' => $roomIndex,
                    );
                    array_push($passengers, $passenger);
                endfor;
            endif;
        endforeach;

        if ($orders): //if booking with provided token exists
            $order = $orders[0];
            $this->orderPostID = $order->ID;
            $post = array(
                'ID' => $order->ID,
                'post_title' => $this->orderNumber,
                'post_status' => 'publish',
                'post_type' => 'booking',
                'meta_input' => array(
                    'order_token' => $this->orderNumber,
                    'event_id' => $event_id,
                    'date-checkin' => $date_checkin,
                    'date-checkout' => $date_checkout,
                    'currency' => $currency,
                    'adults' => $adults_qtt,
                    'childs' => $childs_qtt,
                    'infants' => $infants_qtt,
                    'rooms' => $rooms,
                    'passengers' => $passengers,
                    'client_id' => $clientID,
                    'payment_type' => 'deposit',
                    'booking_type' => 'tour',
                    'created_from_booking' => false,
                ),
            );
            wp_update_post($post);
            $this->setupOrderData();
        else: //if booking with provided token doesnt exist
            $order_data = array(
                'post_title' => $this->orderNumber,
                'post_status' => 'publish',
                'post_type' => 'booking',
                'meta_input' => array(
                    'order_token' => $this->orderNumber,
                    'event_id' => $event_id,
                    'date-checkin' => $date_checkin,
                    'date-checkout' => $date_checkout,
                    'currency' => $currency,
                    'rooms' => $rooms,
                    'adults' => $adults_qtt,
                    'childs' => $childs_qtt,
                    'infants' => $infants_qtt,
                    'passengers' => $passengers,
                    'client_id' => $clientID,
                    'payment_type' => 'deposit',
                    'booking_type' => 'tour',
                    'created_from_booking' => false,
                ),
            );
            $this->orderPostID = wp_insert_post($order_data);
            $this->setupOrderData();
        endif;
    }

    /**
     *
     * Create order
     *
     */
    public function createOrderPostBooking($bookingData)
    {
        $orderEventCode = $bookingData->BookingManagementResult->Booking->ProductCode;
        $orderEventPostID = $this->api->getEventIDbyCode($orderEventCode);

        $checkin = $bookingData->BookingManagementResult->Booking->DepartureDate;
        $checkout = $bookingData->BookingManagementResult->Booking->ReturnDate;
        $checkinFormat = strtotime($checkin);
        $checkoutFormat = strtotime($checkout);

        $currency = $bookingData->BookingManagementResult->Booking->CurrencyCode;
        $price = $bookingData->BookingManagementResult->Booking->TotalPrice;
        $quoteID = $bookingData->BookingManagementResult->Booking->QuoteNumber;
        $bookingID = $bookingData->BookingManagementResult->Booking->BookingReference;
        $clientID = $bookingData->BookingManagementResult->Booking->DirectClient->ClientReference;

        if ($bookingData):
            $orders = get_posts(
                array(
                    'numberposts' => 1,
                    'post_type' => 'booking',
                    'meta_key' => 'order_token',
                    'meta_value' => $this->orderNumber,
                )
            );

            if ($orders): //if booking with provided token exists
                $order = $orders[0];
                $this->orderPostID = $order->ID;
                $post = array(
                    'ID' => $order->ID,
                    'post_title' => $this->orderNumber,
                    'post_status' => 'publish',
                    'post_type' => 'booking',
                    'meta_input' => array(
                        'order_token' => $this->orderNumber,
                        'event_id' => $orderEventPostID,
                        'event_code' => $orderEventCode,
                        'date-checkin' => date('j F Y', $checkinFormat),
                        'date-checkout' => date('j F Y', $checkoutFormat),
                        'currency' => $currency,
                        'price' => $price,
                        'quote_id' => $quoteID,
                        'booking_id' => $bookingID,
                        'client_id' => $clientID,
                        'created_from_booking' => true,
                    ),
                );
                wp_update_post($post);
                $this->setupOrderData();
            else: //if booking with provided token doesnt exist
                $order_data = array(
                    'post_title' => $this->orderNumber,
                    'post_status' => 'publish',
                    'post_type' => 'booking',
                    'meta_input' => array(
                        'order_token' => $this->orderNumber,
                        'event_id' => $orderEventPostID,
                        'event_code' => $orderEventCode,
                        'date-checkin' => date('j F Y', $checkinFormat),
                        'date-checkout' => date('j F Y', $checkoutFormat),
                        'currency' => $currency,
                        'price' => $price,
                        'quote_id' => $quoteID,
                        'booking_id' => $bookingID,
                        'client_id' => $clientID,
                        'created_from_booking' => true,
                    ),
                );
                $this->orderPostID = wp_insert_post($order_data);
                $this->setupOrderData();
            endif;
        endif;
    }

    public function getEventPostID()
    {
        return $this->orderEventPostID;
    }

    public function setOrderType($type)
    {

        $this->orderType = $type;
        if (metadata_exists('post', $this->orderPostID, 'booking_type')):
            update_post_meta($this->orderPostID, 'booking_type', $type);
        else:
            add_post_meta($this->orderPostID, 'booking_type', $type);
        endif;

    }

    public function getOrderType()
    {

        return $this->orderType;

    }

    public function createQuote($clientID = '')
    {

        $result = $this->api->CreateQuote($this->orderEventCode, $this->currency, $this->passengers, $this->dateCheckIn->format('Y-m-d'), $clientID, $this->orderType);

        if ($result):
            if (metadata_exists('post', $this->orderPostID, 'quote_id')):
                update_post_meta($this->orderPostID, 'quote_id', $result->OTA_CreateQuoteResult->TransactionIdentifier);
            else:
                add_post_meta($this->orderPostID, 'quote_id', $result->OTA_CreateQuoteResult->TransactionIdentifier);
            endif;
            $passengers = get_post_meta($this->orderPostID, 'passengers', true);
            if ($passengers):
                $loopCounter = 0;
                if (is_array($result->OTA_CreateQuoteResult->TravelerInfos->TravelerInfo)):
                    foreach ($result->OTA_CreateQuoteResult->TravelerInfos->TravelerInfo as $person):
                        $this->passengers[$loopCounter]['ref_number'] = $person->TravelerRefNumber->RPH;
                        $loopCounter++;
                    endforeach;
                else:
                    $this->passengers[$loopCounter]['ref_number'] = $result->OTA_CreateQuoteResult->TravelerInfos->TravelerInfo->TravelerRefNumber->RPH;
                endif;
            endif;
            update_post_meta($this->orderPostID, 'passengers', $this->passengers);
        endif;
        return $result;
    }

    public function updateQuote($clientID = '')
    {
        $quote = $this->getQuoteFromRes();
        if ($quote):
            $passengers = get_post_meta($this->orderPostID, 'passengers', true);
            if ($passengers):
                $loopCounter = 0;
                if (is_array($quote->OTA_ViewQuoteResult->TravelerInfos->TravelerInfo)):
                    foreach ($quote->OTA_ViewQuoteResult->TravelerInfos->TravelerInfo as $person):
                        if (isset($this->passengers[$loopCounter])):
                            $this->passengers[$loopCounter]['ref_number'] = $person->TravelerRefNumber->RPH;
                        endif;
                        $loopCounter++;
                    endforeach;
                else:
                    $this->passengers[$loopCounter]['ref_number'] = $quote->OTA_ViewQuoteResult->TravelerInfos->TravelerInfo->TravelerRefNumber->RPH;
                endif;
            endif;
            update_post_meta($this->orderPostID, 'passengers', $this->passengers);
        endif;
        $result = $this->api->UpdateQuoteData($this->quoteID, $this->orderEventCode, $this->currency, $this->passengers, $this->dateCheckIn->format('Y-m-d'), $clientID, $this->orderType);

        if ($result):
            $passengers = get_post_meta($this->orderPostID, 'passengers', true);
            if ($passengers):
                $loopCounter = 0;
                if (is_array($result->OTA_CreateQuoteResult->TravelerInfos->TravelerInfo)):
                    foreach ($result->OTA_CreateQuoteResult->TravelerInfos->TravelerInfo as $person):
                        if ($this->passengers[$loopCounter]):
                            $this->passengers[$loopCounter]['ref_number'] = $person->TravelerRefNumber->RPH;
                        endif;
                        $loopCounter++;
                    endforeach;
                else:
                    $this->passengers[$loopCounter]['ref_number'] = $result->OTA_CreateQuoteResult->TravelerInfos->TravelerInfo->TravelerRefNumber->RPH;
                endif;
            endif;
            update_post_meta($this->orderPostID, 'passengers', $this->passengers);
        endif;
        return $result;
    }

    /**
     *
     * Update order Post
     *
     */
    public function UpdateOrderPost($data)
    {

        update_post_meta($this->orderPostID, 'date-checkin', $data['date-checkin']);
        update_post_meta($this->orderPostID, 'date-checkout', $data['date-checkout']);
        update_post_meta($this->orderPostID, 'rooms', $data['rooms']);

        $passengers = array();

        foreach ($data['rooms'] as $roomIndex => $room):
            $adults_qtt = $room['adults_qtt'];
            $childs_qtt = $room['childs_qtt'];
            $infants_qtt = $room['infants_qtt'];
            if ($adults_qtt != 0):
                for ($i = 0; $i < $adults_qtt; $i++):
                    $passenger = array(
                        'Age' => 25,
                        'AgeCode' => 10,
                        'Name' => 'TBD',
                        'Surname' => 'TBD',
                        'full-name' => 'TBD',
                        'room' => $roomIndex,
                    );
                    array_push($passengers, $passenger);
                endfor;
            endif;
            if ($childs_qtt != 0):
                for ($i = 0; $i < $childs_qtt; $i++):
                    $passenger = array(
                        'Age' => $room['ages'][$i],
                        'AgeCode' => 8,
                        'Name' => 'TBD',
                        'Surname' => 'TBD',
                        'full-name' => 'TBD',
                        'room' => $roomIndex,
                    );
                    array_push($passengers, $passenger);
                endfor;
            endif;
            if ($infants_qtt != 0):
                for ($i = 0; $i < $infants_qtt; $i++):
                    $passenger = array(
                        'Age' => $room['infant_ages'][$i],
                        'AgeCode' => 7,
                        'Name' => 'TBD',
                        'Surname' => 'TBD',
                        'full-name' => 'TBD',
                        'room' => $roomIndex,
                    );
                    array_push($passengers, $passenger);
                endfor;
            endif;
        endforeach;
        update_post_meta($this->orderPostID, 'passengers', $passengers);

        $this->passengers = $passengers;
        $this->adultsQtt = 0;
        $this->childsQtt = 0;
        $this->infantsQtt = 0;
        $this->rooms = $data['rooms'];
        $this->dateCheckIn = DateTime::createFromFormat('j F Y', $data['date-checkin']);
        $this->dateCheckOut = DateTime::createFromFormat('j F Y', $data['date-checkout']);

        if (isset($data['currency'])):
            update_post_meta($this->orderPostID, 'currency', $data['currency']);
            $this->currency = $data['currency'];
        endif;

        foreach ($this->passengers as $passenger):
            if ($passenger['Age'] > 11) {
                $this->adultsQtt += 1;
            }

            if ($passenger['Age'] <= 11 && $passenger['Age'] >= 2) {
                $this->childsQtt += 1;
            }

            if ($passenger['Age'] < 2) {
                $this->infantsQtt += 1;
            }

        endforeach;
        $this->setupOrderData();
    }

    /**
     * View Quote
     */
    public function viewQuote()
    {
        return $this->api->ViewQuote($this->orderEventCode, $this->quoteID);
    }

    public function searchAccoms()
    {
        return $this->api->GetAccomData($this->orderNumber, $this->orderEventCode, $this->getAccomPassengersArray(), $this->currency, $this->resort, $this->region, $this->country, $this->dateCheckIn->format('Y-m-d') . 'T00:00:00', $this->dateCheckOut->format('Y-m-d') . 'T00:00:00');
    }

    public function searchBedBanks()
    {
        return $this->api->GetBedBanksData($this->orderNumber, $this->orderEventCode, $this->getAccomPassengersArray(), $this->currency, $this->resort_badbanks , $this->region, $this->country, $this->dateCheckIn->format('Y-m-d') . 'T00:00:00', $this->dateCheckOut->format('Y-m-d') . 'T00:00:00');
    }

    public function searchExtras()
    {
        return $this->api->GetExtrasData($this->orderEventCode, $this->getExtraPassengersArray(), $this->currency, $this->dateCheckIn->format('Y-m-d'), $this->dateCheckOut->format('Y-m-d'));
    }

    public function searchPackageExtras()
    {
        $packageData = $this->getPackagesOrderData();
        //print_r($packageData);
        $packageDataResult = $this->getPackageData($packageData[0]['TourID'], $packageData[0]['TourPriceID'], $packageData[0]['LOFI']);
        return $packageDataResult->TourComponents->ExtrasItems->ExtrasComponent;
    }

    public function searchPackageTransfers()
    {
        $packageData = $this->getPackagesOrderData();
        //print_r($packageData);
        $packageDataResult = $this->getPackageData($packageData[0]['TourID'], $packageData[0]['TourPriceID'], $packageData[0]['LOFI']);
        return $packageDataResult->TourComponents->TransferItems->TransferComponent;
    }

    public function searchPackages()
    {
        return $this->api->GetPackagesData($this->orderNumber, $this->orderEventCode, $this->getPackagePassengersArray(), $this->getPackageRoomsArray(), $this->currency, $this->resort, $this->region, $this->country, $this->dateCheckIn->format('Y-m-d') . 'T00:00:00', $this->dateCheckOut->format('Y-m-d') . 'T00:00:00');
    }

    public function getPackageData($tourCode, $tourPriceID, $lofi)
    {
        return $this->api->GetPackageData($this->orderEventCode, $this->currency, $tourCode, $tourPriceID, $lofi);
    }
    public function getMultiplePackagesData($tourCode, $tourPriceID, $lofi)
    {
        return $this->api->GetMultiplePackagesData($this->orderEventCode, $this->currency, $tourCode, $tourPriceID, $lofi);
    }
    public function getReservedMultiplePackagesData()
    {
        $orderPackages = $this->getPackagesOrderData();
        $tourCode = false;
        $lofi = false;
        $tourPriceID = array();
        foreach ($orderPackages as $package):
            $tourCode = $package['TourID'];
            $lofi = $package['LOFI'];
            $tourPriceID[] = $package['TourPriceDetailId'];
        endforeach;
        return $this->api->GetMultiplePackagesData($this->orderEventCode, $this->currency, $tourCode, $tourPriceID, $lofi);
    }

    public function searchFlights()
    {
        // TODO: ALEX
        return null;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getCurrencySymbol()
    {
        $code = strtoupper($this->currency);
        switch ($code):
            case 'GBP':
                return '£';
                break;
            case 'USD':
                return '$';
                break;
            case 'EUR':
                return '€';
                break;
            case 'AUD':
                return 'A$';
                break;
            case 'CAD':
                return 'C$';
                break;
            case 'DKK':
                return 'kr';
                break;
            case 'CHF':
                return 'Fr';
                break;
            case 'NZD':
                return 'NZ$';
                break;
            case 'SEK':
                return 'kr';
                break;
            default:

        endswitch;
    }

    public function getCurrencySymbolByCode($code = 'GPB')
    {
        switch ($code):
            case 'GBP':
                return '£';
                break;
            case 'USD':
                return '$';
                break;
            case 'EUR':
                return '€';
                break;
            case 'AUD':
                return 'A$';
                break;
            case 'CAD':
                return 'C$';
                break;
            case 'DKK':
                return 'kr';
                break;
            case 'CHF':
                return 'Fr';
                break;
            case 'NZD':
                return 'NZ$';
                break;
            case 'SEK':
                return 'kr';
                break;
            default:

        endswitch;
    }

    public function getRatePlanNameNyCode($code)
    {
        switch ($code):
            case 'BB':
                return 'Bed and Breakfast';
                break;
            case 'RO':
                return 'Room Only';
                break;
            case 'HB':
                return 'Half Board';
                break;
            case 'FB':
                return 'Full Board';
                break;
            default:
                return $code;
        endswitch;
    }

    public function getNightsQtt()
    {
        return $this->dateCheckIn->diff($this->dateCheckOut)->format('%a');
    }

    public function getPeopleQtt()
    {
        if ($this->adultsQtt || $this->childsQtt || $this->infantsQtt):
            $adults_tag = $this->adultsQtt <= 1 ? 'adult' : 'adults';
            $children_tag = $this->childsQtt == 1 ? 'child' : 'children';
            $infants_tag = $this->infantsQtt == 1 ? 'infant' : 'infants';
            return $this->adultsQtt . ' ' . $adults_tag . ', ' . $this->childsQtt . ' ' . $children_tag . ', ' . $this->infantsQtt . ' ' . $infants_tag;
        else:
            return '';
        endif;
    }

    public function getCustomPeopleQtt($adults, $childs)
    {
        if ($adults || $childs):
            $adults_tag = $adults <= 1 ? 'adult' : 'adults';
            $children_tag = $childs == 1 ? 'child' : 'children';
            return $adults . ' ' . $adults_tag . ', ' . $childs . ' ' . $children_tag;
        else:
            return '';
        endif;
    }

    public function getTotalPeopleQtt()
    {
        return $this->adultsQtt + $this->childsQtt + $this->infantsQtt;
    }

    public function getTravellers()
    {
        return $this->passengers;
    }
    public function getPassengersArray()
    {
        /**
         * 10 – Adult o 8 – Child o 7 – Infant
         */
        $passengersArray = array();
        foreach ($this->passengers as $passenger) {
            $code = 10;
            if ($passenger['Age'] < 12):
                $code = 8;
            endif;
            if ($passenger['Age'] < 2):
                $code = 7;
            endif;
            array_push($passengersArray, array(
                'AgeQualifyingCode' => $code,
                'Age' => $passenger['Age'],
                'Count' => 1,
            )
            );
        }

        return $passengersArray;
    }
    public function getAccomPassengersArray()
    {
        /**
         * 10 – Adult o 8 – Child o 7 – Infant
         */
        $passengersArray = array();
        foreach ($this->rooms as $room):
            $roomPassengers = array();
            if ($room['adults_qtt'] != 0):
                for ($i = 0; $i < $room['adults_qtt']; $i++):
                    $code = 10;
                    array_push($roomPassengers, array(
                        'AgeQualifyingCode' => $code,
                        'Age' => 25,
                        'Count' => 1,
                    )
                    );
                endfor;
            endif;
            if ($room['childs_qtt'] != 0):
                for ($i = 0; $i < $room['childs_qtt']; $i++):
                    $code = 8;
                    array_push($roomPassengers, array(
                        'AgeQualifyingCode' => $code,
                        'Age' => $room['ages'][$i],
                        'Count' => 1,
                    )
                    );
                endfor;
            endif;
            if ($room['infants_qtt'] != 0):
                for ($i = 0; $i < $room['infants_qtt']; $i++):
                    $code = 7;
                    array_push($roomPassengers, array(
                        'AgeQualifyingCode' => $code,
                        'Age' => $room['infant_ages'][$i],
                        'Count' => 1,
                    )
                    );
                endfor;
            endif;
            array_push($passengersArray, $roomPassengers);
        endforeach;

        return $passengersArray;
    }

    public function getPackagePassengersArray()
    {
        /**
         * 10 – Adult o 8 – Child o 7 – Infant
         */
        $passengersArray = array();
        foreach ($this->rooms as $room):
            $roomPassengers = array();
            if ($room['adults_qtt'] != 0):
                for ($i = 0; $i < $room['adults_qtt']; $i++):
                    $code = 10;
                    array_push($roomPassengers, array(
                        'Code' => $code,
                        'Age' => 25,
                        'Quantity' => 1,
                    )
                    );
                endfor;
            endif;
            if ($room['childs_qtt'] != 0):
                for ($i = 0; $i < $room['childs_qtt']; $i++):
                    $code = 8;
                    array_push($roomPassengers, array(
                        'Code' => $code,
                        'Age' => 25,
                        'Count' => 1,
                    )
                    );
                endfor;
            endif;
            if ($room['infants_qtt'] != 0):
                for ($i = 0; $i < $room['infants_qtt']; $i++):
                    $code = 7;
                    array_push($roomPassengers, array(
                        'Code' => $code,
                        'Age' => 25,
                        'Count' => 1,
                    )
                    );
                endfor;
            endif;
            array_push($passengersArray, $roomPassengers);
        endforeach;

        return $passengersArray;
    }

    public function getPackageRoomsArray()
    {
        /**
         * 10 – Adult o 8 – Child o 7 – Infant
         */
        $roomsArray = array();
        foreach ($this->rooms as $room):
            $roomsArray[] = array(
                '_' => '',
                'NoOfRooms' => '1',
                'NoOfOccupants' => $room['childs_qtt'] + $room['adults_qtt'] + $room['infants_qtt'],
            );
        endforeach;

        return $roomsArray;
    }

    public function getExtraPassengersArray()
    {
        /**
         * 10 – Adult o 8 – Child o 7 – Infant
         */
        $passengersArray = array();
        foreach ($this->passengers as $passenger) {
            $code = 10;
            if ($passenger['Age'] < 12):
                $code = 8;
            endif;
            if ($passenger['Age'] < 2):
                $code = 7;
            endif;
            array_push($passengersArray, array(
                'Code' => $code,
                'Age' => $passenger['Age'],
                'Quantity' => 1,
            )
            );
        }

        return $passengersArray;
    }

    public function getPassengerTypeQuantity()
    {
        /**
         * 10 – Adult o 8 – Child o 7 – Infant
         */

        $passengersArray = array();
        foreach ($this->passengers as $passenger) {
            $code = 10;
            if ($passenger['Age'] < 12):
                $code = 8;
            endif;
            if ($passenger['Age'] < 2):
                $code = 7;
            endif;
            array_push($passengersArray, array(
                'Code' => $code,
                'Age' => $passenger['Age'],
                'Quantity' => 1,
            )
            );
        }

        return $passengersArray;
    }

    public function getEventCode()
    {
        return $this->orderEventCode;
    }

    public function getCheckoutDate()
    {
        return $this->dateCheckOut->format('j F Y');
    }

    public function getCheckinDate()
    {
        return $this->dateCheckIn->format('j F Y');
    }

    public function getCheckoutDateFormat($format)
    {
        return $this->dateCheckOut->format($format);
    }

    public function getCheckinDateFormat($format)
    {
        return $this->dateCheckIn->format($format);
    }

    public function getCheckinDateVal()
    {
        return $this->dateCheckIn;
    }
    public function getCheckoutDateVal()
    {
        return $this->dateCheckOut;
    }

    public function getDateRange()
    {
        if ($this->dateCheckIn && $this->dateCheckOut):
            $packageData = $this->getPackagesOrderData();
            if ($this->orderType == 'tour' && $packageData) {
                return $packageData[0]['TourStartDate'] . ' - ' . $packageData[0]['TourEndDate'];
            } else {
                return $this->dateCheckIn->format('F j, Y') . ' - ' . $this->dateCheckOut->format('F j, Y');
            }
        else:
            return '';
        endif;
    }

    public function getAdultsQtt()
    {
        return $this->adultsQtt;
    }
    public function getChildQtt()
    {
        return $this->childsQtt;
    }
    public function getInfantsQtt()
    {
        return $this->infantsQtt;
    }

    public function getOrderPrice()
    {
        $price = 0;
        $accoms = $this->getReservedAccommodations();
        $extras = $this->getReservedExtras();
        if ($accoms) {
            foreach ($accoms as $item) {
                $price += $item['price'];
            }
        }
        if ($extras) {
            foreach ($extras as $item) {
                $price += $item['price'];
            }
        }
        return $price;
    }

    public function addAccommodation($data)
    {
        $rooms = $data['hotel_rooms'];
        $rooms_ids = $data['hotel_rooms_id'];
        $titles = $data['hotel_rooms_title'];
        $prices = $data['hotel_rooms_price'];
        $board = $data['hotel_rooms_board'];
        $ref = $data['hotel_rooms_ref'];
        $currencies = $data['hotel_rooms_currency'];
        $locators = $data['hotel_rooms_locator'];
        $combos = $data['hotel_rooms_combo'];
        $hotelID = $data['hotelid'];
        $hotel_name = $data['hotel_name'];
        $accomData = get_post_meta($this->orderPostID, 'accommodation', true);
        $accomDataExist = metadata_exists('post', $this->orderPostID, 'accommodation');

        //used to prevent submitting duplicate data
        $submissionID = $data['submission_key'];
        update_post_meta($this->orderPostID, 'rooms_submission_id', $submissionID);

        $roomsArray = array();
        foreach ($rooms as $room_id => $room):
            for ($i = 0; $i < $room; $i++):
                $roomItem = array(
                    'hotel_id' => $hotelID,
                    'hotel_name' => $hotel_name,
                    'room_id' => $rooms_ids[$room_id],
                    'room_adults' => $this->rooms[$combos[$room_id]]['adults_qtt'],
                    'room_children' => $this->rooms[$combos[$room_id]]['childs_qtt'],
                    'room_infants' => $this->rooms[$combos[$room_id]]['infants_qtt'],
                    'room_children_ages' => $this->rooms[$combos[$room_id]]['ages'],
                    'room_infants_ages' => $this->rooms[$combos[$room_id]]['infant_ages'],
                    'room_locator' => $locators[$room_id],
                    'room_price' => $prices[$room_id],
                    'room_board' => $board[$room_id],
                    'room_ref' => $ref[$room_id],
                    'room_currency' => $currencies[$room_id],
                    'room_title' => $titles[$room_id],
                );
                array_push($roomsArray, $roomItem);
            endfor;
        endforeach;

        if ($accomDataExist):
            $removeIndexes = array();
            foreach ($roomsArray as $selectedRoom):
                $roomID = $selectedRoom['room_id'];
                foreach ($accomData as $index => $item):
                    if ($item['room_id'] == $roomID):
                        array_push($removeIndexes, $index);
                    endif;
                endforeach;
            endforeach;
            if (sizeof($removeIndexes) != 0):
                $removeOffset = 0;
                foreach ($removeIndexes as $removeItem):
                    array_splice($accomData, $removeItem - $removeOffset, 1);
                    $removeOffset++;
                endforeach;
            endif;
            array_push($accomData, ...$roomsArray);


            update_post_meta($this->orderPostID, 'accommodation', $accomData);
        else:
            add_post_meta($this->orderPostID, 'accommodation', $roomsArray);
        endif;
    }

    public function removeAccommodation($data)
    {
        if ($data):
            $accomData = get_post_meta($this->orderPostID, 'accommodation', true);
            $accomDataReserved = get_post_meta($this->orderPostID, 'reservedAccommodation', true);

            if ($accomData && $accomDataReserved):
                $response = $this->api->removeSingleAccom($this->orderNumber, $this->quoteID, $this->orderEventCode, $data, $this->currency);
                if ($response):
                    array_splice($accomData, $data['index'], 1);
                    array_splice($accomDataReserved, $data['index'], 1);
                    update_post_meta($this->orderPostID, 'accommodation', $accomData);
                    update_post_meta($this->orderPostID, 'reservedAccommodation', $accomDataReserved);
                    return true;
                endif;
            else:
                return false;
            endif;
        endif;
        return false;
    }

    public function removeAccommodationRoom($data)
    {
        if ($data):
            $accomDataExist = metadata_exists('post', $this->orderPostID, 'accommodation');
            $accomData = get_post_meta($this->orderPostID, 'accommodation', true);

            if ($accomData):
                array_splice($accomData, $data['index'], 1);
                update_post_meta($this->orderPostID, 'accommodation', $accomData);
                return true;
            else:
                return false;
            endif;
        endif;
        return false;
    }

    public function getRoomsSubmissionID()
    {
        return get_post_meta($this->orderPostID, 'rooms_submission_id', true);
    }

    public function getReserveAccom()
    {
        return get_post_meta($this->orderPostID, 'reservedAccommodation', true);
    }

    public function reserveAccom($data)
    {
        $travellers = $this->passengers;
        $result = "ERROR";
        if ($data):
            $accomData = metadata_exists('post', $this->orderPostID, 'reservedAccommodation');
            if ($accomData):
                $result = update_post_meta($this->orderPostID, 'reservedAccommodation', $data);
            else:
                $result = add_post_meta($this->orderPostID, 'reservedAccommodation', $data);
            endif;

            $roomCounter = 0;

            $travellersIndex = 0;

            foreach ($travellers as $traveller):
                $age = intval($traveller['Age']);
                $age_code = 'ADT';
                $ref_number = $traveller['ref_number'];
                if ($age < 12):
                    $age_code = 'CHD';
                endif;
                if ($age < 2):
                    $age_code = 'INF';
                endif;
                $roomIndex = 0;
                foreach ($data as $room):
                    $passengerIndex = 0;
                    $passengerAdded = false;
                    foreach ($room['passengers'] as $passenger):
                        $actualAge = intval($passenger['age']);
                        $actualTitle = $passenger['title'];
                        $actualName = $passenger['name'];
                        $actualSurname = $passenger['surname'];

                        if (is_celtic_active($this->orderEventPostID)) {
                            $actualCelticCode = $passenger['celtic_code'];
                        }

                        $actualAgeCode = 'ADT';
                        if ($actualAge < 12):
                            $actualAgeCode = 'CHD';
                        endif;
                        if ($actualAge < 2):
                            $actualAgeCode = 'INF';
                        endif;

                        if ($actualAgeCode == $age_code && !isset($data[$roomIndex]['passengers'][$passengerIndex]['ref'])):
                            $data[$roomIndex]['passengers'][$passengerIndex]['ref'] = $ref_number;
                            $travellers[$travellersIndex]['Age'] = $actualAge;
                            $travellers[$travellersIndex]['Title'] = $actualTitle;
                            $travellers[$travellersIndex]['Name'] = $actualName;
                            $travellers[$travellersIndex]['Surname'] = $actualSurname;
                            $travellers[$travellersIndex]['full-name'] = $actualName . ' ' . $actualSurname;

                            if (is_celtic_active($this->orderEventPostID)) {
                                $travellers[$travellersIndex]['celtic_code'] = $actualCelticCode;
                            }

                            $passengerAdded = true;
                            break;
                        endif;
                        $passengerIndex++;
                    endforeach;
                    $roomIndex++;
                    if ($passengerAdded) {
                        break;
                    }

                endforeach;
                $travellersIndex++;
            endforeach;

            $resultUpdaingTravellers = update_post_meta($this->orderPostID, 'passengers', $travellers);

            $removeHotelsData = array();

            $result = $this->api->UpdateQuote($this->quoteID, $this->orderEventCode, $this->currency, $travellers, $this->clientID, $this->orderType);
            $result = $this->api->removeAllAccom($this->orderNumber, $this->quoteID, $this->orderEventCode, $removeHotelsData, $this->currency);
            $result = $this->api->reserveAccom($this->quoteID, $this->orderEventCode, $data);
        endif;

        //$roomsData = get_post_meta( $this->orderPostID , 'reservedAccommodation' , true);
        //$res = $this->api->reserveAccom($this->orderEventCode, $this->quoteID , $roomsData);

        return $result;
    }

    public function addPackageToQuote()
    {
        $travellers = $this->passengers;
        $tour = $this->getPackagesOrderData();
        $accom = $this->getPackageReservedAccom();
        $extras = $this->getReservedPackageExtras();
        $transfers = $this->getReservedPackageTransfers();


        $result = $this->api->reservePackage($this->quoteID, $this->orderEventCode, $this->transactionID, $this->currency, $travellers, $tour, $accom, $extras, $transfers);

        //$roomsData = get_post_meta( $this->orderPostID , 'reservedAccommodation' , true);
        //$res = $this->api->reserveAccom($this->orderEventCode, $this->quoteID , $roomsData);

        return $result;
    }

    public function deletePackageFromQuote()
    {
        $travellers = $this->passengers;
        $tour = $this->getPackagesOrderData();
        $accom = $this->getPackageReservedAccom();
        $extras = $this->getReservedPackageExtras();
        $transfers = $this->getReservedPackageTransfers();


        $result = $this->api->deletePackage($this->quoteID, $this->orderEventCode, $this->transactionID, $this->currency, $travellers, $tour, $accom, $extras, $transfers);

        //$roomsData = get_post_meta( $this->orderPostID , 'reservedAccommodation' , true);
        //$res = $this->api->reserveAccom($this->orderEventCode, $this->quoteID , $roomsData);

        return $result;
    }

    public function reserveExtras($data)
    {
        /**
         * Results codes:
         * 1 - added
         * 2 - removed
         * 3 - removed + added
         */
        $resultCode = 1;
        $removed = false;
        $added = false;
        if ($data):
            $extrasDataExists = metadata_exists('post', $this->orderPostID, 'reservedExtras');
            $extrasData = get_post_meta($this->orderPostID, 'reservedExtras', true);
            if ($extrasDataExists):
                $removeOffset = -1;
                foreach ($extrasData as $index => $item) {
                    if ($item['id'] == $data['id']) {
                        $result = $this->api->removeExtra($this->orderNumber, $this->quoteID, $this->orderEventCode, $item, $this->currency);
                        $removeOffset = $index;
                        $removed = true;
                    }
                }
                if ($removeOffset != -1) {
                    array_splice($extrasData, $removeOffset, 1);
                }

                if (sizeof($data['travellers'])):
                    $result = $this->api->reserveExtra($this->quoteID, $this->orderEventCode, $data);
                    if ($result) {
                        array_push($extrasData, $data);
                    }

                    $added = true;
                endif;
                update_post_meta($this->orderPostID, 'reservedExtras', $extrasData);
            else:
                add_post_meta($this->orderPostID, 'reservedExtras', array($data));
                $added = true;
                $result = $this->api->reserveExtra($this->quoteID, $this->orderEventCode, $data);
            endif;
        endif;

        if ($removed && $added) {
            $resultCode = 3;
        } elseif ($removed && !$added) {
            $resultCode = 2;
        }
        return $resultCode;
    }

    public function reserveExtrasArray($data)
    {
        if ($data):
            $extrasDataExists = metadata_exists('post', $this->orderPostID, 'reservedExtras');
            $extrasData = get_post_meta($this->orderPostID, 'reservedExtras', true);
            if ($extrasDataExists):
                $removeResult = $this->api->removeExtrasArray($this->orderNumber, $this->quoteID, $this->orderEventCode, $this->currency);
                $reserveResult = $this->api->reserveExtrasArray($this->quoteID, $this->orderEventCode, $data);
                update_post_meta($this->orderPostID, 'reservedExtras', $data);
            else:
                add_post_meta($this->orderPostID, 'reservedExtras', $data);
                $reserveResult = $this->api->reserveExtrasArray($this->quoteID, $this->orderEventCode, $data);
            endif;
        endif;

        return $data;
    }
    public function removeExtrasArray()
    {
        $removeResult = $this->api->removeExtrasArray($this->orderNumber, $this->quoteID, $this->orderEventCode, $this->currency);
    }

    public function reservePackageExtras($data)
    {
        if ($data):
            foreach ($data as $extraItem):
                /**
                 * Results codes:
                 * 1 - added
                 * 2 - removed
                 * 3 - removed + added
                 */
                $resultCode = 1;
                $removed = false;
                $added = false;
                if ($extraItem):
                    $extrasDataExists = metadata_exists('post', $this->orderPostID, 'reserved_package_extras');
                    $extrasData = get_post_meta($this->orderPostID, 'reserved_package_extras', true);
                    if ($extrasDataExists):
                        $removeOffset = -1;
                        foreach ($extrasData as $index => $item) {
                            if ($item['id'] == $extraItem['id']) {
                                $removeOffset = $index;
                                $removed = true;
                            }
                        }
                        if ($removeOffset != -1) {
                            array_splice($extrasData, $removeOffset, 1);
                        }

                        if (sizeof($extraItem['travellers'])):
                            $added = true;
                            $extrasData[$extraItem['id']] = $extraItem;
                        endif;

                        update_post_meta($this->orderPostID, 'reserved_package_extras', $extrasData);
                    else:
                        $extras = array();
                        if (sizeof($extraItem['travellers'])):
                            $extras[$extraItem['id']] = $extraItem;
                            $added = true;
                        endif;
                        add_post_meta($this->orderPostID, 'reserved_package_extras', $extras);
                    endif;
                endif;

                if ($removed && $added) {
                    $resultCode = 3;
                } elseif ($removed && !$added) {
                    $resultCode = 2;
                }
            endforeach;
        endif;
        return true;
    }

    public function preaddPackageExtras($data)
    {

        if ($data):
            $extrasDataExists = metadata_exists('post', $this->orderPostID, 'reserved_package_extras');
            if ($extrasDataExists):
                update_post_meta($this->orderPostID, 'reserved_package_extras', $data);
            else:
                add_post_meta($this->orderPostID, 'reserved_package_extras', $data);
            endif;
        endif;

    }

    public function addPackageTransfers($transfers)
    {
        if ($transfers):
            $transfersDataExists = metadata_exists('post', $this->orderPostID, 'reserved_package_transfers');

            if ($transfersDataExists):
                update_post_meta($this->orderPostID, 'reserved_package_transfers', $transfers);
            else:
                add_post_meta($this->orderPostID, 'reserved_package_transfers', $transfers);
            endif;
        endif;
    }

    public function removeExtras($data)
    {
        $result = "ERROR";
        if ($data):
            $result = $this->api->removeExtra($this->orderNumber, $this->quoteID, $this->orderEventCode, $data, $this->currency);

            if ($result):
                $extraArray = array(
                    'ref_number' => $data['ref_number'],
                    'locator' => $data['locator'],
                    'price' => $data['price'],
                    'name' => $data['name'],
                );
            endif;

            $extrasDataExists = metadata_exists('post', $this->orderPostID, 'reservedExtras');
            $extrasData = get_post_meta($this->orderPostID, 'reservedExtras', true);
            if ($extrasDataExists):
                $removeOffset = -1;
                foreach ($extrasData as $index => $item):
                    if ($item['ref_number'] == $data['ref_number'] && $item['name'] == $data['name'] && $item['price'] == $data['price']):
                        $removeOffset = $index;
                    endif;
                endforeach;
                if ($removeOffset != -1) {
                    array_splice($extrasData, $removeOffset, 1);
                }

                update_post_meta($this->orderPostID, 'reservedExtras', $extrasData);
            else:

            endif;

        endif;

        //return $result;
    }

    public function removeExtra($data)
    {
        if ($data):
            $extrasDataExists = metadata_exists('post', $this->orderPostID, 'reservedExtras');
            $extrasData = get_post_meta($this->orderPostID, 'reservedExtras', true);
            $result = false;
            if ($extrasDataExists):
                $removeOffset = -1;
                foreach ($extrasData as $index => $item) {
                    if ($item['id'] == $data['id']) {
                        $result = $this->api->removeExtra($this->orderNumber, $this->quoteID, $this->orderEventCode, $item, $this->currency);
                        $removeOffset = $index;
                        $removed = true;
                    }
                }
                if ($removeOffset != -1) {
                    array_splice($extrasData, $removeOffset, 1);
                }

                update_post_meta($this->orderPostID, 'reservedExtras', $extrasData);

                return $result;
            endif;
        endif;
        return $result;
    }

    public function addPackagesData($data)
    {
        $packagesArray = array();
        foreach ($data as $singleTour):
            $TourID = $singleTour['TourID'];
            $TourName = $singleTour['TourName'];
            $TourPriceID = $singleTour['TourPriceID'];
            $ToursIndId = $singleTour['ToursIndId'];
            $TourPricingTypeId = $singleTour['TourPricingTypeId'];
            $TourPriceDetailId = $singleTour['TourPriceDetailId'];
            $TourStartDate = $singleTour['TourStartDate'];
            $TourEndDate = $singleTour['TourEndDate'];
            $LOFI = $singleTour['LOFI'];
            $AccomExists = $singleTour['accommodation_exists'];
            $ExtrasExists = $singleTour['extras_exists'];
            $TranfersExists = $singleTour['transfers_exists'];
            $price = $singleTour['price'];

            $packageArray = array(
                'TourID' => $TourID,
                'TourName' => $TourName,
                'TourPriceID' => $TourPriceID,
                'ToursIndId' => $ToursIndId,
                'TourPricingTypeId' => $TourPricingTypeId,
                'TourPriceDetailId' => $TourPriceDetailId,
                'TourStartDate' => $TourStartDate,
                'TourEndDate' => $TourEndDate,
                'LOFI' => $LOFI,
                'AccommodationExists' => $AccomExists,
                'ExtrasExists' => $ExtrasExists,
                'TransfersExists' => $TranfersExists,
                'price' => $price,
            );
            array_push($packagesArray, $packageArray);
        endforeach;

        $packageDataExist = metadata_exists('post', $this->orderPostID, 'package_data');
        if ($packageDataExist):
            update_post_meta($this->orderPostID, 'package_data', $packagesArray);
            update_post_meta($this->orderPostID, 'reserved_package_extras', array());
        else:
            add_post_meta($this->orderPostID, 'package_data', $packagesArray);
        endif;
    }

    public function addPackage($data)
    {
        if ($data):
            $hotels = array();
            foreach ($data as $hotel):
                $hotelData = array(
                    'hotel_id' => $hotel['hotel_id'],
                    'hotel_post_id' => $hotel['hotel_post_id'],
                    'room_id' => $hotel['room_id'],
                    'accom_id' => $hotel['AccomId'],
                    'room_title' => $hotel['room_title'],
                    'room_bb' => $hotel['room_bb'],
                    'room_price' => $hotel['room_price'],
                    'room_duration' => $hotel['room_duration'],
                    'room_occupancy' => $hotel['room_occupancy'],
                    'tour_id' => $hotel['tour_id'],
                    'selection_state' => $hotel['SelectionState'],
                );
                array_push($hotels, $hotelData);
            endforeach;

            $accomDataExist = metadata_exists('post', $this->orderPostID, 'package_accommodation');

            if ($accomDataExist):
                update_post_meta($this->orderPostID, 'package_accommodation', $hotels);
            else:
                add_post_meta($this->orderPostID, 'package_accommodation', $hotels);
            endif;
        endif;
    }

    public function reservePackage($data)
    {
        $travellers = $this->passengers;
        $result = "ERROR";
        if ($data):
            $accomData = metadata_exists('post', $this->orderPostID, 'package_accommodation_reserved');
            if ($accomData):
                $result = update_post_meta($this->orderPostID, 'package_accommodation_reserved', $data);
            else:
                $result = add_post_meta($this->orderPostID, 'package_accommodation_reserved', $data);
            endif;

            $roomCounter = 0;

            $travellersIndex = 0;

            foreach ($travellers as $traveller):
                $age = intval($traveller['Age']);
                $age_code = 'ADT';
                $ref_number = $traveller['ref_number'];
                if ($age < 12):
                    $age_code = 'CHD';
                endif;
                if ($age < 2):
                    //$age_code = 'INF';
                endif;
                $roomIndex = 0;
                foreach ($data as $room):
                    $passengerIndex = 0;
                    $passengerAdded = false;
                    foreach ($room['passengers'] as $passenger):
                        $actualAge = intval($passenger['age']);
                        $actualTitle = $passenger['title'];
                        $actualName = $passenger['name'];
                        $actualSurname = $passenger['surname'];
                        $actualType = $passenger['type'];
                        $actualAgeCode = 'ADT';

                        if (is_celtic_active($this->orderEventPostID)) {
                            $actualCelticCode = $passenger['celtic_code'];
                        }

                        if ($actualAge < 12):
                            $actualAgeCode = 'CHD';
                        endif;
                        if ($actualAge < 2):
                            //$actualAgeCode = 'INF';
                        endif;

                        if ($actualAgeCode == $age_code && !isset($data[$roomIndex]['passengers'][$passengerIndex]['ref'])):
                            $data[$roomIndex]['passengers'][$passengerIndex]['ref'] = $ref_number;
                            $travellers[$travellersIndex]['Age'] = $actualAge;
                            $travellers[$travellersIndex]['Title'] = $actualTitle;
                            $travellers[$travellersIndex]['Name'] = $actualName;
                            $travellers[$travellersIndex]['Surname'] = $actualSurname;
                            $travellers[$travellersIndex]['Type'] = $actualType;
                            $travellers[$travellersIndex]['full-name'] = $actualName . ' ' . $actualSurname;

                            if (is_celtic_active($this->orderEventPostID)) {
                                $travellers[$travellersIndex]['celtic_code'] = $actualCelticCode;
                            }

                            $passengerAdded = true;
                            break;
                        endif;
                        $passengerIndex++;
                    endforeach;
                    $roomIndex++;
                    if ($passengerAdded) {
                        break;
                    }

                endforeach;
                $travellersIndex++;
            endforeach;

            $resultUpdaingTravellers = update_post_meta($this->orderPostID, 'passengers', $travellers);

            $removeHotelsData = array();

            $result = $this->api->UpdateQuote($this->quoteID, $this->orderEventCode, $this->currency, $travellers, $this->clientID, $this->orderType);
            //$result = $this->api->reservePackage($this->quoteID, $this->orderEventCode , $tourData , $data);
        endif;

        //$roomsData = get_post_meta( $this->orderPostID , 'reservedAccommodation' , true);
        //$res = $this->api->reserveAccom($this->orderEventCode, $this->quoteID , $roomsData);

        return $result;
    }

    public function reservePackagePassengers($data)
    {
        $travellers = $this->passengers;

        $result = "ERROR";
        if ($data):

            $travellersIndex = 0;
            $passengerIndex = 0;
            foreach ($travellers as $traveller):
                $age = intval($traveller['Age']);
                $age_code = 'ADT';
                $ref_number = $traveller['ref_number'];
                if ($age < 12):
                    $age_code = 'CHD';
                endif;
                if ($age < 2):
                    //$age_code = 'INF';
                endif;
                $roomIndex = 0;
                foreach ($data as $passenger):

                    $actualAge = intval($passenger['age']);
                    $actualTitle = $passenger['title'];
                    $actualName = $passenger['name'];
                    $actualSurname = $passenger['surname'];
                    $actualType = $passenger['type'];
                    $actualAgeCode = 'ADT';
                    if ($actualAge < 18):
                        $actualAgeCode = 'CHD';
                    endif;
                    if ($actualAge < 2):
                        //$actualAgeCode = 'INF';
                    endif;

                    if (is_celtic_active($this->orderEventPostID)) {
                        $actualCelticCode = $passenger['celtic_code'];
                    }

                    if ($actualAgeCode == $age_code && !isset($data[$passengerIndex]['ref'])):
                        $data[$passengerIndex]['ref'] = $ref_number;
                        $travellers[$travellersIndex]['Age'] = $actualAge;
                        $travellers[$travellersIndex]['Title'] = $actualTitle;
                        $travellers[$travellersIndex]['Name'] = $actualName;
                        $travellers[$travellersIndex]['Surname'] = $actualSurname;
                        $travellers[$travellersIndex]['Type'] = $actualType;
                        $travellers[$travellersIndex]['full-name'] = $actualName . ' ' . $actualSurname;

                        if (is_celtic_active($this->orderEventPostID)) {
                            $travellers[$travellersIndex]['celtic_code'] = $actualCelticCode;
                        }

                        $passengerAdded = true;
                        break;
                    endif;
                    $passengerIndex++;
                endforeach;
                $roomIndex++;
                $travellersIndex++;
            endforeach;

            $resultUpdaingTravellers = update_post_meta($this->orderPostID, 'passengers', $travellers);

            $removeHotelsData = array();

            $result = $this->api->UpdateQuote($this->quoteID, $this->orderEventCode, $this->currency, $travellers, $this->clientID, $this->orderType);
            //$result = $this->api->reservePackage($this->quoteID, $this->orderEventCode , $tourData , $data);
        endif;

        //$roomsData = get_post_meta( $this->orderPostID , 'reservedAccommodation' , true);
        //$res = $this->api->reserveAccom($this->orderEventCode, $this->quoteID , $roomsData);

        return $result;
    }

    public function addComment($comment)
    {
        update_post_meta($this->orderPostID, 'comment', $comment);
        $result = $this->api->AddComment($this->quoteID, $this->orderEventCode, $comment);
        return $result;
    }

    public function getAccommodations()
    {
        $accomData = get_post_meta($this->orderPostID, 'accommodation', true);
        return $accomData;
    }

    public function getReservedAccommodations()
    {
        $accomData = get_post_meta($this->orderPostID, 'reservedAccommodation', true);
        return $accomData;
    }
    public function getReservedExtras()
    {
        $extrasData = get_post_meta($this->orderPostID, 'reservedExtras', true);
        return $extrasData;
    }

    public function getReservedPackageExtras()
    {
        $extrasData = get_post_meta($this->orderPostID, 'reserved_package_extras', true);
        return $extrasData;
    }

    public function getReservedPackageTransfers()
    {
        $extrasData = get_post_meta($this->orderPostID, 'reserved_package_transfers', true);
        return $extrasData;
    }

    //package data
    public function getPackagesOrderData()
    {
        $packageData = get_post_meta($this->orderPostID, 'package_data', true);
        return $packageData;
    }
    public function getPackageAccom()
    {
        $accomData = get_post_meta($this->orderPostID, 'package_accommodation', true);
        return $accomData;
    }

    public function getPackageReservedAccom()
    {
        $accomData = get_post_meta($this->orderPostID, 'package_accommodation_reserved', true);
        return $accomData;
    }

    public function getPassengers()
    {
        $people = get_post_meta($this->orderPostID, 'passengers', true);
        return $people;
    }


    public function getRooms()
    {
        return $this->rooms;
    }

    public function getOrderEventID()
    {
        return get_field('event_id', $this->orderEventPostID);
    }

    public function checkIfCreateFromBooking()
    {
        return get_field('created_from_booking', $this->orderEventPostID);
    }

    public function getPriceFromQuote($type = 'full_quote')
    {
        if ($this->quoteID):
            $quote = $this->api->ViewQuote($this->orderEventCode, $this->quoteID);
            $price = false;
            if ($quote):
                if ($type == 'full_quote'):
                    $price = $quote->OTA_ViewQuoteResult->QuoteInfo->TotalFare->Amount;
                elseif ($type == 'due_payment'):
                    $bookingData = $this->api->GetBookingData($this->bookingID);
                    if ($bookingData):
                        $price = floatval($bookingData->BookingManagementResult->Booking->TotalDue);
                    endif;
                elseif ($type == 'deposit'):
                    $price = $quote->OTA_ViewQuoteResult->QuoteInfo->Deposit;
                elseif ($type == 'custom_amount'):
                    $custom_amount = get_post_meta($this->orderPostID, 'custom_amount', true);
                    $price = $custom_amount ? $custom_amount : $quote->OTA_ViewQuoteResult->QuoteInfo->Deposit;
                elseif ($type == 'custom_amount_due'):
                    $bookingData = $this->api->GetBookingData($this->bookingID);
                    if ($bookingData):
                        $custom_amount = get_post_meta($this->orderPostID, 'custom_amount', true);
                        $price = $custom_amount ? $custom_amount : (floatval($bookingData->BookingManagementResult->Booking->TotalDue) / 2);
                    endif;
                else:
                    $bookingData = $this->api->GetBookingData($this->bookingID);
                    if ($bookingData):
                        $price = floatval($bookingData->BookingManagementResult->Booking->TotalDue);
                    else:
                        $price = $quote->OTA_ViewQuoteResult->QuoteInfo->Deposit;
                    endif;
                endif;
                update_post_meta($this->orderPostID, 'quote_price', $quote->OTA_ViewQuoteResult->QuoteInfo->TotalFare->Amount);
            endif;
            return $price;
        endif;
        return false;
    }

    public function getPriceFromBooking()
    {
        $price = false;
        if ($this->bookingID):
            $bookingData = $this->api->GetBookingData($this->bookingID);

            if ($bookingData):
                $price = floatval($bookingData->BookingManagementResult->Booking->TotalDue);
            endif;
        endif;
        return $price;
    }

    public function getPriceFromBookingDeposit()
    {
        $price = false;
        if ($this->bookingID):
            $bookingData = $this->api->GetBookingData($this->bookingID);
            $amountDue = floatval($bookingData->BookingManagementResult->Booking->TotalDue);
            $depositAmount = floatval($bookingData->BookingManagementResult->Booking->Deposit);
            $amountPaid = floatval($bookingData->BookingManagementResult->Booking->TotalPaid);
            $paymentType = $this->getPaymentType();
            if ($bookingData):
                if ($paymentType == 'custom_amount' || $paymentType == 'custom_amount_due'):
                    $custom_amount = get_post_meta($this->orderPostID, 'custom_amount', true);
                    if ($custom_amount):
                        $price = $custom_amount;
                    else:
                        $price = $amountDue / 2;
                    endif;
                else:
                    $price = $depositAmount;
                endif;
            endif;
        endif;
        return $price;
    }

    public function getQuoteFromRes()
    {
        $quote = $this->api->ViewQuote($this->orderEventCode, $this->quoteID);
        return $quote;
    }

    public function getMetaQuotePrice()
    {
        $price = get_post_meta($this->orderPostID, 'quote_price', true);
        return $price;
    }

    public function getPaymentType()
    {
        $price = get_post_meta($this->orderPostID, 'payment_type', true);
        return $price;
    }

    /**
     * Create Client
     */
    public function createClient($data)
    {
        $current_user = wp_get_current_user();
        $api_client_id = get_user_meta($current_user->ID, 'api_client_id', true);
        if ($api_client_id):
            update_post_meta($this->orderPostID, 'client_id', $api_client_id);
            update_post_meta($this->orderPostID, 'user_id', $current_user->ID);
            $this->clientID = $api_client_id;
            $result = $this->api->UpdateQuoteClient($this->quoteID, $this->orderEventCode, $this->currency, $this->clientID);
            if ($result):
                return true;
            else:
                return false;
            endif;
        else:
            $client = $this->api->CreateClient($data, $this->currency);
            if (isset($client['response']->ClientManagementResult->CreateClientsRS->Clients->Client)) {
                update_post_meta($this->orderPostID, 'client_id', $client['response']->ClientManagementResult->CreateClientsRS->Clients->Client->ID);
                update_post_meta($this->orderPostID, 'user_id', $current_user->ID);
                update_post_meta($current_user->ID, 'api_client_request', $client['request']);
                update_post_meta($current_user->ID, 'api_client_response', print_r($client['response'], true));
                update_post_meta($current_user->ID, 'api_client_error', $client['error']);
                $this->clientID = $client['response']->ClientManagementResult->CreateClientsRS->Clients->Client->ID;
                $result = $this->api->UpdateQuoteClient($this->quoteID, $this->orderEventCode, $this->currency, $this->clientID);
                if ($result) {
                    return true;
                }

            }
            return false;
        endif;
    }

    /**
     * Search Client
     */
    public function searchClient($data)
    {
        $client = $this->api->CreateClient($data, $this->currency);
        if (isset($client['response']->ClientManagementResult->CreateClientsRS->Clients->Client)) {
            update_post_meta($this->orderPostID, 'client_id', $client['response']->ClientManagementResult->CreateClientsRS->Clients->Client->ID);
        }
        return $client;
    }

    /**
     * Search Client by Wordpress user ID
     */
    public function searchClientByExternalID($data)
    {

        $client = $this->api->CreateClient($data, $this->currency);
        if (isset($client['response']->ClientManagementResult->CreateClientsRS->Clients->Client)) {
            update_post_meta($this->orderPostID, 'client_id', $client['response']->ClientManagementResult->CreateClientsRS->Clients->Client->ID);
        }
        return $client;
    }

    /**
     * Get payment form
     */

    function getPaymentPrauth($data)
    {
        if (isset($data['payment_option'])):
            $payment_option = $data['payment_option'];
        else:
            $payment_option = get_post_meta($this->orderPostID, 'payment_type', true);
        endif;

        if ($payment_option == 'custom_amount' || $payment_option == 'custom_amount_due'):
            if (isset($data['custom_amount'])):
                update_post_meta($this->orderPostID, 'custom_amount', $data['custom_amount']);
                $price = $this->getPriceFromQuote($payment_option, $data['custom_amount']);
            else:
                $custom_amount = get_post_meta($this->orderPostID, 'custom_amount', true);
                if ($custom_amount):
                    $price = $this->getPriceFromQuote($payment_option, $custom_amount);
                else:
                    $price = $this->getPriceFromQuote($payment_option, $data['custom_amount']);
                endif;
            endif;
        else:
            $price = $this->getPriceFromQuote($payment_option);
        endif;

        if ($payment_option != 'due_payment'):
            update_post_meta($this->orderPostID, 'billing_info', $data);
            update_post_meta($this->orderPostID, 'payment_type', $data['payment_option']);
            $preAuth = $this->api->PaymentPreauth($this->orderNumber, $this->quoteID, $this->clientID, $this->orderEventCode, $data, $price, $this->currency);
            $result = false;
            if (isset($preAuth->PaymentServiceManagementResult->CreatePreAuth->CardCapture->CaptureURL) && $preAuth->PaymentServiceManagementResult->CreatePreAuth->CardCapture->CaptureURL):
                $result = '<iframe id="api-payment-iframe" src="' . $preAuth->PaymentServiceManagementResult->CreatePreAuth->CardCapture->CaptureURL . '"></iframe>';
            endif;
            return $result;
        elseif ($payment_option == 'due_payment'):
            update_post_meta($this->orderPostID, 'rest_billing_info', $data);
            update_post_meta($this->orderPostID, 'payment_type', $data['payment_option']);
            $result = false;
            if ($this->bookingID):
                $bookingData = $this->api->GetBookingData($this->bookingID);
                if ($bookingData):
                    $amountDue = floatval($bookingData->BookingManagementResult->Booking->TotalDue);
                    $preAuth = $this->api->PaymentPreauthBooking($this->orderNumber, $this->quoteID, $this->clientID, $this->orderEventCode, $data, $amountDue, $this->currency, $this->bookingID);
                    if (isset($preAuth->PaymentServiceManagementResult->CreatePreAuth->CardCapture->CaptureURL) && $preAuth->PaymentServiceManagementResult->CreatePreAuth->CardCapture->CaptureURL):
                        $result = '<iframe id="api-payment-iframe" src="' . $preAuth->PaymentServiceManagementResult->CreatePreAuth->CardCapture->CaptureURL . '"></iframe>';
                    endif;
                endif;
            endif;

            return $result;
        elseif ($payment_option == 'custom_amount_due'):
            update_post_meta($this->orderPostID, 'rest_billing_info', $data);
            update_post_meta($this->orderPostID, 'payment_type', $data['payment_option']);
            $result = false;
            if ($this->bookingID):
                $preAuth = $this->api->PaymentPreauth($this->orderNumber, $this->quoteID, $this->clientID, $this->orderEventCode, $data, $price, $this->currency, $this->bookingID);
                $result = false;
                if (isset($preAuth->PaymentServiceManagementResult->CreatePreAuth->CardCapture->CaptureURL) && $preAuth->PaymentServiceManagementResult->CreatePreAuth->CardCapture->CaptureURL):
                    $result = '<iframe id="api-payment-iframe" src="' . $preAuth->PaymentServiceManagementResult->CreatePreAuth->CardCapture->CaptureURL . '"></iframe>';
                endif;
            endif;
            return $result;
        endif;
        return false;
    }

    /**
     * Set Transaction ID
     */
    public function setTransactionID($transaction_id)
    {
        $this->transactionID = $transaction_id;
        update_post_meta($this->orderPostID, 'transaction_id', $transaction_id);
    }
    public function setTransactionIDRemaining($transaction_id)
    {
        $this->transactionIDRest = $transaction_id;
        update_post_meta($this->orderPostID, 'rest_transaction_id', $transaction_id);

        $transaction_ids = get_post_meta($this->orderPostID, 'rest_transactions_ids', true);
        if ($transaction_ids && is_array($transaction_ids)):
            array_push($transaction_ids, $transaction_id);
        else:
            $transaction_ids = array();
            array_push($transaction_ids, $transaction_id);
        endif;
        update_post_meta($this->orderPostID, 'rest_transactions_ids', $transaction_ids);
    }

    /**
     * Get Transaction Status
     */
    public function getTransactionStatus()
    {
        $result = $this->api->getTransactionStatus($this->orderNumber, $this->clientID, $this->orderEventCode, $this->transactionID, $this->currency);
        $status = false;
        if ($result):
            $status = $result->PaymentServiceManagementResult->GetTransactionStatus->TransactionStatus;
            update_post_meta($this->orderPostID, 'transaction_status', $status);
        endif;
        return $status;
    }
    public function getTransactionStatusRemaining()
    {
        $result = $this->api->getTransactionStatus($this->orderNumber, $this->clientID, $this->orderEventCode, $this->transactionIDRest, $this->currency);
        $status = false;
        if ($result):
            $status = $result->PaymentServiceManagementResult->GetTransactionStatus->TransactionStatus;
            update_post_meta($this->orderPostID, 'rest_transaction_status', $status);
        endif;
        return $status;
    }

    /**
     * Make Booking
     */
    public function createBooking()
    {
        $booking = $this->api->createBooking($this->orderNumber, $this->quoteID, $this->clientID, $this->orderEventCode, $this->transactionID, $this->getPriceFromQuote($this->paymentType), $this->currency, $this->orderPostID);
        update_post_meta($this->orderPostID, 'booking_creation', print_r($booking, true));
        if (isset($booking->OTA_BookingResult->Reservation->BookingReferenceID->ID)):
            $bookingID = $booking->OTA_BookingResult->Reservation->BookingReferenceID->ID;
            update_post_meta($this->orderPostID, 'booking_id', $bookingID);

            if (is_celtic_active($this->orderEventPostID)) {
                foreach ($this->passengers as $passenger) {
                    $celtic_code = isset($passenger['celtic_code']) ? $passenger['celtic_code'] : '';

                    if (!empty($celtic_code)) {
                        $celtic_db = new Celtic_Model();
                        $celtic_record = $celtic_db->get_record($celtic_code, $this->orderEventPostID);
                        if (!empty($celtic_record) && $celtic_record->max_used < $celtic_record->used_times) {
                            $celtic_db->update($celtic_record->id, array(
                                'used_times' => $celtic_record->used_times + 1,
                                'user_name' => $passenger['full-name'],
                                'booking_reference' => $bookingID,
                            )
                            );
                        }
                    }
                }
            }


            return true;
        endif;
        return false;
    }

    /**
     * Authorize booking due payment
     */
    public function authorizePaymentBooking()
    {
        $booking = $this->api->authorizeBookingPayment($this->bookingID, $this->transactionIDRest, $this->getPriceFromBooking(), 'Full payment');

        if ($booking):
            return true;
        endif;

        return false;
    }

    /**
     * Authorize booking deposit payment
     */
    public function authorizePaymentBookingDeposit()
    {
        $booking = $this->api->authorizeBookingPayment($this->bookingID, $this->transactionID, $this->getPriceFromBookingDeposit(), 'Deposit payment');

        if ($booking):
            return true;
        endif;

        return false;
    }

    /**
     * Authorize booking custom due payment
     */
    public function authorizePaymentBookingCustomDue()
    {
        $booking = $this->api->authorizeBookingPayment($this->bookingID, $this->transactionIDRest, $this->getPriceFromBookingDeposit(), 'Custom Due Amount payment');

        if ($booking):
            return true;
        endif;

        return false;
    }

}
