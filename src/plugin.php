<?php
final class ZSP_Plugin{

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public $version;

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
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'zerowp-social-profiles' ), '1.0' );
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'zerowp-social-profiles' ), '1.0' );
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Plugin configuration
	 *
	 * @param string $key Optional. Get the config value by key.
	 * @return mixed
	 */
	public function config( $key = false ){
		return zsp_config( $key );
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Build it!
	 */
	public function __construct() {
		$this->version = ZSP_VERSION;

		/* Include core
		--------------------*/
		include_once $this->rootPath() . "autoloader.php";
		include_once $this->rootPath() . "functions.php";

		/* Activation and deactivation hooks
		-----------------------------------------*/
		register_activation_hook( ZSP_PLUGIN_FILE, array( $this, 'onActivation' ) );
		register_deactivation_hook( ZSP_PLUGIN_FILE, array( $this, 'onDeactivation' ) );

		/* Init core
		-----------------*/
		add_action( $this->config( 'action_name' ), array( $this, 'init' ), 0 );
		add_action( 'widgets_init', array( $this, 'initWidgets' ), 0 );

		/* Register and enqueue scripts and styles
		-----------------------------------------------*/
		add_action( 'wp_enqueue_scripts', array( $this, 'frontendScriptsAndStyles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'backendScriptsAndStyles' ) );

		/* Load components, if any...
		----------------------------------*/
		$this->loadComponents();

		/* Plugin fully loaded and executed
		----------------------------------------*/
		do_action( 'zsp:loaded' );
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Init the plugin.
	 *
	 * Attached to `init` action hook. Init functions and classes here.
	 *
	 * @return void
	 */
	public function init() {
		do_action( 'zsp:before_init' );

		$this->loadTextDomain();

		$this->addUserFieldType( 'input', 'SocialProfiles\GeneralForm\FieldInput' );
		$this->addUserFieldType( 'textarea', 'SocialProfiles\GeneralForm\FieldTextarea' );
		$this->addUserFieldType( 'select', 'SocialProfiles\GeneralForm\FieldSelect' );
		$this->addUserFieldType( 'radio', 'SocialProfiles\GeneralForm\FieldRadio' );
		$this->addUserFieldType( 'brands_repeater', 'SocialProfiles\GeneralForm\FieldBrandsRepeater' );

		new SocialProfiles\User\BaseForm;

		// Call plugin classes/functions here.
		do_action( 'zsp:init' );
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Init the widgets of this plugin
	 *
	 * @return void
	 */
	public function initWidgets() {
		do_action( 'zsp:widgets_init' );
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Localize
	 *
	 * @return void
	 */
	public function loadTextDomain(){
		load_plugin_textdomain(
			'zerowp-social-profiles',
			false,
			$this->config( 'lang_path' )
		);
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Load components
	 *
	 * @return void
	 */
	public function loadComponents(){
		$components = glob( ZSP_PATH .'components/*', GLOB_ONLYDIR );
		foreach ($components as $component_path) {
			require_once trailingslashit( $component_path ) .'component.php';
		}
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Front-end scripts & styles
	 *
	 * @return void
	 */
	public function frontendScriptsAndStyles(){

		$id = $this->config( 'id' );

		$this->addStyles(array(
			$id . '-styles' => array(
				'src'     => $this->assetsURL( 'css/styles.css' ),
				'enqueue' => true,
			),
		));

		$this->addScripts(array(
			$id . '-config' => array(
				'src'     => $this->assetsURL( 'js/config.js' ),
				'deps'    => array( 'jquery' ),
				'enqueue' => false,
			),
		));

	}

	//------------------------------------//--------------------------------------//

	/**
	 * Back-end scripts & styles
	 *
	 * @return void
	 */
	public function backendScriptsAndStyles() {

		$id     = $this->config( 'id' );
		$screen = get_current_screen();
		$enqueue_callback = ( is_admin() && (
			'customize' === $screen->base ||
			'widgets' === $screen->base ||
			'profile' === $screen->base
		) );

		$this->addStyles(array(
			$id . '-styles-admin' => array(
				'src'     => $this->assetsURL( 'css/styles-admin.css' ),
				'enqueue' => true,
				'enqueue_callback' => $enqueue_callback,
			),
		));

		$this->addScripts(array(
			$id . '-config-admin' => array(
				'src'     => $this->assetsURL( 'js/config-admin.js' ),
				'deps'    => array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable' ),
				'enqueue' => true,
				'enqueue_callback' => $enqueue_callback,
				'zsp_local' => array(
					'delete_handle' => '<span class="dashicons dashicons-dismiss zsp-delete-single-brand"></span>',
					'move_handle' => '<span class="dashicons dashicons-menu zsp-move-single-brand"></span>',
				),
			),
		));

	}

	/*
	-------------------------------------------------------------------------------
	Styles
	-------------------------------------------------------------------------------
	*/
	public function addStyles( $styles ){
		if( !empty( $styles ) ){

			foreach ($styles as $handle => $s) {

				/* If just calling an already registered style
				------------------------------------------------------*/
				if( is_numeric( $handle ) && !empty($s) ){
					wp_enqueue_style( $s );
					continue;
				}

				/* Merge with defaults
				------------------------------*/
				$s = wp_parse_args( $s, array(
					'deps'    => array(),
					'ver'     => $this->version,
					'media'   => 'all',
					'enqueue' => true,
					'enqueue_callback' => null,
				));

				/* Register style
				-------------------------*/
				wp_register_style( $handle, $s['src'], $s['deps'], $s['ver'], $s['media'] );

				/* Enqueue style
				------------------------*/
				$this->_enqueue( 'style', $s, $handle );
			}

		}
	}

	/*
	-------------------------------------------------------------------------------
	Scripts
	-------------------------------------------------------------------------------
	*/
	public function addScripts( $scripts ){
		if( !empty( $scripts ) ){

			foreach ($scripts as $handle => $s) {

				/* If just calling an already registered script
				----------------------------------------------------*/
				if( is_numeric( $handle ) && !empty($s) ){
					wp_enqueue_script( $s );
					continue;
				}

				/* Register
				----------------*/
				// Merge with defaults
				$s = wp_parse_args( $s, array(
					'deps'      => array( 'jquery' ),
					'ver'       => $this->version,
					'in_footer' => true,
					'enqueue'   => true,
					'enqueue_callback' => null,
				));

				wp_register_script( $handle, $s['src'], $s['deps'], $s['ver'], $s['in_footer'] );

				/* Enqueue
				---------------*/
				$this->_enqueue( 'script', $s, $handle );

				/* Localize
				-----------------*/
				// Remove known keys
				unset( $s['src'], $s['deps'], $s['ver'], $s['in_footer'], $s['enqueue'], $s['enqueue_callback'] );

				// Probably we have localization strings
				if( !empty( $s ) ){

					// Get first key from array. This may contain the strings for wp_localize_script
					$localize_key = key( $s );

					// Get strings
					$localization_strings = $s[ $localize_key ];

					// Localize strings
					if( !empty( $localization_strings ) && is_array( $localization_strings ) ){
						wp_localize_script( $handle, $localize_key, $localization_strings );
					}

				}
			}

		}
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Enqueue
	 *
	 * Try to enqueue, but first check the callback
	 *
	 * @param string $type 'script' or 'style'
	 * @param array $s Parameters
	 * @param string $handle Asset handle
	 * @return void
	 */
	protected function _enqueue( $type, $s, $handle ){
		if( $s['enqueue'] ){
			if( ! isset( $s['enqueue_callback'] ) || (
				! empty( $s['enqueue_callback'] )
				&& ( is_callable( $s['enqueue_callback'] ) && call_user_func( $s['enqueue_callback'] )
				|| ( true === $s['enqueue_callback'] ) )
			) ){

				if( 'style' == $type ){
					wp_enqueue_style( $handle );
				}
				elseif( 'script' == $type ){
					wp_enqueue_script( $handle );
				}

			}
		}
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Actions when the plugin is activated
	 *
	 * @return void
	 */
	public function onActivation() {
		// Code to be executed on plugin activation
		do_action( 'zsp:on_activation' );
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Actions when the plugin is deactivated
	 *
	 * @return void
	 */
	public function onDeactivation() {
		// Code to be executed on plugin deactivation
		do_action( 'zsp:on_deactivation' );
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Get Root URL
	 *
	 * @return string
	 */
	public function rootURL(){
		return ZSP_URL;
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Get Root PATH
	 *
	 * @return string
	 */
	public function rootPath(){
		return ZSP_PATH;
	}

	//------------------------------------//--------------------------------------//

	/**
	 * Get assets url.
	 *
	 * @param string $file Optionally specify a file name
	 *
	 * @return string
	 */
	public function assetsURL( $file = false ){
		$path = ZSP_URL . 'assets/';

		if( $file ){
			$path = $path . $file;
		}

		return $path;
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

	public function parseNetworksList( $networks, $settings ){
		$parser = new SocialProfiles\Create\ParseNetworks( $networks );
		return $parser->parseList( $settings );
	}

}


/*
-------------------------------------------------------------------------------
Main plugin instance
-------------------------------------------------------------------------------
*/
function ZSP() {
	return ZSP_Plugin::instance();
}

/*
-------------------------------------------------------------------------------
Rock it!
-------------------------------------------------------------------------------
*/
ZSP();
