<?php

declare(strict_types=1);

namespace Oleksandrk\RegularCustomer\Model\ResourceModel;

class DiscountRegularRequest extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init('oleksandrk_customer_regular_discount', 'request_id');
    }
}
