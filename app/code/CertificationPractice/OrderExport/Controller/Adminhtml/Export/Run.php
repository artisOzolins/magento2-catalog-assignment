<?php

namespace CertificationPractice\OrderExport\Controller\Adminhtml\Export;

use CertificationPractice\OrderExport\Orchestrator;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use CertificationPractice\OrderExport\Model\HeaderDataFactory;

class Run extends Action implements HttpPostActionInterface
{

    protected JsonFactory $jsonFactory;
    protected HeaderDataFactory $headerDataFactory;
    protected Orchestrator $orchestrator;

    public function __construct(
        Orchestrator $orchestrator,
        JsonFactory $jsonFactory,
        HeaderDataFactory $headerDataFactory,
        Context $context
    )
    {
        $this->orchestrator = $orchestrator;
        $this->jsonFactory = $jsonFactory;
        $this->headerDataFactory = $headerDataFactory;
        parent::__construct($context);
    }

    /**
     * @throws \Exception
     */
    public function execute()
    {
        $headerData = $this->headerDataFactory->create();
        $headerData->setMerchantNotes(htmlspecialchars($this->getRequest()->getParam('merchant_notes')));
        $headerData->setShipDate(new \DateTime($this->getRequest()->getParam('ship_date')));

        $this->orchestrator->run(
            $this->getRequest()->getParam('order_id'),
            $headerData
        );

        return $this->jsonFactory->create([]);
    }
}
