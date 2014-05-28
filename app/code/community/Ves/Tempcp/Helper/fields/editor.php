<?php
class FieldEditor extends FormField{
	public function getElement($value = "", $group = "", $html = ""){
		if($group){
			$this->group = $group;
			$this->fieldname = $this->getFieldName( $this->fieldname );
		}
		$this->value = $value;
		$class = isset($this->element['class'])?(string)$this->element['class']:'';
		$cols = isset($this->element['cols'])?(string)$this->element['cols']:'25';
		$rows = isset($this->element['rows'])?(string)$this->element['rows']:'10';
		$width = isset($this->element['width'])?(string)$this->element['width']:'400px';
		$height = isset($this->element['height'])?(string)$this->element['height']:'300px';
		$html = Mage::helper('ves_tempcp/element')->getElementEditor( $this->group.'_'.$this->fieldname, $this->name, $value, 'class="'.$class.'" cols="'.$cols.'" rows="'.$rows.'" style="width:'.$width.';height:'.$height.'"');

		return parent::getElement($value, $group, $html);
	}
}