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
    const ACTIVITIES = [
        'Aéronautique - défense' => 'Aéronautique - défense',
        'Agroalimentaire' => 'Agroalimentaire',
        'Equipementiers automobile' => 'Equipementiers automobile',
        'Centre d\'appels - Relation clients' => 'Centre d\'appels - Relation clients',
        'Energies nouvelles renouvelables' => 'Energies nouvelles renouvelables',
        'Nucléaire' => 'Nucléaire',
        'Logistique - Transports' => 'Logistique - Transports',
        'Equipementiers machinisme agricoles' => 'Equipementiers machinisme agricoles',
        'Mécanique - Travaux des métaux' => 'Mécanique - Travaux des métaux',
        'Matériaux composites' =>  'Matériaux composites',
        'Banque assurance et mutuelle' =>  'Banque assurance et mutuelle',
        'Objets connectés - IA - Electronique' => 'Objets connectés - IA - Electronique',
        'ESN' => 'ESN',
        'Industrie graphique' => 'Industrie graphique',
        'Parfumerie cosmétique' => 'Parfumerie cosmétique',
        'Santé - Pharmacie' => 'Santé - Pharmacie',
        'Transformation du bois' => 'Transformation du bois',
        'Economie scoiale et solidaire' => 'Economie scoiale et solidaire',
        'Autre' => 'Autre',
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'pictureFile',
                VichFileType::class,
                [
                    'required' => false,
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
                        'Activités' => self::ACTIVITIES
                    ],
                    'expanded' => false,
                    'multiple' => false,
                    'placeholder' => 'Choisissez une activité',
                    'label_attr' => ['class' => 'mb-0'],
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
                    'expanded' => false,
                    'multiple' => false,
                    'placeholder' => 'Choisissez un bassin d\'emploi',
                    'label_attr' => ['class' => 'mb-0'],
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
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Le mot de passe de confirmation ne correspond pas',
                    'required' => true,
                    'first_options' => [
                        'label' => 'Mot de passe',
                        'attr' => ['class' => 'd-flex flex-column col-12 p-0'],

                    ],
                    'second_options' => [
                        'label' => 'Confirmation du mot de passe',
                        'attr' => ['class' => 'd-flex flex-column col-12 p-0'],

                    ],
                    'mapped' => true,
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
