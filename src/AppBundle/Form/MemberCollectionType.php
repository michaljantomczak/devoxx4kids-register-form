<?php
/**
 * Created by PhpStorm.
 * User: mtomczak
 * Date: 05/05/2017
 * Time: 21:24
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberCollectionType extends AbstractType
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
        $resolver->setDefaults(array(
            'entry_type'=>MemberType::class,
            'allow_add'=>true,
            'allow_delete'=>true,
            'required'=>true,
            'by_reference'=>false,
        ));
    }

}