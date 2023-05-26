<?php

namespace CertificationPractice\OrderExport\Model;


class HeaderData
{
    /**
     * @var \DateTime
     */
    private \DateTime $shipDate;

    /**
     * @var string
     */
    private string $merchantNotes;

    /**
     * @return string
     */
    public function getMerchantNotes(): string
    {
        return $this->merchantNotes;
    }

    /**
     * @param string $merchantNotes
     */
    public function setMerchantNotes(string $merchantNotes): void
    {
        $this->merchantNotes = $merchantNotes;
    }

    /**
     * @return \DateTime
     */
    public function getShipDate(): \DateTime
    {
        return $this->shipDate;
    }

    /**
     * @param \DateTime $shipDate
     */
    public function setShipDate(\DateTime $shipDate): void
    {
        $this->shipDate = $shipDate;
    }


}
