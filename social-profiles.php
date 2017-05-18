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
		$settings = apply_filters( 'zsp_config_args', array(
			
			'version'       => $this->version,
			
			// Widget settings
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',

			// Icon sizes
			'icon_size' => apply_filters( 'zsp_icon_size', array(
				''            => __( 'Default', 'social-profiles' ),
				'large'       => __( 'Large', 'social-profiles' ),
				'extra-large' => __( 'Extra large', 'social-profiles' ),
			)),

			// Icon shapes
			'icon_shape' => apply_filters( 'zsp_icon_shape', array(
				''            => __( 'Default', 'social-profiles' ),
				'circle'      => __( 'Circle', 'social-profiles' ),
				'burst'       => __( 'Burst', 'social-profiles' ),
				'burst-alt'   => __( 'Burst alt', 'social-profiles' ),
				'rotated'     => __( 'Rotated', 'social-profiles' ),
				'transparent' => __( 'Transparent', 'social-profiles' ),
				'minimal'     => __( 'Minimal', 'social-profiles' ),
			)),

			// Icon radius
			'icon_radius' => apply_filters( 'zsp_icon_radius', array(
				''          => __( 'Default', 'social-profiles' ),
				'soft'      => __( 'Soft', 'social-profiles' ),
				'square'    => __( 'Square', 'social-profiles' ),
			)),

		));

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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'adminAssets' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customizerEnqueue' ) );
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

		$this->addUserFieldType( 'input', 'SocialProfiles\GeneralForm\FieldInput' );
		$this->addUserFieldType( 'textarea', 'SocialProfiles\GeneralForm\FieldTextarea' );
		$this->addUserFieldType( 'select', 'SocialProfiles\GeneralForm\FieldSelect' );
		$this->addUserFieldType( 'radio', 'SocialProfiles\GeneralForm\FieldRadio' );
		$this->addUserFieldType( 'brands_repeater', 'SocialProfiles\GeneralForm\FieldBrandsRepeater' );

		new SocialProfiles\User\BaseForm;

		$uf = new SocialProfiles\Create\UserFields;
		$uf->addFields();

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

		register_widget( 'SocialProfiles\Widget\NetworksList' );

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
	public function adminAssets( $is_customize = false ){
		$id     = 'social-profiles';
		$assets = $this->rootURL() . 'assets/';
		$screen = get_current_screen();

		if( !empty( $is_customize ) || ( is_admin() && ( 'widgets' == $screen->base || 'profile' == $screen->base ) ) ){

			wp_register_style( 
				$id . '-admin-css', 
				$assets . 'admin.css', 
				'', 
				$this->version 
			);
			wp_enqueue_style( $id . '-admin-css' );

			wp_register_script( 
				$id .'-config', 
				$assets .'config.js', 
				array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable' ), 
				$this->version, 
				true 
			);
			wp_enqueue_script( $id .'-config' );

		}
	}

	public function customizerEnqueue(){
		$this->adminAssets( true );
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

	//------------------------------------//--------------------------------------//

	/**
	 * Brands
	 *
	 * Get all brand icons with their color and name
	 *
	 * @return array 
	 */
	public function brands( $key = false ){
		$brands = array(
			'500px'           => array( '#58a9de', ),
			'8tracks'         => array( '#122c4b', ),
			'airbnb'          => array( '#ff5a5f', ),
			'alliance'        => array( '#144587', ),
			'amazon'          => array( '#ff9900', ),
			'amplement'       => array( '#0996c3', ),
			'android'         => array( '#8ec047', ),
			'angellist'       => array( '#000000', ),
			'apple'           => array( '#B9BFC1', ),
			'appnet'          => array( '#494949', ),
			'baidu'           => array( '#2629d9', ),
			'bandcamp'        => array( '#619aa9', ),
			'battlenet'       => array( '#0096CD', ),
			'beam'            => array( '#536dfe', ),
			'bebee'           => array( '#f28f16', ),
			'bebo'            => array( '#EF1011', ),
			'behance'         => array( '#000000', ),
			'blizzard'        => array( '#01B2F1', ),
			'blogger'         => array( '#ec661c', ),
			'buffer'          => array( '#000000', ),
			'chrome'          => array( '#757575', ),
			'coderwall'       => array( '#3E8DCC', ),
			'curse'           => array( '#f26522', ),
			'dailymotion'     => array( '#004e72', ),
			'deezer'          => array( '#32323d', ),
			'delicious'       => array( '#020202', ),
			'deviantart'      => array( '#c5d200', 'DeviantArt' ),
			'diablo'          => array( '#8B1209', ),
			'digg'            => array( '#1d1d1b', ),
			'discord'         => array( '#7289da', ),
			'disqus'          => array( '#2e9fff', ),
			'douban'          => array( '#3ca353', ),
			'draugiem'        => array( '#ffa32b', ),
			'dribbble'        => array( '#e84d88', ),
			'drupal'          => array( '#00598e', ),
			'ebay'            => array( '#333333', ),
			'ello'            => array( '#000000', ),
			'endomodo'        => array( '#86ad00', ),
			'envato'          => array( '#597c3a', ),
			'etsy'            => array( '#F56400', ),
			'facebook'        => array( '#3e5b98', ),
			'feedburner'      => array( '#ffcc00', ),
			'filmweb'         => array( '#ffc404', ),
			'firefox'         => array( '#484848', ),
			'flattr'          => array( '#F67C1A', ),
			'flickr'          => array( '#1e1e1b', ),
			'formulr'         => array( '#ff5a60', ),
			'forrst'          => array( '#5B9A68', ),
			'foursquare'      => array( '#f94877', ),
			'friendfeed'      => array( '#2F72C4', 'FriendFeed' ),
			'github'          => array( '#221e1b', 'GitHub' ),
			'goodreads'       => array( '#463020', ),
			'google'          => array( '#4285f4', ),
			'googlegroups'    => array( '#4F8EF5', 'Google Groups' ),
			'googlephotos'    => array( '#212121', 'Google Photos' ),
			'googleplus'      => array( '#dd4b39', 'Google+' ),
			'googlescholar'   => array( '#4285f4', 'Google Scholar' ),
			'grooveshark'     => array( '#000000', ),
			'hackerrank'      => array( '#2ec866', 'HackerRank' ),
			'hearthstone'     => array( '#EC9313', ),
			'hellocoton'      => array( '#d30d66', ),
			'heroes'          => array( '#2397F7', ),
			'hitbox'          => array( '#99CC00', ),
			'horde'           => array( '#84121C', ),
			'houzz'           => array( '#7CC04B', ),
			'icq'             => array( '#7EBD00', 'ICQ' ),
			'identica'        => array( '#000000', ),
			'imdb'            => array( '#E8BA00', 'IMDB' ),
			'instagram'       => array( '#000000', ),
			'issuu'           => array( '#F26F61', ),
			'istock'          => array( '#000000', ),
			'itunes'          => array( '#ff5e51', 'iTunes' ),
			'keybase'         => array( '#FF7100', ),
			'lanyrd'          => array( '#3c80c9', ),
			'lastfm'          => array( '#d41316', 'Last.fm' ),
			'line'            => array( '#00B901', ),
			'linkedin'        => array( '#3371b7', 'LinkedIn' ),
			'livejournal'     => array( '#0099CC', 'LiveJournal' ),
			'lyft'            => array( '#FF00BF', ),
			'macos'           => array( '#000000', ),
			'mail'            => array( '#000000', ),
			'medium'          => array( '#000000', ),
			'meetup'          => array( '#e2373c', ),
			'mixcloud'        => array( '#000000', ),
			'modelmayhem'     => array( '#000000', 'ModelMayhem' ),
			'mumble'          => array( '#5AB5D1', ),
			'myspace'         => array( '#323232', 'MySpace' ),
			'newsvine'        => array( '#075B2F', ),
			'nintendo'        => array( '#F58A33', ),
			'npm'             => array( '#C12127', 'npm' ),
			'odnoklassniki'   => array( '#f48420', ),
			'openid'          => array( '#f78c40', 'OpenID' ),
			'opera'           => array( '#FF1B2D', ),
			'outlook'         => array( '#0072C6', ),
			'overwatch'       => array( '#9E9E9E', ),
			'patreon'         => array( '#E44727', ),
			'paypal'          => array( '#009cde', 'PayPal' ),
			'periscope'       => array( '#3AA4C6', ),
			'persona'         => array( '#e6753d', ),
			'pinterest'       => array( '#c92619', ),
			'play'            => array( '#000000', ),
			'player'          => array( '#6E41BD', ),
			'playstation'     => array( '#000000', ),
			'pocket'          => array( '#ED4055', ),
			'qq'              => array( '#4297d3', ),
			'quora'           => array( '#cb202d', ),
			'raidcall'        => array( '#073558', ),
			'ravelry'         => array( '#B6014C', ),
			'reddit'          => array( '#e74a1e', ),
			'renren'          => array( '#2266b0', ),
			'researchgate'    => array( '#00CCBB', 'ResearchGate' ),
			'residentadvisor' => array( '#B3BE1B', 'Resident Advisor' ),
			'reverbnation'    => array( '#000000', 'ReverbNation' ),
			'rss'             => array( '#f26109', 'RSS' ),
			'sharethis'       => array( '#01bf01', ),
			'skype'           => array( '#28abe3', ),
			'slideshare'      => array( '#4ba3a6', 'SlideShare' ),
			'smugmug'         => array( '#ACFD32', 'SmugMug' ),
			'snapchat'        => array( '#ffdf00', 'SnapChat' ),
			'songkick'        => array( '#F80046', 'Songkick' ),
			'soundcloud'      => array( '#fe3801', 'SoundCloud' ),
			'spotify'         => array( '#7bb342', ),
			'stackexchange'   => array( '#2f2f2f', 'Stack Exchange' ),
			'stackoverflow'   => array( '#FD9827', 'Stack Overflow' ),
			'starcraft'       => array( '#002250', ),
			'stayfriends'     => array( '#F08A1C', 'StayFriends' ),
			'steam'           => array( '#171a21', 'StarCraft' ),
			'storehouse'      => array( '#25B0E6', ),
			'strava'          => array( '#FC4C02', ),
			'streamjar'       => array( '#503a60', ),
			'stumbleupon'     => array( '#e64011', 'StumbleUpon' ),
			'swarm'           => array( '#FC9D3C', ),
			'teamspeak'       => array( '#465674', 'TeamSpeak' ),
			'teamviewer'      => array( '#168EF4', 'TeamViewer' ),
			'technorati'      => array( '#5cb030', ),
			'telegram'        => array( '#0088cc', ),
			'tripadvisor'     => array( '#4B7E37', 'TripAdvisor' ),
			'tripit'          => array( '#1982C3', ),
			'triplej'         => array( '#E53531', ),
			'tumblr'          => array( '#45556c', ),
			'twitch'          => array( '#6441a5', ),
			'twitter'         => array( '#4da7de', ),
			'uber'            => array( '#000000', ),
			'ventrilo'        => array( '#77808A', ),
			'viadeo'          => array( '#e4a000', ),
			'viber'           => array( '#7b519d', ),
			'viewbug'         => array( '#2B9FCF', ),
			'vimeo'           => array( '#51b5e7', ),
			'vine'            => array( '#00b389', ),
			'vkontakte'       => array( '#5a7fa6', ),
			'warcraft'        => array( '#1EB10A', ),
			'wechat'          => array( '#09b507', ),
			'weibo'           => array( '#e31c34', ),
			'whatsapp'        => array( '#20B038', ),
			'wikipedia'       => array( '#000000', ),
			'windows'         => array( '#00BDF6', ),
			'wordpress'       => array( '#464646', 'WordPress' ),
			'wykop'           => array( '#328efe', ),
			'xbox'            => array( '#92C83E', ),
			'xing'            => array( '#005a60', ),
			'yahoo'           => array( '#6e2a85', ),
			'yammer'          => array( '#1175C4', ),
			'yandex'          => array( '#FF0000', ),
			'yelp'            => array( '#c83218', ),
			'younow'          => array( '#61C03E', ),
			'youtube'         => array( '#e02a20', 'YouTube' ),
			'zapier'          => array( '#FF4A00', ),
			'zerply'          => array( '#9DBC7A', ),
			'zomato'          => array( '#cb202d', ),
			'zynga'           => array( '#DC0606', ),
			'spreadshirt'     => array( '#00b2a6', ),
			'trello'          => array( '#0079bf', ),
			'gamejolt'        => array( '#191919', ),
			'tunein'          => array( '#36b4a7', 'TuneIn' ),
		);
	
		foreach ($brands as $brand => $b) {
			$brand_label = !empty($b[1]) ? $b[1] : ucfirst($brand);
			$brands[$brand][1] = $brand_label;
		}
		
		if( !empty($key) ){
			if( isset( $brands[$key] ) ){
				return $brands[$key];
			}
			else{
				return false;
			}
		}

		return apply_filters( 'zsp_brands', $brands );
	}

	public function addUserFieldType( $name, $class ){
		new SocialProfiles\GeneralForm\RegisterFieldType( $name, $class );
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