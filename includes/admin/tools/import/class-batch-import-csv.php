<?php
namespace AffWP\Utils\Batch_Process\Import;

use AffWP\Utils\Batch_Process as Batch;
use AffWP\Utils\Importer;

/**
 * CSV importer base class.
 *
 * @since 2.1
 *
 * @see \AffWP\Utils\Batch_Process\Import
 * @see \AffWP\Utils\Importer\CSV
 */
class CSV extends Batch\Import implements Importer\CSV {

	/**
	 * The parsed CSV file being imported.
	 *
	 * @access public
	 * @since  2.1
	 * @var    \parseCSV
	 */
	public $csv;

	/**
	 * Total rows in the CSV file.
	 *
	 * @access public
	 * @since  2.1
	 * @var    int
	 */
	public $total;

	/**
	 * Map of CSV columns > database fields
	 *
	 * @access public
	 * @since  2.1
	 * @var    array
	 */
	public $field_mapping = array();

	/**
	 * Instantiates the importer.
	 *
	 * @access public
	 * @since  2.1
	 *
	 * @param resource $_file File to import.
	 * @param int      $_step Current step.
	 */
	public function __construct( $_file = '', $_step = 1 ) {

		if( ! class_exists( 'parseCSV' ) ) {
			require_once AFFILIATEWP_PLUGIN_DIR . 'includes/libraries/parsecsv.lib.php';
		}

		$this->step = $_step;
		$this->file = $_file;
		$this->done = false;
		$this->csv = new \parseCSV();
		$this->csv->auto( $this->file );

		$this->total = count( $this->csv->data );

		parent::__construct( $_file, $_step );
	}

	/**
	 * Processes a single step (batch).
	 *
	 * @access public
	 * @since  2.1
	 */
	public function process_step() {

	}

	/**
	 * Maps CSV columns to their corresponding import fields.
	 *
	 * @access public
	 * @since  2.1
	 *
	 * @param array $import_fields Import fields to map.
	 */
	public function map_fields( $import_fields = array() ) {
		$this->field_mapping = $import_fields;
	}

	/**
	 * Prepares the data for import.
	 *
	 * @access public
	 * @since  2.0
	 *
	 * @return array[] Multi-dimensional array of data for import.
	 */
	public function get_data() {}

	/**
	 * Performs the import process.
	 *
	 * @access public
	 * @since  2.0
	 *
	 * @return void
	 */
	public function import() {}

	/**
	 * Sets the CSV columns.
	 *
	 * @access public
	 * @since  2.1
	 *
	 * @return array<string,string> CSV columns.
	 */
	public function csv_cols() {}

	/**
	 * Retrieves the CSV columns array.
	 *
	 * Alias for csv_cols(), usually used to implement a filter on the return.
	 *
	 * @access public
	 * @since  2.1
	 *
	 * @return array<string,string> CSV columns.
	 */
	public function get_csv_cols() {}

	/**
	 * Outputs the CSV columns.
	 *
	 * @access public
	 * @since  2.1
	 *
	 * @return void
	 */
	public function csv_cols_out() {}

	/**
	 * Outputs the CSV rows.
	 *
	 * @access public
	 * @since  2.1
	 *
	 * @return void
	 */
	public function csv_rows_out() {}

}
