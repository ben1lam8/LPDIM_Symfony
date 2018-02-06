<?php
/**
 * Created by PhpStorm.
 * User: benoit
 * Date: 06/02/18
 * Time: 13:25
 */

namespace AppBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
        ;
    }
}