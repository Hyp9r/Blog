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

    public function createUser(User $user)
    {
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function search($data){
        return $this->userRepository->search($data);
    }

    public function getPopularUsers(){
        return $this->userRepository->getPopularUser();
    }

    public function getUser($id){
        return $this->userRepository->findOneBy(array('id' => $id), );
    }

    public function getPostsFromUser($id){
        return $this->postRepository->getPostsFromUser($id);
    }

    public function followUser(User $user, User $targetUser){
        $targetUser->setFollower($user);
        $this->entityManager->persist($targetUser);
        $this->entityManager->flush();
    }

    public function unFollowUser(User $user, User $targetUser){
        $targetUser->removeFollow($user);
        $this->entityManager->persist($targetUser);
        $this->entityManager->flush();
    }

    public function getFollowers(User $user){
        return $user->getFollowers();
    }

    public function getFollowings(User $user){
        return $user->getFollowings();
    }

}