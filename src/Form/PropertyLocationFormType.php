<?php

declare(strict_types=1);

namespace App\Form;

use App\Config\PropertyCountryEnum;
use App\DataTransferObject\PropertyLocation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyLocationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class)
            ->add('city', TextType::class)
            ->add('state', TextType::class)
            ->add('zip_code', TextType::class)
            ->add('country', ChoiceType::class, ['choices' => array_column(PropertyCountryEnum::cases(), 'value', 'value')])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PropertyLocation::class,
        ]);
    }
}