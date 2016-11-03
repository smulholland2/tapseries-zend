<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Application\Entity\Page;

/**
 * This form is used to collect page data.
 */
class PageForm extends Form
{
    /**
     * Constructor.     
     */
    public function __construct()
    {
        // Define form name
        parent::__construct('page-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'page');
                
        $this->addElements();
        $this->addInputFilter();  
        
    }
    
    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements() 
    {
                
        // Add "title" field
        $this->add([        
            'type'  => 'text',
            'name' => 'title',
            'attributes' => [
                'id' => 'title'
            ],
            'options' => [
                'label' => 'Title',
            ],
        ]);
        
        // Add "content" field
        $this->add([
            'type'  => 'textarea',
            'name' => 'content',
            'attributes' => [                
                'id' => 'content'
            ],
            'options' => [
                'label' => 'Content',
            ],
        ]);
        
        // Add "tags" field
        $this->add([
            'type'  => 'text',
            'name' => 'tags',
            'attributes' => [                
                'id' => 'tags'
            ],
            'options' => [
                'label' => 'Tags',
            ],
        ]);
        
        // Add "status" field
        $this->add([
            'type'  => 'select',
            'name' => 'status',
            'attributes' => [                
                'id' => 'status'
            ],
            'options' => [
                'label' => 'Status',
                'value_options' => [
                    Page::STATUS_PUBLISHED => 'Published',
                    Page::STATUS_DRAFT => 'Draft',
                ]
            ],
        ]);
        
        // Add the submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Save',
                'id' => 'submitbutton',
            ],
        ]);

        // Add the submit button
        $this->add([
            'type'  => 'button',
            'name' => 'preview',            
            'attributes' => [                
                'value' => 'Preview',
                'id' => 'previewbutton',
                'class' => 'btn btn-default',                
            ],
            'options' => [
                'label' => 'Preview',                
            ]
        ]);
    }
    
    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter() 
    {
        
        $inputFilter = new InputFilter();        
        $this->setInputFilter($inputFilter);
        
        $inputFilter->add([
                'name'     => 'title',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                    ['name' => 'StripNewlines'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 1024
                        ],
                    ],
                ],
            ]);
        
        $inputFilter->add([
                'name'     => 'content',
                'required' => true,
                'filters'  => [                    
                    ['name' => 'StripTags'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 4096
                        ],
                    ],
                ],
            ]);   
        
        $inputFilter->add([
                'name'     => 'tags',
                'required' => true,
                'filters'  => [                    
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                    ['name' => 'StripNewlines'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 1024
                        ],
                    ],
                ],
            ]);
    }
}

