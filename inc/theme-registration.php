<?php

/**
 * Add client to API on user registration
 */
add_action('user_register', 'run_api_call', 10, 1);
function run_api_call($user_id)
{
    $api = new NiravanaAPI();
    $user_info = get_userdata($user_id);
    $first_name = $user_info->first_name;
    $last_name = $user_info->last_name;
    $email = $user_info->user_email;
    $phone = get_user_meta($user_id, 'phone', true);
    $address1 = get_user_meta($user_id, 'address_1', true);
    $address2 = get_user_meta($user_id, 'address_2', true);
    $town = get_user_meta($user_id, 'town', true);
    $postcode = get_user_meta($user_id, 'postcode', true);
    $country = get_user_meta($user_id, 'country', true);


    $json = $_POST['form_data'];
    if($json):
        $form_data_array = json_decode(stripslashes($json), true);
        
        // Create an associative array by field name
        $form_data = array();
        foreach ($form_data_array as $item) {
            $form_data[$item['field_name']] = $item['value'];
        }

        $data = array(
            'first-name' => $form_data['first_name'],
            'last-name' => $form_data['last_name'],
            'address-1' => $form_data['address_1'],
            'address-2' => $form_data['address_2'],
            'town' => $form_data['town'],
            'country' => $form_data['country'],
            'postcode' => $form_data['postcode'],
            'phone' => $form_data['phone'],
            'email' => strtolower($form_data['user_email']),
        );
    else:
        $data = array(
            'first-name' => $first_name,
            'last-name' => $last_name,
            'address-1' => $address1,
            'address-2' => $address2,
            'town' => $town,
            'country' => $country ,
            'postcode' => $postcode,
            'phone' => $phone,
            'email' => strtolower($email),
        );
    endif;

    /**
     * Check if client already exists
     */
    
    $client = $api->CreateClient($data, $user_id);
    if($client['clientID']):
        update_user_meta($user_id, 'api_client_id', $client['clientID']);
        update_user_meta($user_id, 'api_client_request', $client['request']);
        update_user_meta($user_id, 'api_client_response', print_r($client['response'] , true));
        update_user_meta($user_id, 'api_client_error', $client['error'] );
        update_user_meta($user_id, 'api_client_search', print_r($client['clientSearchResult'] , true) );
    else:
        update_user_meta($user_id, 'api_client_request', $client['request']);
        update_user_meta($user_id, 'api_client_response', print_r($client['response'] , true));
        update_user_meta($user_id, 'api_client_error', $client['error'] );
        update_user_meta($user_id, 'api_client_search', print_r($client['clientSearchResult'] , true) );
    endif;

    
}

add_filter( 'registration_errors', 'wedevs_registration_errors', 10, 3 );
function wedevs_registration_errors( $errors, $sanitized_user_login, $user_email ) {

	$errors->add( 'first_or_last', __( 'ERROR: missing Billing compny or Billing RUT', 'wedevs' ) );
	
    return $errors;
}

add_action('show_user_profile', 'add_custom_fields_to_profile');
add_action('edit_user_profile', 'add_custom_fields_to_profile');
function add_custom_fields_to_profile($user)
{
    $api_client_id = get_user_meta($user->ID, 'api_client_id', true);
    $api_client_request = get_user_meta($user->ID, 'api_client_request', true);
    $api_client_response = get_user_meta($user->ID, 'api_client_response', true);
    $api_client_error = get_user_meta($user->ID, 'api_client_error', true);
    $api_client_search = get_user_meta($user->ID, 'api_client_search', true);
    ?>
    <h3>API Fields</h3>
    <table class="form-table">
        <tr>
            <th><label for="phone">Client API ID</label></th>
            <td>
                <input type="text" name="api_client_id" id="api_client_id" value="<?php echo $api_client_id; ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th><label for="phone">Client API Request</label></th>
            <td>
                <span class="description"><?php echo esc_attr($api_client_request); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="phone">Client API Response</label></th>
            <td>
                <span class="description"><?php echo esc_attr($api_client_response); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="phone">Client API Error</label></th>
            <td>
                <span class="description"><?php echo esc_attr($api_client_error); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="phone">Client API Search Result</label></th>
            <td>
                <span class="description"><?php print_r($api_client_search); ?></span>
            </td>
        </tr>
        
    </table>
    <?php
}

// Save new user meta field
function save_user_meta_field( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
	
	update_user_meta( $user_id, 'api_client_id', $_POST['api_client_id'] );
}
add_action( 'personal_options_update', 'save_user_meta_field' );
add_action( 'edit_user_profile_update', 'save_user_meta_field' );

function hide_toolbar_for_subscribers()
{
    if (current_user_can('subscriber')) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'hide_toolbar_for_subscribers');

remove_action('wp_logout', 'wp_logout_message');
