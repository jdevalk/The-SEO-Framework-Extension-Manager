<?php
/**
 * @package TSF_Extension_Manager\Classes
 */
namespace TSF_Extension_Manager;

defined( 'ABSPATH' ) or die;

/**
 * The SEO Framework - Extension Manager plugin
 * Copyright (C) 2016 Sybre Waaijer, CyberWire (https://cyberwire.nl/)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 3 as published
 * by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Class TSF_Extension_Manager\Layout.
 *
 * Outputs layout based on instance.
 *
 * @since 1.0.0
 * @access private
 * 		You'll need to invoke the TSF_Extension_Manager\Core verification handler. Which is impossible.
 * @final Please don't extend this.
 */
final class Layout extends Secure_Abstract {

	/**
	 * Initializes class variables. Always use reset when done with this class.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type Required. The instance type.
	 * @param string $instance Required. The instance key.
	 * @param int $bit Required. The instance bit.
	 */
	public static function initialize( $type = '', $instance = '', $bits = null ) {

		self::reset();

		if ( empty( $type ) ) {
			the_seo_framework()->_doing_it_wrong( __METHOD__, 'You must specify an initialization type.' );
		} else {

			self::set( '_wpaction' );

			switch ( $type ) :
				case 'form' :
				case 'link' :
				case 'list' :
					tsf_extension_manager()->_verify_instance( $instance, $bits[1] ) or die;
					self::set( '_type', $type );
					break;

				default :
					self::reset();
					self::invoke_invalid_type( __METHOD__ );
					break;
			endswitch;
		}
	}

	/**
	 * Returns the layout call.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type Required. Determines what to get.
	 * @return string
	 */
	public static function get( $type = '' ) {

		self::verify_instance() or die;

		if ( empty( $type ) ) {
			the_seo_framework()->_doing_it_wrong( __METHOD__, 'You must specify an get type.' );
			return false;
		}

		switch ( $type ) :
			case 'deactivation-button' :
				return static::get_deactivation_button();
				break;

			case 'free-support-button' :
				return static::get_free_support_button();
				break;

			case 'premium-support-button' :
				return static::get_premium_support_button();
				break;

			case 'account-information' :
				return static::get_account_info();
				break;

			case 'account-upgrade' :
				return static::get_account_upgrade_form();
				break;

			default :
				the_seo_framework()->_doing_it_wrong( __METHOD__, 'You must specify a correct get type.' );
				break;
		endswitch;

		return false;
	}

	/**
	 * Outputs deactivation button.
	 *
	 * @since 1.0.0
	 *
	 * @return string The deactivation button.
	 */
	private static function get_deactivation_button() {

		$output = '';

		if ( 'form' === self::get_property( '_type' ) ) {
			$nonce_action = tsf_extension_manager()->get_nonce_action_field( self::$request_name['deactivate'] );
			$nonce = wp_nonce_field( self::$nonce_action['deactivate'], self::$nonce_name, true, false );

			$field_id = 'deactivation-switcher';
			$deactivate_i18n = __( 'Deactivate', 'the-seo-framework-extension-manager' );
			$ays_i18n = __( 'Are you sure?', 'the-seo-framework-extension-manager' );
			$da_i18n = __( 'Deactivate account?', 'the-seo-framework-extension-manager' );

			$button = '<input type="submit" id="' . $field_id . '-validator">'
					. '<label for="' . $field_id . '-validator" title="' . esc_attr( $ays_i18n ) . '" class="tsfem-button-primary tsfem-switcher-button tsfem-button-warning">' . esc_html( $deactivate_i18n ) . '</label>';

			$switcher = '<div class="tsfem-switch-button-container-wrap"><div class="tsfem-switch-button-container">'
							. '<input type="checkbox" id="' . $field_id . '-action" value="1" />'
							. '<label for="' . $field_id . '-action" title="' . esc_attr( $da_i18n ) . '" class="tsfem-button tsfem-button-flag">' . esc_html( $deactivate_i18n ) . '</label>'
							. $button
						. '</div></div>';

			$output = sprintf( '<form name="deactivate" action="%s" method="post" id="tsfem-deactivation-form">%s</form>',
				esc_url( tsf_extension_manager()->get_admin_page_url() ),
				$nonce_action . $nonce . $switcher
			);
		} else {
			the_seo_framework()->_doing_it_wrong( __METHOD__, 'The deactivation button only supports the form instance.' );
		}

		return $output;
	}

	/**
	 * Outputs free support button.
	 *
	 * @since 1.0.0
	 *
	 * @return string The free support button link.
	 */
	private static function get_free_support_button() {

		if ( 'link' === self::get_property( '_type' ) ) {
			return tsf_extension_manager()->get_support_link( 'free' );
		} else {
			the_seo_framework()->_doing_it_wrong( __METHOD__, 'The free support button only supports the link type.' );
			return '';
		}
	}

	/**
	 * Outputs premium support button.
	 *
	 * @since 1.0.0
	 *
	 * @return string The premium support button link.
	 */
	private static function get_premium_support_button() {

		if ( 'link' === self::get_property( '_type' ) ) {
			return tsf_extension_manager()->get_support_link( 'premium' );
		} else {
			the_seo_framework()->_doing_it_wrong( __METHOD__, 'The premium support button only supports the link type.' );
			return '';
		}
	}


	/**
	 * Outputs premium support button.
	 *
	 * @since 1.0.0
	 *
	 * @return string The premium support button link.
	 */
	private static function get_account_info() {

		if ( 'list' === self::get_property( '_type' ) ) {
			$account = self::$account;

			$email = isset( $account['email'] ) ? $account['email'] : '';
			$data = isset( $account['data'] ) ? $account['data'] : '';
			$level = isset( $account['level'] ) ? $account['level'] : '';
			$domain = '';
			$end_date = '';

			if ( $data ) {
				//* UTC.
				$end_date = isset( $data['status']['status_extra']['end_date'] ) ? $data['status']['status_extra']['end_date'] : '';

				$domain = isset( $data['status']['status_extra']['activation_domain'] ) ? $data['status']['status_extra']['activation_domain'] : '';
			}

			$output = '';

			if ( $email )
				$output .= static::wrap_title_content( __( 'Account email:', 'the-seo-framework-extension-manager' ), $email );

			if ( $level )
				$output .= static::wrap_title_content( __( 'Account level:', 'the-seo-framework-extension-manager' ), $level );

			if ( $domain ) {
				//* Check for domain mismatch. If they don't match no premium extensions can be activated.
				$_domain = str_ireplace( array( 'http://', 'https://' ), '', home_url() );
				$class = $_domain === $domain ? 'tsfem-success' : 'tsfem-error';

				$domain = sprintf( '<span class="tsfem-dashicon %s">%s</time>', esc_attr( $class ), esc_html( $_domain ) );

				$output .= static::wrap_title_content( esc_html__( 'Valid for:', 'the-seo-framework-extension-manager' ), $domain, false );
			}

			if ( $end_date ) {
				$date_until = strtotime( $end_date );
				$now = time();

				$difference = $date_until - $now;
				$class = 'tsfem-success';
				$expires_in = '';

				if ( $difference < 0 ) {
					//* Expired.
					$expires_in = __( 'Account expired', 'the-seo-framework-extension-manager' );
					$class = 'tsfem-error';
				} elseif ( $difference < WEEK_IN_SECONDS ) {
					$expires_in = __( 'Less than a week', 'the-seo-framework-extension-manager' );
					$class = 'tsfem-warning';
				} elseif ( $difference < WEEK_IN_SECONDS * 2 ) {
					$expires_in = __( 'Less than two weeks', 'the-seo-framework-extension-manager' );
					$class = 'tsfem-warning';
				} elseif ( $difference < WEEK_IN_SECONDS * 3 ) {
					$expires_in = __( 'Less than three weeks', 'the-seo-framework-extension-manager' );
				} elseif ( $difference < MONTH_IN_SECONDS ) {
					$expires_in = __( 'Less than a month', 'the-seo-framework-extension-manager' );
				} elseif ( $difference < MONTH_IN_SECONDS * 2 ) {
					$expires_in = __( 'Less than two months', 'the-seo-framework-extension-manager' );
				} else {
					$expires_in = sprintf( __( 'About %d months', 'the-seo-framework-extension-manager' ), round( $difference / MONTH_IN_SECONDS ) );
				}

				$end_date = isset( $end_date ) ? date( 'Y-m-d', strtotime( $end_date ) ) : '';
				$date_until = isset( $date_until ) ? date_i18n( get_option( 'date_format' ), $date_until ) : '';
				$expires_in = sprintf( '<time class="tsfem-question-cursor tsfem-dashicon %s" title="%s" datetime="%s">%s</time>', esc_attr( $class ), esc_attr( $date_until ), esc_attr( $end_date ), esc_html( $expires_in ) );

				$output .= static::wrap_title_content( esc_html__( 'Expires in:', 'the-seo-framework-extension-manager' ), $expires_in, false );
			}

			return sprintf( '<div class="tsfem-flex-account-info-rows tsfem-flex tsfem-flex-nogrowshrink">%s</div>', $output );
		} else {
			the_seo_framework()->_doing_it_wrong( __METHOD__, 'The premium account information output only supports list type.' );
			return '';
		}
	}

	/**
	 * Wraps columnized Title/Content output.
	 * Escapes input prior to outputting when $escape is true.
	 *
	 * @since 1.0.0
	 *
	 * @param string $title The title.
	 * @param string $content The content.
	 * @param bool $escape Whether to escape the output.
	 * @return string The Title/Content wrap.
	 */
	private static function wrap_title_content( $title, $content, $escape = true ) {

		if ( $escape ) {
			$title = esc_html( $title );
			$content = esc_html( $content );
		}

		$output = sprintf( '<div class="tsfem-actions-account-info-title">%s</div><div class="tsfem-actions-account-info-content">%s</div>', $title, $content );

		return sprintf( '<div class="tsfem-flex tsfem-flex-row tsfem-flex-space tsfem-flex-noshrink">%s</div>', $output );
	}

	/**
	 * Outputs premium support button.
	 *
	 * @since 1.0.0
	 *
	 * @return string The premium support button link.
	 */
	private static function get_account_upgrade_form() {

		if ( 'form' === self::get_property( '_type' ) ) {
			$input = sprintf( '<input id="%s" name="%s" type="text" size="15" value="" class="regular-text code tsfem-flex tsfem-flex-row" placeholder="%s">', tsf_extension_manager()->get_field_id( 'key' ), tsf_extension_manager()->get_field_name( 'key' ), esc_attr( 'License key', 'the-seo-framework-extension-manager' ) );
			$input .= sprintf( '<input id="%s" name="%s" type="text" size="15" value="" class="regular-text code tsfem-flex tsfem-flex-row" placeholder="%s">', tsf_extension_manager()->get_field_id( 'email' ), tsf_extension_manager()->get_field_name( 'email' ), esc_attr( 'License email', 'the-seo-framework-extension-manager' ) );

			$nonce_action = tsf_extension_manager()->get_nonce_action_field( self::$request_name['activate-key'] );
			$nonce = wp_nonce_field( self::$nonce_action['activate-key'], self::$nonce_name, true, false );

			$submit = sprintf( '<input type="submit" name="submit" id="submit" class="tsfem-button tsfem-button-primary" value="%s">', esc_attr( 'Use this key', 'the-seo-framework-extension-manager' ) );

			$form = $input . $nonce_action . $nonce . $submit;

			return sprintf( '<form name="%s" action="%s" method="post" id="%s" class="%s">%s</form>', esc_attr( self::$request_name['activate-key'] ), esc_url( tsf_extension_manager()->get_admin_page_url() ), 'input-activation', '', $form );
		} else {
			the_seo_framework()->_doing_it_wrong( __METHOD__, 'The upgrade form only supports the form type.' );
			return '';
		}
	}
}
