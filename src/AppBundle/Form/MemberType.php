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
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class MemberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $beginAt=new \DateTime();
        $beginAt->sub(new \DateInterval('P15Y'));
        $beginAt=new \DateTime($beginAt->format('Y-01-01'));

        $endAt=new \DateTime();
        $endAt->sub(new \DateInterval('P6Y'));
        $endAt=new \DateTime($endAt->format('Y-12-31'));

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
                'attr'=>[
                    'max'=>$endAt->format('Y-m-d'),
                    'min'=>$beginAt->format('Y-m-d'),
                ],
                'constraints'=>[
                    new NotBlank(),
                    new GreaterThanOrEqual(['value'=>$beginAt]),
                    new LessThanOrEqual(['value'=>$endAt]),
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
