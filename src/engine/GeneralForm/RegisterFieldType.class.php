<?php 
namespace SocialProfiles\GeneralForm;

class RegisterFieldType{
	public $name;
	public $class;

	public function __construct( $name, $class ){
		$this->name = $name;
		$this->class = $class;

		if( !array_key_exists( $this->name, Base::types() ) ){
			add_filter( 'zsp_form_field_types', array( $this, 'addType' ) );
		}
	}

	public function addType( $prev ){
		$new[ $this->name ] = $this->class;
		return wp_parse_args( $new, $prev );
	}
}