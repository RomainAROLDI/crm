<?php

namespace App\Form;

use App\Entity\Job;
use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use App\Cmd\Customer\EditCustomerFormCmd;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class EditCustomerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false
            ])
            ->add('company', EntityType::class, [
                'label' => 'Entreprise',
                'class' => Company::class,
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('job', EntityType::class, [
                'label' => 'Poste',
                'class' => Job::class,
                'choice_label' => 'title',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EditCustomerFormCmd::class,
        ]);
    }
}