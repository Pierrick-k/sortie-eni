<?php

namespace App\Repository;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\Model\FiltreSortieModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    private function createQueryBuilderSortie() : QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder
            ->addSelect('l')
            ->leftJoin('s.lieu','l')

            ->addSelect('pa')
            ->leftJoin('s.participants','pa')

            ->addSelect('c')
            ->leftJoin('l.ville', 'v')

            ->addSelect('v')
            ->leftJoin('s.campus','c')

            ->addSelect('o')
            ->leftJoin('s.organisateur','o')

            ->addSelect('e')
            ->leftJoin('s.etat','e')
        ;
        return $queryBuilder;
    }

    public function findSortieById(int $id){
        $queryBuilder = $this->createQueryBuilderSortie();
        $queryBuilder
            ->where('s.id = :id')
            ->setParameter('id', $id);

        $query = $queryBuilder->getQuery();
        $query->setMaxResults(1);
        return $query->getOneOrNullResult();
    }

    public function findSortieByFilter(FiltreSortieModel $filtreSortieModel, ?User $user)
    {
        $queryBuilder = $this->createQueryBuilderSortie();

        // Filtre sur le NOM
        if (!empty($filtreSortieModel->nom)):
            $fltrNom = '%' . $filtreSortieModel->nom . '%';
            $queryBuilder
                ->andWhere('s.nom LIKE :nom')
                ->setParameter('nom', $fltrNom);
        endif;

        //Filtre sur le CAMPUS
        if (!empty($filtreSortieModel->campus)):
            //dd($filtreSortieModel->campus);
            $queryBuilder
                ->andWhere('s.campus = :campus')
                ->setParameter('campus', $filtreSortieModel->campus);
        endif;

        if (!empty($user)):
            //Filtre sur l ORGANISATEUR
            if (!empty($filtreSortieModel->mesSorties)):
                $queryBuilder
                    ->andWhere('s.organisateur = :user')
                    ->setParameter('user', $user);
            endif;

            //Filtre sur INSCRIT
            if (!empty($filtreSortieModel->sortiesInscrit) && empty($filtreSortieModel->sortiesPasInscrit)):
                $queryBuilder
                    ->andWhere('s.participants IN (:participant)')
                    ->setParameter('participant', $user);
            endif;

            //Filtre sur PAS INSCRIT
            if (!empty($filtreSortieModel->sortiesPasInscrit) && empty($filtreSortieModel->sortiesInscrit)):
                $queryBuilder
                    ->andWhere('s.participants NOT IN (:participant)')
                    ->setParameter('participant', $user);
            endif;
        endif;

        //Filtre sur sortie "terminées"
        if (!empty($filtreSortieModel->sortiesTerminees)):
            $queryBuilder
                ->andWhere('e.libelle = :terminees ')
                ->setParameter('terminees', etat::TERMINEE);
        endif;

        //Filtre sur date début
        if(!empty($filtreSortieModel->dateDebut)):
            $queryBuilder
                ->andWhere('s.dateHeureDebut >= :dateDebut')
                ->setParameter('dateDebut', $filtreSortieModel->dateDebut);
        endif;

        //Filtre sur date fin
        if(!empty($filtreSortieModel->dateFin)):
            $queryBuilder
                ->andWhere('s.dateHeureDebut <= :dateFin')
                ->setParameter('dateFin', $filtreSortieModel->dateFin);
        endif;

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function findSortieByAPIFilter(
        ?Etat $etat,
        ?\DateTimeImmutable $filterDateDebut,
        ?\DateTimeImmutable $filterDateFin)
    {
        $queryBuilder = $this->createQueryBuilderSortie();
        $queryBuilder->orderBy('s.dateHeureDebut', 'ASC');

        //Masquage "terminées" et "en création"
        $queryBuilder
            ->andWhere('e.libelle NOT IN (:terminees, :en_creation) ')
            ->setParameter('terminees', etat::TERMINEE)
            ->setParameter('en_creation', etat::EN_CREATION);

        if (!empty($etat)):
            $queryBuilder
                ->andWhere('e.id = :etat ')
                ->setParameter('etat', $etat);
        endif;

        //Filtre sur date début
        if(!empty($filterDateDebut)):
            if(!empty($filterDateFin)):
                $queryBuilder
                    ->andWhere('s.dateHeureDebut >= :dateDebut')
                    ->setParameter('dateDebut', $filterDateDebut->format('Y-m-d H:i:s'))
                    ->andWhere('s.dateHeureDebut <= :dateFin')
                    ->setParameter('dateFin', $filterDateFin->format('Y-m-d H:i:s'));
            else:
                $queryBuilder
                    ->andWhere('s.dateHeureDebut >= :dateDebut')
                    ->setParameter('dateDebut', $filterDateDebut->format('Y-m-d H:i:s'));
            endif;
        endif;

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }
}
