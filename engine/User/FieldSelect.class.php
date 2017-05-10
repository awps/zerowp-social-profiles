<?php 
namespace SocialProfiles\User;

class FieldSelect extends AbstractFieldType{

	public function show( $id, $settings, $saved_value ){
		$output = '';
		if( !empty($settings['options']) && is_array($settings['options']) ){
			$output .= '<select name="'. $id .'">';
			foreach ($settings['options'] as $key => $value) {
				$selected = ( !empty($saved_value) && ($key == $saved_value) ) ? ' selected="selected"' : '';
				$output .= '<option value="'. esc_html( $key ) .'"'. $selected .' >'. esc_attr($value) .'</option>';
			}
			$output .= '</select>';
		}
		return $output;
	}

}