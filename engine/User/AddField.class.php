<?php 
namespace SocialProfiles\User;

use SocialProfiles\GeneralForm\AbstractAddField;

class AddField extends AbstractAddField{

	public static $prefix = 'zsp_user_form_';

	public function hooks( $section_id ){
		// If the field is not registered, register it and display.
		if( !array_key_exists( $this->id, BaseForm::fields() ) ){
			add_filter( static::$prefix . 'fields', array( $this, 'registerField' ) );
			add_action( static::$prefix . 'section_' . $section_id, array($this, 'displayField') );
		}
	}

	public function displayField( $user ){
		$saved_value = get_user_meta( $user->ID, $this->id, true );
		return $this->_displayField( $saved_value );
	}

}