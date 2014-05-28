<?php
class FieldLabel extends FormField{
	public function getElement($value = "", $group = "", $html = ""){
		if($group){
			$this->group = $group;
			$this->fieldname = $this->getFieldName( $this->fieldname );
		}
		$this->value = $value;
		$html = !empty($this->default)?$this->default:Mage::helper("ves_tempcp")->__($this->name);
		return '<tr><td colspan="2"><h4>'.$html.'</h4></td></tr>';
		//return parent::getElement($value, $group, $html);
	}
}