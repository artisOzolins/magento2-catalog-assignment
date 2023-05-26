<?php

namespace CertificationPractice\OrderExport\Plugin;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderInterfaceFactory;

class EnsureOrderDetailsAlwaysExist
{
    public function aftherCreate(OrderInterfaceFactory $subject, OrderInterface $order)
    {
        $extensionsAttributes = $order->getExtensionAttributes() ?? $this->extensionFactory->create();
        $extensionsAttributes->setOrderExportDetails($this->orderExportDetailsFactory->create());

        $order->setExtensionAttributes($extensionsAttributes);
        return $order;
    }
}
