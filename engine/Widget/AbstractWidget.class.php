<?php
namespace SocialProfiles\Widget;

abstract class AbstractWidget extends \WP_Widget{
	
	/*
	-------------------------------------------------------------------------------
	Requirements
	-------------------------------------------------------------------------------
	*/
	abstract public function settings();
	abstract public function options( $instance );
	
	public function widget( $args, $instance ){
		echo '<!-- There is nothing to display! Extend WP_Widget::widget() method in '. get_class( $this ) .' -->';
	}

	// Extend if needed
	// public function update( $new_instance, $old_instance ){
	// 	return $new_instance;
	// }


	/*
	-------------------------------------------------------------------------------
	Auto manage
	-------------------------------------------------------------------------------
	*/
	public function __construct(){

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


	/*
    -------------------------------------------------------------------------------
    Form
    -------------------------------------------------------------------------------
    */
	public function addField( $id, $type, $instance, $settings = array() ){

		// Filter data
		$settings = apply_filters( "zsp_widget_field_{$id}", $settings, $type, $this );

		// Variables
		$value = isset( $instance[ $id ] ) ? $instance[ $id ] : ( isset( $settings[ 'default' ] ) ? $settings[ 'default' ] : '' );
		$grid  = !empty( $settings[ 'grid' ] ) ? ' col-'. $settings[ 'grid' ] : '';
		$class = !empty( $settings[ 'class' ] ) ? esc_attr( $settings[ 'class' ] ) : '';
		$name  = esc_attr( $this->get_field_name( $id ) );

		// The title field, if exists, must have an ID attr. For other fields, 
		// add the ID only if is explicitly set to true.
		$attr_id = ( !empty( $settings[ 'attr_id' ] ) || 'title' === $id ) 
						? ' id="'. esc_attr( $this->get_field_id( $id ) ) .'"' 
						: '';

		// Field row.
		$output = '<div class="zsp-widget-row'. $grid .'">';
			$output .= '<label>';

				/* Label
				-------------*/
				if( !empty( $settings[ 'label' ] ) ){
					$output .= '<div class="label">'. $settings[ 'label' ] .'</div>';
				}

				/* Field
				-----------------*/
				switch ( $type ) {
					case 'text':
						
						$output .= '<input'. $attr_id .' 
							type="text" 
							class="widefat'. $class .'" 
							name="'. $name .'" 
							value="'. esc_attr( $value ) .'" />';

						break;
					
					case 'number':
						
						$output .= '<input'. $attr_id .' 
							type="number" 
							class="'. $class .'" 
							name="'. $name .'" 
							value="'. esc_attr( $value ) .'" />';

						break;
					
					case 'select':

						$output .= '<select'. $attr_id .' name="'. $name .'" class="'. $class .'">';
						
							if( !empty( $settings['options'] ) && is_array( $settings['options'] ) ){
								foreach ( $settings['options'] as $key => $option ) {
									if( !is_array($option) ){
										$selected = ( in_array($key, (array) $value) ) ? ' selected="selected"' : '';
										$output .= '<option value="'. $key .'"'. $selected .'>'. $option .'</option>';
									}
									else{
										$optg_label = !empty($option['label']) ? $option['label'] : '';
										if( !empty( $option['options']) ){
											$output .= '<optgroup label="'. $optg_label .'">';
												foreach ( (array) $option['options'] as $gokey => $govalue) {
													$selected = ( in_array($gokey, (array) $value) ) ? ' selected="selected"' : '';
													$output .= '<option value="'. $gokey .'"'. $selected .'>'. $govalue .'</option>';
												}
											$output .= '</optgroup>';
										}
									}
								}
							}

						$output .= '</select>';

						break;
					
					case 'networks':
						$brands = ZSP()->brands();

						$output .= '<input class="zsp-placeholder-field" type="hidden" value="" name="'. $name .'" />';

						$output .= '<div class="zsp-brands-selected-list">';
							if( !empty($value) && is_array($value) ){
								foreach ($value as $brand => $b) {
									$output .= '<div class="zsp-single-brand '. $brand .'" title="'. esc_attr( $brands[ $brand ][1] ) .'">';
									$output .= '<input type="text" class="widefat" value="'. esc_attr( $b['label'] ) .'" ';
									$output .= 'name="'. $name .'['. $brand .'][label]" placeholder="'. __( 'Follow us on', 'social-profiles' ) .'" ';
									$output .= '/>';
									$output .= '<input type="text" class="widefat" value="'. esc_attr( $b['url'] ) .'" ';
									$output .= 'name="'. $name .'['. $brand .'][url]" placeholder="'. esc_attr( $brands[ $brand ][1] ) .'" ';
									$output .= '/>';
									$output .= '<span class="dashicons dashicons-dismiss zsp-delete-single-brand"></span>';
									$output .= '<span class="dashicons dashicons-menu zsp-move-single-brand"></span>';
									$output .= '</div>';
								}
							}
						$output .= '</div>';

						$output .= '<select class="zsp-brands-dropdown" data-nameholder="'. $name .'">';

							if( !empty( $settings[ 'options_label' ] ) ){
								$output .= '<option value="">'. $settings[ 'options_label' ] .'</option>';
							}

							foreach ($brands as $brand => $b) {
								$output .= '<option value="'. $brand .'">'. $b[1] .'</option>';
							}
						$output .= '</select>';

						break;
					
					default:
						# code...
						break;
				}

			$output .= '</label>';

			/* Description
			-------------------*/
			if( !empty( $settings['description'] ) ){
				$output .= '<div class="zsp-description">'. $settings['description'] .'</div>';
			}

		$output .= '</div>';

		echo $output;
	}


	public function form( $instance ){
		echo '<div class="zsp-grid">';
			$this->options( $instance );
		echo '</div>';
	}


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