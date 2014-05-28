<?php
class FieldList extends FormField{
	public function getElement($value = "", $group = "", $html = ""){
		if($group){
			$this->group = $group;
			$this->fieldname = $this->getFieldName( $this->fieldname );
		}
		$this->value = $value;

		$class = 'select';
		$default = isset($this->element['default'])?$this->element['default']: "";
		$this->value = ($this->value == "" || $this->value === null)?$default:$this->value;
		
		$class .= isset($this->element['class'])?(string)$this->element['class']:'';
		$style = isset($this->element['style'])?(string)$this->element['style']:'';
		$size = isset($this->element['size'])?' size="'.(string)$this->element['size'].'"':'';

		$options = array();
		$options = $this->getOptions($options);

		$multiple = isset($this->element['multiple'])?(string)$this->element['multiple']: false;
		if($multiple){
			$multiple = ' multiple="multiple" ';
			$class .= ' multiselect';
			$this->name .= "[]";
		}else{
			$multiple = "";
		}
		
		$html = '<select name="'.$this->name.'" class="'.$class.'"'.$multiple.$size.' style="'.$style.'">';

		if($options){
			foreach($options as $key => $option){
				$layouts[$key] = Mage::helper('ves_tempcp')->__($option);
			}
		}
		if(!empty($layouts)){
			foreach($layouts as $key=>$val){
				if(($key == $this->value) || (is_array($this->value) && in_array($key, $this->value))){
					$html .= '<option value="'.$key.'" selected="selected">'.$val.'</option>';
				}else{
					$html .= '<option value="'.$key.'">'.$val.'</option>';
				}
			}
		}

		$html .= '</select>';

		return parent::getElement($value, $group, $html);
	}

	protected function getOptions($default = array()){
		$tmp = array();
		if($options = $this->element->children()){
			foreach($options as $option){
				if(isset($option['value'])){
					$tmp[ (string)$option['value']] = (string)$option;
				} else {
					$tmp[(string)$option] = (string)$option;
				}
			}

		}
		if(!empty($default)){
			return array_merge($default, $tmp);
		} 

		return $tmp;
		
	}
}