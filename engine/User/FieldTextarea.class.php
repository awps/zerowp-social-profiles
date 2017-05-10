<?php 
namespace SocialProfiles\User;

class FieldTextarea extends AbstractFieldType{

	public function show( $id, $settings, $saved_value ){
		return '<textarea name="'. $id .'" rows="5" cols="30">'. esc_textarea($saved_value) .'</textarea>';
	}

}