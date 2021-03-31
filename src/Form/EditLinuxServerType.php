<?php

namespace App\Form;

use App\Entity\LinuxServer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditLinuxServerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'serverName',
                TextType::class,
                [
                    'label' => 'serverName',
                    'translation_domain' => 'settings.editLinuxServer'
                ]
            )
            ->add(
                'host',
                TextType::class,
                [
                    'label' => 'host',
                    'translation_domain' => 'settings.editLinuxServer'
                ]
            )
            ->add(
                'username',
                TextType::class,
                [
                    'label' => 'username',
                    'translation_domain' => 'settings.editLinuxServer'
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'label' => 'password',
                    'translation_domain' => 'settings.editLinuxServer'
                ]
            )
            ->add(
                'privateKey',
                HiddenType::class,
                [
                    'empty_data' => ' ',
                    'label' => 'privateKey',
                    'translation_domain' => 'settings.editLinuxServer'
                ]
            )
            ->add(
                'publicKey',
                HiddenType::class,
                [
                    'empty_data' => ' ',
                    'label' => 'publicKey',
                    'translation_domain' => 'settings.editLinuxServer'
                ]
            )
            ->add(
                'passphrase',
                HiddenType::class,
                [
                    'empty_data' => ' ',
                    'label' => 'passphrase',
                    'translation_domain' => 'settings.editLinuxServer'
                ]
            )
            ->add(
                'requireSudo',
                CheckboxType::class,
                [
                    'label' => 'requireSudo',
                    'translation_domain' => 'settings.editLinuxServer'
                ]
            )
            ->add(
                'requirePasswordAfterSudo',
                CheckboxType::class,
                [
                    'label' => 'requirePasswordAfterSudo',
                    'translation_domain' => 'settings.editLinuxServer'
                ]
            )
            ->add('submit', SubmitType::class, [
                'label' => 'submit',
                'attr' => ['class' => 'btn-primary sendSubmitEditUser'],
                'translation_domain' => 'settings.editLinuxServer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LinuxServer::class,
        ]);
    }
}
