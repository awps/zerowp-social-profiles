<?php
/* 
 * Plugin Name: ZeroWP Social Profiles
 * Plugin URI:  http://zerowp.com/social-profiles
 * Description: Create links to profiles from 170+ social networks
 * Author:      ZeroWP Team
 * Author URI:  http://zerowp.com/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: zerowp-social-profiles
 * Domain Path: /languages
 *
 * Version:     1.0
 * 
 */

/* No direct access allowed!
---------------------------------*/
if ( ! defined( 'ABSPATH' ) ) exit;

/* Plugin configuration
----------------------------*/
function zsp_config( $key = false ){
	$settings = apply_filters( 'zsp:config_args', array(
		
		// Plugin data
		'version'          => '1.0',
		'min_php_version'  => '5.3',
		
		// The list of required plugins. 'slug' => array 'name and uri'
		'required_plugins' => array(
			// 'test' => array(
			// 	'plugin_name' => 'Test',
			// 	'plugin_uri' => 'http://example.com/'
			// ),
			// 'another-test' => array(
			// 	'plugin_name' => 'Another Test',
			// ),
		),

		// The priority in plugins loaded. Only if has required plugins
		'priority'         => 10,

		// Main action. You may need to change it if is an extension for another plugin.
		'action_name'      => 'init',

		// Plugin branding
		'plugin_name'      => __( 'ZeroWP Social Profiles', 'zerowp-social-profiles' ),
		'id'               => 'zerowp-social-profiles',
		'namespace'        => 'SocialProfiles',
		'uppercase_prefix' => 'ZSP',
		'lowercase_prefix' => 'zsp',
		
		// Access to plugin directory
		'file'             => __FILE__,
		'lang_path'        => plugin_dir_path( __FILE__ ) . 'languages',
		'basename'         => plugin_basename( __FILE__ ),
		'path'             => plugin_dir_path( __FILE__ ),
		'url'              => plugin_dir_url( __FILE__ ),
		'uri'              => plugin_dir_url( __FILE__ ),//Alias

		// Widget settings
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',

		// Icon sizes
		'icon_size' => apply_filters( 'zsp_icon_size', array(
			''            => __( 'Default', 'zerowp-social-profiles' ),
			'large'       => __( 'Large', 'zerowp-social-profiles' ),
			'extra-large' => __( 'Extra large', 'zerowp-social-profiles' ),
		)),

		// Icon shapes
		'icon_shape' => apply_filters( 'zsp_icon_shape', array(
			''            => __( 'Default', 'zerowp-social-profiles' ),
			'circle'      => __( 'Circle', 'zerowp-social-profiles' ),
			'burst'       => __( 'Burst', 'zerowp-social-profiles' ),
			'burst-alt'   => __( 'Burst alt', 'zerowp-social-profiles' ),
			'rotated'     => __( 'Rotated', 'zerowp-social-profiles' ),
			'transparent' => __( 'Transparent', 'zerowp-social-profiles' ),
			'minimal'     => __( 'Minimal', 'zerowp-social-profiles' ),
		)),

		// Icon radius
		'icon_radius' => apply_filters( 'zsp_icon_radius', array(
			''          => __( 'Default', 'zerowp-social-profiles' ),
			'soft'      => __( 'Soft', 'zerowp-social-profiles' ),
			'square'    => __( 'Square', 'zerowp-social-profiles' ),
		)),

	));

	// Make sure that PHP version is set to 5.3+
	if( version_compare( $settings[ 'min_php_version' ], '5.3', '<' ) ){
		$settings[ 'min_php_version' ] = '5.3';
	}

	// Get the value by key
	if( !empty($key) ){
		if( array_key_exists($key, $settings) ){
			return $settings[ $key ];
		}
		else{
			return false;
		}
	}

	// Get settings
	else{
		return $settings;
	}
}

/* Define the current version of this plugin.
-----------------------------------------------------------------------------*/
define( 'ZSP_VERSION',         zsp_config( 'version' ) );
 
/* Plugin constants
------------------------*/
define( 'ZSP_PLUGIN_FILE',     zsp_config( 'file' ) );
define( 'ZSP_PLUGIN_BASENAME', zsp_config( 'basename' ) );

define( 'ZSP_PATH',            zsp_config( 'path' ) );
define( 'ZSP_URL',             zsp_config( 'url' ) );
define( 'ZSP_URI',             zsp_config( 'url' ) ); // Alias

/* Minimum PHP version required
------------------------------------*/
define( 'ZSP_MIN_PHP_VERSION', zsp_config( 'min_php_version' ) );

/* Plugin Init
----------------------*/
final class ZSP_Plugin_Init{

	public function __construct(){
		
		$required_plugins = zsp_config( 'required_plugins' );
		$missed_plugins   = $this->missedPlugins();

		/* The installed PHP version is lower than required.
		---------------------------------------------------------*/
		if ( version_compare( PHP_VERSION, ZSP_MIN_PHP_VERSION, '<' ) ) {

			require_once ZSP_PATH . 'warnings/php-warning.php';
			new ZSP_PHP_Warning;

		}

		/* Required plugins are not installed/activated
		----------------------------------------------------*/
		elseif( !empty( $required_plugins ) && !empty( $missed_plugins ) ){

			require_once ZSP_PATH . 'warnings/noplugin-warning.php';
			new ZSP_NoPlugin_Warning( $missed_plugins );

		}

		/* We require some plugins and all of them are activated
		-------------------------------------------------------------*/
		elseif( !empty( $required_plugins ) && empty( $missed_plugins ) ){
			
			add_action( 
				'plugins_loaded', 
				array( $this, 'getSource' ), 
				zsp_config( 'priority' ) 
			);

		}

		/* We don't require any plugins. Include the source directly
		----------------------------------------------------------------*/
		else{

			$this->getSource();

		}

	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Get plugin source
	 *
	 * @return void 
	 */
	public function getSource(){
		require_once ZSP_PATH . 'plugin.php';
		
		$components = glob( ZSP_PATH .'components/*', GLOB_ONLYDIR );
		foreach ($components as $component_path) {
			require_once trailingslashit( $component_path ) .'component.php';
		}
	
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Missed plugins
	 *
	 * Get an array of missed plugins
	 *
	 * @return array 
	 */
	public function missedPlugins(){
		$required = zsp_config( 'required_plugins' );
		$active   = $this->activePlugins();
		$diff     = array_diff_key( $required, $active );

		return $diff;
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Active plugins
	 *
	 * Get an array of active plugins
	 *
	 * @return array 
	 */
	public function activePlugins(){
		$active = get_option('active_plugins');
		$slugs  = array();

		if( !empty($active) ){
			$slugs = array_flip( array_map( array( $this, '_filterPlugins' ), (array) $active ) );
		}

		return $slugs;
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Filter plugins callback
	 *
	 * @return string 
	 */
	protected function _filterPlugins( $value ){
		$plugin = explode( '/', $value );
		return $plugin[0];
	}

}

new ZSP_Plugin_Init;