<?php

namespace CertificationPractice\OrderExport;

use CertificationPractice\OrderExport\Action\SaveExportDetailsToOrder;
use CertificationPractice\OrderExport\Action\TransformOrderToArray;
use CertificationPractice\OrderExport\Model\HeaderData;
use CertificationPractice\OrderExport\Model\RequestValidator;
use Exception;
use exmple\PushDetailsToWebservice;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class Orchestrator
{
    protected TransformOrderToArray $orderToArray;

    protected PushDetailsToWebservice $pushDetailsToWebservice;

    protected SaveExportDetailsToOrder $saveExportDetailsToOrder;
    protected RequestValidator $requestValidator;

    public function __construct(
        TransformOrderToArray $orderToArray,
        PushDetailsToWebservice $pushDetailsToWebservice,
        RequestValidator $requestValidator,
        SaveExportDetailsToOrder $saveExportDetailsToOrder
    ) {
        $this->orderToArray = $orderToArray;
        $this->pushDetailsToWebservice = $pushDetailsToWebservice;
        $this->saveExportDetailsToOrder = $saveExportDetailsToOrder;
        $this->requestValidator = $requestValidator;
    }

    /**
     * @throws Exception
     */
    public function run(int $orderId, HeaderData $headerData): array
    {
        $results = ['success' => false, 'error' => null];

        if (!$this->requestValidator->validate($orderId, $headerData)) {
            $results['error'] = (string)__('Invalid order specified.');
            return $results;
        }

        $orderDetails = $this->orderToArray->execute($orderId, $headerData);

        try {
            $results['success'] = $this->pushDetailsToWebservice->execute($orderId, $orderDetails);
        } catch (\Exception $ex) {
            $results['error'] = $ex->getMessage();
        }

        $this->saveExportDetailsToOrder->execute($orderId, $headerData, $results);

        return $results;
    }
}
