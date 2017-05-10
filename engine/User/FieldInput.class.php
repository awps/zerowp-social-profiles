<?php 
namespace SocialProfiles\User;

class FieldInput extends AbstractFieldType{

	public function show( $id, $settings, $saved_value ){
		return '<input type="text" name="'. $id .'" value="'. esc_attr($saved_value) .'" class="regular-text" />';
	}

	public function sanitize( $data, $settings ){
		return sanitize_text_field($data);
	}

}