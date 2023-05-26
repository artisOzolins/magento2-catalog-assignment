<?php


namespace CertificationPractice\OrderExport\Model;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\OrderRepository;

class RequestValidator
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function validate(int $orderId)
    {
        try {
            $this->orderRepository->get($orderId);
        } catch (NoSuchEntityException $ex) {
            return false; // order not found
        } catch (InputException $ex) {
            return false; // no ID specified
        }
        return true;
    }
}
