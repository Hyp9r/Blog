<?php


namespace App\Form;

use App\Entity\Post;
use App\Entity\Tag;
use App\Form\Type\TagsInputType;
use App\Repository\TagRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostType extends AbstractType
{

    private $slugger;
    private $tags;


    public function __construct(SluggerInterface $slugger, TagRepository $tags)
    {
        $this->slugger = $slugger;
        $this->tags = $tags;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('text', TextareaType::class)
            //->add('datePublished', DateType::class)
            ->add('tags', TagsInputType::class, [
                'label' => 'Tags',
                'required' => false,
                'attr' => [
                    'class' => 'select2 select2-hidden-accessible multiple'
                ]
            ])
            ->add('save', SubmitType::class)
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event){
                /**
                 * @var Post
                 */
                $post = $event->getData();
                if(null !== $postTitle = $post->getTitle()) {
                    $post->setSlug($this->slugger->slug($postTitle)->lower());
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Post::class,
            ]
        );
    }
}