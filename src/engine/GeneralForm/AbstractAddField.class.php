<?php 
/**
 * Add field
 *
 * @param string $id The field ID  
 * @param array  $settings The field settings  
 * @param string $section_id The section where the field will be appended
 * @return void
 */
namespace SocialProfiles\GeneralForm;

abstract class AbstractAddField{

	// Field id
	public $id;

	// Field settings
	public $settings;

	public function __construct( $id, $settings = array(), $section_id = '' ){
		$this->id = $id;
		$this->settings = wp_parse_args( $settings, array(
			'title' => $id,
		));

		$this->hooks( $section_id );
	}

	abstract public function hooks( $section_id );
	
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
	 * @return string The HTML 
	 */
	protected function _displayField( $saved_value ){
		$settings = $this->settings;
		$types = Base::types();

		if( array_key_exists($this->settings['type'], $types) ){
			$class = $types[ $this->settings['type'] ];
			$html = new $class( $this->id, $this->settings );

			if( !empty($this->settings['capability']) ){
				if( ! current_user_can( $this->settings['capability'] ) )
					return;
			}

			echo $html->display( $saved_value );
		}
	}
}