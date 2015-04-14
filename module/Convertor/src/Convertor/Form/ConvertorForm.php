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

        $this->add(array(
            'name' => 'type',
            'type' => 'Radio',
            'options' => array(
                'label' => 'Select output format',
                'value_options' => array(
                    'csv' => 'CSV',
                    'xml' => 'XML',
                    'json' => 'JSON',
                ),
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
