<?php
/* 
 * Plugin Name: Social Profiles
 * Plugin URI:  http://zerowp.com/social-profiles
 * Description: Link to profiles of the most popular social networks.
 * Author:      ZeroWP Team
 * Author URI:  http://zerowp.com/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: social-profiles
 * Domain Path: /languages
 *
 * Version:     1.0
 * 
 */
if ( ! defined( 'ABSPATH' ) ) exit;

final class ZeroWPSocialProfiles{

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public $version = '1.0';

	/**
	 * Get all brand icons with their color and name
	 *
	 * @var array
	 */
	public $brands;

	/**
	 * This is the only instance of this class.
	 *
	 * @var string
	 */
	protected static $_instance = null;
	
	//------------------------------------//--------------------------------------//
	
	/**
	 * Plugin instance
	 *
	 * Makes sure that just one instance is allowed.
	 *
	 * @return object 
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Cloning is forbidden.
	 *
	 * @return void 
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'social-profiles' ), '1.0' );
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @return void 
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'social-profiles' ), '1.0' );
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Plugin configuration
	 *
	 * @param string $key Optional. Get the config value by key.
	 * @return mixed 
	 */
	public function config( $key = false ){
		$settings = array(
			'version'       => $this->version,
			
			// Widget settings
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'
		);

		if( !empty($key) && array_key_exists($key, $settings) ){
			return $settings[ $key ];
		}
		else{
			return $settings;
		}
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Build it!
	 */
	public function __construct() {
		$this->constants();
		$this->includeCore();
		$this->initPlugin();

		do_action( 'zsp_loaded' );
	}
	
	//------------------------------------//--------------------------------------//
	
	/**
	 * Define constants
	 *
	 * @return void 
	 */
	private function constants() {
		$this->define( 'ZSP_PLUGIN_FILE', __FILE__ );
		$this->define( 'ZSP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'ZSP_VERSION', $this->version );

		$this->define( 'ZSP_PATH', $this->rootPath() );
		$this->define( 'ZSP_URL', $this->rootURL() );
		$this->define( 'ZSP_URI', ZSP_URL );//Alias
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Define a constant
	 *
	 * @param string $name The constant name
	 * @param mixed $value The constant value
	 * @return void 
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Include core files
	 *
	 * @return void 
	 */
	private function includeCore() {
		include $this->rootPath() . "autoloader.php";
		include $this->rootPath() . "functions.php";

	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Init the plugin
	 *
	 * @return void 
	 */
	private function initPlugin() {
		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'init' ), 0 );
		add_action( 'widgets_init', array( $this, 'initWidgets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'adminAssets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

		$this->brands = SocialProfiles\Brands::all();

	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Init the plugin
	 *
	 * @return void 
	 */
	public function init() {
		do_action( 'before_zsp_init' );

		$this->loadTextDomain();

		new SocialProfiles\TestIcons;

		do_action( 'zsp_init' );
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Init the widgets of this plugin
	 *
	 * @return void 
	 */
	public function initWidgets() {
		do_action( 'before_zsp_widgets_init' );

		register_widget( 'SocialProfiles\WidgetSocialProfiles' );

		do_action( 'zsp_widgets_init' );
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Localize
	 *
	 * @return void 
	 */
	public function loadTextDomain(){
		load_plugin_textdomain( 'social-profiles', false, dirname( plugin_basename(__FILE__) ) . '/languages' );
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Assets
	 *
	 * Register/Enqueue global admin assets
	 *
	 * @return void 
	 */
	public function adminAssets(){
		$id     = 'social-profiles';
		$assets = $this->rootURL() . 'assets/';
		
		$screen = get_current_screen();
		if( is_admin() && ( 'widgets' == $screen->base ) ){

			wp_register_style( $id . '-admin-css', $assets . 'admin.css', '', $this->version );
			wp_enqueue_style( $id . '-admin-css' );

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );

			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-sortable');

		}
	}

	//------------------------------------//--------------------------------------//
	
	/**
	 * Enqueue
	 *
	 * Register/Enqueue global front end assets
	 *
	 * @return void 
	 */
	public function enqueue(){
		$id     = 'social-profiles';
		$assets = $this->rootURL() . 'assets/';
	
		wp_register_style( $id . '-frontend', $assets . 'styles.css', '', $this->version );
		wp_enqueue_style( $id . '-frontend' );
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Actions when the plugin is installed
	 *
	 * @return void
	 */
	public function install() {
		// ?
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Get Root URL
	 *
	 * @return string
	 */
	public function rootURL(){
		return plugin_dir_url( __FILE__ );
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Get Root PATH
	 *
	 * @return string
	 */
	public function rootPath(){
		return plugin_dir_path( __FILE__ );
	}

}


/*
-------------------------------------------------------------------------------
Main plugin instance
-------------------------------------------------------------------------------
*/
function ZSP() {
	return ZeroWPSocialProfiles::instance();
}

/*
-------------------------------------------------------------------------------
Rock it!
-------------------------------------------------------------------------------
*/
ZSP();