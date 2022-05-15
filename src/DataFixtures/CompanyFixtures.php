<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;

/**
 * CompanyFixtures.
 */
class CompanyFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $manager1 = $manager;

        $manager1->persist($this->persistCompanyOne());
        $manager1->persist($this->persistCompanyTwo());
        $manager1->persist($this->persistCompanyThree());
        $manager1->persist($this->persistCompanyFour());

        $manager1->flush();
    }

    /**
     * @throws Exception
     */
    private function persistCompanyOne(): Company
    {
        $company = new Company();
        $company->setCompanyName('Company One')
            ->setEmail('company@one.com')
            ->setAddress('Address 1')
            ->setPhone('123456789')
            ->setCustomerType(Company::TYPE_CREDITOR)
            ->setCreatedAt(new \DateTime(date("Y-m-d H:i:s", strtotime("-1 day"))))
            ->setUpdatedAt(new \DateTime(date("Y-m-d H:i:s", strtotime("-1 day"))));

        return $company;
    }

    /**
     * @throws Exception
     */
    private function persistCompanyTwo(): Company
    {
        $company = new Company();
        $company->setCompanyName('Company Two')
            ->setEmail('company@two.com')
            ->setAddress('Address 2')
            ->setPhone('123456789')
            ->setCustomerType(Company::TYPE_CREDITOR)
            ->setCreatedAt(new \DateTime(date("Y-m-d H:i:s", strtotime("-2 day"))))
            ->setUpdatedAt(new \DateTime(date("Y-m-d H:i:s", strtotime("-2 day"))));

        return $company;
    }

    /**
     * @throws Exception
     */
    private function persistCompanyThree(): Company
    {
        $company = new Company();
        $company->setCompanyName('Company Three')
            ->setEmail('company@three.com')
            ->setAddress('Address 3')
            ->setPhone('123456789')
            ->setCustomerType(Company::TYPE_DEBTOR)
            ->setCreatedAt(new \DateTime(date("Y-m-d H:i:s", strtotime("-3 day"))))
            ->setUpdatedAt(new \DateTime(date("Y-m-d H:i:s", strtotime("-3 day"))));

        return $company;
    }

    /**
     * @throws Exception
     */
    private function persistCompanyFour(): Company
    {
        $company = new Company();
        $company->setCompanyName('Company Four')
            ->setEmail('company@four.com')
            ->setAddress('Address 4')
             ->setPhone('123456789')
             ->setCustomerType(Company::TYPE_DEBTOR)
             ->setCreatedAt(new \DateTime(date("Y-m-d H:i:s", strtotime("-4 day"))))
             ->setUpdatedAt(new \DateTime(date("Y-m-d H:i:s", strtotime("-4 day"))));

        return $company;
    }
}
