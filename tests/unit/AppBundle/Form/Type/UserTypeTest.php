<?php

namespace tests\functional\AppBundle\Form\Type;

use AppBundle\Form\User\UserType;
use AppBundle\Entity\User;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $options = $this->createMock(OptionsResolver::class);

        $formData = array(
            'username' => 'username2',
            'plainPassword' => 'password2',
            'email' => 'email2@email.com'
        );
        $objectToCompare = new User();
        $form = $this->factory->create(UserType::class, $objectToCompare);

        $object = new User();
        $object->setUsername('username2');
        $object->setPlainPassword('password2');
        $object->setEmail('email2@email.com');

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($object, $objectToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}