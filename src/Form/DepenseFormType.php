<?php

namespace App\Form;

use App\Entity\Depense;
use App\Entity\Participants;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepenseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('montant')
            ->add('description')
            ->add('participants', EntityType::class, [
                'class'=>Participants::class,//choix de la classe
                'choice_label'=>'nom', //quel champ sert à l'affichage
                'multiple'=>false, //false, on est en many to one
                'expanded'=>false //false fera un select true fera des boutons radio
                 ])
            ->add("ok", SubmitType::class, [ "label"=>"Enregistrer"]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Depense::class,
        ]);
    }
}
