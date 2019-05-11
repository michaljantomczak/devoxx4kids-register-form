<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

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
            ->add('firstName',null,[
                'constraints'=>[
                    new NotBlank(),
                ]
            ])
            ->add('lastName',null,[
                'constraints'=>[
                    new NotBlank(),
                ]
            ])
            ->add('email',EmailType::class,[
                'constraints'=>[
                    new NotBlank(),
                    new Email(),
                ]
            ])
            ->add('telephone',null,[
                'attr'=>[
                    'maxlength'=>19,
                ],
                'constraints'=>[
                    new NotBlank(),
                    new Regex([
                        'pattern'=>'/^[1-9][0-9]{8}(,[1-9][0-9]{8})*$/',
                        'message'=>'Invalid phone numbers pattern. Allow: nine numbers. For multiple records please separate by comma. E.G. 123321123,432112345'
                    ]),
                    new Length(['max'=>19])
                ]

            ])
            ->add('allowConditions',CheckboxType::class,[
                'label'=>'Potwierdzam, że zapoznałem się z regulaminami i je akceptuję.',
                'required'=>true,
                'mapped'=>false,
                'block_name'=>'allow_conditions',
                'constraints'=>[
                    new NotBlank(),
                ]
            ])
            ->add('allowTerms',null,[
                'label'=>'Wyrażam zgodę na przetwarzanie moich danych osobowych przez Stowarzyszenie Polska Grupa Użytkowników Pythona, ul. Madalińskiego 106, 02-506 Warszawa, w celu udziału w warsztatach programistycznych dla dzieci Czarodzieje Kodu.',
                'required'=>true,
                'constraints'=>[
                    new NotBlank(),
                ]
            ])
            ->add('allowMarketing',null,[
                'label'=>'Wyrażam zgodę na przesyłanie mi przez Stowarzyszenie Polska Grupa Użytkowników Pythona, ul. Madalińskiego 106, 02-506 Warszawa za pomocą środków komunikacji elektronicznej informacji o przyszłych warsztatach (np. newsletterów).',
            ])
            ->add('countChild',ChoiceType::class,[
                'placeholder'=>'Select...',
                'required'=>true,
                'choices'=>$countChildChoices,
                'mapped'=>false,
                'constraints'=>[
                    new NotBlank(),
                ]
            ])
            ->add('members',MemberCollectionType::class,[
                'constraints'=>[
                    new Count(['min'=>1,'max'=>5]),
                ]
            ])
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
