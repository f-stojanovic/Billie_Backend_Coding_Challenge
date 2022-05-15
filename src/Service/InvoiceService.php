<?php

namespace App\Service;

use App\Entity\Company;
use App\Entity\Invoice;
use App\Repository\CompanyRepository;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;

/**
 * Class InvoiceService
 */
class InvoiceService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var InvoiceRepository
     */
    private $invoiceRepository;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * InvoiceService constructor.
     * @param EntityManagerInterface $entityManager
     * @param InvoiceRepository $invoiceRepository
     * @param CompanyRepository $companyRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        InvoiceRepository $invoiceRepository,
        CompanyRepository $companyRepository
    ) {
        $this->entityManager = $entityManager;
        $this->invoiceRepository = $invoiceRepository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * @param int|null $id
     * @param Company $debtorCompanyId
     * @param Company $creditorCompanyId
     * @param string|null $service
     * @param string $quantity
     * @param string $cost
     * @param string $statusType
     * @return Invoice
     * @throws Exception
     */
    public function addOrEditInvoice(
        ?int $id,
        Company $debtorCompanyId,
        Company $creditorCompanyId,
        ?string $service,
        string $quantity,
        string $cost,
        string $statusType
    ): Invoice {

        $persist = false;
        if ($id) {
            /** @var InvoiceRepository $invoiceRepository */
            $invoice = $this->invoiceRepository->find($id);
            if (!$invoice) throw new \Exception("Id invalid!");
        }else {
            $invoice = new Invoice();
            $persist = true;
        }

        $debtorCompany = $this->companyRepository->find($debtorCompanyId);
        $invoice->setDebtorCompanyId($debtorCompany);
        $creditorCompany = $this->companyRepository->find($creditorCompanyId);
        $invoice->setCreditorCompanyId($creditorCompany);
        $invoice->setService($service);
        $invoice->setQuantity($quantity);
        $invoice->setCost($cost);
        $invoice->setStatusType($statusType);
        $invoice->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $invoice->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));

        if ($persist){
            $this->entityManager->persist($invoice);
        }
        $this->entityManager->flush();

        return $invoice;
    }

    /**
     * @param $entity
     * @return void
     */
    public function generateInvoiceNo($entity)
    {
        $generateNo =  str_pad($entity->getId(), 7, "0", STR_PAD_LEFT);
        $entity->setInvoiceNo("F-" . $generateNo);
        $this->entityManager->flush();
    }

    /**
     * @param $debtorCompanyId
     * @param $newCost
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function checkDebtLimit($debtorCompanyId, $newCost): bool
    {
       $invoicesAmount = $this->invoiceRepository->getInvoicesAmountForCompany($debtorCompanyId);
       $invoicesSum = $invoicesAmount + $newCost;

       $companyDebtLimit =  $this->companyRepository->findOneBy(['id' => $debtorCompanyId]);
       $limit = $companyDebtLimit->getDebtLimit();

       if ($invoicesSum >= $limit) {
           return true;
       } else return false;
    }
}
