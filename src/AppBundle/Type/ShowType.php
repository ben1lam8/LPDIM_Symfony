<?php
/**
 * Created by PhpStorm.
 * User: benoit
 * Date: 05/02/18
 * Time: 16:13
 */

namespace AppBundle\Type;


use AppBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

class ShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('category', EntityType::class, ['class' => Category::class, 'choice_label' => 'name'])
            ->add('abstract')
            ->add('country', CountryType::class, ['preferred_choices' => ['FR', 'UK', 'US']])
            ->add('author')
            ->add('releaseDate')
            ->add('tmpPicture', FileType::class, ['label' => 'Main Picture']);
    }
}