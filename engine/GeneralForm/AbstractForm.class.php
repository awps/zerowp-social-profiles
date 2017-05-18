<?php 
/**
 * Main form class
 *
 * If the section is already registered, the settings will be ignored and the 
 * fields will be appended to the already registered section.
 *
 * @param string $section Section ID  
 * @param array  $section_settings Section settings  
 * @return void 
 */
namespace SocialProfiles\GeneralForm;

abstract class AbstractForm {

	public static $prefix;

	// Section ID
	public $section;

	// Section settings
	public $settings;

	public function __construct( $section, $section_settings = array() ){
		$this->section = $section;
		$this->settings = wp_parse_args( $section_settings, array(
			'title' => $section,
		));

		$this->hooks();
	}

	abstract public function hooks();

	/**
	 * Register
	 *
	 * Register this section
	 *
	 * @filter-hook smk_user_form_sections
	 *
	 * @param array $prev Previously registered sections array  
	 * @return array 
	 */
	public function registerSection( $prev ){
		$new[ $this->section ] = $this->settings;
		return wp_parse_args( $new, $prev );
	}

	/**
	 * Add field
	 *
	 * Add a field to this section. This method is for public use.
	 *
	 * @param string $id The field ID. Must be unique. If it is already registered it will be ignored.  
	 * @param array $settings The field settings.  
	 * @return void 
	 */
	abstract public function addField( $id, $settings = array() );

}