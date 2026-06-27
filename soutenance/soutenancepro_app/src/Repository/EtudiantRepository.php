<?php

namespace App\Repository;

use App\Entity\Etudiant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EtudiantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etudiant::class);
    }

    public function searchByNom(?string $nom): array
    {
        $qb = $this->createQueryBuilder('e');
        if ($nom) {
            $qb->andWhere('e.nom LIKE :nom OR e.prenom LIKE :nom')
               ->setParameter('nom', '%'.$nom.'%');
        }
        return $qb->orderBy('e.nom', 'ASC')->getQuery()->getResult();
    }
}
