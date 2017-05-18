<?php 
namespace SocialProfiles\GeneralForm;

class FieldRadio extends AbstractFieldType{

	public function show( $id, $settings, $saved_value ){
		$output = '';
		if( !empty($settings['options']) && is_array($settings['options']) ){

			$val_exists = !empty($saved_value) && array_key_exists( $saved_value, $settings['options']);
			$first = false;

			foreach ($settings['options'] as $key => $value) {
				if( $val_exists && $first == false ){
					$selected = ( !empty($saved_value) && ($key == $saved_value) ) ? ' checked="checked"' : '';
				}
				else{
					$selected = ($first == false) ? ' checked="checked"' : '';
					$first = true;
				}
				$output .= '<label style="margin-right: 15px;"><input name="'. $id .'" type="radio" value="'. esc_html( $key ) .'"'. $selected .' >'. esc_attr($value) .' </label>';
			}
		}
		return $output;
	}

}