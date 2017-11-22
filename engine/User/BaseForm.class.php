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

use SocialProfiles\GeneralForm\Base;


class BaseForm extends Base{

	public static $prefix = 'zsp_user_form_';

	public function __construct(){
	 	add_action( 'show_user_profile', array( $this, 'display' ), 5 );
		add_action( 'edit_user_profile', array( $this, 'display' ), 5 );
		add_action( 'personal_options_update', array( $this, 'update' ), 5 );
		add_action( 'edit_user_profile_update', array( $this, 'update' ), 5 );
	}

}
