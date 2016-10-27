<?php

namespace App\FormationBundle\Test;

use Symfony\Component\Form\Test\TypeTestCase as BaseTypeTestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;

class TypeTestCase extends BaseTypeTestCase
{
    public function fromArray($object, array $formData)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($formData as $key => $data) {
            $propertyAccessor->setValue($object, $key, $data);
        }

        return $object;
    }
}