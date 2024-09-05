<?php
$order = $args['order'];
$api   = $args['api'];
$quote = $args['quote'];


if ( isset($quote->OTA_ViewQuoteResult->Transfers->Transfer) ) :
    $transfers = $quote->OTA_ViewQuoteResult->Transfers->Transfer;
    if(!is_array($transfers)) $transfers = array($transfers);
	?>
	<div class="summary-extras mt-3">
		<h3 class="mb-1">Transfers</h3>
		<ul class="summary-extras__list">
            <?php $airports = new AirportsEntriesTable(); ?>
			<?php foreach ( $transfers as $transferItem ) : ?>
                <?php
                $transferArrival = $transferItem->TransferItinerary->OriginDestinationOptions->OriginDestinationOptions->TransferSegment;

                $arrival_DepartureLocation = $transferArrival->DepartureLocation;
                $arrival_ArrivalLocation = $transferArrival->ArrivalLocation;
                $arrival_DepartureTime = strtotime($transferArrival->TransferDate);
                $arrival_TravelTime = $transferArrival->TravelTime;

                $arrival_DepartureLocation = $airports->get_airport_name_by_code($arrival_DepartureLocation)?$airports->get_airport_name_by_code($arrival_DepartureLocation):$arrival_DepartureLocation;
                $arrival_ArrivalLocation = $airports->get_airport_name_by_code($arrival_ArrivalLocation)?$airports->get_airport_name_by_code($arrival_ArrivalLocation):$arrival_ArrivalLocation;

                $arrival_title = $transferArrival->Vehicle->_.' - '.$arrival_DepartureLocation.' to '.$arrival_ArrivalLocation;

                $qtt = 1;
                $TravelerRefNumber = $transferItem->TravelerRefNumber;
                if(is_array($TravelerRefNumber)){
                    $qtt = sizeof($TravelerRefNumber);
                }

                $price = $transferItem->TransferPricingInfo->ItinTotalFare->TotalFare->Amount;
                if(false):
                    ?>
                        <pre><?php print_r($transferItem); ?></pre>
                    <?php
                endif;
                
                ?>
				<li class="mb-1">
                    <div class="extras-card p-1">
                        <div class="extras-card__header d-flex align-items-center py-3">
                            <div class="extras-card__content">
                                <div class="extras-card__content-inner d-flex justify-content-between align-items-center">
                                    <div class="extras-card__content-title">
                                        <h3><?php echo $arrival_title; ?></h3>
                                    </div>
                                    <div class="extras-card__content-right d-flex justify-content-between justify-content-sm-end align-items-center">
                                        <span class="extras-card__qtt">Qty <?php echo $qtt; ?></span>
                                        <?php if($price!=0): ?>
                                        <span class="h3 extras-card__price text-align-right" style="min-width: 120px;text-align: end;">
                                            
                                            <?php echo $order->getCurrencySymbol(); ?><?php echo number_format( $price, 2, '.', ',' ); ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                </li>

			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
