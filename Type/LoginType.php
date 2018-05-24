<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 14/02/18
 * Time: 11:41
 */

namespace Lch\UserBundle\Type;

use Lch\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType {
    public function buildForm( FormBuilderInterface $builder, array $options ) {
        $builder
            ->add( 'username_or_email', TextType::class, [
                'data' => $options['last_username'],
                'label' => 'lch.user_bundle.login_form.username_or_email.label'
            ] )
            ->add( 'password', PasswordType::class, [
                'label' => 'lch.user_bundle.login_form.password.label'
            ] )
            ->add( 'remember_me', CheckboxType::class, [
                'required' => false,
                'label' => 'lch.user_bundle.login_form.remember_me.label'
            ] )
            ->add('submit', SubmitType::class, [
                'label' => 'lch.user_bundle.login_form.connexion.label'
            ])
        ;
    }

    public function configureOptions( OptionsResolver $resolver ) {
        $resolver->setDefaults( [
            'last_username' => '',
            'translation_domain' => 'LchUserBundle',
        ] );
    }
}