<?php 
/**
 * Main form class
 *
 * This is used by user, to init the object and make accessible the methods to add the fields.
 * Also this accepts section ID and section settings. If the section is already registered, 
 * the settings will be ignored and the fields will be appended to the already registered section.
 *
 * @param string $section Section ID  
 * @param array  $section_settings Section settings  
 * @return void 
 */
namespace SocialProfiles\User;

use SocialProfiles\GeneralForm\AbstractForm;

class Form extends AbstractForm {

	public static $prefix = 'zsp_user_form_';

	public function hooks(){
		// If section is not registered
		if( !array_key_exists( $this->section, BaseForm::sections() ) ){
			add_filter( static::$prefix . 'sections', array( $this, 'registerSection' ) );
		}
	}

	public function addField( $id , $settings = array() ){
		$input = new AddField( $id, $settings, $this->section );
	}

}