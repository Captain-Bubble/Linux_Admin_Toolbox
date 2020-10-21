<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
							'label' => 'username',
							'translation_domain' => 'settings.editUserAccount'
						])
            ->add('roles', ChoiceType::class, [
							'multiple' => true,
							'expanded' => true,
							'choices' => User::ROLES,
							'label' => 'roles',
							'translation_domain' => 'settings.editUserAccount'
						])
            ->add('password', PasswordType::class, [
							'label' => 'password',
							'translation_domain' => 'settings.editUserAccount'
						])
					->add( 'submit', SubmitType::class, [
						'label' => 'submit',
						'attr' => ['class' => 'btn-primary sendSubmitEditUser'],
						'translation_domain' => 'settings.editUserAccount'
					])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
