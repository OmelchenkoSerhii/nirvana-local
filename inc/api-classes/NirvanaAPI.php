<?php

class NiravanaAPI
{
    protected const API_CF_ID = 'c8b895eda340e584be2111e951b2e38b.access';
    protected const API_CF_SECRET = '33afd522512efd35a41159c41b9674210ff83db1ede5af1d407dbe4f8b340093';
    private $API_URL;
    private $API_DB;
    private $LOG_REQUESTS;

    private $order_token;

    public $client;

    public function __construct($order_token = false)
    {
        $this->order_token = $order_token;
        $this->LOG_REQUESTS = true;

        $this->API_URL = get_field('api_mode', 'option') == 'live' ? 'https://nirvana-live-ota.hosting.inspiretec.com/TravelinkCEService.svc?wsdl' : 'https://nirvana-uat-ota.hosting.inspiretec.com/TravelinkCEService.svc?wsdl';
        $this->API_DB = get_field('api_mode', 'option') == 'live' ? 'NIRVANA_LIVE' : 'Nirvana_UAT';

        $aHTTP['http']['header'] = "User-Agent: PHP-SOAP/5.5.11\r\n";
        $aHTTP['http']['header'] .= "CF-Access-Client-Id: " . self::API_CF_ID . "\r\n" . "CF-Access-Client-Secret: " . self::API_CF_SECRET . "\r\n";
        $context = stream_context_create($aHTTP);

        $soapClientOptions = array(
            'cache_wsdl' => WSDL_CACHE_DISK,
            'soap_version' => SOAP_1_1,
            'encoding' => 'utf-8',
            'exceptions' => true,
            'stream_context' => $context,
            'location' => $this->API_URL,
            'trace' => 1,
        );

        $this->client = new SoapClient($this->API_URL, $soapClientOptions);
    }
    /**
     *
     * Create order
     *
     */
    public function CreateQuote($productCode, $currency, $travellers, $date, $clientID = '' , $orderType = 'tailor')
    {

        $travellersArray = array();
        $index = 1;
        foreach ($travellers as $traveller) :
            $ageCode = 'ADT';
            $BirthDate = '1965-02-09';
            if ($traveller['Age'] < 12) :
                $ageCode = 'CHD';
                $BirthDate = '2020-01-01';
            endif;
            if($orderType != 'tour'):
                if ($traveller['Age'] < 2) :
                    $ageCode = 'INF';
                endif;
            endif; 

            array_push($travellersArray, array(
                'PersonName' => array(
                    'NamePrefix' => 'Mr',
                    'GivenName' => $traveller['Name'],
                    'MiddleName' => '',
                    'Surname' => $traveller['Surname'],
                ),
                'Email' => '',
                'Address' => array(
                    'AddressLine' => array(
                        0 => '',
                        1 => '',
                        2 => '',
                    ),
                    'PostalCode' => '',
                ),
                'TPA_Extensions' => array(
                    'PrimaryContactIsAgent' => 'False',
                ),
                'TitleAPIS' => '',
                'ForenameAPIS' => '',
                'MiddleNameAPIS' => '',
                'SurnameAPIS' => '',
                'GenderAPIS' => '',
                'DOBDateAPIS' => '',
                'PassportNumberAPIS' => '',
                'NationalityAPIS' => '',
                'PassportIssueDateAPIS' => '',
                'PassportExpiryDateAPIS' => '',
                'PassportCountryOfIssueAPIS' => '',
                'CountryOfResidenceAPIS' => '',
                'AlienRegistrationNumberAPIS' => '',
                'ArrivalAddressFor1stNightAPIS' => '',
                'DestinationAddressAPIS' => '',
                'RedressNumberAPIS' => '',
                'KnownTravellerNumberAPIS' => '',
                'DestinationAddressStreetAPIS' => '',
                'DestinationAddressCityAPIS' => '',
                'DestinationAddressStateAPIS' => '',
                'DestinationAddressCountryAPIS' => '',
                'DestinationAddressZipPostalCodeAPIS' => '',
                'OtherDetails' => array(
                    'NoMiddleName' => '',
                    'RedressNumber' => '',
                    'PassportNumber' => '',
                    'Nationality' => '',
                    'IssueDate' => '2014-01-31',
                    'ExpiryDate' => '2024-02-01',
                    'IssuingCountry' => 'UK',
                    'CountryOfResidence' => 'UK',
                    'PlaceOfIssue' => 'UK',
                    'BirthCountry' => 'UK',
                    'UsAlienRegistration' => '',
                    'ArrivalAddressFor1stNight' => '',
                    'DestinationAddress' => '',
                    'KnownPassengerNumber' => '',
                    'DestinationAddressIsDestination' => '',
                    'DestinationAddressStreet' => '',
                    'DestinationAddressCity' => '',
                    'DestinationAddressState' => '',
                    'DestinationAddressCountry' => '',
                    'DestinationAddressZipPostalCode' => '',
                    'PlaceOfBirth' => '',
                    'Weight' => '',
                    'Height' => '',
                    'ShoeSize' => '',
                    'Occupation' => '',
                    'FathersName' => '',
                ),
                'Comments' => '',
                'VisaInformation' => array(),
                'InsuranceInformation' => array(
                    '_' => '',
                    'CompanyName' => '',
                    'PolicyNumber' => '',
                    'EmergencyTelephoneNumber' => '',
                    'ValidFrom' => '2014-12-01',
                    'ValidTo' => '2015-11-30',
                ),
                'BirthDate' => $BirthDate,
                'PassengerTypeCode' => $ageCode,
                'DietaryInformation' => 'N/a',
                'MedicalInformation' => 'Medical certificate',
                'isLead' => $index==1?1:0,
                'OwnInsurance' => '',
                'Gender' => 'Male',
            ));
            $index++;
        endforeach;

        $params = array(
            'OTA_CreateQuoteRQ' => array(
                'POS' => array(
                    'Source' => array(
                        'Authentication' => array(
                            'DataBase' => array(
                                '_' => $this->API_DB,
                                'DataBase' => $this->API_DB,
                                'IP' => 'Web',
                                'ProductCode' => $productCode,
                            ),
                            'ProductCode' => $productCode,
                            'ClientID' => $clientID,
                        ),
                        'ISOCurrency' => $currency,
                    ),
                ),
                'TravelerInfos' => array(
                    'TravelerInfo' => $travellersArray,
                ),
                'MarketingSource' => 'Website',
                'SuppressClientRecordCreation' => true,
                'RetailShopID' => false,
                'DepartureDate' => $date . 'Z', //'2022-09-02Z'
            ),
        );

        $response = $this->client->OTA_CreateQuote($params);


        $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
        $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
        //echo "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";
        //print_r($response );

        return $response;
    }

    /**
     *
     * Create order
     *
     */
    public function UpdateQuoteData($quoteID , $productCode, $currency, $travellers, $date, $clientID = '', $orderType = 'tailor')
    {

        $travellersArray = array();
        $index = 1;
        foreach ($travellers as $traveller) :
            $ageCode = 'ADT';
            $BirthDate = '1965-02-09';
            if ($traveller['Age'] < 12) :
                $ageCode = 'CHD';
                $BirthDate = '2020-01-01';
            endif;
            if($orderType != 'tour'):
                if ($traveller['Age'] < 2) :
                    $ageCode = 'INF';
                endif;
            endif;
            $appendTraveller = array(
                'PersonName' => array(
                    'NamePrefix' => 'Mr',
                    'GivenName' => $traveller['Name'],
                    'MiddleName' => '',
                    'Surname' => $traveller['Surname'],
                ),
                'Email' => '',
                'Address' => array(
                    'AddressLine' => array(
                        0 => '',
                        1 => '',
                        2 => '',
                    ),
                    'PostalCode' => '',
                ),
                'TPA_Extensions' => array(
                    'PrimaryContactIsAgent' => 'False',
                ),
                'TitleAPIS' => '',
                'ForenameAPIS' => '',
                'MiddleNameAPIS' => '',
                'SurnameAPIS' => '',
                'GenderAPIS' => '',
                'DOBDateAPIS' => '',
                'PassportNumberAPIS' => '',
                'NationalityAPIS' => '',
                'PassportIssueDateAPIS' => '',
                'PassportExpiryDateAPIS' => '',
                'PassportCountryOfIssueAPIS' => '',
                'CountryOfResidenceAPIS' => '',
                'AlienRegistrationNumberAPIS' => '',
                'ArrivalAddressFor1stNightAPIS' => '',
                'DestinationAddressAPIS' => '',
                'RedressNumberAPIS' => '',
                'KnownTravellerNumberAPIS' => '',
                'DestinationAddressStreetAPIS' => '',
                'DestinationAddressCityAPIS' => '',
                'DestinationAddressStateAPIS' => '',
                'DestinationAddressCountryAPIS' => '',
                'DestinationAddressZipPostalCodeAPIS' => '',
                'OtherDetails' => array(
                    'NoMiddleName' => '',
                    'RedressNumber' => '',
                    'PassportNumber' => '',
                    'Nationality' => '',
                    'IssueDate' => '2014-01-31',
                    'ExpiryDate' => '2024-02-01',
                    'IssuingCountry' => 'UK',
                    'CountryOfResidence' => 'UK',
                    'PlaceOfIssue' => 'UK',
                    'BirthCountry' => 'UK',
                    'UsAlienRegistration' => '',
                    'ArrivalAddressFor1stNight' => '',
                    'DestinationAddress' => '',
                    'KnownPassengerNumber' => '',
                    'DestinationAddressIsDestination' => '',
                    'DestinationAddressStreet' => '',
                    'DestinationAddressCity' => '',
                    'DestinationAddressState' => '',
                    'DestinationAddressCountry' => '',
                    'DestinationAddressZipPostalCode' => '',
                    'PlaceOfBirth' => '',
                    'Weight' => '',
                    'Height' => '',
                    'ShoeSize' => '',
                    'Occupation' => '',
                    'FathersName' => '',
                ),
                'Comments' => '',
                'VisaInformation' => array(),
                'InsuranceInformation' => array(
                    '_' => '',
                    'CompanyName' => '',
                    'PolicyNumber' => '',
                    'EmergencyTelephoneNumber' => '',
                    'ValidFrom' => '2014-12-01',
                    'ValidTo' => '2015-11-30',
                ),
                'BirthDate' => $BirthDate,
                'PassengerTypeCode' => $ageCode,
                'DietaryInformation' => 'N/a',
                'MedicalInformation' => 'Medical certificate',
                'isLead' => $index==1?1:0,
                'OwnInsurance' => '',
                'Gender' => 'Male',
            );
            if(isset($traveller['ref_number'])):
                $appendTraveller['TravelerRefNumber'] = array(
                    'RPH' => $traveller['ref_number'],
                );
            endif;
            array_push($travellersArray, $appendTraveller);
            $index++;
        endforeach;

        $params = array(
            'OTA_CreateQuoteRQ' => array(
                'POS' => array(
                    'Source' => array(
                        'Authentication' => array(
                            'DataBase' => array(
                                '_' => $this->API_DB,
                                'DataBase' => $this->API_DB,
                                'IP' => 'Web',
                                'ProductCode' => $productCode,
                            ),
                            'ProductCode' => $productCode,
                            'ClientID' => $clientID,
                        ),
                        'ISOCurrency' => $currency,
                    ),
                ),
                'TravelerInfos' => array(
                    'TravelerInfo' => $travellersArray,
                ),
                'MarketingSource' => 'Website',
                'SuppressClientRecordCreation' => true,
                'RetailShopID' => false,
                'DepartureDate' => $date . 'Z', //'2022-09-02Z'
                'TransactionIdentifier' => $quoteID,
            ),
        );

        $response = $this->client->OTA_CreateQuote($params);

        $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
        $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");

        return $response;
    }

    /**
     *
     * Update order with travellers names and actual ages
     *
     */
    public function UpdateQuote($quoteID, $productCode, $currency, $travellers, $clientID, $orderType = 'tailor')
    {

        $travellersArray = array();
        $index = 1;
        foreach ($travellers as $traveller) :
            $ageCode = 'ADT';
            $BirthDate = '1965-02-09';
            if ($traveller['Age'] < 12) :
                $ageCode = 'CHD';
                $BirthDate = '2020-01-01';
            endif;
            if($orderType != 'tour'):
                if ($traveller['Age'] < 2) :
                    $ageCode = 'INF';
                endif;
            endif;
            array_push($travellersArray, array(
                'PersonName' => array(
                    'NamePrefix' => $traveller['Title'],
                    'GivenName' => $traveller['Name'],
                    'MiddleName' => '',
                    'Surname' => $traveller['Surname'],
                ),
                'Email' => '',
                'Address' => array(
                    'AddressLine' => array(
                        0 => '',
                        1 => '',
                        2 => '',
                    ),
                    'PostalCode' => '',
                ),
                'TravelerRefNumber' => array(
                    'RPH' => $traveller['ref_number'],
                ),
                'TPA_Extensions' => array(
                    'PrimaryContactIsAgent' => 'False',
                ),
                'TitleAPIS' => '',
                'ForenameAPIS' => '',
                'MiddleNameAPIS' => '',
                'SurnameAPIS' => '',
                'GenderAPIS' => '',
                'DOBDateAPIS' => '',
                'PassportNumberAPIS' => '',
                'NationalityAPIS' => '',
                'PassportIssueDateAPIS' => '',
                'PassportExpiryDateAPIS' => '',
                'PassportCountryOfIssueAPIS' => '',
                'CountryOfResidenceAPIS' => '',
                'AlienRegistrationNumberAPIS' => '',
                'ArrivalAddressFor1stNightAPIS' => '',
                'DestinationAddressAPIS' => '',
                'RedressNumberAPIS' => '',
                'KnownTravellerNumberAPIS' => '',
                'DestinationAddressStreetAPIS' => '',
                'DestinationAddressCityAPIS' => '',
                'DestinationAddressStateAPIS' => '',
                'DestinationAddressCountryAPIS' => '',
                'DestinationAddressZipPostalCodeAPIS' => '',
                'OtherDetails' => array(
                    'NoMiddleName' => '',
                    'RedressNumber' => '',
                    'PassportNumber' => '',
                    'Nationality' => '',
                    'IssueDate' => '2014-01-31',
                    'ExpiryDate' => '2024-02-01',
                    'IssuingCountry' => 'UK',
                    'CountryOfResidence' => 'UK',
                    'PlaceOfIssue' => 'UK',
                    'BirthCountry' => 'UK',
                    'UsAlienRegistration' => '',
                    'ArrivalAddressFor1stNight' => '',
                    'DestinationAddress' => '',
                    'KnownPassengerNumber' => '',
                    'DestinationAddressIsDestination' => '',
                    'DestinationAddressStreet' => '',
                    'DestinationAddressCity' => '',
                    'DestinationAddressState' => '',
                    'DestinationAddressCountry' => '',
                    'DestinationAddressZipPostalCode' => '',
                    'PlaceOfBirth' => '',
                    'Weight' => '',
                    'Height' => '',
                    'ShoeSize' => '',
                    'Occupation' => '',
                    'FathersName' => '',
                ),
                'Comments' => '',
                'VisaInformation' => array(),
                'InsuranceInformation' => array(
                    '_' => '',
                    'CompanyName' => '',
                    'PolicyNumber' => '',
                    'EmergencyTelephoneNumber' => '',
                    'ValidFrom' => '2014-12-01',
                    'ValidTo' => '2015-11-30',
                ),
                'BirthDate' => $BirthDate,
                'PassengerTypeCode' => $ageCode,
                'DietaryInformation' => 'N/a',
                'MedicalInformation' => 'Medical certificate',
                'isLead' => $index==1?1:0 ,
                'OwnInsurance' => '',
                'Gender' => 'Male',
            ));
            $index++;
        endforeach;

        $params = array(
            'OTA_CreateQuoteRQ' => array(
                'MarketingSource' => 'Website',
                'SuppressClientRecordCreation' => false,
                'RetailShopID' => false,
                'TransactionIdentifier' => $quoteID,
                'POS' => array(
                    'Source' => array(
                        'Authentication' => array(
                            'DataBase' => array(
                                '_' => $this->API_DB,
                                'DataBase' => $this->API_DB,
                                'IP' => 'Web',
                                'ProductCode' => $productCode,
                            ),
                            'ProductCode' => $productCode,
                            'ClientID' => $clientID,
                        ),
                        'ISOCurrency' => $currency,
                    ),
                ),
                'TravelerInfos' => array(
                    'TravelerInfo' => $travellersArray,
                ),
            ),
        );

        $response = $this->client->OTA_CreateQuote($params);

        $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
        $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");

        return $response;
    }

    /**
     *
     * Update order with client ID
     *
     */
    public function UpdateQuoteClient($quoteID, $productCode, $currency, $clientID)
    {

        $params = array(
            'OTA_CreateQuoteRQ' => array(
                'MarketingSource' => 'Website',
                'SuppressClientRecordCreation' => false,
                'RetailShopID' => false,
                'TransactionIdentifier' => $quoteID,
                'POS' => array(
                    'Source' => array(
                        'Authentication' => array(
                            'DataBase' => array(
                                '_' => $this->API_DB,
                                'DataBase' => $this->API_DB,
                                'IP' => 'Web',
                                'ProductCode' => $productCode,
                            ),
                            'ProductCode' => $productCode,
                            'ClientID' => $clientID,
                        ),
                        'ISOCurrency' => $currency,
                    ),
                ),
            ),
        );

        try {
            $response = $this->client->OTA_CreateQuote($params);

            $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
            $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
            //print_r($response);
            //echo "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";
            if ($response) :
                return true;
            endif;
        } catch (Exception $e) {
            $this->logSOAPRequests("REQUEST ERROR:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
            return false;
        }

        return false;
    }

    /**
     *
     * Get events data
     *
     */
    public function GetStaticData()
    {
        $params = array(
            'GetStaticDataRQ' => array(
                'DBName' => $this->API_DB,
                'Translate' => 'false',
                'AmendCanxReasons' => 0,
                'Products' => 1,
                'MailingOptions' => 0,
                'AccomDetails' => array(
                    '_' => 'true',
                    'Resort' => '',
                    'AccomAddress' => 'true',
                    'AccomFacilities' => 'true',
                    'PackageTypes' => 'true',
                    'BoardBasis' => 'true',
                ),
                'Tours' => 1,
            ),
        );

        try {
            $response = $this->client->GetStaticData($params);

            $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
            $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
            if ($response) :
                return $response->GetStaticDataResult;
            else: 
                return false;
            endif;
        } catch (Exception $e) {
            $this->logSOAPRequests("REQUEST ERROR:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
            return false;
        }

        return false;
    }

    /**
     * View Quote
     */
    public function ViewQuote($product_id, $quote_id)
    {
        $response = false;
        $params = array(

            'OTA_ViewQuoteRQ' => array(
                'POS' => array(
                    'Source' => array(
                        'Authentication' => array(
                            'DataBase' => array(
                                '_' => $this->API_DB,
                                'DataBase' => $this->API_DB,
                                'ProductCode' => $product_id,
                                'IP' => 'Web',
                            ),
                        ),
                    ),
                ),

                'TransactionIdentifier' => $quote_id,
                'BasketChecks' => 'false',
            ),
        );

        try {
            $response = $this->client->OTA_ViewQuote($params);
        } catch (Exception $e) {
            //echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        
        $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
        //$this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");

        return $response;
    }

    /**
     *
     * Get Accom data
     *
     */
    public function GetAccomData($order_id, $productID, $travellers, $currency, $resort, $region = false, $country = false, $checkin = false, $checkout = false)
    {
        /**
         * 10 – Adult o 8 – Child o 7 – Infant
         */

        $responseArray = array();

        $refType = array();
        if ($country) :
            $refType['_'] = $country;
            $refType['RefPointType'] = 1;
        elseif ($region) :
            $refType['_'] = $region;
            $refType['RefPointType'] = 3;
        else :
            $refType['_'] = $resort;
            $refType['RefPointType'] = 2;
        endif;

        foreach ($travellers as $roomTravellers) :
            $travellersData = array();
            array_push($travellersData, array(
                'GuestCounts' => array(
                    'GuestCount' => $roomTravellers,
                ),
            ));

            $params = array(
                'OTA_HotelAvailRQ' => array(
                    'POS' => array(
                        'Source' => array(
                            'Authentication' => array(
                                'DataBase' => array(
                                    '_' => $this->API_DB,
                                    'DataBase' => $this->API_DB,
                                    'ProductCode' => $productID,
                                    'IP' => 'Web',
                                    'TraceSessionID' => $order_id,
                                ),
                            ),
                            'ISOCurrency' => $currency,
                        ),
                    ),
                    'AvailRequestSegments' => array(
                        'AvailRequestSegment' => array(
                            'StayDateRange' => array(
                                'Start' => $checkin,
                                'End' => $checkout,
                                /*
                            'DateWindowRange' => array(
                            'WindowBefore' => 3,
                            'WindowAfter' => 3,
                            ),
                             */
                            ),
                            'RoomStayCandidates' => array(
                                'RoomStayCandidate' => $travellersData,
                            ),
                            'HotelSearchCriteria' => array(
                                'Criterion' => array(
                                    'RefPoint' => $refType,
                                ),
                                'AvailableOnlyIndicator' => 'false',
                                'IncludeFacilities' => 'false',
                            ),
                            'HotelSources' => array(
                                'HotelSource' => array(
                                    'Source' => 'Travelink',
                                    'AllowOnRequest' => 'true',
                                ),
                            ),
                        ),
                    ),
                    'Version' => '0',
                    'VerboseLoggingSpecified' => 'false',
                    'UseMemberPricing' => 'true',
                    'RequestedCurrency' => $currency,
                ),

            );

            try {
                $response = $this->client->OTA_HotelAvail($params);
                if ($response && isset($response->OTA_HotelAvailResult->RoomStays)) :
                    array_push($responseArray, $response->OTA_HotelAvailResult->RoomStays->RoomStay);
                else :
                    array_push($responseArray, false);
                endif;
            } catch (Exception $e) {
                array_push($responseArray, false);
            }
            
        //echo "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";
        endforeach;

        return $responseArray;
    }

    /**
     *
     * Get Accom data
     *
     */
    public function GetBedBanksData($order_id, $productID, $travellers, $currency, $resort, $region = false, $country = false, $checkin = false, $checkout = false)
    {
        /**
         * 10 – Adult o 8 – Child o 7 – Infant
         */

        $responseArray = array();

        $refType = array();
        $refType['_'] = $resort;
        $refType['RefPointType'] = 2;

        foreach ($travellers as $roomTravellers) :
            $travellersData = array();
            array_push($travellersData, array(
                'GuestCounts' => array(
                    'GuestCount' => $roomTravellers,
                ),
            ));

            $params = array(
                'OTA_HotelAvailRQ' => array(
                    'POS' => array(
                        'Source' => array(
                            'Authentication' => array(
                                'DataBase' => array(
                                    '_' => $this->API_DB,
                                    'DataBase' => $this->API_DB,
                                    'ProductCode' => $productID,
                                    'IP' => 'Web',
                                    'TraceSessionID' => $order_id,
                                ),
                            ),
                            'ISOCurrency' => $currency,
                        ),
                    ),
                    'AvailRequestSegments' => array(
                        'AvailRequestSegment' => array(
                            'StayDateRange' => array(
                                'Start' => $checkin,
                                'End' => $checkout,
                                /*
                            'DateWindowRange' => array(
                            'WindowBefore' => 3,
                            'WindowAfter' => 3,
                            ),
                             */
                            ),
                            'RoomStayCandidates' => array(
                                'RoomStayCandidate' => $travellersData,
                            ),
                            'HotelSearchCriteria' => array(
                                'Criterion' => array(
                                    'RefPoint' => $refType,
                                ),
                                'AvailableOnlyIndicator' => 'false',
                                'IncludeFacilities' => 'false',
                            ),
                            'HotelSources' => array(
                                'HotelSource' => array(
                                    'Source' => 'ABS',
                                    //'AllowOnRequest' => 'true',
                                ),
                            ),
                        ),
                    ),
                    'Version' => '0',
                    'VerboseLoggingSpecified' => 'false',
                    'UseMemberPricing' => 'true',
                    'RequestedCurrency' => $currency,
                ),

            );

            try {
                $response = $this->client->OTA_HotelAvail($params);
                if ($response && isset($response->OTA_HotelAvailResult->RoomStays)) :
                    array_push($responseArray, $response->OTA_HotelAvailResult->RoomStays->RoomStay);
                else :
                    array_push($responseArray, false);
                endif;
            } catch (Exception $e) {
                array_push($responseArray, false);
            }
            
        //echo "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";
        endforeach;

        return $responseArray;
    }

    /**
     *
     * Get Packages data
     *
     */
    public function GetPackagesData($order_id, $productID, $travellers, $rooms, $currency, $resort, $region = false, $country = false, $checkin = false, $checkout = false)
    {
        /**
         * 10 – Adult o 8 – Child o 7 – Infant
         */

        $responseArray = array();
        $response = false;

        $params = array(
            'ToursSearchRQ' => array(
                'Authentication' => array(
                    'DBName' => $this->API_DB,
                    'IP' => 'Web',
                    'ISOCurrency' => $currency,
                    'ProductCode' => $productID,
                ),
                'SearchCriteria' => array(
                    'StartDate' => $checkin,
                    'EndDate' => $checkout,
                    'TravelRefSummary' => array(
                        'PassengerTypeQuantities' => array(
                            'PassengerTypeQuantity' => $travellers,
                        ),
                    ),
                    'RoomConfigurations' => array(
                        'RoomConfiguration' => $rooms,
                    ),
                    'CruiseRegionId' => '',
                    'CruiseShipId' => '',
                    'AllCountries' => 0,
                    'AllInterests' => 0,
                    'AvailableToursOnly' => 0,
                    'ClearedGuaranteedToOperate' => 0,
                    'DiscountedTours' => 0,
                    'AllTags' => 0,
                    'ValidateAgent' => 0,
                    'APISearch' => 1,
                    'AllowOnlyOnePricingType' => 0,
                ),
            ),

        );

        try {
            $response = $this->client->ToursSearch($params);
            if (isset($response->ToursSearchResult->Tours->Tour)) :
                $response = $response->ToursSearchResult->Tours->Tour;
            else :
                $response = false;
            endif;
        } catch (Exception $e) {
            //print_r($e);
        }
        $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
        $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
        //echo '<pre>';
        //echo "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";
        
        //echo '</pre>';
        return $response;
    }

    /**
     *
     * Get Single Package Components Data
     *
     */
    public function GetPackageData($productID, $currency, $tourID, $tourPriceID, $lofi)
    {

        $response = false;

        $params = array(

            'TourComponentSearchRQ' => array(
                'Authentication' => array(
                    'DBName' => $this->API_DB,
                    'IP' => '127.0.0.1',
                    'ISOCurrency' => $currency,
                    'ProductCode' => $productID,
                ),
                'ValidateAgent' => 'false',
                'Tours' => array(
                    'TourComponentSearchCriteria' => array(
                        'TourPriceDetailID' => $tourPriceID,
                        'TourCode' => $tourID,
                        'LOFI' => $lofi,
                    ),
                ),
            ),

        );

        try {
            $api_response = $this->client->TourComponentSearch($params);
            if ($api_response->TourComponentSearchResult->Tours->Tour) :
                $response = $api_response->TourComponentSearchResult->Tours->Tour;
            else :
                $response = false;
            endif;
        } catch (Exception $e) {
            //print_r($e);
        }
        $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
        $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
        //echo "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";
        return $response;
    }

    /**
     *
     * Get Multiple Packages Components Data
     *
     */
    public function GetMultiplePackagesData($productID, $currency, $tourID, $tourPriceID, $lofi)
    {

        $response = array();

        foreach($tourPriceID as $price_id):
            $params = array(

                'TourComponentSearchRQ' => array(
                    'Authentication' => array(
                        'DBName' => $this->API_DB,
                        'IP' => '127.0.0.1',
                        'ISOCurrency' => $currency,
                        'ProductCode' => $productID,
                    ),
                    'ValidateAgent' => 'false',
                    'Tours' => array(
                        'TourComponentSearchCriteria' => array(
                            'TourPriceDetailID' => $price_id,
                            'TourCode' => $tourID,
                            'LOFI' => $lofi,
                        ),
                    ),
                ),

            );

            try {
                $api_response = $this->client->TourComponentSearch($params);
                if ($api_response->TourComponentSearchResult->Tours->Tour) :
                    $response[] = $api_response->TourComponentSearchResult->Tours->Tour;
                else :
                    $response[] = false;
                endif;
            } catch (Exception $e) {
                //print_r($e);
            }
        endforeach;
        //echo "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";
        return $response;
    }

    public function getEventHotels($eventID)
    {
        $eventCode = get_field('event_code', $eventID);
        if ($eventCode) :
            $params = array(
                'GetStaticDataRQ' => array(
                    'DBName' => $this->API_DB,
                    'Translate' => 'false',
                    'AmendCanxReasons' => 0,
                    'Products' => 0,
                    'Product' => $eventCode,
                    'MailingOptions' => 0,
                    'AccomDetails' => array(
                        '_' => 'true',
                        'Resort' => '',
                        'AccomAddress' => 'true',
                        'AccomFacilities' => 'true',
                        'PackageTypes' => 'true',
                        'BoardBasis' => 'true',
                    ),
                ),
            );

            try {
                $response = $this->client->GetStaticData($params);
                if (isset($response->GetStaticDataResult->Accommodation->Hotel)) :
                    $response = $response->GetStaticDataResult->Accommodation->Hotel;
                else :
                    $response = false;
                endif;
            } catch (Exception $e) {
                $response = false;
            }
            
            return $response;
        else :
            return false;
        endif;
    }

    public function getAirports()
    {
        $params = array(
            'GetStaticDataRQ' => array(
                'DBName' => $this->API_DB,
                'Translate' => 'false',
                'AmendCanxReasons' => 0,
                'MailingOptions' => 0,
                'Products' => 0,
                'Product' => '',
                'Airports' => 1,
            ),
        );

        try {
            $airports = $this->client->GetStaticData($params);
            return $airports->GetStaticDataResult->Airports->Airport;
        } catch (Exception $e) {
            return false;
        }
       
    }

    public function getHotelIDbyCode($code)
    {
        $hotelsPosts = get_posts(array(
            'numberposts' => 1,
            'post_type' => 'hotel',
            'meta_key' => 'hotel_id',
            'meta_value' => $code,
        ));
        if ($code && $hotelsPosts) : //if booking with provided token exists
            $hotelPost = $hotelsPosts[0];
            $hotelID = $hotelPost->ID;
            return $hotelID;
        endif;
        return false;
    }
    public function getEventIDbyCode($code)
    {
        $hotelsPosts = get_posts(array(
            'numberposts' => 1,
            'post_type' => 'event',
            'meta_key' => 'event_code',
            'meta_value' => $code,
        ));
        if ($code) : //if booking with provided token exists
            $hotelPost = $hotelsPosts[0];
            $hotelID = $hotelPost->ID;
            return $hotelID;
        endif;
        return false;
    }

    /**
     *
     * Get Accom dataxf
     *
     */
    public function GetAccomHotelPostID($hotelID)
    {

        $hotelPostID = false;
        $hotelsPosts = get_posts(array(
            'numberposts' => 1,
            'post_type' => 'hotel',
            'meta_key' => 'hotel_id',
            'meta_value' => $hotelID,
        ));

        if ($hotelsPosts) : //if booking with provided token exists
            $hotelPost = $hotelsPosts[0];
            $hotelPostID = $hotelPost->ID;
        endif;

        return $hotelPostID;
    }

    /**
     *
     * Get Soonest future event by hotel id
     *
     */
    public function find_event_by_hotel_id($hotel_id) {
        // Get the current date
        $current_date = date('Ymd');
    
        // Query events with ACF post object field 'event_hotels' containing the hotel ID
        $args = array(
            'post_type' => 'event',
            'posts_per_page' => -1, // Get all events
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'event_hotels', // ACF post object field
                    'value' => '"' . $hotel_id . '"', // Hotel ID
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'event_end_date', // ACF date field for event end date
                    'value' => $current_date,
                    'compare' => '>=', // Find events with end date greater than or equal to the current date
                    'type' => 'DATE',
                ),
            ),
            'orderby' => 'meta_value_num', // Order by event start date
            'meta_key' => 'event_start_date', // ACF date field for event start date
            'order' => 'ASC', // Ascending order
        );
    
        $query = new WP_Query($args);
    
        if ($query->have_posts()) {
            // The soonest future event is the first one in the sorted list
            $event = $query->posts[0];
            return $event;
        } else {
            // No future events found for the hotel
            return null;
        }
    }

    /**
     *
     * Get Package data
     *
     */
    public function GetPackagePostID($packageID)
    {

        $packagePostID = false;
        $packagePosts = get_posts(array(
            'numberposts' => 1,
            'post_type' => 'tour',
            'meta_key' => 'tmcode',
            'meta_value' => $packageID,
        ));

        if ($packagePosts) : //if booking with provided token exists
            $packagePost = $packagePosts[0];
            $packagePostID = $packagePost->ID;
        endif;

        return $packagePostID;
    }

    /**
     *
     * Get Hotel Info
     *
     */
    public function GetHotelInfo($eventCode, $passengers, $hotelID, $checkin = false, $checkout = false)
    {
        //var_dump($client);
        /**
         * 10 – Adult o 8 – Child o 7 – Infant
         */
        $params = array(
            'OTA_HotelInfoRQ' => array(
                'POS' => array(
                    'Source' => array(
                        'Authentication' => array(
                            'DataBase' => array(
                                '_' => $this->API_DB,
                                'IP' => 'IP',
                                'ProductCode' => $eventCode,
                            ),
                        ),
                    ),
                ),
                'Locator' => '<locator><v key="CheckinDate">' . $checkin . ' 00:00:00</v><v key="CaheckoutDate">' . $checkout . ' 00:00:00</v><v key="bb">BB</v><v key="FreeNights">0</v><v key="BBOriginal">BB</v><v key="isOnRequest">False</v><v key="Hcode">' . $hotelID . '</v><v key="IT">HTL</v><v key="Source">TRAVELINK</v></locator>',
            ),
        );

        try {
            $info = $this->client->OTA_HotelInfo($params);
            //echo "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";
            return $info->OTA_HotelInfoResult->HotelDescriptiveContent;
        } catch (Exception $e) {
            return false;
        }

    }

    /**
     *
     * Get BedBankHotel Info
     *
     */
    public function GetBedBankHotelInfo($eventCode, $passengers, $locator, $checkin = false, $checkout = false)
    {
        //var_dump($client);
        /**
         * 10 – Adult o 8 – Child o 7 – Infant
         */
        $params = array(
            'OTA_HotelInfoRQ' => array(
                'POS' => array(
                    'Source' => array(
                        'Authentication' => array(
                            'DataBase' => array(
                                '_' => $this->API_DB,
                                'IP' => 'IP',
                                'ProductCode' => $eventCode,
                            ),
                        ),
                    ),
                ),
                'Locator' => $locator,
            ),
        );

        try {
            $info = $this->client->OTA_HotelInfo($params);
            //echo "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";
            return $info->OTA_HotelInfoResult->HotelDescriptiveContent;
        } catch (Exception $e) {
            return false;
        }
        

    }

    /**
     *
     * Get Accom Errata data
     *
     */
    public function GetAccomErrata($productID, $currency, $checking = '', $checkout = '')
    {

        $params = array(

            'OTA_HotelErrataRQ' => array(
                'POS' => array(
                    'Source' => array(
                        'Authentication' => array(
                            'DataBase' => array(
                                '_' => $this->API_DB,
                                'IP' => 'Web',
                                'ProductCode' => $productID,
                            ),
                        ),
                        '@attributes' => array(
                            'ISOCurrency' => $currency,
                        ),
                    ),
                ),
                'StartDate' => '2023-04-01T00:00:00',
                'EndDate' => '2023-04-08T00:00:00',
                'HUCode' => 'HOT0000497000001',
                '@attributes' => array(
                    'TransactionIdentifier' => '348',
                ),
            ),

        );

        $response = $this->client->OTA_HotelErrata($params);
        return json_encode($response);
    }

    /**
     *
     * Get Accom Supplements
     *
     */
    public function GetAccomSupplements($eventCode, $currency , $passengers, $hotelID, $checkin = false, $checkout = false)
    {
        $params = array(

            'OTA_SupplementsAvailRQ' =>
            array(
                'POS' =>
                array(
                    'Source' =>
                    array(
                        'Authentication' =>
                        array(
                            'DataBase' =>
                            array(
                                '_' => $this->API_DB,
                                'IP' => 'Web',
                                'ProductCode' => $eventCode,
                            ),
                        ),
                        'ISOCurrency' => $currency,
                    ),
                ),
                'SupplementsAvailInfo' =>
                array(
                    'StayDateRange' =>
                    array(
                        '_' => '',
                        'Start' => $checkin,
                        'End' => $checkout,
                    ),
                    'ElementType' => 'Room',
                    'Code' => $hotelID,
                ),
                'TravelerInfoSummary' =>
                array(
                    'TransferTravelerAvail' =>
                    array(
                        'PassengerTypeQuantity' => $passengers,
                    ),
                ),
            ),

        );
        try {
            $info = $this->client->OTA_SupplementsAvail($params);
            //echo "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";
            //print_r($info);
            return $info->OTA_SupplementsAvailResult;
        } catch (Exception $e) {
            //print_r($e);
            //echo "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";
            return false;
        }
    }

    /**
     *
     * Get Extras data
     *
     */
    public function GetExtrasData($productID, $passengers, $currency, $checkin = '', $checkout = '')
    {
        $params = array(
            'OTA_ExtraAvailRQ' => array(
                'RequestedCurrency' => $currency,
                'POS' => array(
                    'Source' => array(
                        'Authentication' => array(
                            'DataBase' => array(
                                '_' => $this->API_DB,
                                'IP' => 'Web',
                                'ProductCode' => $productID,
                            ),
                        ),
                        'ISOCurrency' => $currency,
                    ),
                ),
                'ExtraAvailInfo' => array(
                    'StayDateRange' => array(
                        'Start' => $checkin,
                        'End' => $checkout,
                    ),
                    'Quantity' => '1',
                    'Type' => '',
                    'ShowExtraResultForAllPossibleDates' => 'true',
                    'ShowExtraResultForAllPossibleDurations' => 'false',
                    'InclusiveDates' => 'true',
                ),
                'TravelerInfoSummary' => array(
                    'TransferTravelerAvail' => array(
                        'PassengerTypeQuantity' => $passengers, //$passengers,
                    ),
                ),
            ),
        );

        $response = $this->client->OTA_ExtraAvail($params);
        return $response->OTA_ExtraAvailResult->Extras->Extra;
    }

    public function reserveAccom($quote_id, $product_id, $data)
    {
        $items = array();
        foreach ($data as $room) :
            $travellersArray = array();
            foreach ($room['passengers'] as $traveller) :
                array_push($travellersArray, array(
                    '_' => '',
                    'RPH' => $traveller['ref'],
                ));
            endforeach;

            array_push($items, array(
                'Locator' => $room['locator'],
                'TravelerInformation' => array(
                    'Traveler' => $travellersArray,
                ),
                'RPH' => '1',
            ));
        endforeach;

        $params = array(
            'OTA_AddToQuoteRQ' => array(
                'POS' => array(
                    'Source' => array(
                        'Authentication' => array(
                            'DataBase' => array(
                                '_' => $this->API_DB,
                                'IP' => 'IP',
                                'ProductCode' => $product_id,
                            ),
                        ),
                    ),
                ),
                'Items' => array(
                    'Item' => $items,
                ),
                'TransactionIdentifier' => $quote_id,
            ),

        );
        $response = $this->client->OTA_AddToQuote($params);

        $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
        $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
        //$quote = $this->ViewQuote($product_id, $quote_id);
        //return $quote;
        return $response;
    }
    public function deletePackage(
        $quote_id,
        $product_id,
        $order_id,
        $currency,
        $passengers,
        $tourData,
        $tourAccom,
        $tourExtras,
        $tourTransfers
    ) {
        $passengersArray = [];
        $extras = [];
    
        // Remove existing tour if it exists
        $quote = $this->ViewQuote($product_id, $quote_id);
        $response = false;
        if ($quote && isset($quote->OTA_ViewQuoteResult->Tours->Tour)) {
            $removeToursIDs = [];
            $tourList = is_array($quote->OTA_ViewQuoteResult->Tours->Tour)
                ? $quote->OTA_ViewQuoteResult->Tours->Tour
                : [$quote->OTA_ViewQuoteResult->Tours->Tour];
            
            
    
            foreach ($tourList as $item) {
                $ids = explode('|', $item->ID);
                $removeToursIDs = array_merge($removeToursIDs, $ids);
            }

            if(isset($quote->OTA_ViewQuoteResult->Transfers->Transfer)):
                $transfersList = is_array($quote->OTA_ViewQuoteResult->Transfers->Transfer)
                ? $quote->OTA_ViewQuoteResult->Transfers->Transfer
                : [$quote->OTA_ViewQuoteResult->Transfers->Transfer];
                foreach ($transfersList as $item) {
                    $id = explode('|', $item->ID);
                    $removeToursIDs = array_merge($removeToursIDs, $id);
                }
            endif;
    
            if (!empty($removeToursIDs)) {
                $deleteItems = array_map(
                    function ($item) {
                        return ['_' => '', 'ID' => $item];
                    },
                    $removeToursIDs
                );    
                $deleteRequest = [
                    'OTA_DeleteFromQuoteRQ' => [
                        'POS' => [
                            'Source' => [
                                'Authentication' => [
                                    'DataBase' => [
                                        '_' => $this->API_DB,
                                        'IP' => 'IP',
                                        'ProductCode' => $product_id,
                                    ],
                                    'TraceSessionID' => $order_id,
                                ],
                                'ISOCurrency' => $currency,
                            ],
                        ],
                        'DeletedItems' => ['DeletedItem' => $deleteItems],
                        'TransactionIdentifier' => $quote_id,
                    ],
                ];
    
                try {
                    $response = $this->client->OTA_DeleteFromQuote($deleteRequest);
                    $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
                    $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
                    return $response;
                } catch (Exception $e) {
                    $response = false;
                    $this->logSOAPRequests("ERROR REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
                    $this->logSOAPRequests("ERROR RESPONSE:\n" . $e->getMessage() . "\n");
                    return $response;
                }
            }
        }
    }

    public function reservePackage(
        $quote_id,
        $product_id,
        $order_id,
        $currency,
        $passengers,
        $tourData,
        $tourAccom,
        $tourExtras,
        $tourTransfers
    ) {
        $passengersArray = [];
        $extras = [];
    
        // Remove existing tour if it exists
        $quote = $this->ViewQuote($product_id, $quote_id);
        $response = false;
        if ($quote && isset($quote->OTA_ViewQuoteResult->Tours->Tour)) {
            $removeToursIDs = [];
            $tourList = is_array($quote->OTA_ViewQuoteResult->Tours->Tour)
                ? $quote->OTA_ViewQuoteResult->Tours->Tour
                : [$quote->OTA_ViewQuoteResult->Tours->Tour];
            
            
    
            foreach ($tourList as $item) {
                $ids = explode('|', $item->ID);
                $removeToursIDs = array_merge($removeToursIDs, $ids);
            }

            if(isset($quote->OTA_ViewQuoteResult->Transfers->Transfer)):
                $transfersList = is_array($quote->OTA_ViewQuoteResult->Transfers->Transfer)
                ? $quote->OTA_ViewQuoteResult->Transfers->Transfer
                : [$quote->OTA_ViewQuoteResult->Transfers->Transfer];
                foreach ($transfersList as $item) {
                    $id = explode('|', $item->ID);
                    $removeToursIDs = array_merge($removeToursIDs, $id);
                }
            endif;
    
            if (!empty($removeToursIDs)) {
                $deleteItems = array_map(
                    function ($item) {
                        return ['_' => '', 'ID' => $item];
                    },
                    $removeToursIDs
                );
    
                $deleteRequest = [
                    'OTA_DeleteFromQuoteRQ' => [
                        'POS' => [
                            'Source' => [
                                'Authentication' => [
                                    'DataBase' => [
                                        '_' => $this->API_DB,
                                        'IP' => 'IP',
                                        'ProductCode' => $product_id,
                                    ],
                                    'TraceSessionID' => $order_id,
                                ],
                                'ISOCurrency' => $currency,
                            ],
                        ],
                        'DeletedItems' => ['DeletedItem' => $deleteItems],
                        'TransactionIdentifier' => $quote_id,
                    ],
                ];
    
                try {
                    $response = $this->client->OTA_DeleteFromQuote($deleteRequest);
                    $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
                    $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
                } catch (Exception $e) {
                    $response = false;
                    $this->logSOAPRequests("ERROR REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
                    $this->logSOAPRequests("ERROR RESPONSE:\n" . $e->getMessage() . "\n");
                }
                //echo htmlspecialchars($this->client->__getLastRequest());
                //print_r($response);
            }
        }
    
        // Add new tour
        $roomPassengers = array(); // passengers for each room
        foreach ($passengers as $index => $passenger) {
            $age = intval($passenger['Age']);
            $roomID = intval($passenger['room']);
            $age_code = $age >= 18 ? 'ADT' : 'CHD';
            $passengerArray = [
                '_' => '',
                'PassengerId' => $passenger['ref_number'],
                'Age' => $age,
                'PassengerType' => $age_code,
            ];
            $passengersArray[] = $passengerArray;
            if(!isset($roomPassengers[$roomID])) $roomPassengers[$roomID] = array();
            array_push($roomPassengers[$roomID] , $passengerArray);
        }
    
        $tourItems = array();
        foreach($tourData as $tourIndex => $tourDataSingle):
            $tourItem = [
                'ToursIndId' => $tourDataSingle['ToursIndId'],
                'TourPricingTypeId' => $tourDataSingle['TourPricingTypeId'],
                'TourPriceDetailId' => $tourDataSingle['TourPriceDetailId'],
                'LOFI' => $tourDataSingle['LOFI'],
                'PassengerAllocations' => $roomPassengers[$tourIndex],
            ];
            array_push($tourItems , $tourItem);
        endforeach;
        
        //function to create duplicate of an array
        function copyArray($source){
            $result = array();
        
            foreach($source as $key => $item){
                $result[$key] = (is_array($item) ? copyArray($item) : $item);
            }
        
            return $result;
        }


        if ($tourAccom) {
            foreach($tourAccom as $index => $accom):
                $accomID = $accom['component_id'];
                $accomRoomID = $accom['id'];
                $selectionState = $accom['selection_state'];
                $roomOrderIndex = $accom['room_order_index'];
                $roomTourIndex = $accom['room_tour_index'];
                $roomAllocations = [];
                $passengers = copyArray($roomPassengers[$roomOrderIndex]);
                $roomAllocations[] = [
                    'Passengers' => ['Passenger' =>  $passengers],
                    'RoomNumber' => $accomRoomID,
                ];
                
                if(!isset($tourItems[$roomTourIndex]['Accommodations'])):
                    $tourItems[$roomTourIndex]['Accommodations'] = ['Accommodation' => array()];
                endif;
                $tourItems[$roomTourIndex]['Accommodations']['Accommodation'][] = [
                    'RoomAllocations' => ['RoomAllocation' => $roomAllocations],
                    'ComponentID' => $accomID,
                ];

            endforeach;
        }
    
        if ($tourExtras) {
            foreach($tourData as $tourIndex => $tourDataSingle):
                $extrasArray = [];
                foreach ($tourExtras as $extra) {
                    $extrasPassengers = [];
                    foreach ($extra['travellers'] as $traveller) {
                        $travellerArray = null;
                        $ref_number = $traveller['ref_number'];
                        $tour_id = $traveller['tour_id'];
                        foreach ($passengersArray as $passenger) {
                            if ($passenger['PassengerId'] == $ref_number && $tour_id == $tourIndex) {
                                $travellerArray = $passenger;
                                break;
                            }
                        }
                        if ($travellerArray) {
                            $extrasPassengers[] = $travellerArray;
                        }
                    }
                    if(sizeof($extrasPassengers)):
                        $extraItem = [
                            'PassengerAllocations' => $extrasPassengers,
                            'ComponentID' => $extra['id'],
                            'NBRCode' => $extra['NBRCode'],
                        ];
                        $extrasArray[] = $extraItem;
                    endif;
                }
                $tourItems[$tourIndex]['Extras']['Extra'] = $extrasArray;    
            endforeach;
        }
    
        if ($tourTransfers) {
            foreach($roomPassengers as $tourIndex => $tourPassengers):
                $transfersArray = [];
                foreach ($tourTransfers as $transfer) {
                    $transferPassengers = [];
                    foreach ($transfer['passengers'] as $traveller) {
                        $travellerArray = null;
                        $ref_number = $traveller;
                        foreach ($tourPassengers as $passenger) {
                            if ($passenger['PassengerId'] == $ref_number) {
                                $travellerArray = $passenger;
                                break;
                            }
                        }
                        if ($travellerArray) {
                            $transferPassengers[] = $travellerArray;
                        }
                    }
                    $transferItem = [
                        'PassengerAllocations' => $transferPassengers,
                        'ComponentID' => $transfer['transfer_id'],
                    ];
                    $transfersArray[] = $transferItem;
                }
                $tourItems[$tourIndex]['Transfers']['Transfer'] = $transfersArray;
            endforeach;
        }    

        $tourRequestItems = [];
        foreach($tourItems as $item):
            $tourRequestItems[] =  [
                'RPH' => '1',
                'AddTour' => $item,
            ];
        endforeach;
        
        $addToQuoteRequest = [
            'OTA_AddToQuoteRQ' => [
                'POS' => [
                    'Source' => [
                        'Authentication' => [
                            'DataBase' => [
                                '_' => $this->API_DB,
                                'IP' => 'IP',
                                'ProductCode' => $product_id,
                            ],
                        ],
                    ],
                ],
                'Items' => [
                    'Item' => $tourRequestItems,
                ],
                'TransactionIdentifier' => $quote_id,
            ],
        ];
    
        //print_r($addToQuoteRequest);
        $result = false;
       
        try {
            $response = $this->client->OTA_AddToQuote($addToQuoteRequest);
            $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
            $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
            $result = true;
        } catch (Exception $e) {
            $this->logSOAPRequests("ERROR REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
            $this->logSOAPRequests("ERROR RESPONSE:\n" . $e->getMessage() . "\n");
            //echo 'Caught exception: ', $e->getMessage(), "\n";
        }
       
        //echo  "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";
        //echo '<hr>';
        //print_r($addToQuoteRequest);
        return $result;
    }

    public function reserveExtra($quote_id, $product_id, $data)
    {
        $travellersArray = array();
        foreach ($data['travellers'] as $traveller) {
            if ($traveller['selected'] == 1) {
                array_push($travellersArray, array(
                    '_' => '',
                    'RPH' => $traveller['ref_number'],
                ));
            }
        }

        $items = array(
            array(
                'Locator' => $data['locator'],
                'TravelerInformation' => array(
                    'Traveler' => $travellersArray,
                ),
                'RPH' => '1',
            ),
        );

        $params = array(
            'OTA_AddToQuoteRQ' => array(
                'POS' => array(
                    'Source' => array(
                        'Authentication' => array(
                            'DataBase' => array(
                                '_' => $this->API_DB,
                                'IP' => 'IP',
                                'ProductCode' => $product_id,
                            ),
                        ),
                    ),
                ),
                'Items' => array(
                    'Item' => $items,
                ),
                'TransactionIdentifier' => $quote_id,
            ),

        );
        try {
            $response = $this->client->OTA_AddToQuote($params);
            $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
            $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
        } catch (Exception $e) {
            //echo 'Caught exception: ', $e->getMessage(), "\n";
            $this->logSOAPRequests("ERROR REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
            $this->logSOAPRequests("ERROR RESPONSE:\n" . $e->getMessage() . "\n");
        }
        return $response;
    }

    public function reserveExtrasArray($quote_id, $product_id, $data)
    {
        $items = array();
        foreach($data as $extra):
            $travellersArray = array();
            foreach ($extra['travellers'] as $traveller) {
                if ($traveller['selected'] == 1) {
                    array_push($travellersArray, array(
                        '_' => '',
                        'RPH' => $traveller['ref_number'],
                    ));
                }
            }
            array_push($items , array(
                'Locator' => $extra['locator'],
                'TravelerInformation' => array(
                    'Traveler' => $travellersArray,
                ),
                'RPH' => '1',
            ));
        endforeach;
        
        $params = array(
            'OTA_AddToQuoteRQ' => array(
                'POS' => array(
                    'Source' => array(
                        'Authentication' => array(
                            'DataBase' => array(
                                '_' => $this->API_DB,
                                'IP' => 'IP',
                                'ProductCode' => $product_id,
                            ),
                        ),
                    ),
                ),
                'Items' => array(
                    'Item' => $items,
                ),
                'TransactionIdentifier' => $quote_id,
            ),

        );
        try {
            $response = $this->client->OTA_AddToQuote($params);
            $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
            $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
        } catch (Exception $e) {
            $this->logSOAPRequests("ERROR REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
            $this->logSOAPRequests("ERROR RESPONSE:\n" . $e->getMessage() . "\n");
        }
        return $response;
    }

    /**
     *
     * Remove Accom From Quote
     *
     */
    public function removeAllAccom($order_id, $quote_id, $product_id, $data, $currency)
    {
        $quote = $this->ViewQuote($product_id, $quote_id);
        //return $quote;
        $response = false;
        if ($quote && isset($quote->OTA_ViewQuoteResult->Hotels->Hotel)) :
            $removeHotelIDs = array();
            if (is_array($quote->OTA_ViewQuoteResult->Hotels->Hotel)) :
                foreach ($quote->OTA_ViewQuoteResult->Hotels->Hotel as $item) :
                    $ids = explode('|', $item->ID);
                    foreach ($ids as $id) {
                        array_push($removeHotelIDs, $id);
                    }

                endforeach;
            else :
                $ids = explode('|', $quote->OTA_ViewQuoteResult->Hotels->Hotel->ID);
                foreach ($ids as $id) {
                    array_push($removeHotelIDs, $id);
                }
            endif;

            if (sizeof($removeHotelIDs)) :
                $deleteItems = array();
                foreach ($removeHotelIDs as $item) :
                    array_push($deleteItems, array(
                        '_' => '',
                        'ID' => $item,
                    ));
                endforeach;

                $params = array(

                    'OTA_DeleteFromQuoteRQ' => array(
                        'POS' => array(
                            'Source' => array(
                                'Authentication' => array(
                                    'DataBase' => array(
                                        '_' => $this->API_DB,
                                        'IP' => 'IP',
                                        'ProductCode' => $product_id,
                                    ),
                                    'TraceSessionID' => $order_id,
                                ),
                                'ISOCurrency' => $currency,
                            ),
                        ),
                        'DeletedItems' => array(
                            'DeletedItem' => $deleteItems,
                        ),
                        'TransactionIdentifier' => $quote_id,
                    ),

                );
                try {
                    $response = $this->client->OTA_DeleteFromQuote($params);
                    $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
                    $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
                } catch (Exception $e) {
                    $response = false;
                    $this->logSOAPRequests("ERROR REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
                    $this->logSOAPRequests("ERROR RESPONSE:\n" . $e->getMessage() . "\n");
                }
            endif;
        endif;
        return $response;
    }

    /**
     *
     * Remove Accom From Quote
     *
     */
    public function removeSingleAccom($order_id, $quote_id, $product_id, $data, $currency)
    {
        $quote = $this->ViewQuote($product_id, $quote_id);
        //return $quote;
        $response = false;
        if ($quote && isset($quote->OTA_ViewQuoteResult->Hotels->Hotel)) :
            $removeHotelIDs = array();
            $roomIndex = 0;
            if (is_array($quote->OTA_ViewQuoteResult->Hotels->Hotel)) :
                foreach ($quote->OTA_ViewQuoteResult->Hotels->Hotel as $item) :
                    if (is_array($item->RoomStays->RoomStay)) :
                        foreach ($item->RoomStays->RoomStay as $room) :
                            if (($room->HUCode == $data['id'] || $room->RoomType == $data['name']) || $roomIndex == intval($data['index'])) :
                                array_push($removeHotelIDs, $room->ID);
                                break;
                            endif;
                            $roomIndex++;
                        endforeach;
                    else :
                        if (($item->RoomStays->RoomStay->HUCode == $data['id'] || $item->RoomStays->RoomStay->RoomType == $data['name']) || $roomIndex == intval($data['index'])) :
                            array_push($removeHotelIDs, $item->RoomStays->RoomStay->ID);
                        endif;
                        $roomIndex++;
                    endif;
                endforeach;
            else :
                if (is_array($quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay)) :
                    foreach ($quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay as $room) :
                        if (($room->HUCode == $data['id'] || $room->RoomType == $data['name']) || $roomIndex == intval($data['index'])) :
                            array_push($removeHotelIDs, $room->ID);
                            break;
                        endif;
                        $roomIndex++;
                    endforeach;
                else :
                    if ((isset($quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay->HUCode) && $quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay->HUCode == $data['id'] || $quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay->RoomType == $data['name']) || $roomIndex == intval($data['index'])) :
                        array_push($removeHotelIDs, $quote->OTA_ViewQuoteResult->Hotels->Hotel->RoomStays->RoomStay->ID);
                        $roomIndex++;
                    endif;
                endif;
            endif;

            if (sizeof($removeHotelIDs)) :
                $deleteItems = array();
                foreach ($removeHotelIDs as $item) :
                    array_push($deleteItems, array(
                        '_' => '',
                        'ID' => $item,
                    ));
                endforeach;

                $params = array(

                    'OTA_DeleteFromQuoteRQ' => array(
                        'POS' => array(
                            'Source' => array(
                                'Authentication' => array(
                                    'DataBase' => array(
                                        '_' => $this->API_DB,
                                        'IP' => 'IP',
                                        'ProductCode' => $product_id,
                                    ),
                                    'TraceSessionID' => $order_id,
                                ),
                                'ISOCurrency' => $currency,
                            ),
                        ),
                        'DeletedItems' => array(
                            'DeletedItem' => $deleteItems,
                        ),
                        'TransactionIdentifier' => $quote_id,
                    ),

                );
                try {
                    $response = $this->client->OTA_DeleteFromQuote($params);
                    $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
                    $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
                } catch (Exception $e) {
                    $response = false;
                    $this->logSOAPRequests("ERROR REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
                    $this->logSOAPRequests("ERROR RESPONSE:\n" . $e->getMessage() . "\n");
                }
            endif;
        endif;
        return $response;
    }

    /**
     *
     * Remove Extra From Quote
     *
     */
    public function removeExtra($order_id, $quote_id, $product_id, $data, $currency)
    {
        $quote = $this->ViewQuote($product_id, $quote_id);
        $response = false;
        if ($quote) :

            $removeExtraID = false;
            if (is_array($quote->OTA_ViewQuoteResult->Extras->Extra)) :
                foreach ($quote->OTA_ViewQuoteResult->Extras->Extra as $item) :
                    if ($item->Description == $data['name']) {
                        $removeExtraID = $item->ID;
                    }
                endforeach;
            else :
                $removeExtraID = $quote->OTA_ViewQuoteResult->Extras->Extra->ID;
            endif;

            if ($removeExtraID) :

                $params = array(

                    'OTA_DeleteFromQuoteRQ' => array(
                        'POS' => array(
                            'Source' => array(
                                'Authentication' => array(
                                    'DataBase' => array(
                                        '_' => $this->API_DB,
                                        'IP' => 'IP',
                                        'ProductCode' => $product_id,
                                    ),
                                    'TraceSessionID' => $order_id,
                                ),
                                'ISOCurrency' => $currency,
                            ),
                        ),
                        'DeletedItems' => array(
                            'DeletedItem' => array(
                                '_' => '',
                                'ID' => $removeExtraID,
                            ),
                        ),
                        'TransactionIdentifier' => $quote_id,
                    ),

                );
                try {
                    $response = $this->client->OTA_DeleteFromQuote($params);
                    $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
                    $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
                } catch (Exception $e) {
                    $response = false;
                    $this->logSOAPRequests("ERROR REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
                    $this->logSOAPRequests("ERROR RESPONSE:\n" . $e->getMessage() . "\n");
                }
            endif;
        endif;
        return $response;
    }

    /**
     *
     * Remove Extra From Quote
     *
     */
    public function removeExtrasArray($order_id, $quote_id, $product_id, $currency)
    {
        $quote = $this->ViewQuote($product_id, $quote_id);
        $response = false;
        if ($quote) :

            $removeExtraIDs = array();
            if(isset($quote->OTA_ViewQuoteResult->Extras->Extra)):
                if (is_array($quote->OTA_ViewQuoteResult->Extras->Extra)) :
                    foreach ($quote->OTA_ViewQuoteResult->Extras->Extra as $item) :
                        if(strtolower($item->Type) != 'accom supplement'):
                            array_push($removeExtraIDs , $item->ID);
                        endif;
                    endforeach;
                else :
                    if(strtolower($quote->OTA_ViewQuoteResult->Extras->Extra->Type) != 'accom supplement'):
                        array_push($removeExtraIDs , $quote->OTA_ViewQuoteResult->Extras->Extra->ID);
                    endif;
                endif;
            endif;

            if (sizeof($removeExtraIDs)) :

                $deleteItemsArray = array();
                foreach($removeExtraIDs as $itemID):
                    array_push($deleteItemsArray , array(
                        '_' => '',
                        'ID' => $itemID,
                    ));
                endforeach;

                $params = array(

                    'OTA_DeleteFromQuoteRQ' => array(
                        'POS' => array(
                            'Source' => array(
                                'Authentication' => array(
                                    'DataBase' => array(
                                        '_' => $this->API_DB,
                                        'IP' => 'IP',
                                        'ProductCode' => $product_id,
                                    ),
                                    'TraceSessionID' => $order_id,
                                ),
                                'ISOCurrency' => $currency,
                            ),
                        ),
                        'DeletedItems' => array(
                            'DeletedItem' => $deleteItemsArray,
                        ),
                        'TransactionIdentifier' => $quote_id,
                    ),

                );
                try {
                    $response = $this->client->OTA_DeleteFromQuote($params);
                    $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
                    $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
                } catch (Exception $e) {
                    $response = false;
                    $this->logSOAPRequests("ERROR REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
                    $this->logSOAPRequests("ERROR RESPONSE:\n" . $e->getMessage() . "\n");
                }
            endif;
        endif;
        return $response;
    }

    /**
     *
     * Get Flights data
     *
     */
    public function GetFlightsData($productID, $passengers, $checkin = '', $checkout = '')
    {
        $params = array(
            'OTA_AirLowFareSearchRQ' => array(
                'POS' => array(
                    'Source' => array(
                        'Authentication' => array(
                            'DataBase' => array(
                                '_' => $this->API_DB,
                                'IP' => 'IP',
                                'ProductCode' => 'N6',
                            ),
                        ),
                    ),
                ),
                'OriginDestinationInformation' => array(
                    'DepartureDateTime' => '2023-03-01T00:00:00',
                    'OriginLocation' => array(
                        '_' => '',
                        'LocationCode' => 'LHR',
                    ),
                    'DestinationLocation' => array(
                        '_' => '',
                        'LocationCode' => 'FRA',
                    ),
                    'TPA_Extensions' => array(
                        'FareTypes' => array(
                            '_' => '',
                            'Charter' => 'false',
                            'Cat35' => 'true',
                            'Published' => 'true',
                            'IT' => 'true',
                            'SO' => 'true',
                            'Nett' => 'false',
                            'Bulk' => 'false',
                        ),
                        'FlightSources' => array(
                            '_' => '',
                            'CTCFlights' => 'true',
                            'MulticomFlights' => 'false',
                        ),
                    ),
                ),
                'TravelPreferences' => array(
                    'FlightTypePref' => array(
                        '_' => '',
                        'DirectAndNonStopOnlyInd' => 'false',
                    ),
                    'FareRestrictPref' => array(
                        '_' => '',
                        'FareDisplayCurrency' => 'GBP',
                    ),
                ),
                'TravelerInfoSummary' => array(
                    'AirTravelerAvail' => array(
                        'PassengerTypeQuantity' => array(
                            '_' => '',
                            'Code' => '10',
                            'Quantity' => '2',
                        ),
                    ),
                ),

                'Target' => 'Production',
                'Version' => '0',
                'DirectFlightsOnly' => 'false',
                'CacheMode' => 'NoCache',
                'SortMode' => 'PriceAsc',
                'AllowMultiCarriersFlights' => 'true',
                'IncludeOvernightFlights' => 'true',
                'IncludeOvernightLayovers' => 'true',
            ),

        );

        $response = $this->client->OTA_AirLowFareSearch($params);
        $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
        $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
        return $response->OTA_ExtraAvailResult->Extras->Extra;
    }

    /**
     * Add Comment
     */
    public function AddComment($quote_id, $product_id, $comment)
    {
        //echo $quote_id.' - '.$product_id.' - '.$comment;
        $params = array(
            'QuoteManagementRQ' => array(
                'Authentication' => array(
                    'DBName' => $this->API_DB,
                    'IP' => 'IP',
                    'ISOCurrency' => 'GBP',
                    'ProductCode' => $product_id,
                ),
                'UpdateQuoteRQ' => array(
                    'SessionId' => $quote_id,
                    'Comments' => array(
                        'Comment' => array(
                            array(
                                'ID' => 0,
                                'Type' => 'clientComment',
                                'Comment' => array(
                                    '_' => $comment,
                                ),
                                'ShowOnDocs' => true,
                                'Supplier' => 'Mr',
                            ),
                        ),
                    ),
                    'ExportItinerary' => false,
                ),
            ),
        );
        try {
            $response = $this->client->QuoteManagement($params);
            $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
            $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
        } catch (Exception $e) {
            //echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        //echo "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";

        return $response;
    }

    /**
     * Create Client
     */
    public function CreateClient($data, $wp_user_id)
    {
        $response = array();
        $response['response'] = '';
        $response['request'] = '';
        $response['error'] = '';
        $response['error_registration'] = false;
        $response['clientID'] = false;
        $response['clientSearch'] = false;
        $response['clientSearchResult'] = false;

        $clientFromAPI = $this->SearchClientByEmail($data['email']);
        $response['clientSearchResult'] = $clientFromAPI;
        
        $updateClientInRes = false;
        if ($clientFromAPI) :

            if ($clientFromAPI['id'] == 1 && !$clientFromAPI['client']) {
                $clientFromAPI['id'] = 0;
                $updateClientInRes = true;
            }
            if ($clientFromAPI['id'] > 1) $clientFromAPI['id'] = 2;

            //print_r($clientFromAPI);
            switch ($clientFromAPI['id']):
                case 2: //duplicates in API - output message
                    $response['error_registration'] = 'Please contact us (duplicate accounts)';
                    $response['clientID'] = false;
                    $response['clientSearch'] = 0;
                    break;
                case 1: //client Exists
                    $response['clientID'] = $clientFromAPI['client'];
                    $response['clientSearch'] = 1;
                    break;
                case 0: //No client exists
                    $response['clientSearch'] = 2;
                    $params = array(
                        'ClientManagementRQ' => array(
                            'Authentication' => array(
                                'DBName' => $this->API_DB,
                                'IP' => 'IP',
                                'ISOCurrency' => 'GBP',
                            ),
                            'CreateClientsRQ' => array(
                                'Clients' => array(
                                    'Client' => array(
                                        'Status' => '1',
                                        'FirstName' => $data['first-name'],
                                        'LastName' => $data['last-name'],
                                        'Title' => 'Mr',
                                        'ConcatenatdName' => $data['first-name'] . ' ' . $data['last-name'],
                                        'DateCreated' => date('c'),
                                        'DOB' => '01/01/1900',
                                        'Gender' => 'u',
                                        'NoMiddleName' => 'True',
                                        'LastUpdated' => date('c'),
                                        'Blacklisted' => 'false',
                                        'Deceased' => 'false',
                                        'Add1' => $data['address-1'],
                                        'Add2' => $data['address-2'],
                                        'TownCity' => $data['town'],
                                        'Country' => $data['country'],
                                        'PostCode' => $data['postcode'],
                                        'Telephone' => $data['phone'],
                                        'Commission1' => '',
                                        'Commission2' => '',
                                        'CommType' => '',
                                        'BookingCount' => '',
                                        'ContactDetailsLastUpdated' => date('c'),
                                        'AllowAgentToSellInsurance' => '',
                                        'ActiveMember' => '',
                                        'AgentPermission' => '',
                                        'ExternalClientId' => $wp_user_id,
                                        'Contacts' => array(
                                            'Contact' => array(
                                                array(
                                                    'ContactId' => '?',
                                                    'FormalDescription' => 'Mobile',
                                                    'TelephoneNumber' => $data['phone'],
                                                    'Default' => true,
                                                    'ContactOrder' => 0,
                                                ),
                                                array(
                                                    'ContactId' => '?',
                                                    'FormalDescription' => 'E-mail',
                                                    'TelephoneNumber' => $data['email'],
                                                    'Default' => true,
                                                    'ContactOrder' => 0,
                                                ),
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    );
                    try {
                        $response['response'] = $this->client->ClientManagement($params);
                        $response['request'] = "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest());
                        $response['error'] = '';
                        $response['clientID'] = false;
                        if (isset($response['response']->ClientManagementResult->CreateClientsRS->Clients->Client)) :
                            $response['clientID'] = $response['response']->ClientManagementResult->CreateClientsRS->Clients->Client->ID;
                            if ($updateClientInRes) :
                                $this->UpdateClientIDfromRES($data['email'], $response['clientID']);
                            endif;
                        endif;
                    } catch (Exception $e) {
                        $response['request'] = "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest());
                        $response['response'] = '';
                        $response['error'] = "ERROR:\n" . $e->getMessage();
                        $response['clientID'] = false;
                    }
                    break;
            endswitch;
        else :
            $response['error_registration'] = 'Failed API request';
        endif;

        return $response;
    }

    /**
     * Search Client By Email
     */
    public function SearchClientByEmail($client_email)
    {
        $token_dev = '030c035e-24be-4148-872d-6239393753d9';
        $token_live = '9fec7d7b-1c2a-4f7a-a96e-091fe96b8a42';

        $base_url_dev = 'https://uat-nirvana-crm.hosting.inspiretec.com/api/external/clientsearch?token=' . $token_dev . '&createddate=2019-07-10::';
        $base_url_live = 'https://live-nirvana-crm.hosting.inspiretec.com/api/external/clientsearch?token=' . $token_live . '&createddate=2019-07-10::';

        $base_url = '';
        if (get_field('api_mode', 'option') == 'live') :
            $base_url = $base_url_live;
        else :
            $base_url = $base_url_dev;
        endif;

        $base_url .= '&email=' . $client_email;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $base_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "content-type: application/json",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);


        if ($err) {
            return false;
        } else {
            $response = json_decode($response, true);
            $result = array();
            $resultID = $response['_meta']['results'];
            $result['response_full'] = $response;
            $result['id'] = intval($resultID);
            switch ($resultID):
                case 0:

                    break;
                case 1:
                    $result['response'] = $response['results'][0];
                    $result['client'] = $response['results'][0]['customFields']['tlClientRef']['value'];
                    break;
                case 2:

                    break;
            endswitch;

            return $result;
        }
    }

    /**
     * Update Client By Email
     */
    public function UpdateClientIDfromRES($client_email, $clientID)
    {
        $token_dev = '030c035e-24be-4148-872d-6239393753d9';
        $token_live = '9fec7d7b-1c2a-4f7a-a96e-091fe96b8a42';

        $base_url_dev = 'https://uat-nirvana-crm.hosting.inspiretec.com/api/external/clientsearch?token=' . $token_dev . '&createddate=2019-07-10::';
        $base_url_live = 'https://live-nirvana-crm.hosting.inspiretec.com/api/external/clientsearch?token=' . $token_live . '&createddate=2019-07-10::';

        $update_url_dev = 'https://uat-nirvana-crm.hosting.inspiretec.com/api/external/clients/update';
        $update_url_live = 'https://live-nirvana-crm.hosting.inspiretec.com/api/external/clients/update';


        $base_url = '';
        $update_url = '';
        if (get_field('api_mode', 'option') == 'live') :
            $base_url = $base_url_live;
            $update_url = $update_url_live;
        else :
            $base_url = $base_url_dev;
            $update_url = $update_url_dev;
        endif;

        $base_url .= '&email=' . $client_email;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $base_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "content-type: application/json",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);



        if ($err) {
            return false;
        } else {
            $response = json_decode($response, true);

            $result = array();
            $resultID = $response['_meta']['results'];
            $result['id'] = $resultID;
            $resultID = 1;
            switch ($resultID):
                case 0:

                    break;
                case 1:
                    $client = $response['results'];
                    if (is_array($client)) :
                        for ($i = 0; $i < sizeof($client); $i++) :
                            $client[$i]['customFields']['tlClientRef']['value'] = $clientID;
                        endfor;
                    endif;
                    $clientJson = json_encode($client);
                    $clientJson = substr($clientJson, 1, -1);
                    $clientJson = str_replace("[]", "{}", $clientJson);
                    $clientJson = str_replace('"associatedVisitors":{}', '"associatedVisitors":[]', $clientJson);
                    $clientJson = str_replace('"assignees":{}', '"assignees":[]', $clientJson);
                    $clientJson = str_replace('"fax":{}', '"fax":[]', $clientJson);

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $update_url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $clientJson,
                        CURLOPT_HTTPHEADER => array(
                            'X-Access-Token: 84b0e88f-982d-41d3-917f-4fffd83feb33',
                            'Content-Type: application/json',
                            'Cookie: CF_AppSession=n36e35d7fedd497ab'
                        ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);

                    break;
                case 2:

                    break;
            endswitch;
            return $result;
        }
    }
    

    /**
     * Search Client By ID
     */
    public function SearchClient($client_id)
    {
        $params = array(

            'ClientManagementRQ' => array(
                'Authentication' => array(
                    'DBName' => $this->API_DB,
                    'IP' => '0.0.0.0',
                    'ISOCurrency' => 'GBP',
                ),
                'SearchRQ' => array(
                    'ExternalClientId' => $client_id,
                    'SearchType' => 'ExactMatch',
                    'ShowSuppressed' => '',
                    'Status' => '',
                ),

            ),

        );
        try {
            $response = $this->client->ClientManagement($params);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

        return $response;
    }

    /**
     * Create Payment Preauth
     */
    public function PaymentPreauth($order_id, $quote_id, $client_id, $product_id, $billing_info, $price, $currency)
    {
        $redirect_url = get_field('api_confirmation_page', 'option');
        $params = array(
            'request' => array(
                'Authentication' => array(
                    'DBName' => $this->API_DB,
                    'IP' => 'IP',
                    'ISOCurrency' => $currency,
                    'ProductCode' => $product_id,
                    'TraceSessionID' => $order_id,
                    'ClientID' => $client_id,
                ),
                'CreatePreAuth' => array(
                    'PaymentCharge' => array(
                        'CardType' => 'visa',
                        'CurrencyCode' => $currency,
                        'Amount' => $price,
                        'Surcharge' => '0.00',
                        'SurchargeType' => 'UNKNOWN',
                        'ProductCode' => $product_id,
                    ),
                    'PaymentCardCapture' => array(
                        'Browser' => array(
                            'AcceptHeaders' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,/;q=0.8,application/signed-exchange;v=b3;q=0.9',
                            'DeviceCategory' => 'VALUE_0',
                            'UserAgent' => $_SERVER['HTTP_USER_AGENT'],
                        ),
                        'CardHolder' => array(
                            'Address' => array(
                                'AddressLine' => $billing_info['address-1'] . ' ' . $billing_info['address-2'],
                                'CityName' => $billing_info['town'],
                                'PostalCode' => $billing_info['postcode'],
                                'County' => $billing_info['country'],
                                'CountryName' => array(
                                    '_' => '',
                                    'Code' => 'GB',
                                ),
                            ),
                        ),
                        'ExpiredURL' => $redirect_url,
                        'NotificationURL' => $redirect_url . '?order=' . $order_id,
                        'CaptureMethod' => 'ECOMM',
                    ),
                    'QuoteReference' => $quote_id,
                    'IPAddress' => '127.0.0.1',
                ),
            ),
        );
        try {
            $response = $this->client->PaymentServiceManagement($params);
            $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
            $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
            //print_r($response);
        } catch (Exception $e) {
            $this->logSOAPRequests("ERROR REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
            $this->logSOAPRequests("ERROR RESPONSE:\n" . $e->getMessage() . "\n");
        }
        //echo "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";
        return $response;
    }

    /**
     * Create Payment Preauth for Booking
     */
    public function PaymentPreauthBooking($order_id, $quote_id, $client_id, $product_id, $billing_info, $price, $currency, $bookingID)
    {
        $redirect_url = get_field('api_confirmation_page', 'option');
        $params = array(
            'request' => array(
                'Authentication' => array(
                    'DBName' => $this->API_DB,
                    'IP' => 'IP',
                    'ISOCurrency' => $currency,
                    'ProductCode' => $product_id,
                    'TraceSessionID' => $order_id,
                    'ClientID' => $client_id,
                ),
                'CreatePreAuth' => array(
                    'PaymentCharge' => array(
                        'CardType' => 'visa',
                        'CurrencyCode' => $currency,
                        'Amount' => $price,
                        'Surcharge' => '0.00',
                        'SurchargeType' => 'UNKNOWN',
                        'ProductCode' => $product_id,
                    ),
                    'PaymentCardCapture' => array(
                        'Browser' => array(
                            'AcceptHeaders' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,/;q=0.8,application/signed-exchange;v=b3;q=0.9',
                            'DeviceCategory' => 'VALUE_0',
                            'UserAgent' => $_SERVER['HTTP_USER_AGENT'],
                        ),
                        'CardHolder' => array(
                            'Address' => array(
                                'AddressLine' => $billing_info['address-1'] . ' ' . $billing_info['address-2'],
                                'CityName' => $billing_info['town'],
                                'PostalCode' => $billing_info['postcode'],
                                'County' => $billing_info['country'],
                                'CountryName' => array(
                                    '_' => '',
                                    'Code' => 'GB',
                                ),
                            ),
                        ),
                        'ExpiredURL' => $redirect_url,
                        'NotificationURL' => $redirect_url . '?order=' . $order_id,
                        'CaptureMethod' => 'ECOMM',
                    ),
                    'BookingReference' => $bookingID,
                    'QuoteReference' => $quote_id,
                    'IPAddress' => '127.0.0.1',
                ),
            ),
        );
        try {
            $response = $this->client->PaymentServiceManagement($params);
            $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
            $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
        } catch (Exception $e) {
            $this->logSOAPRequests("ERROR REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
            $this->logSOAPRequests("ERROR RESPONSE:\n" . $e->getMessage() . "\n");
            //echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        //echo "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";
        //echo '<hr>';
        //print_r($response);
        return $response;
    }

    /**
     * Get Transaction Status
     */
    public function getTransactionStatus($order_id, $client_id, $product_id, $transaction_id, $currency)
    {
        $response = false;
        $params = array(
            'request' => array(
                'Authentication' => array(
                    'DBName' => $this->API_DB,
                    'IP' => 'IP',
                    'ISOCurrency' => $currency,
                    'ProductCode' => $product_id,
                    'TraceSessionID' => $order_id,
                    'ClientID' => $client_id,
                ),
                'GetTransactionStatus' => array(
                    'Guid' => $transaction_id,
                    'RequestType' => 'PreAuth',
                    'AllowReferral' => '',
                ),
            ),
        );
        try {
            $response = $this->client->PaymentServiceManagement($params);
            $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
            $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
        } catch (Exception $e) {
            $this->logSOAPRequests("ERROR REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
            $this->logSOAPRequests("ERROR RESPONSE:\n" . $e->getMessage() . "\n");
            //echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        //echo "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";
        //echo '<hr>';
        //print_r($response);
        //echo '<hr>';
        return $response;
    }

    /**
     * Create Booking
     */
    public function createBooking($order_id, $quote_id, $client_id, $product_id, $transaction_id, $price, $currency, $orderPostID)
    {
        $response = false;
        $params = array(
            'OTA_BookingRQ' => array(
                'POS' => array(
                    'Source' => array(
                        'Authentication' => array(
                            'DataBase' => array(
                                '_' => $this->API_DB,
                                'IP' => 'IP',
                                'ProductCode' => $product_id,
                            ),
                            'TraceSessionID' => $order_id,
                            'ClientID' => $client_id,
                        ),
                    ),
                    'RequestOrigin' => 'WEB',
                ),
                'PaymentDetails' => array(
                    'PaymentDetail' => array(
                        'TakeFinalBalance' => 'true',
                        'PaymentCard' => array(
                            '_' => '',
                            'CardCode' => 'Card Payment',
                        ),
                        'PaymentAmount' => array(
                            '_' => '',
                            'Amount' => $price,
                            'PayerAuthRequestID' => $transaction_id,
                            'CurrencyCode' => $currency,
                        ),
                    ),
                ),
                'MembershipFailure' => '',
                'TransactionIdentifier' => $quote_id,
            ),

        );
        try {
            $response = $this->client->OTA_Booking($params);
            $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
            $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
        } catch (Exception $e) {
            $this->logSOAPRequests("ERROR REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
            $this->logSOAPRequests("ERROR RESPONSE:\n" . $e->getMessage() . "\n");
        }

        update_post_meta($orderPostID, 'booking_creation_request', htmlspecialchars($this->client->__getLastRequest()));

        //echo "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";
        //print_r($response);
        return $response;
    }

    /**
     * Authorize Booking Payment
     */
    public function authorizeBookingPayment($booking_id, $transaction_id, $price, $payment_type = "Full payment")
    {
        $response = false;
        if ($price) :
            $params = array(
                'BookingManagementRQ' => array(
                    'Authentication' => array(
                        'DBName' => $this->API_DB,
                    ),
                    'BookingReference' => $booking_id,
                    'PaymentRQ' => array(
                        'PaymentType' => 'Visa Credit',
                        'PaymentAmount' => $price,
                        'transactionId' => $transaction_id,
                        'Comment' => $payment_type,
                        'StoreConsent' => '',
                        'TakeFinalBalance' => 'true',
                    ),
                    'ExportItinerary' => '',
                ),

            );
            try {
                $response = $this->client->BookingManagement($params);
                $this->logSOAPRequests("REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
                $this->logSOAPRequests("RESPONSE:\n" . print_r($response , true) . "\n");
            } catch (Exception $e) {
                $this->logSOAPRequests("ERROR REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n");
                $this->logSOAPRequests("ERROR RESPONSE:\n" . $e->getMessage() . "\n");
            }
        //echo "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";
        //echo '<hr>';
        //print_r($response);
        //echo '<hr>';
        endif;
        return $response;
    }

    /**
     * Get Booking Data
     */
    public function GetBookingData($booking_id)
    {
        $response = false;
        $params = array(
            'BookingManagementRQ' => array(
                'Authentication' => array(
                    'DBName' => $this->API_DB,
                ),
                'BookingReference' => $booking_id,
                'ExportItinerary' => '',
            ),

        );
        try {
            $response = $this->client->BookingManagement($params);
        } catch (Exception $e) {
            //echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        //echo "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";
        return $response;
    }

    /**
     * Get currency symbol by code
     */
    public function getCurrencySymbolByCode($code = 'GPB')
    {
        $code = strtoupper($code);
        switch ($code):
            case 'GBP':
                return '£';
                break;
            case 'GPB':
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

    /**
     * Download Documnt
     */
    public function downloadDoc($type, $event, $booking)
    {
        $response = false;
        $params = array(
            'GetDocumentRQ' => array(

                'Authentication' => array(
                    'DBName' => $this->API_DB,
                    'IP' => 'IP',
                    'ProductCode' => $event,
                ),
                'BCode' => $booking,
                'DocName' => $type,

                'RestrictByAgency' => 0,
                'AgentABTA' => 0,
                'ReturnFileInURL' => array(
                    '_' => 0,
                ),
                'EMailDocument' => array(
                    '_' => 0,
                ),
            ),

        );
        try {
            $response = $this->client->GetDocument($params);
        } catch (Exception $e) {
            //echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        return $response;
    }

    /**
     * Get Bookings by client ID
     */
    public function GetBookingsByClient($clientID)
    {
        $response = false;
        $params = array(
            'BookingSearchRQ' => array(
                'Authentication' => array(
                    'DBName' => $this->API_DB,
                    'IP' => 'IP',
                ),
                'Client' => $clientID,
                'OptionedBookings' => false,
                'ConfirmedBookings' => true,
                'CancelledBookings' => false,
                'SortBy' => 'BOOKED',
                'BookedDateFrom' => '1997-12-12',
                'BookedDateTo' => '2025-12-12',
                'DepartureDateFrom' => '1997-12-12',
                'DepartureDateTo' => '2025-12-12',
                'AgencyGroupId' => '0',
                'FlightBookings' => '0',
                'IncludeAmendedBookings' => true,
                'ResultLimit' => 100,
            ),
        );
        try {
            $response = $this->client->BookingSearch($params);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        //echo "REQUEST:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n";
        //echo '<hr>';
        //print_r($response);
        return $response;
    }

    private function logSOAPRequests($request_data , $order_token = false){
        if($this->LOG_REQUESTS):
            // Define the log directory
            $log_dir = WP_CONTENT_DIR . '/booking_logs/';

            // Create the log directory if it doesn't exist
            if (!file_exists($log_dir)) {
                mkdir($log_dir, 0755, true);
            }

            $log_file_token = $order_token?$order_token:$this->order_token;
            $log_file_token = $log_file_token?$log_file_token:'general';

            // Generate a unique log filename for each booking
            $log_filename = $log_dir . 'booking_' . $log_file_token . '_log.txt';

            $request_data = htmlspecialchars_decode($request_data);

            // Create or open the log file for writing
            $log_file = fopen($log_filename, 'a');

            if ($log_file) {
                // Format the log entry with timestamp
                $log_entry = "[" . date("Y-m-d H:i:s") . "] " . $request_data . "\n";

                // Write the log entry to the log file
                fwrite($log_file, $log_entry);

                // Close the log file
                fclose($log_file);
            } else {
                // Log file couldn't be opened
                //error_log("Failed to open log file: " . $log_filename);
            }
        endif;
    }
}
