<?php

namespace App\Form;

use App\Config\PropertyCountryEnum;
use App\Config\PropertyStatusEnum;
use App\Config\PropertyTypeEnum;
use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            // ->add('address', TextType::class)
            // ->add('city', TextType::class)
            // ->add('state', TextType::class)
            // ->add('zip_code', TextType::class)
            // ->add('country', ChoiceType::class, ['choices' => array_column(PropertyCountryEnum::cases(), 'value', 'value')])
            ->add('description', TextareaType::class)
            ->add('features', TextareaType::class)
            ->add('build_year', ChoiceType::class, ['choices' => $this->getYears(1900)])
            ->add('image_path', FileType::class, ['required' => false, 'mapped' => false ])
            ->add('bedrooms', TypeIntegerType::class)
            ->add('bathrooms', TypeIntegerType::class)
            ->add('sqft', TextType::class)
            ->add('acres', TextType::class)
            ->add('type', ChoiceType::class, ['choices' => array_column(PropertyTypeEnum::cases(), 'value', 'value')])
            ->add('status', ChoiceType::class, ['choices' => array_column(PropertyStatusEnum::cases(), 'value', 'value')])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
            'require_image_path' => true
        ]);
        $resolver->setAllowedTypes('require_image_path', 'bool');
    }

    private function getYears($min, $max = 'current') {
         $years = range($min, ($max === 'current' ? date('Y') : $max));
         return array_combine($years, $years);
    }
}
