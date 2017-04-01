<?php
namespace AffWP\Utils\Batch_Process;

use AffWP\Utils\Batch_Process as Batch;

/**
 * Implements a batch processor for importing affiliate accounts from a CSV file.
 *
 * @since 2.0
 *
 * @see \AffWP\Utils\Batch_Process\Import\CSV
 * @see \AffWP\Utils\Batch_Process\With_PreFetch
 */
class Import_Affiliates extends Batch\Import\CSV implements Batch\With_PreFetch {

	/**
	 * Whether to use 'strict' mode when sanitizing generated usernames.
	 *
	 * See {@see 'affwp_batch_import_affiliates_strict_usernames'}.
	 *
	 * @access public
	 * @since  2.1
	 * @var    bool
	 */
	public $use_strict;

	/**
	 * Instantiates the batch process.
	 *
	 * @param string $_file
	 * @param int    $_step
	 */
	public function __construct( $_file = '', $_step = 1 ) {

		/**
		 * Filters whether to generate new affiliate usernames using 'strict' mode,
		 * i.e. reduce generated usernames to ASCII-only.
		 *
		 * Notes: Some platform scenarios such as multisite will apply further sanitization
		 * to usernames regardless of whether `$use_strict` is enabled or not.
		 *
		 * @since 2.1
		 *
		 * @param bool $use_strict Whether to use 'strict' mode. Default true.
		 */
		$this->use_strict = apply_filters( 'affwp_batch_import_affiliates_strict_usernames', true );

		$fields = affiliate_wp()->affiliates->get_columns();

		unset( $fields['affiliate_id'] );
		unset( $fields['user_id'] );

		$fields   = array_keys( $fields );
		$fields[] = 'user_name';
		$fields   = array_fill_keys( $fields, '' );

		$this->map_fields( $fields );

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
	public function init( $data = null ) {
		if ( null !== $data ) {
			if ( ! empty( $data['affwp-import-field'] ) ) {
				$this->data = $data['affwp-import-field'];
			}
		}
	}

	/**
	 * Pre-fetches data to speed up processing.
	 *
	 * @access public
	 * @since  2.1
	 */
	public function pre_fetch() {
		$total_to_import = $this->get_total_count();

		if ( false === $total_to_import  ) {
			$this->set_total_count( absint( $this->total ) );
		}
	}

	/**
	 * Processes a single step of importing affiliates.
	 *
	 * @access public
	 * @since  2.1
	 *
	 * @return int|string
	 */
	public function process_step() {
		if ( ! $this->can_import() ) {
			wp_die( __( 'You do not have permission to import data.', 'affiliate-wp' ), __( 'Error', 'affiliate-wp' ), array( 'response' => 403 ) );
		}

		$current_count = $this->get_current_count();
		$running_count = 0;
		$offset        = $this->get_offset();

		if ( $offset >= $this->total ) {
			return 'done';
		}

		$core_fields = array_keys( $this->get_columns() );

		if ( $this->csv->data ) {
			$data = $this->csv->data;

			$data = array_slice( $data, $offset, null, true );

			foreach ( $data as $key => $row ) {
				$args = $this->map_row( $core_fields, $row );

				if ( empty( $args['payment_email'] ) ) {
					continue;
				}

				if ( $user_id = $this->create_user( $args ) ) {
					$args['user_id'] = $user_id;

					if ( false !== affwp_add_affiliate( $args ) ) {
						$running_count ++;
					}
				}
			}
		}

		$this->set_current_count( $current_count + absint( $running_count ) );

		return ++$this->step;
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
		$this->field_mapping = $import_fields;
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

	/**
	 * Helper that attempts to create a user account for the new affiliate.
	 *
	 * If a user account is found matching the given payment email, that user ID is returned instead.
	 *
	 * @access protected
	 * @since  2.1
	 *
	 * @param array $args Arguments for adding a new affiliate.
	 * @return int|false User ID if a user was found or derived, otherwise false.
	 */
	protected function create_user( $args ) {
		$user = get_user_by( 'email', $args['payment_email'] );

		if ( $user ) {
			if ( affiliate_wp()->affiliates->get_by( 'user_id', $user->ID ) ) {
				return false;
			} else {
				return $user->ID;
			}
		} else {
			$first_name = $last_name = '';

			if ( ! empty( $args['first_name'] ) ) {
				$first_name = sanitize_text_field( $args['first_name'] );
			}

			if ( ! empty( $args['last_name'] ) ) {
				$last_name = sanitize_text_field( $args['last_name'] );
			}

			$user_id = wp_insert_user( array(
				'user_login' => sanitize_user( $args['payment_email'], $this->use_strict ),
				'user_email' => sanitize_email( $args['payment_email'] ),
				'user_pass'  => wp_generate_password( 20, false ),
				'first_name' => $first_name,
				'last_name'  => $last_name,
			) );

			if ( ! is_wp_error( $user_id ) ) {
				return $user_id;
			} else {
				return false;
			}
		}
	}

	/**
	 * Defines logic to execute once batch processing is complete.
	 *
	 * @access public
	 * @since  2.1
	 */
	public function finish() {
		// Invalidate the affiliates cache.
		wp_cache_set( 'last_changed', microtime(), 'affiliates' );

		parent::finish();
	}

}
