<?php

namespace WebImage\Store\Payments;

/**
 * Consolidate payment interface display to one class.
 * At this time this helper class is only used by
 * billing.html.php to build the payment interface.
 * It is not intended to be used anywhere else, or to be entirely flexible.
 */
class PaymentInterfaceHelper {
	function attachPaymentOptionsToControlByName($placeholder_name, $order) {
		// Get payment info
		$payments = $order->getPayments();
		if (isset($payments[0])) $payment = $payments[0];

		// Get object (no error checking)
		$placeholder_object = Page::getControlById($placeholder_name);

		// Load required logic
		FrameworkManager::loadLogic('paymentoption');
		FrameworkManager::loadLogic('customerpaymentmethod');

		// Get available payment options
		$available_payment_options = PaymentOptionLogic::getPaymentOptions();

		// Add required javascript library
		Page::addScript(ConfigurationManager::get('DIR_WS_GASSETS_JS') . 'jquery/jquery.js');

		if ($available_payment_options->getCount() == 0) {
			$error_control = new LiteralControl();
			$error_control->setText('<p>There are not currently any payment methods available.  Site may be misconfigured.  If this problem persists, please contact support.');
			$placeholder_object->addControl($error_control);
		}
		// Iterate thru options
		while ($available_payment_option = $available_payment_options->getNext()) {

			$payment_option_id = 'payment_' . $available_payment_option->file_key;
			$payment_option_checkbox_id = 'payment_option_' . $available_payment_option->file_key;

			// Only display the payment option as an option if there are multiple options available
			if ($available_payment_options->getCount() > 1) {
				$input_type = 'radio';
			} else {
				$input_type = 'hidden';
			}

			// Create the input control (radio or hidden)
			$input_control_options = [
				'id'		=> $payment_option_checkbox_id,
				'structKey'	=> 'payment_option',
				'type'		=> $input_type,
				'value'		=> $available_payment_option->file_key,
				'text'		=> $available_payment_option->name
			];

			$has_option = false;

			if ($payment_option = PaymentManager::getPaymentOption($available_payment_option)) {
				$payment_option_struct = $payment_option->getPaymentOption();

				if (isset($payment) && $payment_option_struct->id == $payment->payment_option_id) {

					$payment_option->setPayment( CustomerPaymentMethodLogic::unlockAndSummarizePaymentStruct($payment) );
				}

				$payment_option_interface = $payment_option->getInterface();

				$payment_method_control = new LiteralControl(['class'=>'payment-option', 'style'=>'border:1px solid #ccc;']);
				$payment_method_control->setWrapOutput('<div%s>%s</div>');
				$payment_method_control->setId($payment_option_id);
				$payment_method_control->setText($payment_option_interface);

				$has_option = true;

			}

			$select_payment_option = new InputControl($input_control_options);

			if (Page::get('payment_option') != $available_payment_option->file_key && $available_payment_options->getCount() > 1) {
				$hide_payment_option = "$('#" . $payment_option_id . "').hide();";
			} else {
				$hide_payment_option = '';
			}

			// Wrap whole payment option
			$wrap_payment_method = new LiteralControl(['class'=>'payment-option-container']);
			$wrap_payment_method->setWrapOutput('
								<div%s>%s
									<script type="text/javascript" language="javascript">
										' . $hide_payment_option . '
										$(\'#' . $payment_option_checkbox_id . '\').click(function() {
											$(\'#' . $placeholder_name . ' .payment-option:not(' . $payment_option_id . ')\').hide();
											$(\'#' . $payment_option_id . '\').show();
										});
									</script>
								</div>');

			// Wrap payment option label
			$wrap_payment_label = new LiteralControl(['class'=>'payment-option-label']);
			$wrap_payment_label->setWrapOutput('<div%s>%s</div>');

			// Add select box to label
			$wrap_payment_label->addControl($select_payment_option);

			// Add label to payment option
			$wrap_payment_method->addControl($wrap_payment_label);

			if ($has_option) {
				$wrap_payment_method->addControl($payment_method_control);
			}

			// Add payment option to payment option list
			$placeholder_object->addControl($wrap_payment_method);
		}
	}
}