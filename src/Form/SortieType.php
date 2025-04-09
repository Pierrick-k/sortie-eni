<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('id', HiddenType::class, [
            'mapped' => false,
            ])
            ->add('nom',TextType::class,[
                'label'=>'Nom de la sortie',
            ])
            ->add('dateHeureDebut', DateTimeType::class,[
                'label'=>'Date et heure de la sortie',
                'widget' => 'single_text'])
            ->setMethod('GET')
            ->add('dateLimiteInscription', DateType::class,[
                'label'=>'Date limite d\'inscription',
                'widget' => 'single_text'])
            ->add('nbInscriptionMax', IntegerType::class,[
                'label'=>'Nombre de places',
                'attr'=>[
                    'min'=>1,
                    'required'=>true,
                ]
            ])
            ->add('duree', IntegerType::class,[
                'label'=>'Durée (en minutes)',
                'attr'=>[
                    'min'=>1,
                    'max'=>1440,
                ]
            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'nom',
                'mapped' => false,
                'placeholder' => '--Choisissez une ville--',
                'required' => false,
            ])
            ->add('lieu', EntityType::class, [
                'label'=>'Lieu',
                'class' => Lieu::class,
                'choice_label' => 'nom',
                "placeholder" => "--Choisissez un lieu--",
            ])
            ->add('infosSortie', TextAreaType::class,[
                'label'=>'Description et infos',
                'required'=>false,
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
