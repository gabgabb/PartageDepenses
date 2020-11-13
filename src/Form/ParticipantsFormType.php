<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Participants;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('quotient')
            ->add('evenement', EntityType::class, [
                'class'=>Evenement::class,//choix de la classe
                'choice_label'=>'nom', //quel champ sert à l'affichage
                'multiple'=>false, //false, on est en many to one
                'expanded'=>false //false fera un select true fera des boutons radio
            ])
            ->add("ok", SubmitType::class, ["label"=>"Enregistrer"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participants::class,
        ]);
    }
}
