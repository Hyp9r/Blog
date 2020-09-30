<?php


namespace App\Form\Type;


use App\Form\DataTransformer\TagArrayToStringTransformer;
use App\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagsInputType extends AbstractType
{

    private $tags;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tags = $tagRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer(new CollectionToArrayTransformer(), true);
            //->addModelTransformer(new TagArrayToStringTransformer($this->tags), true);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['tags'] = $this->tags->findAll();
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $choices = array();
        $tags1 = $this->tags->findAll();
        foreach ($tags1 as &$tag){
            $choices[$tag->getTitle()] = $tag;
        }
        $resolver->setDefaults(
            [
                'choices' => [
                    $choices
                ],
                'multiple' => true
            ]
        );
    }

}