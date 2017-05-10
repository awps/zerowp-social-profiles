<?php 
namespace SocialProfiles\User;

class RegisterFieldType{
	public $name;
	public $class;

	public function __construct( $name, $class ){
		$this->name = $name;
		$this->class = $class;

		if( !array_key_exists( $this->name, BaseForm::types() ) ){
			add_filter( 'smk_user_form_field_types', array( $this, 'addType' ) );
		}
	}

	public function addType( $prev ){
		$new[ $this->name ] = $this->class;
		return wp_parse_args( $new, $prev );
	}
}