<?php
/**
 * @package TSF_Extension_Manager\Extension\Local\Admin
 * @package TSF_Extension_Manager\Extension\Local\Front
 */
namespace TSF_Extension_Manager\Extension\Local;

defined( 'ABSPATH' ) or die;

if ( \tsf_extension_manager()->_has_died() or false === ( \tsf_extension_manager()->_verify_instance( $_instance, $bits[1] ) or \tsf_extension_manager()->_maybe_die() ) )
	return;

/**
 * Local extension for The SEO Framework
 * Copyright (C) 2017 Sybre Waaijer, CyberWire (https://cyberwire.nl/)
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
 * Require extension options trait.
 * @since 1.0.0
 */
\TSF_Extension_Manager\_load_trait( 'extension-options' );

/**
 * @package TSF_Extension_Manager\Traits
 */
use \TSF_Extension_Manager\Enclose_Stray_Private as Enclose_Stray_Private;
use \TSF_Extension_Manager\Construct_Core_Interface as Construct_Core_Interface;
use \TSF_Extension_Manager\Extension_Options as Extension_Options;

/**
 * Class TSF_Extension_Manager\Extension\Local\Core
 *
 * Holds extension core methods.
 *
 * @since 1.0.0
 * @access private
 */
class Core {
	use Enclose_Stray_Private, Construct_Core_Interface, Extension_Options;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	private function construct() {

		$that = __NAMESPACE__ . ( \is_admin() ? '\\Admin' : '\\Front' );
		$this instanceof $that or \wp_die( -1 );

		$this->_init_options();
	}

	/**
	 * Initializes extension options.
	 *
	 * @since 1.0.0
	 * @uses trait \TSF_Extension_Manager\Extension_Options
	 */
	protected function _init_options() {

		$this->o_index = 'local';
	}
}
