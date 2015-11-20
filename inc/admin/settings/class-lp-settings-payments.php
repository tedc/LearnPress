<?php

/**
 * Class LP_Settings_Payment
 *
 * @author ThimPress
 * @package LearnPress/Admin/Classes
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class LP_Settings_Payments extends LP_Settings_Base {
	/**
	 * Constructor
	 */
	function __construct() {
		$this->id   = 'payments';
		$this->text = __( 'Payments', 'learn_press' );
		parent::__construct();
	}

	/**
	 * @return mixed
	 */
	function get_sections() {
		$gateways = LP_Gateways::instance()->get_gateways();
		if( $gateways ) foreach( $gateways as $id => $gateway ){
			$gateways[ $id ] = $gateway->get_title();
		}
		return $gateways;
	}

	function output() {
		$section = $this->section;
		?>
		<h3 class=""><?php echo $this->section->text; ?></h3>
		<table class="form-table">
			<tbody>
			<?php
			if ( 'paypal' == $section['id'] ) {
				$this->output_section_paypal();
			} else {
				do_action( 'learn_press_section_' . $this->id . '_' . $section['id'] );
			}
			?>
			</tbody>
		</table>
		<script type="text/javascript">
			jQuery(function ($) {
				var $sandbox_mode = $('#learn_press_paypal_sandbox_mode'),
					$paypal_type = $('#learn_press_paypal_type');
				$paypal_type.change(function () {
					$('.learn_press_paypal_type_security').toggleClass('hide-if-js', 'security' != this.value);
				});
				$sandbox_mode.change(function () {
					this.checked ? $('.sandbox input').removeAttr('readonly') : $('.sandbox input').attr('readonly', true);
				});
			})
		</script>
		<?php
	}

	/**
	 * Print admin options for paypal section
	 */
	function output_section_paypal() {
		$view = learn_press_get_admin_view( 'settings/payments.php' );
		include_once $view;
	}

	function saves() {

		$settings = LP_Admin_Settings::instance( 'payment' );
		$section  = $this->section['id'];
		if ( 'paypal' == $section ) {
			$post_data = $_POST['lpr_settings'][$this->id];

			$settings->set( 'paypal', $post_data );
		} else {
			do_action( 'learn_press_save_' . $this->id . '_' . $section );
		}
		$settings->update();

	}
}

new LP_Settings_Payments();