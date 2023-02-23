<?php

namespace App\Repository;

use App\Entity\Show;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Show>
 *
 * @method Show|null find($id, $lockMode = null, $lockVersion = null)
 * @method Show|null findOneBy(array $criteria, array $orderBy = null)
 * @method Show[]    findAll()
 * @method Show[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Show::class);
    }

    public function save(Show $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Show $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findRecentShow(): array
    {
        $dateDuJour = new DateTime('now');

        $shows = $this->createQueryBuilder('s')
            ->andWhere('s.date_end >= :dateDuJour')
            ->andWhere('s.date_start >= :dateDuJour')
            ->orderBy('s.date_start')
            ->setParameter('dateDuJour', $dateDuJour);

        $paginator = new Paginator($shows, true);

        return $paginator->getQuery()->getResult();
    }
}
