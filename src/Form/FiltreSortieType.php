<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
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
            ])
            ->add('mes_sorties', CheckboxType::class, [
                'label'=>'Sorties dont je suis l\'organisateur/trice',
                'required' => false,
            ])
            ->add('sorties_inscrit', CheckboxType::class, [
                'label'=>'Sorties auxquelles je suis inscrit/e',
                'required' => false,
            ])
            ->add('sorties_pas_inscrit', CheckboxType::class, [
                'label'=>'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
            ])
            ->add('sorties_finie', CheckboxType::class, [
                'label'=>'Sorties terminées',
                'required' => false,
            ])
            ->add('nom', TextType::class, [
                'label' => 'Le nom de la sortie contient',
                'attr'=>['placeholder' => 'search'],
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Entre',
                'widget' => 'single_text',
                'html5' => true,
                /*'format' => 'dd-MM-yyyy',
                'attr' => ['class' => 'js-datepicker'],*/
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'et',
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('btnSubmit', SubmitType::class, [
                'label' => 'Rechercher',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
