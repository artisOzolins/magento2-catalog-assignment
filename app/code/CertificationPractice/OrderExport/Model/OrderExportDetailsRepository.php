<?php

namespace CertificationPractice\OrderExport\Model;

use CertificationPractice\OrderExport\Api\Data\OrderExportDetailsInterface;
use CertificationPractice\OrderExport\Api\Data\OrderExportDetailsSearchResultsInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use CertificationPractice\OrderExport\Api\Data\OrderExportDetailsSearchResultsInterfaceFactory;
use CertificationPractice\OrderExport\Model\ResourceModel\OrderExportDetails as OrderExportDetailsResource;
use CertificationPractice\OrderExport\Model\ResourceModel\OrderExportDetails\CollectionFactory as DetailsCollectionFactory;

class OrderExportDetailsRepository
{

    /**
     * @var OrderExportDetailsResource
     */
    protected $resource;

    /**
     * @var OrderExportDetailsFactory
     */
    protected $detailsFactory;

    /**
     * @var DetailsCollectionFactory
     */
    protected $detailsCollectionFactory;

    /**
     * @var OrderExportDetailsSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    public function __construct(
        OrderExportDetailsResource $resource,
        OrderExportDetailsFactory $detailsFactory,
        DetailsCollectionFactory $detailsCollectionFactory,
        OrderExportDetailsSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
    ) {
        $this->resource = $resource;
        $this->detailsFactory = $detailsFactory;
        $this->detailsCollectionFactory = $detailsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    public function save(OrderExportDetailsInterface $order)
    {
        try {
            $this->resource->save($order);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $order;
    }

    public function getById($detailsId)
    {
        $details = $this->detailsFactory->create();
        $this->resource->load($details, $detailsId);
        if (!$details->getId()) {
            throw new NoSuchEntityException(__('The details with the "%1" ID doesn\'t exist.', $detailsId));
        }
        return $details;
    }

    public function getList(SearchCriteriaInterface $criteria)
    {
        /** @var OrderExportDetailsResource\Collection $collection */
        $collection = $this->detailsCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var OrderExportDetailsSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    public function delete(OrderExportDetailsInterface $details)
    {
        try {
            $this->resource->delete($details);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    public function deleteById($detailsId)
    {
        return $this->delete($this->getById($detailsId));
    }

}
