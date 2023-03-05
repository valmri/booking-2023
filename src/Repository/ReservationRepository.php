<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function save(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Permet de savoir si un utilisateur a déjà réservé une place
     * @param int $user_id
     * @param int $show_id
     * @return bool
     */
    public function isReserve(int $user_id, int $show_id):bool
    {
        $resultat = false;

        $recherche = $this->createQueryBuilder('s')
            ->select('count(s.id)')
            ->andWhere('s.user = :user')
            ->andWhere('s.spectacle = :show')
            ->setParameter(':user', $user_id)
            ->setParameter(':show', $show_id)
            ->getQuery()
            ->getResult();

        if(isset($recherche[0][1]) && $recherche[0][1] > 0) {
            $resultat = true;
        }

        return $resultat;
    }

    /**
     * Permet de savoir si la place choisie est déjà réservée
     * @param int $show_id
     * @param int $seat_id
     * @return bool
     */
    public function isPlaceReservee(int $show_id, int $seat_id): bool
    {
        $resultat = false;

        $recherche = $this->createQueryBuilder('s')
            ->select('count(s.id)')
            ->andWhere('s.spectacle = :spectacle')
            ->andWhere('s.seat = :seat')
            ->setParameter(':spectacle', $show_id)
            ->setParameter(':seat', $seat_id)
            ->getQuery()
            ->getResult();

        if(isset($recherche[0][1]) && $recherche[0][1] > 0) {
            $resultat = true;
        }

        return $resultat;
    }

//    /**
//     * @return Reservation[] Returns an array of Reservation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
