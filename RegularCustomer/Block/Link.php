<?php

namespace Oleksandrk\RegularCustomer\Block;

use Magento\Customer\Block\Account\SortLinkInterface;

class Link extends \Magento\Framework\View\Element\Html\Link implements SortLinkInterface
{
    /**
     * Template name
     *
     * @var string
     */
    protected $_template = 'Oleksandrk_RegularCustomer::link.phtml';

    /**
     * Url link
     *
     * @return string
     */
    public function getHref()
    {
        return $this->getUrl('regular_customer/request/view');
    }

    /**
     * Label Name
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('My Requests');
    }

    /**
     * Sort link
     *
     * @return object
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }
}
