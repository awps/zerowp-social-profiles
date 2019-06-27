<?php
namespace SocialProfiles\Component\UserForm;

use SocialProfiles\User\Form;

class CreateFields{
	
	public function __construct(){

	}

	public function addFields(){
		$form = new Form( 'zsp_user_profile_fields', array(
			'title' => __( 'Social profiles', 'zerowp-social-profiles' ),
		) );

		$form->addField( 'social_networks', array(
			'title' => __( 'Social Networks', 'zerowp-social-profiles' ),
			'type' => 'brands_repeater',
			'options_label' => __( 'Select network', 'zerowp-social-profiles' ),
			'no_label' => true,
		) );
	}

}