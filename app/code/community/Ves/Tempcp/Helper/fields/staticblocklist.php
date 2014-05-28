<?php
class FieldStaticblocklist extends FormField{
	public function getElement($value = "", $group = "", $html = ""){
		if($group){
			$this->group = $group;
			$this->fieldname = $this->getFieldName( $this->fieldname );
		}
		$this->value = $value;
		$class = 'select';
		$default = isset($this->element['default'])?$this->element['default']: "";
		$this->value = ($this->value =="" || $this->value == null) ?$default:$this->value;
		
		$class .= isset($this->element['class'])?(string)$this->element['class']:'';
		$style = isset($this->element['style'])?(string)$this->element['style']:'';

		$size = isset($this->element['size'])?(string)$this->element['size']:'30';
		$multiple = isset($this->element['multiple'])?(string)$this->element['multiple']: false;
		if($multiple){
			$multiple = ' multiple="multiple" ';
			$this->name .= "[]";
		}else{
			$multiple = "";
		}

		$html = '<select name="'.$this->name.'" class="'.$class.'"'.$multiple.' style="'.$style.'">';
		$blocks = Mage::getModel('cms/block')->getCollection()
											->addFilter("is_active", 1)
											->getItems();
		$html .= '<option value="0">'.Mage::helper('ves_tempcp')->__("---- Select a Static Block ----").'</option>';
		if(!empty($blocks)){
			foreach($blocks as $block){
				if($block->getIdentifier() == $this->value){
					$html .= '<option value="'.$block->getIdentifier().'" selected="selected">'.$block->getTitle().'</option>';
				}else{
					$html .= '<option value="'.$block->getIdentifier().'">'.$block->getTitle().'</option>';
				}
			}
		}

		$html .= '</select>';

		return parent::getElement($value, $group, $html);
	}
}