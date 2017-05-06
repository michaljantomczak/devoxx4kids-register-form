<?php
/**
 * Created by PhpStorm.
 * User: mtomczak
 * Date: 05/05/2017
 * Time: 21:33
 */

namespace AppBundle\Form;


use AppBundle\Entity\Food;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FoodCollectionType extends AbstractType
{

    public function getParent()
    {
        return CollectionType::class;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $food = new Food();
        $food->setName('Foa');
        $resolver->setDefaults(array(
            'entry_type' => CheckboxType::class,
            'required' => true,
            'entry_options' => [
                'choices' => [
                    'a'=>'b',
                ]
            ],
            'mapped' => false,
        ));
    }

}