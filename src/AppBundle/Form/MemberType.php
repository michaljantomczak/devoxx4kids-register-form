<?php

namespace AppBundle\Form;

use AppBundle\Entity\Food;
use AppBundle\Entity\Member;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class MemberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',null,[
                'constraints'=>[
                    new NotBlank(),
                ],
            ])
            ->add('lastName',null,[
                'constraints'=>[
                    new NotBlank(),
                ],
            ])
            ->add('bornAt',DateType::class,[
                'widget'=>'single_text',
                'constraints'=>[
                    new NotBlank(),
                ],
            ])
            ->add('prohibitedFood',EntityType::class,[
                'class'=>Food::class,
                'expanded'=>true,
                'multiple'=>true,
            ])
            ->add('tShirtSize',null,[
                'required'=>true,
                'placeholder'=>'Select...',
                'constraints'=>[
                    new NotBlank(),
                ],
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
            'data_class' => Member::class,
        ));
    }


}
