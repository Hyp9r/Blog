<?php


namespace App\Service;


use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $passwordEncoder;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var PostRepository
     */
    protected $postRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * AdminService
     * @param UserRepository $userRepository
     * @param PostRepository $postRepository
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        UserRepository $userRepository,
        PostRepository $postRepository,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function createUser($data)
    {
        $user = new User();
        $user->setUsername($data['username']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $data['password']));
        $user->setDisplayName($data['displayName']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function search($data){
        return $this->userRepository->search($data);
    }

    public function followUser($user){
        
    }

}