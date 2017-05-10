<?php 
namespace SocialProfiles\User;


abstract class AbstractFieldType{

	public $input_id;
	public $input_settings;

	public function __construct( $input_id, $input_settings = array() ){
		$this->input_id = $input_id;
		$this->input_settings = $input_settings;
	}

	public function show( $id, $settings, $saved_value ){
		return false;
	}

	public function sanitize( $data, $settings ){
		return $data;
	}

	public function display( $user ){
		$id = $this->input_id;
		$settings = $this->input_settings;
		$saved_value = get_user_meta($user->ID, $id, false);

		if( ! isset($saved_value[0]) ){
			$saved_value = isset($settings[ 'default' ]) ? $settings[ 'default' ] : '';
		}
		else{
			$saved_value = $saved_value[0];
		}

		$output = '<tr>';
			$output .= '<th><label>'. $settings['title'] .'</label></th>';
			$output .= '<td>';
				$output .= $this->show( $id, $settings, $saved_value );
				if( !empty($settings['description']) ){
					$output .= '<p class="description">'. $settings['description'] .'</p>';
				}
			$output .= '</td>';
		$output .= '</tr>';

		echo $output;
	}
}