<?php

namespace App\Repository;

use App\Entity\Chat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chat>
 *
 * @method Chat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chat[]    findAll()
 * @method Chat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Chat $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Chat $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // Find all chats where the user is a member
    public function findAllChatsByUser($user)
    {
        return $this->createQueryBuilder('c')
            ->join('c.users', 'u')
            ->where('u.id = :user')
            ->leftJoin('c.messages', 'm')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function isUserMemberOfChat($user, $chatId)
    {
        return $this->createQueryBuilder('c')
            ->join('c.users', 'u')
            ->where('u.id = :user')
            ->andWhere('c.id = :chatId')
            ->setParameter('user', $user)
            ->setParameter('chatId', $chatId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
