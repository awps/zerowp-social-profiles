<?php 
/* No direct access allowed!
---------------------------------*/
if ( ! defined( 'ABSPATH' ) ) exit;

/* Inject the component
----------------------------*/
add_action( 'zsp:init', function(){

	$uf = new SocialProfiles\Component\UserForm\CreateFields;
	$uf->addFields();

});