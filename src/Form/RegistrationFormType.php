<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichFileType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'pictureFile',
                VichFileType::class,
                [
                    'required' => true,
                    'allow_delete' => false,
                    'download_uri' => false,
                ]
            )
            ->add('login')
            ->add(
                'firstname',
                TextType::class
            )
            ->add(
                'lastName',
                TextType::class
            )
            ->add(
                'company',
                TextType::class
            )
            ->add(
                'activity',
                ChoiceType::class,
                [
                    'choices' => [
                        'activité1' => 'activite1',
                        'activité2' => 'activite2',
                        'activité3' => 'activite3',
                        'activité4' => 'activite4',
                    ],
                    'expanded' => true,
                    'multiple' => false,
                ]
            )
            ->add(
                'employmentArea',
                ChoiceType::class,
                [
                    'choices' => [
                        'Orléans' => 'Orleans',
                        'Pithiviers' => 'Pithiviers',
                        'Montargis' => 'Montargis',
                        'Gien' => 'Gien',
                    ],
                    'expanded' => true,
                    'multiple' => false,
                ]
            )
            ->add(
                'email',
                EmailType::class
            )
            ->add(
                'phoneNumber',
                TextType::class
            )
            ->add(
                'street',
                TextType::class
            )
            ->add(
                'city',
                TextType::class
            )
            ->add(
                'postalCode',
                TextType::class
            )
            ->add(
                'description',
                TextareaType::class
            )
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Le mot de passe de confirmation ne correspond pas',
                    'required' => false,
                    'first_options' => [
                        'label' => 'Mot de passe',
                    ],
                    'second_options' => [
                        'label' => 'Confirmation du mot de passe',
                    ],
                    'mapped' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
