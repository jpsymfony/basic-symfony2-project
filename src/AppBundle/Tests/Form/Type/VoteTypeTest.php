<?php

namespace AppBundle\Tests\Form\Type;

use AppBundle\Test\TypeTestCase;
use AppBundle\Entity\Vote;
use AppBundle\Form\Type\VoteType;

class VoteTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = ['score' => '5'];

        $form = $this->factory->create(VoteType::class);

        $vote = new Vote();
        $object = $this->fromArray($vote, $formData);

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