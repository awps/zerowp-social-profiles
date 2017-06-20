<?php 
/*
This is just an example component. It may not fit your needs, so you're free to modify it however you like.
All you have to do is to create a folder with a unique name in `components` directory.
The folder name will be included in namspace for classes included in this component.
Note the priority is set by default to 10. If you have a component that requires another
component, just set a higher priority number.

In this example we hook all code directly in 'zsp:init', but in some cases this may be too late.
For example to add support for theme features, you may want to use `after_setup_theme`.
*/

/* No direct access allowed!
---------------------------------*/
if ( ! defined( 'ABSPATH' ) ) exit;

/* Inject the component
----------------------------*/
add_action( 'zsp:init', function(){
	
	// Do anything you want here...
	// It's recomended to do all logic in classes, then call them here 
	// and they will be included automatically by autoloader.php
	
	// Example: This hook works
	// echo str_repeat('Hello World!<br>', 10);

	// Example: Autoloading works
	// SocialProfiles\Component\ExampleComponent\Thing\Create::test();

}, 90);