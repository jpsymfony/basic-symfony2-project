<?php

namespace AppBundle\Tests\Form\Type;

use AppBundle\Test\TypeTestCase;
use AppBundle\Entity\Contact;
use AppBundle\Form\Type\ContactType;

class ContactTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'firstName' => 'Mon prénom',
            'lastName' => 'Mon nom de famille',
            'cellphone' => '06xxxxxxxx',
            'email' => 'jp.saulnier11@gmail.com',
            'additionalInformation' => 'Pas grand chose à dire',
            'knowledge' => 'internet',
            'other' => '',
        ];

        $form = $this->factory->create(ContactType::class);

        $contact = new Contact();
        $object = $this->fromArray($contact, $formData);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }

        $validationError = $form->getErrors();
        $this->assertEquals($validationError->count(), 0);
    }
}