<?php 
/**
 * Add field
 *
 * Adds the field defined by user. This is used in the main form class
 *
 * @param string $id The field ID  
 * @param array  $settings The field settings  
 * @param string $section The section where the field will be appended
 * @return void
 */
namespace SocialProfiles\User;

class AddField{

	// Field id
	public $id;

	// Field settings
	public $settings;

	public function __construct( $id, $settings = array(), $section = '' ){
		$this->id = $id;
		$this->settings = wp_parse_args( $settings, array(
			'title' => $id,
		));

		// If the field is not registered, register it and display.
		if( !array_key_exists( $this->id, BaseForm::fields() ) ){
			add_filter( 'smk_user_form_fields', array( $this, 'registerField' ) );
			add_action( 'smk_user_fields' . $section, array($this, 'displayField') );
		}
	}
	
	/**
	 * Register
	 *
	 * Register this user field
	 *
	 * @filter-hook smk_user_form_fields
	 *
	 * @param array $prev Previously registered fields array  
	 * @return array 
	 */
	public function registerField( $prev ){
		$new[ $this->id ] = $this->settings;
		return wp_parse_args( $new, $prev );
	}

	/**
	 * Display
	 *
	 * Display the field HTML
	 *
	 * @param object $user  
	 * @return string The HTML 
	 */
	public function displayField( $user ){
		$settings = $this->settings;
		$types = BaseForm::types();
		if( array_key_exists($this->settings['type'], $types) ){
			$class = $types[ $this->settings['type'] ];
			if( class_exists($class) ){
				$html = new $class( $this->id, $this->settings );

				if( !empty($this->settings['capability']) ){
					if( ! current_user_can( $this->settings['capability'] ) )
						return;
				}

				echo $html->display( $user );
			}
		}
	}
}