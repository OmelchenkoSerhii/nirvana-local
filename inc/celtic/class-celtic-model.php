<?php

class Celtic_Model
{

	private string $table_name;

	public function __construct()
	{
		global $wpdb;

		$this->table_name = $wpdb->prefix . 'celtic';
	}

	/**
	 * Retrieve a record by code.
	 *
	 * @param string $code
	 *
	 * @return object|null Database query results.
	 */
	public function get_by_code($code)
	{
		global $wpdb;

		// phpcs:ignore
		return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table_name} WHERE code = %s", $code));
	}

	/**
	 * Retrieve a record by code and event.
	 *
	 * @param string $code
	 * @param string $event_id
	 *
	 * @return object|null Database query results.
	 */
	public function get_record($code, $event_id)
	{
		global $wpdb;

		// phpcs:ignore
		return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table_name} WHERE code = %s AND event_id = %s", $code, $event_id));
	}

	/**
	 * Retrieve a records by event.
	 *
	 * @param string $event_id
	 *
	 * @return object|null Database query results.
	 */
	public function get_records($event_id)
	{
		global $wpdb;

		// phpcs:ignore
		return $wpdb->get_results($wpdb->prepare("SELECT * FROM {$this->table_name} WHERE event_id = %d", $event_id));
	}

	/**
	 * Retrieve records with used_times = 1 for a specific event.
	 *
	 * @param string $event_id
	 *
	 * @return object|null Database query results.
	 */
	public function get_used_records($event_id)
	{
		global $wpdb;

		// phpcs:ignore
		return $wpdb->get_results($wpdb->prepare("SELECT * FROM {$this->table_name} WHERE event_id = %d AND used_times > 0", $event_id));
	}

	/**
	 * Retrieve records by event with pagination.
	 *
	 * @param string $event_id
	 * @param int $page Current page number.
	 * @param int $items_per_page Number of records to fetch per page.
	 *
	 * @return object|null Database query results.
	 */

	public function get_records_paginated($event_id, $page, $items_per_page, $show_used_times_one = false)
	{
		global $wpdb;

		$offset = ($page - 1) * $items_per_page;

		// Build the query
		$query = "SELECT * FROM {$this->table_name} WHERE event_id = %d";

		if ($show_used_times_one) {
			$query .= " AND used_times > 0";
		}

		$query .= " LIMIT %d OFFSET %d";

		// phpcs:ignore
		return $wpdb->get_results(
			$wpdb->prepare($query, $event_id, $items_per_page, $offset)
		);
	}

	

	/**
	 * Retrieve the total number of records for a given event.
	 *
	 * @param string $event_id Event ID for which to retrieve records count.
	 * @return int|null Total number of records for the event.
	 */
	public function get_records_count($event_id)
	{
		global $wpdb;

		// Prepare and execute SQL query to count records
		$count_query = $wpdb->prepare(
			"SELECT COUNT(*) FROM {$this->table_name} WHERE event_id = %d",
			$event_id
		);

		// Retrieve and return the count
		// phpcs:ignore
		return $wpdb->get_var($count_query);
	}

	/**
	 * Retrieve the total number of used records for a given event.
	 *
	 * @param string $event_id Event ID for which to retrieve records count.
	 * @return int|null Total number of records for the event.
	 */
	public function get_records_used_count($event_id)
	{
		global $wpdb;

		// Prepare and execute SQL query to count records
		$count_query = $wpdb->prepare(
			"SELECT COUNT(*) FROM {$this->table_name} WHERE event_id = %d AND used_times > 0",
			$event_id
		);

		// Retrieve and return the count
		// phpcs:ignore
		return $wpdb->get_var($count_query);
	}

	/**
	 * Insert a new record.
	 *
	 * @param array $data New data to insert.
	 *
	 * @return int|false The number of rows inserted, or false on error.
	 */
	public function insert($data)
	{
		global $wpdb;

		return $wpdb->insert($this->table_name, $data);
	}

	/**
	 * Update a record by code.
	 *
	 * @param string $code Code of the record to update.
	 * @param array  $data New data for the record.
	 *
	 * @return int|false The number of rows updated, or false on error.
	 */
	public function update($id, $data)
	{
		global $wpdb;

		$where = array('id' => $id);
		return $wpdb->update($this->table_name, $data, $where);
	}

	/**
	 * Delete a record by code.
	 *
	 * @param string $code Code of the record to delete.
	 *
	 * @return int|false The number of rows updated, or false on error.
	 */
	public function delete($code)
	{
		global $wpdb;

		$where = array('code' => $code);
		return $wpdb->delete($this->table_name, $where);
	}
}
