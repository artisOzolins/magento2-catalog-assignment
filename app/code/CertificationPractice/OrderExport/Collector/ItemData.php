<?php

namespace CertificationPractice\OrderExport\Collector;


use CertificationPractice\OrderExport\Api\DataCollectorInterface;
use CertificationPractice\OrderExport\Model\HeaderData;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;

class ItemData implements DataCollectorInterface
{

    /**
     * @var string[]
     */
    protected array $allowedProductTypes;

    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $collectionFactory;

    public function __construct(
        array $allowedProductTypes,
        CollectionFactory $collectionFactory
    ) {
        $this->allowedProductTypes = $allowedProductTypes;
        $this->collectionFactory = $collectionFactory;
    }

    public function collect(HeaderData $headerData, OrderInterface $order): array
    {
        $items = $order->getItems();

        $items = array_filter($items, function(OrderItemInterface $orderItem) {
            return in_array(
                $this->getProductTypeFor($orderItem->getProductId()),
                $this->allowedProductTypes, true);
        });

        return array_map(function(OrderItemInterface $item){
            return [
                "sku" =>$item->getSku(),
                "qty" => $item->getQtyOrdered(),
                "item_price" => $item->getBasePrice(),
                "item_cost" => $item->getBaseCost(),
                "total" => $item->getRowTotal()
            ];
        }, $items);
    }

    private function getProductTypeFor(int $productId) {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('entity_id', ['eq' => $productId]);

        /**
         * @var Product $product
         */
        $product = $collection->getFirstItem();

        if (!$product->getId()) {
            throw new NoSuchEntityException(__('This product dose not exists'));
        }

        return (string)$product->getTypeId();
    }
}
