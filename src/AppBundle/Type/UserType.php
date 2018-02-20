<?php


namespace AppBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullname', TextType::class, ['label' => 'Full Name'])
            ->add('username', EmailType::class, ['label' => 'Username/Email'])
            ->add(
                'password',
                RepeatedType::class,
                [
                'type' => PasswordType::class,
                'label' => 'Password',
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm password'],
                'invalid_message' => 'Passwords must match...'
                ])
            ->add('roles', TextType::class, ['label' => 'Roles (separated by commas)'])
        ;

        $builder
            ->get('roles')->addModelTransformer(
                new CallbackTransformer(
                    function ($rolesAsArray) {
                        // Model -> View
                        if(!empty($rolesAsArray))
                            return implode(',', $rolesAsArray);
                        else
                            return '';
                    },
                    function ($rolesAsString) {
                        return explode(',', $rolesAsString);
                    }
                )
            )
        ;
    }
}
