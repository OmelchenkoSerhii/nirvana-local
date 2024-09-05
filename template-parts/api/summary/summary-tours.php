<?php
$order = $args['order'];
$api   = $args['api'];
$quote = $args['quote'];


if ( isset($quote->OTA_ViewQuoteResult->Tours->Tour) ) :
    $tours = $quote->OTA_ViewQuoteResult->Tours->Tour;
    if (!is_array($tours)) {
        $tours = array($tours);
    }
	?>
	<div class="summary-extras mt-3">
		<h3 class="mb-1">Tours</h3>
		<ul class="summary-extras__list">
			<?php foreach ( $tours as $tour ) : ?>
                <?php 
                $tourName = $tour->TourName;
                $tourPrice = $tour->TotalPrice;
                $tourAdults = $tour->Adults;
                $tourChilds = $tour->Children + $tour->Infants;
                ?>
                <li class="mb-1">
                    <div class="extras-card p-1">
                        <div class="extras-card__header d-flex align-items-center py-3">
                            <div class="extras-card__content">
                                <div class="extras-card__content-inner d-flex justify-content-between align-items-center">
                                    <div class="extras-card__content-title">
                                        <h3><?php echo $tourName; ?></h3>
                                    </div>
                                    <div class="extras-card__content-right d-flex justify-content-between justify-content-sm-end align-items-center">
                                        <span class="extras-card__qtt">
                                            <?php
                                            printf(
                                                esc_html__('%1$s', 'nirvana'),
                                                $order->getCustomPeopleQtt($tourAdults , $tourChilds),
                                            );
                                            ?>
                                            </span>
                                        <span class="h3 extras-card__price text-align-right mr-0 ml-2" style="min-width: 120px;text-align: end;">
                                            
                                            <?php echo $order->getCurrencySymbol(); ?><?php echo number_format( $tourPrice, 2, '.', ',' ); ?>
                                        </span>
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
