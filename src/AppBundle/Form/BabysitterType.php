<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BabysitterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $countChildChoices=[];
        for($i=1; $i<=5; $i++){
            $countChildChoices[$i]=$i;
        }

        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('telephone')
            ->add('allowTerms')
            ->add('allowMarketing')
            ->add('countChild',ChoiceType::class,[
                'placeholder'=>'Select...',
                'required'=>true,
                'choices'=>$countChildChoices,
                'mapped'=>false,
            ])
            ->add('members',CollectionType::class,[
                'allow_add'=>true,
                'required'=>true,
                'entry_type'=>MemberType::class,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Babysitter'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_babysitter';
    }


}
