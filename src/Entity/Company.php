<?php

namespace App\Entity;

use App\Traits\TimeTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company
{
    use TimeTrait;

    const TYPE_CREDITOR = 'seller';
    const TYPE_DEBTOR   = 'buyer';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, nullable=false)
     */
    private string $companyName;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private ?string $address;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private ?string $phone;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Choice(
     *     choices={
     *      Company::TYPE_CREDITOR,
     *      Company::TYPE_DEBTOR
     *      }, message="VALIDATION_ERROR.INVALID_TYPE_OF_CUSTOMER", strict=true)
     */
    private string $customerType;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    protected int $debtLimit = 0;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    /**
     * @param mixed
     * @return Company
     */
    public function setCompanyName(string $companyName): Company
    {
        $this->companyName = $companyName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return Company
     */
    public function setEmail(?string $email): Company
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     * @return Company
     */
    public function setAddress(?string $address): Company
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return Company
     */
    public function setPhone(?string $phone): Company
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerType(): string
    {
        return $this->customerType;
    }

    /**
     * @param string $customerType
     * @return Company
     */
    public function setCustomerType(string $customerType): self
    {
        $this->customerType = $customerType;
        return $this;
    }

    /**
     * @return int
     */
    public function getDebtLimit(): int
    {
        return $this->debtLimit;
    }

    /**
     * @param int $debtLimit
     * @return Company
     */
    public function setDebtLimit(int $debtLimit): self
    {
        $this->debtLimit = $debtLimit;
        return $this;
    }
}