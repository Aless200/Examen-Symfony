<?php

namespace App\Form;

use App\Class\Contact;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Prenom',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'example@example.com',
                ]
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' => 'Admin à contacter',
                'choice_label' => 'firstName',
                'query_builder' => function (EntityRepository $er) {
                return $er
                    ->createQueryBuilder('u')
                    ->where('u.roles LIKE :roles')
                    ->setParameter('roles', '%ROLE_ADMIN%');
                },
                'placeholder' => 'Sélectionnez un admin',
            ])
            ->add('subject', TextType::class, [
                'label' => 'Sujet',
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
