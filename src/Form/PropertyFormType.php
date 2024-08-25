<?php

namespace App\Form;

use App\Config\PropertyTypeEnum;
use App\Entity\Broker;
use App\Entity\Property;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType as TypeIntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class)
            ->add('city', TextType::class)
            ->add('zip_code', TextType::class)
            ->add('description', TextareaType::class)
            ->add('build_year', DateType::class, [
                    'widget' => 'choice',
                    'input'  => 'datetime_immutable'
            ])
            ->add('image_path', FileType::class, array(
                'required' => false,
                'mapped' => false
                ))
            ->add('bedrooms', TypeIntegerType::class)
            ->add('bathrooms', TypeIntegerType::class)
            ->add('sqft', TextareaType::class)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Single' => PropertyTypeEnum::Single->value,
                    'Townhouse' => PropertyTypeEnum::Townhouse->value,
                    'MultiFamily' => PropertyTypeEnum::MultiFamily->value,
                    'Bungalow' => PropertyTypeEnum::Bungalow->value,
                ],
            ])
            ->add('broker', EntityType::class, [
                'class' => Broker::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
