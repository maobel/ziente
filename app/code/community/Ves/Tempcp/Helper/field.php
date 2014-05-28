<?php

/**
 * Abstract Form Field class for the Magento Platform.
 *
 * @package     Helper.field
 * @subpackage  Form
 * @since       11.1
 */
class FormField
{
	/**
	 * The description text for the form field.  Usually used in tooltips.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $description;

	/**
	 * The SimpleXMLElement object of the <field /> XML element that describes the form field.
	 *
	 * @var    SimpleXMLElement
	 * @since  11.1
	 */
	protected $element;

	/**
	 * The hidden state for the form field.
	 *
	 * @var    boolean
	 * @since  11.1
	 */
	protected $hidden = false;

	/**
	 * True to translate the field label string.
	 *
	 * @var    boolean
	 * @since  11.1
	 */
	protected $translateLabel = true;

	/**
	 * True to translate the field description string.
	 *
	 * @var    boolean
	 * @since  11.1
	 */
	protected $translateDescription = true;

	/**
	 * The document id for the form field.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $id;

	/**
	 * The input for the form field.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $input;

	/**
	 * The label for the form field.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $label;

	/**
	 * The multiple state for the form field.  If true then multiple values are allowed for the
	 * field.  Most often used for list field types.
	 *
	 * @var    boolean
	 * @since  11.1
	 */
	protected $multiple = false;

	/**
	 * The name of the form field.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $name;

	/**
	 * The name of the field.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $fieldname;

	/**
	 * The group of the field.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $group;

	/**
	 * The required state for the form field.  If true then there must be a value for the field to
	 * be considered valid.
	 *
	 * @var    boolean
	 * @since  11.1
	 */
	protected $required = false;

	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type;

	/**
	 * The validation method for the form field.  This value will determine which method is used
	 * to validate the value for a field.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $validate;

	/**
	 * The value of the form field.
	 *
	 * @var    mixed
	 * @since  11.1
	 */
	protected $value;

	/**
	 * The label's CSS class of the form field
	 *
	 * @var    mixed
	 * @since  11.1
	 */
	protected $labelClass;

	protected $name_prefix;

	protected $name_supfix;

	protected $options;

	/**
	 * The count value for generated name field
	 *
	 * @var    integer
	 * @since  11.1
	 */
	protected static $count = 0;

	protected $formControl = "";


	/**
	 * The string used for generated fields names
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected static $generated_fieldname = '__field';

	/**
	 * Method to instantiate the form field object.
	 *
	 * @param   JForm  $form  The form to attach to the form field object.
	 *
	 * @since   11.1
	 */
	public function __construct(SimpleXMLElement $element, $default_value = "", $group = '', $name_prefix = "", $name_supfix = "")
	{
		// Make sure there is a valid JFormField XML element.
		if ((string) $element->getName() != 'field')
		{
			return false;
		}

		// Reset the input and label values.
		$this->input = null;
		$this->label = null;

		$this->name_prefix = $name_prefix;
		$this->name_supfix = $name_supfix;

		// Set the XML element object.
		$this->element = $element;

		// Get some important attributes from the form field element.
		$class = (string) $element['class'];
		$id = (string) $element['id'];
		$multiple = (string) $element['multiple'];
		$name = (string) $element['name'];
		$required = (string) $element['required'];

		$this->default = (string) $default_value;
		$this->type = (string) $element['type'];

		// Set the required and validation options.
		$this->required = ($required == 'true' || $required == 'required' || $required == '1');
		$this->validate = (string) $element['validate'];

		// Add the required class if the field is required.
		if ($this->required)
		{
			if ($class)
			{
				if (strpos($class, 'required') === false)
				{
					$this->element['class'] = $class . ' required';
				}
			}
			else
			{
				$this->element['class'] = 'required';
			}
		}

		// Set the multiple values option.
		$this->multiple = ($multiple == 'true' || $multiple == 'multiple');

		// Set the field description text.
		$this->description = (string) $element['description'];

		// Set the visibility.
		$this->hidden = ((string) $element['type'] == 'hidden' || (string) $element['hidden'] == 'true');

		$this->group = $group;
		// Set the field name and id.
		$this->fieldname = $this->getFieldName($name);
		$this->fieldname = $this->name_prefix.$this->fieldname.$this->name_supfix;
		$this->name = $this->getName($this->fieldname);
		$this->id = $this->getId($id, $this->fieldname);

		// Set the CSS class of field label
		$this->labelClass = (string) $element['labelclass'];

		return $this;
	}
	protected function getElement($value = "", $group = "", $html = ""){
		$content  = '<tr>';
		$label = $this->getLabel();
		if(!empty($label)){
			$content .= '<td class="">'.$label.'</td>';
		}else{
			$content .= '<td colspan="2">'.$html.'</td>';	
		}
		$content .= '<td class="">'.$html.'</td>';
		
		$content .= '</tr>';

		return $content;
	}
	/**
	 * Method to get the id used for the field input tag.
	 *
	 * @param   string  $fieldId    The field element id.
	 * @param   string  $fieldName  The field element name.
	 *
	 * @return  string  The id to be used for the field input tag.
	 *
	 * @since   11.1
	 */
	protected function getId($fieldId, $fieldName)
	{
		$id = '';

		// If there is a form control set for the attached form add it first.
		if ($this->formControl)
		{
			$id .= $this->formControl;
		}

		// If the field is in a group add the group control to the field id.
		if ($this->group)
		{
			// If we already have an id segment add the group control as another level.
			if ($id)
			{
				$id .= '_' . str_replace('.', '_', $this->group);
			}
			else
			{
				$id .= str_replace('.', '_', $this->group);
			}
		}

		// If we already have an id segment add the field id/name as another level.
		if ($id)
		{
			$id .= '_' . ($fieldId ? $fieldId : $fieldName);
		}
		else
		{
			$id .= ($fieldId ? $fieldId : $fieldName);
		}

		// Clean up any invalid characters.
		$id = preg_replace('#\W#', '_', $id);

		return $id;
	}
	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to the the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   11.1
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'description':
			case 'formControl':
			case 'hidden':
			case 'id':
			case 'multiple':
			case 'name':
			case 'required':
			case 'type':
			case 'style':
			case 'validate':
			case 'value':
			case 'labelClass':
			case 'fieldname':
			case 'staticblocklist':
			case 'group':
				return $this->$name;

			case 'input':
				// If the input hasn't yet been generated, generate it.
				if (empty($this->input))
				{
					$this->input = $this->getInput();
				}

				return $this->input;

			case 'label':
				// If the label hasn't yet been generated, generate it.
				if (empty($this->label))
				{
					$this->label = $this->getLabel();
				}

				return $this->label;

			case 'title':
				return $this->getTitle();
		}

		return null;
	}
	
	/**
	 * Method to get the field title.
	 *
	 * @return  string  The field title.
	 *
	 * @since   11.1
	 */
	protected function getTitle()
	{
		$title = '';

		if ($this->hidden)
		{

			return $title;
		}

		// Get the label text from the XML element, defaulting to the element name.
		$title = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
		$title = Mage::helper("ves_tempcp")->__($title);

		return $title;
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 *
	 * @since   11.1
	 */
	protected function getLabel()
	{
		$label = '';

		if ($this->hidden)
		{
			return $label;
		}

		// Get the label text from the XML element, defaulting to the element name.
		$text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
		$text = Mage::helper("ves_tempcp")->__($text);
		$description = $this->element['description'] ? (string) $this->element['description'] : "";
		$description = Mage::helper("ves_tempcp")->__($description);
		$description = '<br/><span class="help">'.$description.'</span>';

		// Build the class for the label.
		$class = !empty($this->description) ? 'hasTooltip' : '';
		$class = $this->required == true ? $class . ' required' : $class;
		$class = !empty($this->labelClass) ? $class . ' ' . $this->labelClass : $class;

		// Add the opening label tag and main attributes attributes.
		$label .= '<label id="' . $this->id . '-lbl" for="' . $this->id . '" class="' . $class . '"';

		// Add the label text and closing tag.
		if ($this->required)
		{
			$label .= '>' . $text . '<span class="star">&#160;*</span>'.$description.'</label>';
		}
		else
		{
			$label .= '>' . $text . $description. '</label>';
		}

		return $label;
	}

	/**
	 * Method to get the name used for the field input tag.
	 *
	 * @param   string  $fieldName  The field element name.
	 *
	 * @return  string  The name to be used for the field input tag.
	 *
	 * @since   11.1
	 */
	protected function getName($fieldName)
	{
		$name = '';
		
		// If the field is in a group add the group control to the field name.
		if ($this->group)
		{
			// If we already have a name segment add the group control as another level.
			$groups = explode('.', $this->group);
			if ($name)
			{
				foreach ($groups as $group)
				{
					$name .= '[' . $group . ']';
				}
			}
			else
			{
				$name .= array_shift($groups);
				foreach ($groups as $group)
				{
					$name .= '[' . $group . ']';
				}
			}
		}

		// If we already have a name segment add the field name as another level.
		if ($name)
		{
			$name .= '[' . $fieldName . ']';
		}
		else
		{
			$name .= $fieldName;
		}
		// If the field should support multiple values add the final array segment.
		if ($this->multiple)
		{
			switch (strtolower((string) $this->element['type']))
			{
				case 'text':
				case 'textarea':
				case 'email':
				case 'password':
				case 'radio':
				case 'calendar':
				case 'editor':
				case 'hidden':
					break;
				default:
					$name .= '[]';
			}
		}

		return $name;
	}

	/**
	 * Method to get the field name used.
	 *
	 * @param   string  $fieldName  The field element name.
	 *
	 * @return  string  The field name
	 *
	 * @since   11.1
	 */
	protected function getFieldName($fieldName)
	{
		if ($fieldName)
		{
			return $fieldName;
		}
		else
		{
			self::$count = self::$count + 1;
			return self::$generated_fieldname . self::$count;
		}
	}
}
