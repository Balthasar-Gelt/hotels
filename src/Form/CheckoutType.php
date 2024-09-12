<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'First name is required']),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'First name must be at least {{ limit }} characters',
                        'maxMessage' => 'First name cannot exceed {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('last_name', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Last name is required']),
                ],
            ])
            ->add('email', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Email is required']),
                    new Assert\Email(['message' => 'The email "{{ value }}" is not a valid email.']),
                ],
            ])
            ->add('postal_code', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Postal code is required']),
                    new Assert\Length([
                        'min' => 4,
                        'max' => 10,
                        'minMessage' => 'Postal code must be at least {{ limit }} characters',
                        'maxMessage' => 'Postal code cannot exceed {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('city', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'City is required']),
                ],
            ])
            ->add('card_number', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Card number is required']),
                    new Assert\Length(['min' => 16, 'max' => 16, 'exactMessage' => 'Card number must be 16 digits long']),
                    new Assert\Regex([
                        'pattern' => '/^\d{16}$/',
                        'message' => 'Card number must only contain numbers',
                    ]),
                ],
            ])
            ->add('card_expire', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Expire date is required']),
                    new Assert\Regex([
                        'pattern' => '/^(0[1-9]|1[0-2])\/\d{2}$/',
                        'message' => 'Expire date must be in MM/YY format',
                    ]),
                ],
            ])
            ->add('card_cvc', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'CVC is required']),
                    new Assert\Length(['min' => 3, 'max' => 4, 'exactMessage' => 'CVC must be 3 or 4 digits long']),
                    new Assert\Regex([
                        'pattern' => '/^\d{3,4}$/',
                        'message' => 'CVC must only contain numbers',
                    ]),
                ],
            ])
            ->add('billing_address', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Billing address is required']),
                ],
            ])
            ->add('contact_person', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Contact person is required']),
                ],
            ])
            ->add('contact_phone', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Phone number is required']),
                    new Assert\Regex([
                        'pattern' => '/^\+?\d{10,15}$/',
                        'message' => 'Please enter a valid phone number',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
