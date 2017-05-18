<?php
namespace SocialProfiles\Create;

use SocialProfiles\User\Form;

class UserFields{
	
	public function __construct(){

	}

	public function addFields(){
		$form = new Form( 'zsp_user_profile_fields', array(
			'title' => __( 'Social profiles', 'social-profiles' ),
		) );

		$form->addField( 'social_networks', array(
			'title' => __( 'Social Networks', 'social-profiles' ),
			'type' => 'brands_repeater',
			'options_label' => __( 'Select network', 'social-profiles' ),
			'no_label' => true,
		) );
	}

}