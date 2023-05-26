<?php

namespace CertificationPractice\OrderExport\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface OrderExportDetailsSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \CertificationPractice\OrderExport\Api\Data\OrderExportDetailsInterface[]
     */
    public function getItems();

    /**
     * @return \CertificationPractice\OrderExport\Api\Data\OrderExportDetailsInterface[]
     */
    public function setItems(array $items);
}
