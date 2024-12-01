<?php

namespace App\Form;

use App\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'actualitée',
            ])

            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
            ])

//            ->add('createdAt', null, [
//                'label' => 'Crée le',
//                'widget' => 'single_text',
//            ])
//            ->add('slug')
//            ->add('updatedAt', null, [
//                'widget' => 'single_text',
//            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => false,
                'asset_helper' => true,
                'constraints' => $options['is_new'] ? [
                    new NotBlank([
                        'message' => 'l\'image est requise',
                    ])
                ] : [],
            ]);
//            ->add('is_published')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => News::class,
            'is_new' => false,
        ]);
    }
}
