<?php

namespace Hibrido\Task1\Block\Frontend;

use Magento\Cms\Model\Page;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;

class Header extends Template
{
    /**
     * @var Page
     */
    private $page;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        Template\Context $context,
        Page $page,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->page = $page;
        $this->scopeConfig = $scopeConfig;
    }

    public function getPage(): Page
    {
        return $this->page;
    }

    public function canShow(): bool
    {
        return count($this->getLocales()) > 1;
    }

    private function getLocales(): array
    {
        $locales = [];
        foreach ($this->getPageStores() as $store) {
            $locales[] = $this->getIsoStoreLocale($store);
        }

        return array_unique($locales);
    }

    public function getPageStores(): array
    {
        // all store views
        if (in_array(0, $this->page->getStores())) {
            return $this->_storeManager->getStores();
        }

        $stores = [];
        foreach ($this->page->getStores() as $storeId) {
            try {
                $stores[] = $this->_storeManager->getStore($storeId);
            } catch (NoSuchEntityException $e) {
            }
        }

        return $stores;
    }

    public function getIsoStoreLocale(StoreInterface $store)
    {
        $locale = $this->scopeConfig->getValue('general/locale/code','store', $store);
        $locale = str_replace('_', '-', $locale);
        $locale = mb_strtolower($locale);

        return $locale;
    }

    public function getPageUrl(StoreInterface $store): string
    {
        try {
            return $store->getUrl($this->page->getIdentifier());
        } catch (NoSuchEntityException $e) {
            return '#';
        }
    }
}
