<?php

namespace Convertor\Form;

use Zend\Form\Form;

class ConvertorForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('convertor');

        $this->add(array(
            'name' => 'input-file',
            'type' => 'File',
            'options' => array(
            'label' => 'Select input file',
            ),
            'attributes' => array(
                'required' => true,
            'id' => 'input-file',
            ),
        ));

        // Format array of supported file types (extensions)
        $extArray = array();
        $dirIt = new \DirectoryIterator(__DIR__.'/../Model/dataFormats/');
        $dirIt->rewind();
        while ($dirIt->valid()) {
            $fileName = $dirIt->getBasename();
            if (preg_match('/^format\w*\.php$/', $fileName)) {
                $ext = preg_replace('/^format(\w*)\.php$/', '$1', $fileName);
                $extArray[$ext] = $ext;
            }
            $dirIt->next();
        }

        $this->add(array(
            'name' => 'type',
            'type' => 'Radio',
            'options' => array(
                'label' => 'Select output format',
                'value_options' => $extArray,
            ),
            'attributes' => array(
                'required' => true,
            ),
         ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
             ),
         ));
    }
}
