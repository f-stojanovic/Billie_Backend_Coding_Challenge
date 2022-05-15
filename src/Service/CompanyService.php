<?php

namespace App\Service;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * Class CompanyService
 */
class CompanyService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * CompanyService constructor.
     * @param EntityManagerInterface $entityManager
     * @param CompanyRepository $companyRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        CompanyRepository $companyRepository
    ) {
        $this->entityManager = $entityManager;
        $this->companyRepository = $companyRepository;
    }

    /**
     * @param int|null $id
     * @param string $companyName
     * @param string $email
     * @param string|null $address
     * @param string|null $phone
     * @param string $customerType
     * @param float $debtLimit
     * @return Company
     * @throws Exception
     */
    public function addOrEditCompany(
        ?int $id,
        string $companyName,
        string $email,
        ?string $address,
        ?string $phone,
        string $customerType,
        float $debtLimit
    ): Company {

        $persist = false;
        if ($id) {
            /** @var CompanyRepository $companyRepository */
            $company = $this->companyRepository->find($id);
            if (!$company) throw new \Exception("Id invalid!");
        }else {
            $company = new Company();
            $persist = true;
        }

        $company->setCompanyName($companyName);
        $company->setEmail($email);
        $company->setAddress($address);
        $company->setPhone($phone);
        $company->setCustomerType($customerType);
        $company->setDebtLimit($debtLimit);
        $company->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $company->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));

        if ($persist){
            $this->entityManager->persist($company);
        }
        $this->entityManager->flush();

        return $company;
    }
}
