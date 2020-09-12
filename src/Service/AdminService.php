<?php


namespace App\Service;


use App\Entity\Admin;
use App\Repository\AdminRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;

class AdminService
{

    /**
     * @var AdminRepository
     */
    protected $adminRepository;

    /**
     * @var PostRepository
     */
    protected  $postRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * AdminService
     * @param AdminRepository $adminRepository
     * @param PostRepository $postRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        AdminRepository $adminRepository,
        PostRepository $postRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->adminRepository = $adminRepository;
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
    }

    public function createAdmin($data){
        $admin = new Admin();
        $admin->setUsername($data['username']);
        $admin->setPassword($data['password']);
        $admin->setDisplayName($data['displayName']);

        $this->entityManager->persist($admin);
        $this->entityManager->flush();
    }
}