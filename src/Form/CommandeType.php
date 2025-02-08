<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Plat;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idcommande')
            ->add('dateheurecommande', null, [
                'widget' => 'single_text',
            ])
            ->add('iduser', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('listplat', EntityType::class, [
                'class' => Plat::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
