<?php

namespace App\Repository;

use App\Entity\Enseignant;
use App\Entity\Soutenance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SoutenanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Soutenance::class);
    }

    public function searchByDate(?\DateTimeInterface $date): array
    {
        $qb = $this->createQueryBuilder('s')
            ->andWhere('s.statut != :annulee')
            ->setParameter('annulee', 'annulee');
        if ($date) {
            $qb->andWhere('s.date = :date')->setParameter('date', $date->format('Y-m-d'));
        }
        return $qb->orderBy('s.date', 'ASC')->addOrderBy('s.heure', 'ASC')->getQuery()->getResult();
    }

    public function isSalleOccupee(int $salleId, \DateTimeInterface $date, \DateTimeInterface $heure, ?int $excludeId = null): bool
    {
        $qb = $this->createQueryBuilder('s')
            ->andWhere('s.salle = :salle')
            ->andWhere('s.date = :date')
            ->andWhere('s.heure = :heure')
            ->andWhere('s.statut != :annulee')
            ->setParameter('salle', $salleId)
            ->setParameter('date', $date->format('Y-m-d'))
            ->setParameter('heure', $heure->format('H:i:s'))
            ->setParameter('annulee', 'annulee');
        if ($excludeId) {
            $qb->andWhere('s.id != :exclude')->setParameter('exclude', $excludeId);
        }
        return count($qb->getQuery()->getResult()) > 0;
    }

    public function isEnseignantOccupe(int $enseignantId, \DateTimeInterface $date, \DateTimeInterface $heure, ?int $excludeId = null): bool
    {
        $qb = $this->createQueryBuilder('s')
            ->andWhere('(s.president = :ens OR s.rapporteur = :ens OR s.examinateur = :ens)')
            ->andWhere('s.date = :date')
            ->andWhere('s.heure = :heure')
            ->andWhere('s.statut != :annulee')
            ->setParameter('ens', $enseignantId)
            ->setParameter('date', $date->format('Y-m-d'))
            ->setParameter('heure', $heure->format('H:i:s'))
            ->setParameter('annulee', 'annulee');
        if ($excludeId) {
            $qb->andWhere('s.id != :exclude')->setParameter('exclude', $excludeId);
        }
        return count($qb->getQuery()->getResult()) > 0;
    }

    public function findByEnseignant(Enseignant $enseignant): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.president = :e OR s.rapporteur = :e OR s.examinateur = :e')
            ->andWhere('s.statut != :annulee')
            ->setParameter('e', $enseignant)
            ->setParameter('annulee', 'annulee')
            ->orderBy('s.date', 'ASC')
            ->getQuery()->getResult();
    }
}
