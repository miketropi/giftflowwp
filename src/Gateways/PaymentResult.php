<?php
/**
 * Payment Result value object.
 *
 * @package GiftFlow
 * @subpackage Gateways
 */

namespace GiftFlow\Gateways;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Immutable result returned by PaymentProcessorInterface::process_payment().
 */
class PaymentResult {

	/**
	 * Whether the payment succeeded.
	 *
	 * @var bool
	 */
	private bool $success;

	/**
	 * Transaction ID from the gateway.
	 *
	 * @var string
	 */
	private string $transaction_id;

	/**
	 * Redirect URL (for off-site gateways like PayPal).
	 *
	 * @var string
	 */
	private string $redirect_url;

	/**
	 * Human-readable message.
	 *
	 * @var string
	 */
	private string $message;

	/**
	 * Raw response data from the gateway.
	 *
	 * @var array
	 */
	private array $raw_response;

	/**
	 * Constructor.
	 *
	 * @param bool   $success        Whether payment succeeded.
	 * @param string $transaction_id Gateway transaction ID.
	 * @param string $redirect_url   URL to redirect to (off-site payments).
	 * @param string $message        Human-readable message.
	 * @param array  $raw_response   Raw gateway response data.
	 */
	public function __construct(
		bool $success = false,
		string $transaction_id = '',
		string $redirect_url = '',
		string $message = '',
		array $raw_response = array()
	) {
		$this->success        = $success;
		$this->transaction_id = $transaction_id;
		$this->redirect_url   = $redirect_url;
		$this->message        = $message;
		$this->raw_response   = $raw_response;
	}

	/**
	 * Create a successful result.
	 *
	 * @param string $transaction_id Gateway transaction ID.
	 * @param string $message        Optional message.
	 * @return self
	 */
	public static function success( string $transaction_id, string $message = '' ): self {
		return new self( true, $transaction_id, '', $message );
	}

	/**
	 * Create a failed result.
	 *
	 * @param string $message      Error message.
	 * @param array  $raw_response Optional raw response.
	 * @return self
	 */
	public static function failure( string $message, array $raw_response = array() ): self {
		return new self( false, '', '', $message, $raw_response );
	}

	/**
	 * Create a redirect result (off-site payment like PayPal).
	 *
	 * @param string $redirect_url URL to redirect to.
	 * @return self
	 */
	public static function redirect( string $redirect_url ): self {
		return new self( true, '', $redirect_url );
	}

	/**
	 * Check if payment was successful.
	 *
	 * @return bool
	 */
	public function is_success(): bool {
		return $this->success;
	}

	/**
	 * Check if a redirect is needed.
	 *
	 * @return bool
	 */
	public function needs_redirect(): bool {
		return '' !== $this->redirect_url;
	}

	/**
	 * Get the transaction ID.
	 *
	 * @return string
	 */
	public function get_transaction_id(): string {
		return $this->transaction_id;
	}

	/**
	 * Get the redirect URL.
	 *
	 * @return string
	 */
	public function get_redirect_url(): string {
		return $this->redirect_url;
	}

	/**
	 * Get the message.
	 *
	 * @return string
	 */
	public function get_message(): string {
		return $this->message;
	}

	/**
	 * Get the raw gateway response.
	 *
	 * @return array
	 */
	public function get_raw_response(): array {
		return $this->raw_response;
	}

	/**
	 * Convert to array (useful for JSON/AJAX responses).
	 *
	 * @return array
	 */
	public function to_array(): array {
		return array(
			'success'        => $this->success,
			'transaction_id' => $this->transaction_id,
			'redirect_url'   => $this->redirect_url,
			'message'        => $this->message,
		);
	}
}
