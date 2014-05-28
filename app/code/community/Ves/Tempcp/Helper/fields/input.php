<?php
class FieldInput extends FormField{
	public function getElement($value = "", $group = "", $html = ""){
		if($group){
			$this->group = $group;
			$this->fieldname = $this->getFieldName( $this->fieldname );
		}
		$this->value = $value;
		$class = isset($this->element['class'])?(string)$this->element['class']:'';
		$style = isset($this->element['style'])?(string)$this->element['style']:'';
		$max_length = isset($this->element['max_length'])?(string)$this->element['max_length']:'150';
		$size = isset($this->element['size'])?(string)$this->element['size']:'30';
		$html = '<input type="text" name="'.$this->name.'" max_length="'.$max_length.'" size="'.$size.'" style="'.$style.'" class="'.$class.'" value="'.$this->value.'"/>';

		return parent::getElement($value, $group, $html);
	}
}