<?php

namespace CertificationPractice\OrderExport\Api\Data;

interface OrderExportDetailsInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return int
     */
    public function getOrderId(): int;

    /**
     * @param int $orderId
     *
     * @return void
     */
    public function setOrderId(int $orderId): void;

    /**
     * @return \DateTime
     */
    public function getShipOn(): \DateTime;

    /**
     * @param \DateTime $shipOn
     *
     * @return void
     */
    public function setShipOn(\DateTime $shipOn): void;

    /**
     * @return \DateTime
     */
    public function getExportedAt(): \DateTime;

    /**
     * @param \DateTime $exportedAt
     *
     * @return void
     */
    public function setExportedAt(\DateTime $exportedAt): void;

    /**
     * @return string
     */
    public function getMerchantNotes(): string;

    /**
     * @param string $merchantNotes
     *
     * @return void
     */
    public function setMerchantNotes(string $merchantNotes): void;

    /**
     * @return bool
     */
    public function hasBeenExported(): bool;
}
