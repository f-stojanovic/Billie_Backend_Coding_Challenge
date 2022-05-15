<?php

namespace App\Entity;

use App\Traits\TimeTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvoiceRepository")
 */
class Invoice
{
    use TimeTrait;

    const STATUS_OPEN     = 'open';
    const STATUS_CLOSED   = 'closed';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private string $invoiceNo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company")
     * @ORM\JoinColumn(name="creditor_company_id", referencedColumnName="id", nullable=false)
     */
    protected $creditorCompanyId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company")
     * @ORM\JoinColumn(name="debtor_company_id", referencedColumnName="id", nullable=false)
     */
    protected $debtorCompanyId;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private ?string $service;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected int $quantity = 0;

    /**
     * @ORM\Column(type="integer", precision=10, scale=2)
     */
    protected int $cost = 0;

    /**
    * @ORM\Column(type="string", options={"default": "EUR"})
     */
    private string $currency = 'EUR';

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Choice(
     *     choices={
     *      Invoice::STATUS_OPEN,
     *      Invoice::STATUS_CLOSED
     *      }, message="VALIDATION_ERROR.INVALID_TYPE_OF_STATUS", strict=true)
     */
    private string $statusType;

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
    public function getInvoiceNo(): string
    {
        return $this->invoiceNo;
    }

    /**
     * @param string $invoiceNo
     */
    public function setInvoiceNo(string $invoiceNo): void
    {
        $this->invoiceNo = $invoiceNo;
    }

    /**
     * @return mixed
     */
    public function getCreditorCompanyId()
    {
        return $this->creditorCompanyId;
    }

    /**
     * @param mixed $creditorCompanyId
     */
    public function setCreditorCompanyId($creditorCompanyId): void
    {
        $this->creditorCompanyId = $creditorCompanyId;
    }

    /**
     * @return mixed
     */
    public function getDebtorCompanyId()
    {
        return $this->debtorCompanyId;
    }

    /**
     * @param mixed $debtorCompanyId
     */
    public function setDebtorCompanyId($debtorCompanyId): void
    {
        $this->debtorCompanyId = $debtorCompanyId;
    }

    /**
     * @return string|null
     */
    public function getService(): ?string
    {
        return $this->service;
    }

    /**
     * @param string|null $service
     */
    public function setService(?string $service): void
    {
        $this->service = $service;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }

    /**
     * @param int $cost
     */
    public function setCost(int $cost): void
    {
        $this->cost = $cost;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getStatusType(): string
    {
        return $this->statusType;
    }

    /**
     * @param string $statusType
     */
    public function setStatusType(string $statusType): void
    {
        $this->statusType = $statusType;
    }
}