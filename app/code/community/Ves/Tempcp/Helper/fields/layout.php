<?php
class FieldLayout extends FormField{
	public function getElement($value = "", $group = "", $html = ""){
		if($group){
			$this->group = $group;
			$this->fieldname = $this->getFieldName( $this->fieldname );
		}
		$this->value = $value;
		$class = 'select';
		$default = isset($this->element['default'])?$this->element['default']: "";
		$this->value = empty($this->value)?$default:$this->value;
		
		$class .= isset($this->element['class'])?(string)$this->element['class']:'';
		$style = isset($this->element['style'])?(string)$this->element['style']:'';
		$size = isset($this->element['size'])?' size="'.(string)$this->element['size'].'"':'';

		$vars = get_object_vars($this->element);
		$options = $vars['option'];
		$options = $this->getOptions($options);

		$multiple = isset($this->element['multiple'])?(string)$this->element['multiple']: true;
		if($multiple){
			$multiple = ' multiple="multiple" ';
			$class .= ' multiselect';
			$this->name .= "[]";
		}else{
			$multiple = "";
		}
		
		$html = '<select name="'.$this->name.'" class="'.$class.'"'.$multiple.$size.' style="'.$style.'">';

		$layouts = array(
					"all" => Mage::helper('ves_tempcp')->__("All Pages"),
					"home" => Mage::helper('ves_tempcp')->__("Home"));
		if($options){
			foreach($options as $option){
				$layouts[$option] = Mage::helper('ves_tempcp')->__($option);
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

	protected function getOptions($options = array()){
		$default = array(
						'cms',
						'contacts',	
						'catalog-product',
						'catalog-category',
						'checkout',
						'tag',
						'catalogsearch',
						'sales',
						'customer',
						'wishlist',
						'review',
						'oauth',
						'newsletter',
						'downloadable');
		return array_merge($default, $options);
	}
}