<?php

namespace App\Repository;

use App\Entity\Invoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InvoiceRepository|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvoiceRepository|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvoiceRepository[]    findAll()
 * @method InvoiceRepository[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    /**
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getInvoiceListPaginated(int $page = 1, int $pageSize = 20): array
    {
        $query = $this->createQueryBuilder('i')
            ->select('i')
            ->orderBy('i.createdAt', 'DESC')
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
    public function getInvoicesCount(): mixed
    {
        return $this->createQueryBuilder('i')
            ->select('count(i.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return int|mixed|string
     */
    public function getInvoicesForDashboard(): mixed
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    /**
     * Delete invoice
     *
     * @param $id
     * @return float|int|mixed|string
     */
    public function deleteInvoice($id): mixed
    {
        return $this->createQueryBuilder('i')
            ->delete('App:Invoice', 'i')
            ->where('i.id = :id')
            ->setParameter("id", $id)
            ->getQuery()
            ->execute();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getInvoicesAmountForCompany($debtorCompanyId): int
    {
        $invoicesAmount = $this->createQueryBuilder('t')
            ->select('sum(abs(t.cost)) as amount')
            ->where('t.debtorCompanyId = :debtorCompanyId')
            ->andWhere('t.statusType = :statusType')
            ->setParameters([
                'debtorCompanyId' => $debtorCompanyId,
                'statusType'      => Invoice::STATUS_OPEN
            ])
            ->getQuery()
            ->getSingleScalarResult();

        return intval($invoicesAmount);
    }
}