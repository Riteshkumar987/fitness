<?php

// namespace App\Form;

// use App\Entity\User;
// use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\Extension\Core\Type\EmailType;
// use Symfony\Component\Form\Extension\Core\Type\PasswordType;
// use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
// use Symfony\Component\Form\Extension\Core\Type\TextType;
// use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\OptionsResolver\OptionsResolver;

// class UserType extends AbstractType
// {
//     public function buildForm(FormBuilderInterface $builder, array $options)
//     {
//         $builder
//             ->add('email', EmailType::class)
//             ->add('name', TextType::class)
//             ->add('password', RepeatedType::class, [
//                 'type' => PasswordType::class,
//                 'first_options' => ['label' => 'Password'],
//                 'second_options' => ['label' => 'Confirm Password']
//             ])
//         ;
//     }

//     public function configureOptions(OptionsResolver $resolver)
//     {
//         $resolver->setDefaults([
//             'data_class' => User::class,
//         ]);
//     }
// }

// src/Form/UserType.php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
            ]);

        // Only add the password field if "is_edit" is false
        if (!$options['is_edit']) {
            $builder->add('password', PasswordType::class, [
                'label' => 'Password',
            ]);
        }

        if ($options['is_edit']) {
            $builder->add('roles', ChoiceType::class, [
                'label' => 'Roles',
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER',
                ],
                'multiple' => true, // Allow multiple roles
                'expanded' => true, // Display as checkboxes
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false, // Default value for the "is_edit" option
        ]);

        $resolver->setAllowedTypes('is_edit', 'bool'); // Ensure "is_edit" is a boolean
    }

}
