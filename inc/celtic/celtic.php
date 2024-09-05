<?php
function is_celtic_active($event_id)
{
	if (empty(get_field('celtic_status', $event_id)) || empty(get_field('celtic_file', $event_id))) {
		return false;
	}

	return true;
}

require_once 'class-celtic-model.php';
require_once 'class-csv-file-handler.php';
require_once 'celtic-ajax.php';

// Add a custom metabox to the 'event' post type
function event_custom_metabox()
{
	add_meta_box(
		'event_details', // Metabox ID
		'CELTIC Event Codes', // Metabox Title
		'event_metabox_content', // Callback function to display the metabox content
		'event', // Post type to add the metabox to
		'normal', // Context (normal, side, advanced)
		'high' // Priority (high, low)
	);
}
add_action('add_meta_boxes', 'event_custom_metabox');

function custom_pagination_html($current_page, $total_pages)
{
	$output = '<div class="custom-pagination">';

	// Display the "Previous" link
	if ($current_page > 1) {
		$output .= '<a href="#" class="custom-pagination-link" data-page="' . ($current_page - 1) . '">Previous</a>';
	}

	// Always show the first page
	$output .= '<a href="#" class="custom-pagination-link' . ($current_page === 1 ? ' active' : '') . '" data-page="1">1</a>';

	// Display "..." if there are skipped pages before the current page
	if ($current_page > 3) {
		$output .= '<span class="pagination-ellipsis">...</span>';
	}

	// Display a limited number of page links in the middle
	$max_page_links = 5; // You can adjust this number as needed
	$start_page = max(2, $current_page - 2);
	$end_page = min($total_pages - 1, $current_page + 2);

	for ($i = $start_page; $i <= $end_page; $i++) {
		$class = ($i === $current_page) ? ' active' : '';
		$output .= '<a href="#" class="custom-pagination-link' . $class . '" data-page="' . $i . '">' . $i . '</a>';
	}

	// Display "..." if there are skipped pages after the current page
	if ($current_page < $total_pages - 2) {
		$output .= '<span class="pagination-ellipsis">...</span>';
	}

	// Always show the last page
	if ($total_pages > 1) {
		$output .= '<a href="#" class="custom-pagination-link' . ($current_page === $total_pages ? ' active' : '') . '" data-page="' . $total_pages . '">' . $total_pages . '</a>';
	}

	// Display the "Next" link
	if ($current_page < $total_pages) {
		$output .= '<a href="#" class="custom-pagination-link" data-page="' . ($current_page + 1) . '">Next</a>';
	}

	$output .= '</div>';

	return $output;
}

// Callback function to display the metabox content
function event_metabox_content($post)
{
	// Check the value of the ACF field 'celtic_active'
	$celtic_active = get_field('celtic_status', $post->ID);

	if ($celtic_active) {
		$event_id = $post->ID;
		$items_per_page = 100; // Adjust as needed

		$celticDB = new Celtic_Model();

		// Get the total number of records for the current event
		$total_records = $celticDB->get_records_count($event_id);
		$total_records_used = $celticDB->get_records_used_count($event_id);

		// Calculate the total number of pages for pagination
		$total_pages = ceil($total_records / $items_per_page);
		$total_pages_used = ceil($total_records_used / $items_per_page);

		$records = $celticDB->get_records_paginated($event_id, 1, $items_per_page); // Fetch records for the first page
		?>
		<div class="toggle-container" style="margin-bottom: 20px;">
			<input type="checkbox" id="used-times-toggler" class="toggle-checkbox">
			<label for="used-times-toggler" class="toggle-label">Show only used codes</label>
		</div>

		<button id="csv-export-btn">Export CSV</button>

		<div id="celtic-search" class="celtic-search" style="margin-bottom: 20px;">
			<input type="text" placeholder="Search by code">
			<button type="submit" style="padding: 5px;">Search</button>
		</div>


		<table class="celtic_table_codes">
			<thead>
				<td>ID</td>
				<td>Code</td>
				<td>Used times</td>
				<td>User Name</td>
				<td>Booking Reference</td>
			</thead>
			<tbody>
				<?php foreach ($records as $record) : ?>
					<tr>
						<td><?php echo $record->id; ?></td>
						<td><?php echo $record->code; ?></td>
						<td><?php echo $record->used_times; ?></td>
						<td><?php echo $record->user_name; ?></td>
						<td><?php echo $record->booking_reference; ?></td>
						<td><button class="edit-record-btn" data-record-id="<?php echo $record->id; ?>">Edit</button></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<!-- Pagination links -->
		<div class="custom_pagination_wrapper">
			<?php echo custom_pagination_html(1, $total_pages); ?>
		</div>

		<style>
			.celtic_table_codes td {
				padding: 10px;
				border: 1px solid gray;
			}

			.custom-pagination {
				display: flex;
				align-items: center;
				justify-content: center;
			}

			.custom-pagination a {
				padding: 5px;
			}

			.toggle-container {
				display: inline-block;
				margin-right: 10px;
			}

			.toggle-checkbox {
				display: none;
			}

			.toggle-label {
				display: inline-block;
				cursor: pointer;
				background-color: #f0f0f0;
				padding: 5px 10px;
				border: 1px solid #ccc;
				border-radius: 4px;
				font-size: 14px;
			}

			.toggle-checkbox:checked+.toggle-label {
				background-color: #4caf50;
				color: white;
				border-color: #4caf50;
			}
		</style>

		<script>
			jQuery(document).ready(function($) {
				var currentPage = 1; // Initialize the current page number

				$(document).on('click', '.custom-pagination a', function(e) {
					e.preventDefault();
					currentPage = $(this).data('page'); // Update the current page

					updateTableAndPagination(currentPage, $('#used-times-toggler').prop('checked'));
				});

				$('#used-times-toggler').on('click', function(e) {
					var showUsedTimesOne = $(this).prop('checked'); // Get the state of the toggler
					updateTableAndPagination(1, showUsedTimesOne);
				});

				// Initial fetch on page load
				//fetchRecords(currentPage);

				function updateTableAndPagination(page, showUsedTimesOne) {
					$.ajax({
						type: 'POST',
						url: ajaxurl, // WordPress AJAX handler URL
						data: {
							action: 'fetch_paged_records',
							event_id: <?php echo $post->ID; ?>,
							page: page,
							total_pages: <?php echo $total_pages; ?>,
							total_pages_used: <?php echo $total_pages_used; ?>,
							show_used_times_one: showUsedTimesOne,
						},
						success: function(response) {
							if (response) {
								$('.celtic_table_codes tbody').html(response.data.records_html);
								$('.custom_pagination_wrapper').html(response.data.pagination_html);
							}

						},
						error: function(error) {
							console.log(error);
						}
					});
				}

				//edit row
				// Handle click on the "Edit" button
				$(document).on('click', '.edit-record-btn', function(e) {
					e.preventDefault(); // Prevent automatic page reload

					var recordId = $(this).data('record-id');
					var $row = $(this).closest('tr');

					// Switch to editing mode for specific columns
					var columnsToEdit = [2, 3, 4]; // Indexes of columns to edit (0-based)
					columnsToEdit.forEach(function(index) {
						var value = $row.find('td:eq(' + index + ')').text();
						$row.find('td:eq(' + index + ')').html('<input type="text" value="' + value + '">');
					});

					// Add a "Save" button to save changes
					$row.append('<td><button class="save-record-btn" data-record-id="' + recordId + '">Save</button></td>');
				});

				// Handle click on the "Save" button
				$(document).on('click', '.save-record-btn', function(e) {
					e.preventDefault(); // Prevent automatic page reload

					var recordId = $(this).data('record-id');
					var $row = $(this).closest('tr');

					// Collect edited values from input fields
					var editedValues = [];
					$row.find('input').each(function() {
						editedValues.push($(this).val());
					});

					var $saveButton = $(this);

					// Make AJAX request to save edited values
					$.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {
							action: 'save_edited_record',
							record_id: recordId,
							edited_values: editedValues
						},
						success: function(response) {
							// Update the row with the saved values
							// You can also update the table and pagination if needed

							// Remove input fields and "Save" button
							$row.find('input').each(function() {
								var value = $(this).val();
								$(this).replaceWith(value);
							});
							$saveButton.remove();
							// Remove the "Save" column
							$row.find('td:last-child').remove();
						}
					});
				});

				//Search entries by code
				$('#celtic-search').each(function(e){
					var btn = $(this).find('button');
					var form = $(this);
					var searchCodeInput = $(this).find('input');
					var currentEventId = <?php echo get_the_ID(); ?>; // Assuming you have the current event ID available in JavaScript
					btn.on('click' , function(e){
						e.preventDefault();
						var searchCode = searchCodeInput.val();
						$.ajax({
							type: 'POST',
							url: ajaxurl, // WordPress AJAX handler URL
							data: {
								action: 'search_by_code',
								event_id: <?php echo $post->ID; ?>,
								search_code: searchCode,
								page: 1,
								total_pages: <?php echo $total_pages; ?>,
								total_pages_used: <?php echo $total_pages_used; ?>,
								show_used_times_one: $('#used-times-toggler').prop('checked'),
							},
							success: function(response) {
								if (response) {
									$('.celtic_table_codes tbody').html(response.data.records_html);
									$('.custom_pagination_wrapper').html(response.data.pagination_html);
								}

							},
							error: function(error) {
								console.log(error);
							}
						});
					});
					
				});


				// Handle click on the CSV export button
				$('#csv-export-btn').on('click', function(e) {
					e.preventDefault();
					var currentEventId = <?php echo get_the_ID(); ?>; // Assuming you have the current event ID available in JavaScript
					$.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {
							action: 'export_used_codes_csv',
							event_id: currentEventId // Pass the event ID
						},
						success: function(response) {
							if(response){
								// Trigger the CSV download
								downloadCSV(response);
							}
							
						}
					});
				});

				// Function to trigger CSV download
				function downloadCSV(csvData) {
					var blob = new Blob([csvData], { type: 'text/csv;charset=utf-8;' });
					var link = document.createElement("a");
					var url = URL.createObjectURL(blob);
					link.setAttribute("href", url);
					link.setAttribute("download", "celtic_used_codes.csv");
					link.style.visibility = 'hidden';
					document.body.appendChild(link);
					link.click();
					document.body.removeChild(link);
				}

			});
		</script>


	<?php
	} else {
		echo 'Celtic Active is not set to true.';
	}
}

// Save metabox data when the post is saved
function event_save_metabox($post_id)
{
}
add_action('save_post_event', 'event_save_metabox');

add_action('wp_ajax_fetch_paged_records', 'fetch_paged_records');
add_action('wp_ajax_nopriv_fetch_paged_records', 'fetch_paged_records');

function fetch_paged_records()
{
	$page = intval($_POST['page']);
	$event_id = intval($_POST['event_id']);
	$items_per_page = 100; // Or any desired value
	$total_pages = intval($_POST['total_pages']);
	$total_pages_used = intval($_POST['total_pages_used']);
	$show_used_times_one = isset($_POST['show_used_times_one']) && $_POST['show_used_times_one'] === 'true';

	$celticDB = new Celtic_Model();
	$paged_records = $celticDB->get_records_paginated($event_id, $page, $items_per_page, $show_used_times_one);

	ob_start(); // Start output buffering
	foreach ($paged_records as $record) {
	?>
		<tr>
			<td><?php echo $record->id; ?></td>
			<td><?php echo $record->code; ?></td>
			<td><?php echo $record->used_times; ?></td>
			<td><?php echo $record->user_name; ?></td>
			<td><?php echo $record->booking_reference; ?></td>
			<td><button class="edit-record-btn" data-record-id="<?php echo $record->id; ?>">Edit</button></td>
		</tr>
<?php
	}
	$records_html = ob_get_clean(); // Get the buffered content and clean the buffer

	// Prepare the AJAX response
	$response = array(
		'records_html' => $records_html,
		'pagination_html' => $show_used_times_one ? custom_pagination_html($page, $total_pages_used) : custom_pagination_html($page, $total_pages), // Generate pagination HTML
	);

	return wp_send_json_success($response);
	die();
}

add_action('wp_ajax_search_by_code', 'search_by_code');
add_action('wp_ajax_nopriv_search_by_code', 'search_by_code');
function search_by_code(){
	$event_id = intval($_POST['event_id']);
	$search_code = $_POST['search_code'];
	$page = intval($_POST['page']);
	$items_per_page = 100; // Or any desired value
	$total_pages = intval($_POST['total_pages']);
	$total_pages_used = intval($_POST['total_pages_used']);
	$show_used_times_one = isset($_POST['show_used_times_one']) && $_POST['show_used_times_one'] === 'true';

	$celticDB = new Celtic_Model();
	$record = $celticDB->get_record($search_code, $event_id);
	if($search_code):
		$record = $celticDB->get_record($search_code, $event_id);
		if($record):
			ob_start(); // Start output buffering
			?>
				<tr>
					<td><?php echo $record->id; ?></td>
					<td><?php echo $record->code; ?></td>
					<td><?php echo $record->used_times; ?></td>
					<td><?php echo $record->user_name; ?></td>
					<td><?php echo $record->booking_reference; ?></td>
					<td><button class="edit-record-btn" data-record-id="<?php echo $record->id; ?>">Edit</button></td>
				</tr>
			<?php
			$records_html = ob_get_clean(); // Get the buffered content and clean the buffer
		else:
			$records_html = '<tr><td colspan="6">Code not found.</td></tr>';
		endif;

		// Prepare the AJAX response
		$response = array(
			'records_html' => $records_html,
			'pagination_html' => '', 
		);
	else:
		$paged_records = $celticDB->get_records_paginated($event_id, $page, $items_per_page, $show_used_times_one);
		ob_start(); // Start output buffering
		foreach ($paged_records as $record) {
		?>
			<tr>
				<td><?php echo $record->id; ?></td>
				<td><?php echo $record->code; ?></td>
				<td><?php echo $record->used_times; ?></td>
				<td><?php echo $record->user_name; ?></td>
				<td><?php echo $record->booking_reference; ?></td>
				<td><button class="edit-record-btn" data-record-id="<?php echo $record->id; ?>">Edit</button></td>
			</tr>
		<?php
		}
		$records_html = ob_get_clean(); // Get the buffered content and clean the buffer

		// Prepare the AJAX response
		$response = array(
			'records_html' => $records_html,
			'pagination_html' => $show_used_times_one ? custom_pagination_html($page, $total_pages_used) : custom_pagination_html($page, $total_pages), // Generate pagination HTML
		);
	endif;

	return wp_send_json_success($response);
	die();
}

add_action('wp_ajax_save_edited_record', 'save_edited_record');
add_action('wp_ajax_nopriv_save_edited_record', 'save_edited_record');
function save_edited_record() {
    $record_id = intval($_POST['record_id']);
    $edited_values = $_POST['edited_values'];

    $celticDB = new Celtic_Model();

    // Prepare the data to update
    $data_to_update = array(
        'used_times' => $edited_values[0], // Assuming the first value is for 'used_times'
        'user_name' => $edited_values[1],  // Assuming the second value is for 'user_name'
        'booking_reference' => $edited_values[2] // Assuming the third value is for 'booking_reference'
    );

    // Update the record in the database
    $update_result = $celticDB->update($record_id, $data_to_update);

    if ($update_result !== false) {
        wp_send_json_success();
    } else {
        wp_send_json_error(array('message' => 'Failed to update the record.'));
    }
}

add_action('wp_ajax_export_used_codes_csv', 'export_used_codes_csv');
add_action('wp_ajax_nopriv_export_used_codes_csv', 'export_used_codes_csv');
function export_used_codes_csv() {
    $event_id = intval($_POST['event_id']); // Retrieve the event ID from the AJAX request
    $celticDB = new Celtic_Model();
    $used_records = $celticDB->get_used_records($event_id); // Modify this function to get used records for the specific event

    $csv_data = "Code,Used times,User Name,Booking Reference\n"; // CSV header
    foreach ($used_records as $used_record) {
        // Retrieve the required fields from the used record
        $code = $used_record->code;
        $used_times = $used_record->used_times;
        $user_name = $used_record->user_name;
        $booking_reference = $used_record->booking_reference;

        // Append the fields to the CSV data
        $csv_data .= "$code,$used_times,$user_name,$booking_reference\n";
    }

    $response = array(
        'csv_data' => $csv_data
    );

    // Set the response content type to force download as a CSV file
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="used_codes.csv"');

    // Output the CSV data and exit
    echo $csv_data;
    exit();
}