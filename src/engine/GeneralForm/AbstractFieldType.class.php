<?php 
namespace SocialProfiles\GeneralForm;

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

	public function display( $saved_value ){
		$id = $this->input_id;
		$settings = $this->input_settings;

		if( ! isset($saved_value) ){
			$saved_value = isset($settings[ 'default' ]) ? $settings[ 'default' ] : '';
		}
		else{
			$saved_value = $saved_value;
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