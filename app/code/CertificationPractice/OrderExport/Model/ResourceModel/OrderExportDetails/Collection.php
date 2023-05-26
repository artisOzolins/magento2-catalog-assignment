<?php

namespace CertificationPractice\OrderExport\Model\ResourceModel\OrderExportDetails;

use CertificationPractice\OrderExport\Model\OrderExportDetails as OrderExportDetailsModel;
use CertificationPractice\OrderExport\Model\ResourceModel\OrderExportDetails as OrderExportDetailsResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    public function __construct()
    {
        $this->_init(OrderExportDetailsModel::class,OrderExportDetailsResourceModel::class);
    }

}
