<?php

namespace CertificationPractice\OrderExport\Collector;

use CertificationPractice\OrderExport\Api\DataCollectorInterface;
use CertificationPractice\OrderExport\Model\HeaderData as HeaderDataModel;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Sales\Api\Data\OrderAddressInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderAddressRepositoryInterface;
use Magento\Store\Model\ScopeInterface;

class HeaderData implements DataCollectorInterface
{
    protected ScopeConfigInterface $scopeConfig;
    protected OrderAddressRepositoryInterface $orderAddress;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        OrderAddressRepositoryInterface $orderAddress,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->orderAddress = $orderAddress;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function collect(HeaderDataModel $headerData, OrderInterface $order): array
    {

       // "password" => $this->scopeConfig->getValue('sales/order_export/password'),

        $output = [
            "password" => $this->scopeConfig->getValue(
                'sales/order_export/password',
                ScopeInterface::SCOPE_STORES,
                $order->getStoreId()
            ),
            "id" => $order->getIncrementId(),
            "currency" => $order->getBaseCurrencyCode(),
            "customer_notes" => $order->getExtensionAttributes()->getBoldOrderComment(),
            "merchant_notes" => $headerData->getMerchantNotes(),
            "discount" => $order->getBaseDiscountAmount(),
            "total" => $order->getBaseGrandTotal()
        ];

        $address = $this->getShippingAddress($order);

        if($address) {
            $output['shipping'] =  [
                "name" => $address->getFirstname().' '.$address->getLastname(),
                "address" => $address->getStreet(),
                "city" => $address->getCity(),
                "state" => $address->getRegionCode(),
                "postcode" => $address->getPostcode(),
                "country" => $address->getCountryId(),
                "amount" => $order->getBaseShippingAmount(),
                "method" => $order->getShippingDescription(),
                "ship_on" => $headerData->getShipDate()->format('Y-m-d')
            ];
        }

        return $output;
    }

    public function getShippingAddress(OrderInterface $order) : ?OrderAddressInterface
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('parent_id', $order->getEntityId())
            ->addFilter('address_type', 'shipping')
            ->create();
        $addresses = $this->orderAddress->getList($searchCriteria)->getItems();

        return count($addresses) ? reset($addresses) : null;
    }
}
