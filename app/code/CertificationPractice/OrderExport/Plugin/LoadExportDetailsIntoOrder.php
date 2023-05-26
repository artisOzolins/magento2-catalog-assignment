<?php

namespace CertificationPractice\OrderExport\Plugin;

use CertificationPractice\OrderExport\Model\OrderExportDetailsFactory;
use CertificationPractice\OrderExport\Model\OrderExportDetailsRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class LoadExportDetailsIntoOrder
{
    protected OrderExtensionFactory $extensionFactory;
    protected OrderExportDetailsRepository $exportDetailsRepositroy;

    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    protected OrderExportDetailsFactory $orderExportDetailsFactory;

    public function __construct(
        OrderExtensionFactory $extensionFactory,
        OrderExportDetailsRepository $exportDetailsRepositroy,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderExportDetailsFactory $orderExportDetailsFactory
    ) {
        $this->extensionFactory = $extensionFactory;
        $this->exportDetailsRepositroy = $exportDetailsRepositroy;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderExportDetailsFactory = $orderExportDetailsFactory;
    }

    public function aftherGet(OrderRepositoryInterface $subject, OrderInterface $order): OrderInterface
    {
        return $this->injectDetails($order);
    }
    public function aftherGetList(OrderRepositoryInterface $subject, OrderSearchResultInterface $result): OrderSearchResultInterface
    {
        foreach ($result->getItems() as $order) {
            $this->injectDetails($order);
        }
        return $result;
    }

    /**
     * @param OrderInterface $order
     *
     * @return OrderInterface
     */
    public function injectDetails(OrderInterface $order): OrderInterface
    {
        $extensionsAttributes = $order->getExtensionAttributes() ?? $this->extensionFactory->create();

        $details = $this->exportDetailsRepositroy->getList(
            $this->searchCriteriaBuilder->addFilter
            ('order_id', $order->getEntityId()
            )->create()
        )->getItems();

        if (count($details)) {
            $extensionsAttributes->setOrderExportDetails(reset($details));
        } else {
            $extensionsAttributes->setOrderExportDetails($this->orderExportDetailsFactory->create());
        }
        $order->setExtensionAttributes($extensionsAttributes);
        return $order;
    }


}
