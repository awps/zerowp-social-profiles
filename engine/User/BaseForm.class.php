<?php	
/**
 * User form base
 *
 * This class is instatiated only once. Here are added all fields and they are accessible via
 * static methods. Also here the class adds the fields to user profile.
 *
 * @return void 
 */
namespace SocialProfiles\User;

class BaseForm{
	
	public function __construct(){
	 	add_action( 'show_user_profile', array( $this, 'display' ) );
		add_action( 'edit_user_profile', array( $this, 'display' ) );
		add_action( 'personal_options_update', array( $this, 'update' ) );
		add_action( 'edit_user_profile_update', array( $this, 'update' ) );
	}
		
	/**
	 * Display
	 *
	 * Display all registered fields and sections in the admin form
	 * 
	 * @action-hook show_user_profile
	 * @action-hook edit_user_profile
	 *
	 * @param object $user The user object  
	 * @return string The HTML formated table with all sectons and fields 
	 */
	public function display( $user ){
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
					do_action( 'smk_user_fields' . $section_id, $user );
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
	function update( $user_id ){
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
						$types = BaseForm::types();

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
		return apply_filters('smk_user_form_sections', array());
	}

	/**
	 * All fields
	 *
	 * Get all registered fields
	 *  
	 * @return array / field_id => field_settings 
	 */
	public static function fields(){
		return apply_filters('smk_user_form_fields', array());
	}

	/**
	 * All field types
	 *
	 * Get all registered field types
	 *  
	 * @return array / field_type => field_class_name 
	 */
	public static function types(){
		return apply_filters('smk_user_form_field_types', array());
	}
	
}