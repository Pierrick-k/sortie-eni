<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\Model\FiltreSortieModel;
use App\Model\FiltreSortieDTO;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
                'label_attr' => [
                    'class' => 'd-inline-block w-25'
                ],
                'attr'=>[
                    'class' => 'd-inline-block w-50'],
            ])
            ->add('mesSorties', CheckboxType::class, [
                'label'=>'Sorties dont je suis l\'organisateur/trice',
                'required' => false,
                'row_attr' => [
                    'class' => 'fs-6',
                ],
            ])
            ->add('sortiesInscrit', CheckboxType::class, [
                'label'=>'Sorties auxquelles je suis inscrit/e',
                'required' => false,
                'row_attr' => [
                    'class' => 'fs-6',
                ],
            ])
            ->add('sortiesPasInscrit', CheckboxType::class, [
                'label'=>'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
                'row_attr' => [
                    'class' => 'fs-6',
                ],
            ])
            ->add('sortiesTerminees', CheckboxType::class, [
                'label'=>'Sorties terminées',
                'required' => false,
                'row_attr' => [
                    'class' => 'fs-6',
                ],
            ])
            ->add('nom', TextType::class, [
                /*'label' => 'Le nom de la sortie contient',*/
                'label' => '🔍',
                'row_attr' => [
                    'class' => 'input-group w-auto d-inline-block mb-3',
                ],
                'label_attr' => [
                    'class' => 'd-inline-block'
                ],
                'attr'=>[
                    'placeholder' => 'search',
                    'class' => 'd-inline-block w-auto'],
                'required' => false,
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Entre',
                'required' => false,
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'et',
                'required' => false,
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('btnSubmit', SubmitType::class, [
                'label' => 'Rechercher',
                'row_attr' => ['class' => 'btn btn-primary h-25'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
            'data_class' => FiltreSortieModel::class,
        ]);
    }
}
