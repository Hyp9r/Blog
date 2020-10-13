<?php


namespace App\Service;


use App\Entity\History;
use App\Repository\HistoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class HistoryService
{

    /**
     * @var HistoryRepository
     */
    protected $historyRepository;


    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * HistoryService constructor.
     * @param HistoryRepository $historyRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(HistoryRepository $historyRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->historyRepository = $historyRepository;
    }

    public function getUserHistory($user_id)
    {
        return $this->historyRepository->findLastFiveByUsername($user_id);
    }

    public function addToUserHistory($data){
        $history = new History();
        $history->addUser($data['user']);
        $history->setSearch($data['search']);
        $this->entityManager->persist($history);
        $this->entityManager->flush();
    }

}