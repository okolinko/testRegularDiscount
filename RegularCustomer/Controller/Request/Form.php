<?php

declare(strict_types=1);

namespace Oleksandrk\RegularCustomer\Controller\Request;

class Form implements
    \Magento\Framework\App\Action\HttpGetActionInterface
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;

    /**
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     *  Create page my controllers link
     *
     * @return object
     */
    public function execute(): object
    {
        return $this->resultPageFactory->create();
    }
}
