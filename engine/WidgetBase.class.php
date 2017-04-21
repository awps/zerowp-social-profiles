<?php
namespace SocialProfiles;

class WidgetBase extends \WP_Widget{
	
	public $form;

	public function options(){

	}

	/*
	-------------------------------------------------------------------------------
	Requirements
	-------------------------------------------------------------------------------
	*/
	public function settings(){
		return array();
	}


	/*
	-------------------------------------------------------------------------------
	Auto manage
	-------------------------------------------------------------------------------
	*/
	public function __construct(){

		// Prepare fields
		$this->options();

		// Prepare settings
		$settings = wp_parse_args( $this->settings(), array(
			'id_base'     => false,
			'name'        => false,
			'classname'   => '',
			'description' => '',
			'height'      => 200,
			'width'       => 250,
		));
		
		// Create a class from ID base if is empty.
		if( empty( $settings[ 'classname' ] ) ){
			$settings[ 'classname' ] = sanitize_html_class( $settings[ 'id_base' ] );
		}

		// Create widget
		\WP_Widget::__construct( 
			$settings[ 'id_base' ], 
			$settings[ 'name' ], 
			$settings, 
			$settings 
		);
	}

	public function widget( $args, $instance ) {
        die('function WP_Widget::widget() must be over-ridden in a sub-class.');
    }

	public function form( $instance ){
		echo '<div class="zsp-block">Form</div>';
		echo '<pre>';
		print_r( get_current_screen() );
		echo '</pre>';
		// return $instance;
	}

	// public function update( $new_instance, $old_instance ){
	// 	return $new_instance;
	// }


	/*
	-------------------------------------------------------------------------------
	Helpers
	-------------------------------------------------------------------------------
	*/
	public function getTitle( $args, $instance, $prepend = '', $append = '' ){
		if( empty($instance[ 'title' ]) )
			return false;

		$title = apply_filters( 'widget_title', $instance[ 'title' ], $this->id_base );
		return $args['before_title'] . $prepend . $title . $append . $args['after_title'];
	}

	public function openWidget( $args ){
		$output = apply_filters( 'zsp_open_widget', $args[ 'before_widget' ], $this->id_base );
		return ! empty( $output ) ? $output : false;
	}

	public function closeWidget( $args ){
		$output = apply_filters( 'zsp_close_widget', $args[ 'after_widget' ], $this->id_base );
		return ! empty( $output ) ? $output : false;
	}

}