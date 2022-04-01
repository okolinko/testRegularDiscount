<?php

declare(strict_types=1);

namespace Oleksandrk\RegularCustomer\ViewModel\Customer;

use Oleksandrk\RegularCustomer\Model\ResourceModel\DiscountRegularRequest\CollectionFactory as DiscountRegularRequestCollectionFactory;
use Oleksandrk\RegularCustomer\Model\ResourceModel\DiscountRegularRequest\Collection as DiscountRegularRequestCollection;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Catalog\Model\Product;
use Magento\Store\Model\Website;

class RequestList implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var DiscountRegularRequestCollectionFactory $discountRegularRequestCollectionFactory
     */
    private DiscountRegularRequestCollectionFactory $discountRegularRequestCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    private \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    private \Magento\Store\Model\StoreManagerInterface $storeManager;

    /**
     * @var DiscountRegularRequestCollection $loadedDiscountRegularRequestCollection
     */
    private DiscountRegularRequestCollection $loadedDiscountRegularRequestCollection;

    /**
     * @var ProductCollection $loadedProductCollection
     */
    private ProductCollection $loadedProductCollection;

    /**
     * @param DiscountRegularRequestCollectionFactory $discountRegularRequestCollectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        DiscountRegularRequestCollectionFactory $discountRegularRequestCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->discountRegularRequestCollectionFactory = $discountRegularRequestCollectionFactory;
        $this->storeManager = $storeManager;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * Get a list of customer discount requests
     *
     * @return DiscountRegularRequestCollection
     */
    public function getDiscountRegularRequestCollection(): DiscountRegularRequestCollection
    {
        if (isset($this->loadedDiscountRegularRequestCollection)) {
            return $this->loadedDiscountRegularRequestCollection;
        }

        /** @var Website $website */
        $website = $this->storeManager->getWebsite();

        /** @var DiscountRegularRequestCollection $collection */
        $collection = $this->discountRegularRequestCollectionFactory->create();
        // @TODO: get current customer's ID
        // $collection->addFieldToFilter('customer_id', 2);
        // @TODO: check if accounts are shared per website or not
        $collection->addFieldToFilter('store_id', ['in' => $website->getStoreIds()]);
        $this->loadedDiscountRegularRequestCollection = $collection;

        return $this->loadedDiscountRegularRequestCollection;
    }

    /**
     * Get product for customer discount request
     *
     * @param int $productId
     * @return Product|null
     */
    public function getProduct(int $productId): ?Product
    {
        if (isset($this->loadedProductCollection)) {
            return $this->loadedProductCollection->getItemById($productId);
        }

        $DiscountRegularRequestCollection = $this->getDiscountRegularRequestCollection();
        $productIds = array_filter($DiscountRegularRequestCollection->getColumnValues('product_id'));

        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToFilter('entity_id', $productIds)
            ->addAttributeToSelect('name')
            ->addWebsiteFilter();
        $this->loadedProductCollection = $productCollection;

        return $this->loadedProductCollection->getItemById($productId);
    }
}
