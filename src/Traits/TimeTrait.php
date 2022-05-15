<?php
namespace App\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;

trait TimeTrait {

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $updatedAt;

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * @throws Exception
     */
    public function setCreatedAt(DateTime $date)
    {
        $this->createdAt = $date->getTimestamp();
        return $this;
    }

    /**
     * @throws Exception
     */
    public function setUpdatedAt(DateTime $date)
    {
        $this->updatedAt = $date->getTimestamp();
        return $this;
    }

}
