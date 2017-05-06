<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
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
            ->add('allowTerms',null,[
                'label'=>'Wyrażam zgodę na przetwarzanie moich danych osobowych przez Fundację Coder Dojo Polska z siedzibą w Zambrowie (ul. Papieża Jana Pawła II 12A/34, 18-300 Zambrów), w celu udziału w warsztatach programistycznych dla dzieci Devoxx4Kids.',
                'required'=>true,
            ])
            ->add('allowMarketing',null,[
                'label'=>'Wyrażam zgodę na przesyłanie mi przez  Fundację Coder Dojo Polska za pomocą środków komunikacji elektronicznej informacji o przyszłych warsztatach (np. newsletterów).',
            ])
            ->add('countChild',ChoiceType::class,[
                'placeholder'=>'Select...',
                'required'=>true,
                'choices'=>$countChildChoices,
                'mapped'=>false,
            ])
            ->add('members',MemberCollectionType::class)
            ->add('submit',SubmitType::class);
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
