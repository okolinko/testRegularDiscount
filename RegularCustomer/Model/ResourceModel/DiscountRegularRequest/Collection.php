<?php

declare(strict_types=1);

namespace Oleksandrk\RegularCustomer\Model\ResourceModel\DiscountRegularRequest;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        parent::_construct();
        $this->_init(
            \Oleksandrk\RegularCustomer\Model\DiscountRegularRequest::class,
            \Oleksandrk\RegularCustomer\Model\ResourceModel\DiscountRegularRequest::class
        );
    }
}
