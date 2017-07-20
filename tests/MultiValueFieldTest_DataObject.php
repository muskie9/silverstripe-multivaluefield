<?php
namespace Symbiote\MultiValueField\Tests;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;
use Symbiote\MultiValueField\Fields\MultiValueField;

/**
 * @ignore
 * @property MultiValueField $MVField
 */
class MultiValueFieldTest_DataObject extends DataObject implements TestOnly {

    private static $db = array(
        'MVField' => MultiValueField::class,
    );

    private static $table_name = 'MultiValueFieldTest_DataObject';

}
