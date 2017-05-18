<?php 
namespace SocialProfiles\GeneralForm;

class Base{
	
	public static $prefix;

	/**
	 * Display
	 *
	 * Display all registered fields and sections in the admin form
	 *
	 * @return string The HTML formated table with all sectons and fields 
	 */
	public function display( $data ){
		$sections = $this->sections();

		if( !empty( $sections ) ){

			foreach ($sections as $section_id => $section_settings) {
				$title = !empty($section_settings['title']) ? $section_settings['title'] : $section_id;
				
				if( !empty($section_settings['capability']) ){
					if( ! current_user_can( $section_settings['capability'] ) )
						continue;
				}

				echo '<h3>'. $title . '</h3>';
				echo '<table class="form-table">';
					do_action( static::$prefix . 'section_' . $section_id, $data );
				echo '</table>';
			}

		}
	}
		
	/**
	 * Update the form
	 *
	 * Update the form fields on submit
	 *
	 * @action-hook personal_options_update
	 * @action-hook edit_user_profile_update
	 *
	 * @param string $user_id The user id where the data should be updated 
	 */
	public function update( $user_id ){
		$fields = $this->fields();
		if( isset($_POST) ){
			if( !empty($fields) ){
				foreach ($fields as $field_id => $field_settings) {
					if( isset( $_POST[ $field_id ] ) ){
						
						if( !empty($field_settings['capability']) ){
							if( ! current_user_can( $field_settings['capability'] ) )
								continue;
						}

						$data = $_POST[ $field_id ];
						$types = static::types();

						if( array_key_exists($field_settings['type'], $types) ){
							$class = $types[ $field_settings['type'] ];
							if( class_exists($class) ){
								$obj = new $class( $field_id, $field_settings );
								$data = $obj->sanitize( $data, $field_settings );
							}
						}

						update_user_meta( $user_id, $field_id, $data );
					}
				}
			}
		}
	}

	/**
	 * All sections
	 *
	 * Get all registered sections
	 *  
	 * @return array / section_id => section_settings 
	 */
	public static function sections(){
		return apply_filters( static::$prefix . 'sections', array());
	}

	/**
	 * All fields
	 *
	 * Get all registered fields
	 *  
	 * @return array / field_id => field_settings 
	 */
	public static function fields(){
		return apply_filters( static::$prefix . 'fields', array());
	}

	/**
	 * All field types
	 *
	 * Get all registered field types
	 *  
	 * @return array / field_type => field_class_name 
	 */
	public static function types(){
		return apply_filters('zsp_form_field_types', array());
	}
	
}