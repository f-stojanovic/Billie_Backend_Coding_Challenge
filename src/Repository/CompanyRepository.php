<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method CompanyRepository|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyRepository|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyRepository[]    findAll()
 * @method CompanyRepository[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    /**
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getCompanyListPaginated(int $page = 1, int $pageSize = 20): array
    {
        $query = $this->createQueryBuilder('c')
            ->select('c')
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery();

        $paginator = new Paginator($query);
        $totalItems = count($paginator);
        $pagesCount = ceil($totalItems / $pageSize);
        $paginator->getQuery()->setFirstResult($pageSize * ($page - 1))->setMaxResults($pageSize);

        return [
            'data' => $paginator,
            'totalItems' => $totalItems,
            'pagesCount' => $pagesCount,
        ];
    }

    /**
     * @return float|int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getCompaniesCount(): mixed
    {
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Delete company
     *
     * @param $id
     * @return float|int|mixed|string
     */
    public function deleteCompany($id): mixed
    {
        return $this->createQueryBuilder('c')
            ->delete('App:Company', 'c')
            ->where('c.id = :id')
            ->setParameter("id", $id)
            ->getQuery()
            ->execute();
    }
}