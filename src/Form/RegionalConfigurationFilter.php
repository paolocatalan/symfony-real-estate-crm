<?php

declare(strict_types=1);

namespace App\Form;

use App\DataTransferObject\RegionalConfiguration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegionalConfigurationFilter extends AbstractType
{
    public function __construct(private readonly RequestStack $requestStack) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $session = $this->requestStack->getSession();
        $builder
            ->add('timezone', TimezoneType::class, [
                'data' => $session->get('timezone'),
                'required' => false
                ])
            ->add('currency', CurrencyType::class, [
                'data' => $session->get('currency'),
                'required' => false
                ])
            ->add('locale', LanguageType::class, [
                'data' => $session->get('_locale'),
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegionalConfiguration::class,
        ]);
    }
}