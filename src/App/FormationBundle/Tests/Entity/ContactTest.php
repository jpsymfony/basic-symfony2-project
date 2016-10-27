<?php

namespace App\FormationBundle\Tests\Entity;

use App\FormationBundle\Test\TypeTestCase;
use App\FormationBundle\Entity\Contact;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class ContactTest extends TypeTestCase
{
    public function testViolation()
    {
        $contact = new Contact();

        $formData = [
            'firstName' => 'Mon prénom',
            'lastName' => 'Mon nom de famille',
            'cellphone' => '06xxxxxxxx',
            'email' => 'jpsymfony@free.fr',
            'additionalInformation' => 'Pas grand chose à dire',
            'knowledge' => 'autre',
            'other' => null,
        ];

        $contact = $this->fromArray($contact, $formData);

        $constraintViolationBuilder = $this->getMock(ConstraintViolationBuilderInterface::class);
        $constraintViolationBuilder
            ->expects($this->once())
            ->method('atPath')
            ->with('other')
            ->willReturn($constraintViolationBuilder);

        $constraintViolationBuilder
            ->expects($this->once())
            ->method('addViolation');

        $executionContext = $this->getMock(ExecutionContextInterface::class);
        $executionContext
            ->expects($this->once())
            ->method('buildViolation')
            ->with("Vous devez remplir ce champ si vous avez coché 'autre'")
            ->willReturn($constraintViolationBuilder);

        $contact->validate($executionContext);
    }

    public function testViolationWillNotAddViolation()
    {
        $contact = new Contact();

        $formData = [
            'firstName' => 'Mon prénom',
            'lastName' => 'Mon nom de famille',
            'cellphone' => '06xxxxxxxx',
            'email' => 'jpsymfony@free.fr',
            'additionalInformation' => 'Pas grand chose à dire',
            'knowledge' => 'facebook',
            'other' => null,
        ];

        $contact = $this->fromArray($contact, $formData);

        $constraintViolationBuilder = $this->getMock(ConstraintViolationBuilderInterface::class);
        $constraintViolationBuilder
            ->expects($this->never())
            ->method('atPath')
            ->with('other')
            ->willReturn($constraintViolationBuilder);

        $constraintViolationBuilder
            ->expects($this->never())
            ->method('addViolation');

        $executionContext = $this->getMock(ExecutionContextInterface::class);
        $executionContext
            ->expects($this->never())
            ->method('buildViolation')
            ->with("Vous devez remplir ce champ si vous avez coché 'autre'")
            ->willReturn($constraintViolationBuilder);

        $contact->validate($executionContext);
    }
}