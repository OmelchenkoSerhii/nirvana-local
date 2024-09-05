<?php
use League\Csv\Reader;
use League\Csv\Info;

/**
 * Class for handling uploaded CSV files.
 */
class CSV_File_Handler {
	/**
	 * @var string The reference table name.
	 */
	const REF_TABLE_NAME = 'Client Ref';

	/**
	 * @var string The reference ACF field key on event edit page.
	 */
	const FIELD_KEY = 'celtic_file';

	/**
	 * The post ID to be processed.
	 *
	 * @var int
	 */
	protected $post_id;

	/**
	 * CSVFileHandler constructor.
	 *
	 * @param int $post_id The ID of the post to be processed.
	 */
	public function __construct( $post_id ) {
		$this->post_id = $post_id;
	}

	/**
	 * This method will be called when a post is saved or updated.
	 */
	public function handle_uploaded_csv() {
		if ( $this->check_prerequisites() ) {
			$file_field = get_field( self::FIELD_KEY, $this->post_id );

			if ( ! empty( $file_field ) ) {
				$this->process_csv( $file_field );
			}
		}
	}

	/**
	 * Check the prerequisites for CSV processing.
	 *
	 * @return bool Whether the prerequisites are met.
	 */
	protected function check_prerequisites() {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		if ( 'event' !== get_post_type( $this->post_id ) ) {
			return false;
		}

		if ( ! class_exists( 'League\Csv\Reader' ) ) {
			return false;
		}

		if ( ! is_celtic_active( $this->post_id ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Process the CSV file.
	 *
	 * @param string $file_field The file field value.
	 */
	protected function process_csv( $file_field ) {
		$file_path = get_attached_file( $file_field );
		$file_name = basename( $file_path );

		// Create a new CSV reader instance.
		$csv = Reader::createFromPath( $file_path, 'r' );
		$this->set_csv_properties( $csv, $file_name );

		$records = $csv->getRecords();
		if ( ! empty( $records ) ) {
			$this->db_insert_records( $records );
		}
	}

	/**
	 * Set CSV properties and check the header.
	 *
	 * @param Reader $csv The CSV reader instance.
	 * @param string $file_name The name of the file.
	 */
	protected function set_csv_properties( Reader $csv, $file_name ) {
		$csv->setHeaderOffset( 0 );
		$delimiters = Info::getDelimiterStats( $csv, array( ';', ',' ) );
		$delimiter  = array_keys( $delimiters, max( $delimiters ), true )[0];
		$csv->setDelimiter( $delimiter );

		$header = $csv->getHeader();
		if ( ! in_array( self::REF_TABLE_NAME, $header, true ) ) {
			$message = sprintf(
				'<b>Client Ref</b> heading was undefined in the uploaded <b><i>%s</i></b> file.<br>
                Please, check your table.<br><br>
                Your database contains these headers:
                <pre>%s</pre>',
				$file_name,
				var_export( $header, true )
			);
			wp_die( wp_kses_post( $message ), 'Celtic File Error' );
		}
	}

	/**
	 * Insert records into the database.
	 *
	 * @param array $records The records to be inserted.
	 */
	protected function db_insert_records( $records ) {
		global $wpdb;

		/** Create table if not exist */
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'celtic';

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			code text NOT NULL,
			event_id bigint(20) UNSIGNED NOT NULL,
			used_times int(11) DEFAULT '0',
			max_uses int(11) DEFAULT '0',
			is_active tinyint(1) DEFAULT '0',
			user_name varchar(255), 
    		booking_reference varchar(255),
			PRIMARY KEY (id),
			KEY event_id (event_id),
			KEY code (code(50))
		) $charset_collate;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		$alter_sql = "ALTER TABLE $table_name
			ADD CONSTRAINT wp_celtic_ibfk_1 FOREIGN KEY (event_id) REFERENCES wp_posts (ID) ON DELETE CASCADE ON UPDATE CASCADE;";

		$wpdb->query($alter_sql);
		
		//Insert records
		foreach ( $records as $record ) {
			$code = $record[ self::REF_TABLE_NAME ];

			$query = $wpdb->prepare(
				'INSERT INTO wp_celtic (`code`, `event_id`, `max_uses`, `is_active`) SELECT %s, %d, 1, 1 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM wp_celtic WHERE `code` = %s AND `event_id` = %d)',
				$code,
				$this->post_id,
				$code,
				$this->post_id
			);

            // phpcs:ignore
			$wpdb->query( $query );
		}
	}

}

/**
 * Wrapper function to be used with the 'acf/save_post' action.
 *
 * @param int $post_id The post ID.
 */
function handle_uploaded_csv( $post_id ) {
	$csv_handler = new CSV_File_Handler( $post_id );
	$csv_handler->handle_uploaded_csv();
}

add_action( 'acf/save_post', 'handle_uploaded_csv' );
