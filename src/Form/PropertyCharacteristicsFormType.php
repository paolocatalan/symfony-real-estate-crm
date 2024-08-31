<?php

declare(strict_types=1);

namespace App\Form;

use App\Config\PropertyStatusEnum;
use App\Config\PropertyTypeEnum;
use App\DataTransferObject\PropertyCharacteristics;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType as TypeIntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyCharacteristicsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('bedrooms', TypeIntegerType::class)
            ->add('bathrooms', TypeIntegerType::class)
            ->add('sqft', TextType::class)
            ->add('acres', TextType::class)
            ->add('build_year', ChoiceType::class, ['choices' => $this->getYears(1900)])
            ->add('type', ChoiceType::class, ['choices' => array_column(PropertyTypeEnum::cases(), 'value', 'value')])
            ->add('image_path', FileType::class, ['required' => false, 'mapped' => false ])
            ->add('feature', TextareaType::class)
            ->add('description', TextareaType::class)
            ->add('status', ChoiceType::class, ['choices' => array_column(PropertyStatusEnum::cases(), 'value', 'value')])
            // ->add('broker', EntityType::class, [
            //     'class' => User::class,
            //     'choices' => 'name',
            //     'expanded' => true
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PropertyCharacteristics::class,
        ]);
    }

    private function getYears($min, $max = 'current') {
        $years = range($min, ($max === 'current' ? date('Y') : $max));
        return array_combine($years, $years);
    }
}
