<?php

namespace CertificationPractice\OrderExport\Action;

use CertificationPractice\OrderExport\Model\HeaderData;
use CertificationPractice\OrderExport\Model\OrderExportDetailsRepository;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Sales\Api\OrderRepositoryInterface;

class SaveExportDetailsToOrder
{
    /**
     * @var OrderRepositoryInterface
     */
    protected OrderRepositoryInterface $orderRepository;
    protected OrderExportDetailsRepository $detailsRepository;

    public function __construct(OrderRepositoryInterface $orderRepository, OrderExportDetailsRepository $detailsRepository)
    {

        $this->orderRepository = $orderRepository;
        $this->detailsRepository = $detailsRepository;
    }

    /**
     * @throws CouldNotSaveException
     */
    public function execute(int $orderId, array $results, HeaderData $headerData): void
    {
        $order = $this->orderRepository->get($orderId);
        $details = $order->getExtensionAttributes()->getOrderExportDetails();

        if (isset($results['success']) && $results['success'] === true) {
            $details->setExportedAt((new \DateTime())->setTimezone(new \DateTimeZone('UTC')));
        }
        $details->setOrderId($orderId);
        $details->setMerchantNotes($headerData->getMerchantNotes());
        $details->setShipOn($headerData->getShipDate());

        $this->detailsRepository->save($details);
    }
}
