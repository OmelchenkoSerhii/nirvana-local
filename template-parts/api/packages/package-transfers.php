<?php
$debug = false;
$order = $args['order'];
$api = $args['api'];

$transfers = $order->searchPackageTransfers();
$travellers = $order->getTravellers();
$book_start_date = $order->getCheckinDate();
?>


<?php
if ($debug) :
?>
	<pre>
		<?php //print_r($travellers); ?>
        <hr>
		<?php print_r($order->deletePackageFromQuote()); ?>
        <hr>
		<?php print_r($order->addPackageToQuote()); ?>
		<hr>
        <?php print_r($order->viewQuote()); ?>
        <hr>
		<?php //print_r($transfers); ?>
	</pre>
<?php
endif;

$link = get_field('api_summary_page', 'option');
?>

<form action="<?php the_field('api_summary_page', 'option'); ?>" id="api-package-tranfers-form" class="tranfers">
	<input id="order-number" type="hidden" name="" value="<?php echo $order->getOrderNumber() ; ?>">
	<div class="book-accommodation__form-loading" style="display: none;">
		<img src="<?php echo get_template_directory_uri(); ?>/assets/images/loader.svg" alt="">
	</div>
	<ul class="transfers-list d-flex flex-column">
		<?php
		$index = 0;
        $airports = new AirportsEntriesTable();
        if(!is_array($transfers)) $transfers = array($transfers);
		foreach ($transfers as $transferItem) :
			$show = true;
			
			if(false):
                ?>
                    <pre>
                        <?php print_r($transferItem); ?>
                        <hr>
                        <?php //api_sync_airports(); ?>
                    </pre>
                <?php
			endif;
            
            $transfersItems = array();
            if(is_array($transferItem->TransferItinerary->OriginDestinationOptions->OriginDestinationOption)):
                $transfersItems = $transferItem->TransferItinerary->OriginDestinationOptions->OriginDestinationOption;
            else:
                array_push($transfersItems , $transferItem->TransferItinerary->OriginDestinationOptions->OriginDestinationOption);
            endif;

			foreach($transfersItems as $transfer):
                $transferArrival = $transfer->TransferSegment;

                $arrival_ID = $transferArrival->ComponentID;
                $arrival_DepartureLocation = $transferArrival->DepartureLocation;
                $arrival_ArrivalLocation = $transferArrival->ArrivalLocation;
                $arrival_DepartureTime = strtotime($transferArrival->TransferDate);
                $arrival_ArrivalTime = strtotime($transferArrival->DropOffTime);
                $arrival_TravelTime = $transferArrival->TravelTime;

                $arrival_SurchargePrice = $transferArrival->SurchargePrice;

                $selectionState = $transferArrival->TPA_Extensions->SelectionState;

                $arrival_DepartureLocation = $airports->get_airport_name_by_code($arrival_DepartureLocation)?$airports->get_airport_name_by_code($arrival_DepartureLocation):$arrival_DepartureLocation;
                $arrival_ArrivalLocation = $airports->get_airport_name_by_code($arrival_ArrivalLocation)?$airports->get_airport_name_by_code($arrival_ArrivalLocation):$arrival_ArrivalLocation;

                $arrival_title = $transferArrival->Vehicle->_.' - '.$arrival_DepartureLocation.' to '.$arrival_ArrivalLocation;
                ?>
				<li class="mb-1" style="order: <?php echo date('nd', $arrival_DepartureTime); ?>">
					<div class="transfer-card p-2">
                        <div class="transfer-card__content">
                            <label class="transfer-card__travellers-item__label d-flex align-items-start">
                                <span class="checkbox-style mr-2" style="margin-top: 8px">
                                    <input class="transfer" type="checkbox" name="transfer_id" value="<?php echo $arrival_ID; ?>" <?php if(($index==0 || $index==1) && $selectionState != 3) echo 'checked="true"'; ?>>
                                    <span class="checkbox-style__pseudo"></span>
                                </span>
                               
                                <div class="transfer-card__info">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="mb-2"><?php echo $arrival_title; ?></h3>
                                        <?php if($arrival_SurchargePrice): ?>
                                            <span class="transfer-card__price"><?php echo $order->getCurrencySymbol(); ?><?php echo number_format($arrival_SurchargePrice, 2, '.', ','); ?><span>/pp</span></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <p class="transfer-card__info__row h4 mt-1">
                                        <span class="transfer-card__info__row__title"><?php _e('Departing:'); ?></span>
                                        <span class="transfer-card__info__row__content"><?php echo date('F j, Y', $arrival_DepartureTime); ?></span>
                                    </p>
                                    <p class="transfer-card__info__row h4 mt-1">
                                        <span class="transfer-card__info__row__title"><?php _e('Est. duration:'); ?></span>
                                        <span class="transfer-card__info__row__content"><?php echo $arrival_TravelTime; ?> minutes</span>
                                    </p>
                                    <?php if ($travellers) : ?>
                                        <div class="extras-card__travellers mt-2">
                                            <div class="extras-card__travellers__header d-flex justify-content-between">
                                                <h4>Assign to travellers</h4>
                                            </div>
                                            <ul class="transfer-card__travellers-list">
                                                <?php
                                                $indexInner = 1;
                                                foreach ($travellers as $passenger) :
                                                    $passengerAgeCode = $passenger['AgeCode'];
                                                    $passengerRefNumber = $passenger['ref_number'];
                                                    $passengerType = $passenger['Type'];
                                                    $price = 0;
                                                    $showFlag = true;                                                    

                                                    if ($showFlag) :
                                                        
                                                        ?>
                                                        <li class="extras-card__travellers-item">
                                                            <label class="extras-card__travellers-item__label d-flex align-items-center">
                                                                <span class="checkbox-style mr-1 d-none">
                                                                    <input class="person" type="checkbox" name="person_<?php echo $indexInner; ?>" value="<?php echo $passenger['ref_number']; ?>" <?php if($index==0 || $index==1) echo 'checked="true"'; ?>>
                                                                    <span class="checkbox-style__pseudo"></span>
                                                                </span>
                                                                <span><?php echo $passenger['full-name']; ?></span>
                                                            </label>
                                                        </li>
                                                    <?php endif;
                                                    $indexInner++; ?>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>        
                                </div>
                            </label>
                        </div>
					</div>
				</li>
                
				<?php $index++; ?>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</ul>


	

	<div class="mt-auto pt-4 mx-n2 mb-n3 ">
		<div class="package__buttons-bar d-flex p-2 ">
			<div class="package__buttons-bar__item">
				<a class="button button--dark-white" onclick="history.back()"><?php esc_html_e( 'Back to Event Services', 'nirvana' ); ?></a>
			</div>
			<div class="package__buttons-bar__item">
				<button type="submit" class="button button--orange w-100"><?php esc_html_e( 'Continue', 'nirvana' ); ?></button>
			</div>
		</div>
	</div>
	
</form>