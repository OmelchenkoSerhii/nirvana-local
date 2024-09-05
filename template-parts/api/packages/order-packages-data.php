<?php

$packages = $args['packages'];
$order  = $args['order'];
$api    = $args['api'];

$orderEventId     = $order->getEventPostID();
$orderEventTours = get_field( 'event_tours', $orderEventId );
$availableTours  = array();
?>

<ul class="accommodations__list js-accoms-list d-flex flex-column">
	<?php $packagesCount = 0; ?>
	<?php if ( $packages ) : ?>
       
            <?php if ( is_array( $packages ) ) : ?>
                <?php foreach ( $packages as $package ) : ?>
                    <?php 
                    if($package->StartDate == $order->getCheckinDateVal()->format('Y-m-d') . 'T00:00:00'):  
                        $packagesCount++;  
                        ?>
                        <?php
                        get_template_part(
                            'template-parts/api/packages/package',
                            'card',
                            array(
                                'order' => $order,
                                'api'   => $api,
                                'package' => $package,
                            )
                        );
                        array_push( $availableTours, $package->TMcode );
                        ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else : ?>
                <?php 
                if($packages->StartDate == $order->getCheckinDateVal()->format('Y-m-d') . 'T00:00:00'):  
                    $packagesCount++;  
                    ?>
                    <?php
                    get_template_part(
                        'template-parts/api/packages/package',
                        'card',
                        array(
                            'order' => $order,
                            'api'   => $api,
                            'package' => $packages,
                        )
                    );
                    array_push( $availableTours, $packages->TMcode );    
                    ?>
                <?php endif;?>
            <?php endif; ?>
	<?php else : ?>
		<h4 class="mb-2">There are no available packages for your chosen number of nights. Please expand your date range.</h4>
	<?php endif; ?>
    <?php if($packagesCount == 0 && $packages ): ?>
        <h4 class="mb-2">There are no available packages for your chosen number of nights. Please expand your date range.</h4>
    <?php endif; ?>

    <?php
	if ( $orderEventTours ) :
		foreach ( $orderEventTours as $tour ) :
			$tourID = get_field( 'tmcode', $tour );
			if ( ! in_array( $tourID, $availableTours ) ) :
				get_template_part(
					'template-parts/api/packages/package',
					'card-soldout',
					array(
						'tourPostID'   => $tour,
						'tourID' => $tourID,
						'order'     => $order,
					)
				);
			endif;
		endforeach;
	endif;
	?>
</ul>
