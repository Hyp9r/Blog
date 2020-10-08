<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'text',
            TextareaType::class,
            [
                'label' => 'New Comment',
                'help' => 'Tell us what do you think about this post.',
                'attr' => array(
                    'placeholder' => 'Write comment here...'
                )
            ]
        )
            ->add(
                'comment',
                SubmitType::class,
                [
                    'label' => 'Comment!',
                    'attr' => array(
                        'id' => 'swal-5',
                    )
                ]
            )
            ->addEventListener(
                FormEvents::SUBMIT,
                function (FormEvent $event) {
                }
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'App\Entity\Comment'
                //'attr' => ['id' => 'swal-5']
            ]
        );
    }

}