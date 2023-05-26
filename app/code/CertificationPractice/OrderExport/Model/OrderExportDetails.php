<?php

namespace CertificationPractice\OrderExport\Model;


use CertificationPractice\OrderExport\Api\Data\OrderExportDetailsInterface;
use CertificationPractice\OrderExport\Model\ResourceModel\OrderExportDetails as OrderExportDetailsResource;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class OrderExportDetails extends AbstractModel implements OrderExportDetailsInterface, IdentityInterface
{
    public const CACHE_TAG = 'order_d';

    protected function __construct()
    {
        $this->_init(OrderExportDetailsResource::class);
    }
    public function getOrderId(): int
    {
        return (int)$this->getData('order_id');
    }

    public function setOrderId(int $orderId): void
    {
        $this->setData('order_id', $orderId);
    }

    public function getShipOn(): \DateTime
    {
        return new \DateTime($this->getData('ship_on'));
    }

    public function setShipOn(\DateTime $shipOn): void
    {
        $this->setData('ship_on', $shipOn);
    }

    public function getExportedAt(): \DateTime
    {
        return new \DateTime($this->getData('exported_at'));
    }

    public function setExportedAt(\DateTime $exportedAt): void
    {
        $this->setData('exported_at', $exportedAt);
    }

    public function hasBeenExported(): bool
    {
        return (bool)$this->getData('exported_at');
    }

    public function getMerchantNotes(): string
    {
        return (string)$this->getData('merchant_notes');
    }

    public function setMerchantNotes(string $merchantNotes): void
    {
        $this->setData('merchant_notes', $merchantNotes);
    }


    public function getIdentities()
    {
        return [self::CACHE_TAG.`_`.$this->getId()];
    }
}
