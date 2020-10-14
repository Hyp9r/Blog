<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'text',
            \Symfony\Component\Form\Extension\Core\Type\SearchType::class,
            [
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Search',
                    'aria-label' => 'Search',
                    'data-width' => 250,
                    'autocomplete' => 'off'
                )
            ]
        )->add(
            'submit',
            SubmitType::class,
            [
                'label_html' => true,
                'label' => '<i class="fas fa-search"></i>',
                'attr' => array(
                    'class' => 'btn'
                )
            ]
        );
    }

    public function getName()
    {
        return 'top_form';
    }

}