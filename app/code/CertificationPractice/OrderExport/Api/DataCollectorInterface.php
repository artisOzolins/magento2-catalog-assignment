<?php

namespace CertificationPractice\OrderExport\Api;

use CertificationPractice\OrderExport\Model\HeaderData;
use Magento\Sales\Api\Data\OrderInterface;

interface DataCollectorInterface
{
    public function collect(HeaderData $headerData, OrderInterface $order): array;
}
