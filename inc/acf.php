<?php
/*
=====================
	ACF functions
=====================
*/

function my_acf_google_map_api($api)
{
	$api['key'] = 'AIzaSyBxNkwTLxaXsJJJo4kGFqRZnQDaZBdoqfk';
	return $api;
}
add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

function acf_map_display()
{
?>
	<style type="text/css">
		.acf-map , .acf-hotels-map{
			width: 100%;
			height: 100%;
			min-height: 500px;
		}

		.acf-map img {
			max-width: inherit !important;
		}
	</style>
	<script type="text/javascript">
		function map_callback(){

		}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBxNkwTLxaXsJJJo4kGFqRZnQDaZBdoqfk&callback=map_callback"></script>
<?php
}


/*
=====================
	ACF options page
=====================
*/
function my_acf_op_init()
{

	// Check function exists.
	if (function_exists('acf_add_options_page')) {

		// Add parent.
		$parent = acf_add_options_page(
			array(
				'page_title' => __('Theme Options'),
				'menu_title' => __('Theme Options'),
				'redirect'   => false,
			)
		);

		// Add sub page.
		$headerOptions    = acf_add_options_page(
			array(
				'page_title'  => __('Header Options'),
				'menu_title'  => __('Header'),
				'parent_slug' => $parent['menu_slug'],
			)
		);
		$footerOptions    = acf_add_options_page(
			array(
				'page_title'  => __('Footer Options'),
				'menu_title'  => __('Footer'),
				'parent_slug' => $parent['menu_slug'],
			)
		);
		$locationsOptions = acf_add_options_page(
			array(
				'page_title'  => __('Locations'),
				'menu_title'  => __('Locations'),
				'parent_slug' => $parent['menu_slug'],
			)
		);
		$scripts          = acf_add_options_page(
			array(
				'page_title'  => __('Scripts'),
				'menu_title'  => __('Scripts'),
				'parent_slug' => $parent['menu_slug'],
			)
		);

		$api = acf_add_options_page(
			array(
				'page_title'  => __('Booking API'),
				'menu_title'  => __('Booking API'),
				'parent_slug' => $parent['menu_slug'],
			)
		);
	}
}
add_action('acf/init', 'my_acf_op_init');


/*
=====================
	ACF Flexible Template Loop
=====================
*/
function the_acf_loop()
{
	get_template_part('template-parts/loop/acf-blocks', 'loop');
}


/*
=====================
	ACF Section Options Handler
=====================
*/

function get_acf_block_options()
{
	$options = get_sub_field('options');

	$params = array(
		'id'               => '',
		'class'            => '',
		'style'            => '',
		'background_image' => '',
	);

	if ($options) :

		//Block spacings
		if ($options['change_topbottom_spacings']) :

			//spacings desktop
			$params['class'] .= ' pt-lg-' . $options['spacing_top_desktop'];
			$params['class'] .= ' pb-lg-' . $options['spacing_bottom_desktop'];

			//spacings tablet
			$params['class'] .= ' pt-md-' . $options['spacing_top_tablet'];
			$params['class'] .= ' pb-md-' . $options['spacing_bottom_tablet'];

			//spacings mobile
			$params['class'] .= ' pt-' . $options['spacing_top_mobile'];
			$params['class'] .= ' pb-' . $options['spacing_bottom_mobile'];

		endif;

		//Block custom classes
		if ($options['block_custom_classes']) :
			$params['class'] .= ' ' . $options['block_custom_classes'];
		endif;

		//Block background color
		if ($options['change_background_color']) :
			$params['style'] .= 'background-color:' . $options['background_color'] . ';';
		endif;

		//Block background image
		if ($options['background_image'] && 'full' === $options['background_image_size']) :
			$params['style'] .= 'background-image: url(' . $options['background_image']['link'] . ');';
			$params['style'] .= 'background-repeat: no-repeat;';
			$params['style'] .= 'background-size: cover;';
		elseif ($options['background_image'] && 'container' === $options['background_image_size']) :
			$params['background_image'] = $options['background_image'];
		endif;

		//Block text color
		if ('default' !== $options['text_color']) :
			$params['class'] .= ' text--color--' . $options['text_color'];
		endif;

		//Block ID
		$params['id'] = $options['block_id'] ? $options['block_id'] : '';
	endif;

	return $params;
}

/**
 * Dynamically populate ACF select field with Ninja Forms
 *
 * https://www.advancedcustomfields.com/resources/dynamically-populate-a-select-fields-choices/
 *
 * There are 4 ways to hook into `acf_load_field`
 * https://www.advancedcustomfields.com/resources/acfload_field/
 *
 * ---------------------------------------------
 * @return array    $field
 * ---------------------------------------------
 **/
function acf_load_ninja_forms_field_choices($field) {
    // Retrieve all forms using the Ninja Forms API.
    $forms = Ninja_Forms()->form()->get_forms();
    $choices = [];
    
    // Check if there are any forms retrieved.
    if (!empty($forms)) {
        foreach ($forms as $form) {
            // The form object provides methods to get properties like form ID and title.
            $value = $form->get_id();
            $label = $form->get_setting('title');
    
            // Populate the choices array with form ID as key and form title as value.
            $choices[$value] = $label;
        }
    }

    // Add an empty option for default or placeholder value.
    $field['choices'] = ['' => 'Select...'] + $choices;

    // Return the modified field with the choices populated.
    return $field;
}

add_filter('acf/load_field/name=ninja_form', 'acf_load_ninja_forms_field_choices');
