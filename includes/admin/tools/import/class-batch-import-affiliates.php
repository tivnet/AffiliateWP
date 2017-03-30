<?php
namespace AffWP\Utils\Batch_Process;

use AffWP\Utils\Batch_Process as Batch;

class Import_Affiliates extends Batch\Import\CSV implements Batch\With_PreFetch {

	/**
	 * Instantiates the batch process.
	 *
	 * @param string $_file
	 * @param int    $_step
	 */
	public function __construct( $_file = '', $_step = 1 ) {

		$this->map_fields( affiliate_wp()->affiliates->get_columns() );

		parent::__construct( $_file, $_step );
	}

	/**
	 * Initializes the batch process.
	 *
	 * This is the point where any relevant data should be initialized for use by the processor methods.
	 *
	 * @access public
	 * @since  2.1
	 */
	public function init( $data = null ) {}

	/**
	 * Pre-fetches data to speed up processing.
	 *
	 * @access public
	 * @since  2.1
	 */
	public function pre_fetch() {
//		$affiliate_emails = affiliate_wp()->utils->data->get( "{$this->batch_id}_affiliate_totals", array() );
	}

	/**
	 * Maps affiliate fields for pairing with the imported CSV.
	 *
	 * @access public
	 * @since  2.1
	 *
	 * @param array $import_fields Fields to import.
	 */
	public function map_fields( $import_fields = array() ) {
		unset( $import_fields['affiliate_id'] );
		unset( $import_fields['user_id'] );

		$import_fields = array_fill_keys( array_keys( $import_fields ), '' );

		parent::map_fields( $import_fields );
	}

	/**
	 * Retrieves a message for the given code.
	 *
	 * @access public
	 * @since  2.0
	 *
	 * @param string $code Message code.
	 * @return string Message.
	 */
	public function get_message( $code ) {

		switch( $code ) {

			case 'done':
				$final_count = $this->get_current_count();

				$message = sprintf(
					_n(
						'%s affiliate was successfully imported.',
						'%s affiliates were successfully imported.',
						$final_count,
						'affiliate-wp'
					), number_format_i18n( $final_count )
				);
				break;

			default:
				$message = '';
				break;
		}

		return $message;
	}

}
