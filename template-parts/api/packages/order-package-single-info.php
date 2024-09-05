<?php
$debug = false;

$order = $args['order'];
$api   = $args['api'];

$packages = $order->searchPackages();
$currentPackage = false;
$tourCode   = $_GET['tourcode'];
if(is_array($packages)):
    foreach($packages as $package):
        if($package->Tcode == $tourCode):
            $currentPackage = $package;
            break;
        endif;
    endforeach;
else:
    if($packages->Tcode == $tourCode):
        $currentPackage = $packages;
    endif;
endif;

if($currentPackage):
    $tourPriceID   = $currentPackage->TourPriceDetailId;
    $lofi   =  $currentPackage->LandOrFlight;
endif;

//get single package link
$link = get_field('api_package_single_page', 'option');
$link  = $link . '?tourcode=' . $tourCode;

$travellersRooms = $order->getRooms();
$ToursPriceDetailId = array();
foreach($travellersRooms as $room):
    $travellersQtt = $room['adults_qtt'] + $room['childs_qtt'];
    if (is_array($currentPackage->PricingTypes->PricingType)) :
        foreach ($currentPackage->PricingTypes->PricingType as $PricingType) :
            if ($PricingType->Occupancy == $travellersQtt) :
                $ToursPriceDetailId[] = $PricingType->TourPriceDetailID;
            endif;
        endforeach;
    else :
        $ToursPriceDetailId[] = $currentPackage->PricingTypes->PricingType->TourPriceDetailID;
    endif;
endforeach;

//Get tours components data
$toursData = $order->getMultiplePackagesData($tourCode , $ToursPriceDetailId , $lofi);
$packagePostId = $api->GetPackagePostID( $toursData[0]->TourDetails->TMcode );

//Get tours sharing data from first array item
$tourStartDate = strtotime($toursData[0]->TourDetails->StartDate);
$tourEndDate = strtotime($toursData[0]->TourDetails->StartDate. ' + '.$toursData[0]->TourDetails->Duration.' days');
$tourStartDateStr = date('F j, Y', $tourStartDate);
$tourEndDateStr = date('F j, Y', $tourEndDate);

$accommodationsExists = isset($toursData[0]->TourComponents->AccommodationItems->AccommodationComponent);
$extrasExists = isset($toursData[0]->TourComponents->ExtrasItems->ExtrasComponent);
$transfersExists = isset($toursData[0]->TourComponents->TransferItems->TransferComponent);

$packageInclusions = false;
$packageDescritpion = false;
$packageContent = false;
$packageTitle = $toursData[0]->TourDetails->TourName;
if($packagePostId):
    $packageDescritpion = get_field('description' , $packagePostId);
    $packageInclusions = get_field('inclusions' , $packagePostId);
    $packageContent = get_field('info_page_content' , $packagePostId);
    $packageTitle = get_the_title($packagePostId);
endif;


//Add packages data to order
$packagesData = array();
foreach($toursData as $index => $tourData):

    /* Calculate price */
    $price = 0;
    $price += $tourData->TourDetails->AdultPrice * $travellersRooms[$index]['adults_qtt'];
    $price += $tourData->TourDetails->ChildPrice * $travellersRooms[$index]['childs_qtt'];

    $packageData = array(
        'TourID' => $tourCode,
        'TourName' => $packageTitle,
        'TourPriceID' => $tourData->TourDetails->TourPriceDetailId,
        'ToursIndId' => $tourData->TourDetails->ToursIndId,
        'TourPricingTypeId' => $tourData->TourDetails->ToursMasterPricingTypesID,
        'TourPriceDetailId' => $tourData->TourDetails->TourPriceDetailId,
        'TourStartDate' => $tourStartDateStr,
        'TourEndDate' => $tourEndDateStr,
        'LOFI' => $tourData->TourDetails->LandOrFlight,
        'accommodation_exists' => $accommodationsExists,
        'extras_exists' => $extrasExists,
        'transfers_exists' => $transfersExists,
        'price' => $price,
    );
    array_push($packagesData , $packageData);
endforeach;

$order->addPackagesData($packagesData);

if(!$accommodationsExists){
    $link = get_field('api_package_order_page' , 'option');
}
?>

<div class="package-main-wrapper">
	
    <h1 class="h3 mb-3"><?php echo $packageTitle; ?></h1>

    <?php if($debug): ?>
        <pre><?php print_r($tourData); ?></pre>
    <?php endif; ?>

    <?php 
    if($packageContent):
        global $post;
        $post = $packageContent;
        setup_postdata( $post );
        ?>
        <div class="package-info-content">
            <?php the_acf_loop(); ?>
        </div>
        <?php
        wp_reset_postdata();
    endif;
    ?>

    <script>
        jQuery(window).on('load' , function(){
            jQuery('.animate').addClass('in');
        });
    </script>

	<div class="package__buttons-bar d-flex p-2 mt-2 mx-n2 mb-n3">
		<div class="package__buttons-bar__item">
			<a href="<?php the_field('api_packages_page' , 'option'); ?>" class="button button--dark-white">Back to Event Packages</a>
		</div>
        <div class="package__buttons-bar__item">
			<a href="<?php echo $link; ?>" class="button button--orange">CONTINUE TO BOOK</a>
		</div>
	</div>
</div>
<?php
