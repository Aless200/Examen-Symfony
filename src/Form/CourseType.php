<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Course;
use App\Entity\Level;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('small_description', TextareaType::class, [
                'label' => 'Petite description',
            ])
            ->add('full_description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('duration', TextType::class, [
                'label' => 'Durée'
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
                'scale' => 2
            ] )
//            ->add('created_at', DateTimeType::class, [
//                'label' => 'Crée le : ',
//                'widget' => 'single_text',
//            ])
//            ->add('is_published')
//            ->add('slug')
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => false,
                'asset_helper' => true,
                'constraints' => $options['is_new'] ? [
                    new NotBlank([
                        'message' => 'l\'image est requise.',
                    ])
                ] : [],
            ])

            ->add('pdfFile', VichFileType::class, [
                'label' => 'Programme (PDF)',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => true,
                'download_label' => 'Télécharger le fichier actuel',
                'asset_helper' => true,
                'constraints' => $options['is_new'] ? [
                    new NotBlank([
                        'message' => 'le pdf est requis.',
                    ])
                ] : [],
            ])

            ->add('category', EntityType::class, [
                'label' => 'Categorie',
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez une categorie',
            ])

            ->add('level', EntityType::class, [
                'label' => 'Niveau',
                'class' => Level::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un niveau',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
            'is_new' => false,
        ]);
    }
}
