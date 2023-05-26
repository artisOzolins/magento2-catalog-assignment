<?php

namespace CertificationPractice\OrderExport\Action;

use CertificationPractice\OrderExport\Api\DataCollectorInterface;
use CertificationPractice\OrderExport\Model\HeaderData;
use Magento\Sales\Api\OrderRepositoryInterface;

class TransformOrderToArray
{


    /**
     * @var DataCollectorInterface[]
     */
    private $dataCollectors;

    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository, array $dataCollectors)
    {
        $this->orderRepository = $orderRepository;
        $this->dataCollectors = $dataCollectors;
    }

    /**
     * @throws \Exception
     */
    public function execute(int $orderId, HeaderData $headerData)
    {
        $order = $this->orderRepository->get($orderId);
        $output = [];

        foreach ($this->dataCollectors as $collector) {
            if (!($collector instanceof DataCollectorInterface)) {
                continue;
            }

            $output = array_merge($output, $collector->collect($headerData, $order));
        }

        return $output;
    }

}
