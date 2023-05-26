<?php

namespace CertificationPractice\OrderExport\Action;

use Magento\Framework\Exception\NoSuchEntityException;

class PushDetailsToWebservice
{
    public function execute(int $orderId, array $orderDetails)
    {
        return true;
    }

}
