<?php


namespace App\Service;


use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminService
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

    public function createAdmin($data)
    {
        $admin = new User();
        $admin->setUsername($data['username']);
        $admin->setPassword($this->passwordEncoder->encodePassword($admin, $data['password']));
        $admin->setDisplayName($data['displayName']);
        $admin->setRoles(['ROLE_ADMIN']);

        $this->entityManager->persist($admin);
        $this->entityManager->flush();
    }
}