<?php

namespace CertificationPractice\OrderExport\ViewModel;

use http\Client\Request;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class OrderDetails implements ArgumentInterface
{
    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @var FormKey
     */
    protected FormKey $formKey;

    /**
     * @var UrlInterface
     */
    protected UrlInterface $url;

    /**
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param FormKey $formKey
     * @param UrlInterface $url
     * @param RequestInterface $request
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        FormKey $formKey,
        UrlInterface $url,
        RequestInterface $request
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->formKey = $formKey;
        $this->url = $url;
        $this->request = $request;
    }

    /**
     * @return bool
     */
    public function isAllowed(): bool
    {
        return $this->scopeConfig->isSetFlag('sales/order_export/enabled');
    }

    /**
     * @return string
     */
    public function getButtonMessage(): string
    {
        return (string)__('Send Order to Fulfillment');
    }

    /**
     * @throws LocalizedException
     */
    public function getConfig(): array
    {
        return [
            'sending_message' => __('Sending..'),
            'original_message' => $this->getButtonMessage(),
            'form_key' => $this->formKey->getFormKey(),
            'upload_url' => $this->url->getUrl(
                'order_export/export/run',
                [
                    'order_id' => (int)$this->request->getParam('order_id')
                ]
            )
        ];
    }
}
