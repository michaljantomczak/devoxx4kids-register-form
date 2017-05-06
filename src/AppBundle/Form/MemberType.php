<?php

namespace AppBundle\Form;

use AppBundle\Entity\Food;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('bornAt',DateType::class,[
                'widget'=>'single_text',
            ])
            ->add('prohibitedFood',EntityType::class,[
                'class'=>Food::class,
                'expanded'=>true,
                'multiple'=>true,
            ])
            ->add('tShirtSize',null,[
                'required'=>true,
                'placeholder'=>'Select...',
            ]);

        $builder->get('prohibitedFood')
            ->addModelTransformer(new CallbackTransformer(function($encode){
                return $encode;
            },function ($decode){
                $data=[];
                foreach($decode as $item){
                    $data[]=(string)$item;
                }
                return implode(', ',$data);
            }));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Member'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_member';
    }


}
