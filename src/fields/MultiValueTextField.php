<?php

namespace Symbiote\MultiValueField\Fields;

use SilverStripe\Forms\FormField;
use SilverStripe\View\HTML;
use SilverStripe\View\Requirements;
use SilverStripe\Core\Convert;
use SilverStripe\Control\Controller;
use SilverStripe\CMS\Controllers\ContentController;

/**
 * A text field for multivalued text entry
 *
 * @author Marcus Nyeholt <marcus@symbiote.com.au>
 */
class MultiValueTextField extends FormField
{
	const KEY_SEP = '__';

	protected $tag = 'input';

	public function Field($properties = [])
    {
	    if (Controller::curr() instanceof ContentController) {
		    Requirements::javascript('silverstripe/admin: thirdparty/jquery/jquery.js');
	    }
	    Requirements::javascript('symbiote/multivaluefield: javascript/multivaluefield.js');
	    Requirements::css('symbiote/multivaluefield: css/multivaluefield.css');

		$name = $this->name . '[]';
		$fields = [];

		$attributes = [
			'type' => 'text',
			'class' => 'text mvtextfield mventryfield ' . ($this->extraClass() ? $this->extraClass() : ''),
			// 'id' => $this->id(),
			'name' => $name,
			// 'value' => $this->Value(),
		];

		if($this->disabled) $attributes['disabled'] = 'disabled';

		$fieldAttr = $attributes;
		if ($this->value) {
			foreach ($this->value as $i => $v) {
				$fieldAttr['id'] = $this->id().MultiValueTextField::KEY_SEP.$i;
				$fieldAttr['value'] = $v;
				if ($this->readonly) {
					unset($fieldAttr['value']);
					$fields[] = $this->createReadonlyInput($fieldAttr, $v);
				} else {
					$fields[] = $this->createInput($fieldAttr, $v);
				}
			}
		}

		if (!$this->readonly) {
			$fields[] = $this->createInput($attributes);
		}

		if (count($fields)) {
			return '<ul id="'.$this->id().'" class="multivaluefieldlist '.$this->extraClass().'"><li>'.implode('</li><li>', $fields).'</li></ul>';
		} else {
			return '<div id="'.$this->id().'" class="multivaluefieldlist '.$this->extraClass().'"></div>';
		}
	}

	public function createReadonlyInput($attributes, $value)
    {
		return HTML::createTag('span', $attributes, Convert::raw2xml($value));
	}

	public function createInput($attributes, $value = null)
    {
		return HTML::createTag($this->tag, $attributes, $value);
	}

	public function performReadonlyTransformation()
    {
		$new = clone $this;
		$new->setReadonly(true);
		return $new;
	}

	public function setValue($v, $data = NULL)
    {
		if (is_array($v)) {
			// we've been set directly via the post - lets prune any empty values
			foreach ($v as $key => $val) {
				if (!strlen($val)) {
					unset($v[$key]);
				}
			}
		}
 		if ($v instanceof MultiValueField) {
			$v = $v->getValues();
		}

		if (!is_array($v)) {
			$v = [];
		}

		parent::setValue($v);
	}

	public function setTag($tag)
    {
		$this->tag = $tag;
		return $this;
	}
}
