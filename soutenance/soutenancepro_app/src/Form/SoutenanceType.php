<?php

namespace App\Form;

use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Entity\Salle;
use App\Entity\Soutenance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SoutenanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etudiant', EntityType::class, [
                'class' => Etudiant::class,
                'choice_label' => fn(Etudiant $e) => $e->getPrenom().' '.$e->getNom(),
                'label' => 'Étudiant',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('president', EntityType::class, [
                'class' => Enseignant::class,
                'choice_label' => fn(Enseignant $e) => $e->getPrenom().' '.$e->getNom(),
                'label' => 'Président du jury',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('rapporteur', EntityType::class, [
                'class' => Enseignant::class,
                'choice_label' => fn(Enseignant $e) => $e->getPrenom().' '.$e->getNom(),
                'label' => 'Rapporteur',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('examinateur', EntityType::class, [
                'class' => Enseignant::class,
                'choice_label' => fn(Enseignant $e) => $e->getPrenom().' '.$e->getNom(),
                'label' => 'Examinateur',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('salle', EntityType::class, [
                'class' => Salle::class,
                'choice_label' => 'code',
                'label' => 'Salle',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('date', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('heure', TimeType::class, [
                'label' => 'Heure',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Soutenance::class]);
    }
}
