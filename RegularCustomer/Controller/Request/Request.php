<?php

declare(strict_types=1);

namespace Oleksandrk\RegularCustomer\Controller\Request;

use Oleksandrk\RegularCustomer\Model\DiscountRegularRequest;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;

class Request implements
    \Magento\Framework\App\Action\HttpPostActionInterface,
    \Magento\Framework\App\CsrfAwareActionInterface
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     */
    private \Magento\Framework\Controller\Result\JsonFactory $jsonFactory;

    /**
     * @var \Oleksandrk\RegularCustomer\Model\DiscountRegularRequestFactory $discountRequestFactory
     */
    private \Oleksandrk\RegularCustomer\Model\DiscountRegularRequestFactory $discountRequestFactory;

    /**
     * @var \Oleksandrk\RegularCustomer\Model\ResourceModel\DiscountRegularRequest $discountRequestResource
     */
    private \Oleksandrk\RegularCustomer\Model\ResourceModel\DiscountRegularRequest $discountRequestResource;

    /**
     * @var \Magento\Framework\App\RequestInterface $request
     */
    private \Magento\Framework\App\RequestInterface $request;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    private \Magento\Store\Model\StoreManagerInterface $storeManager;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     */
    private \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator;

    /**
     * @var \Psr\Log\LoggerInterface $logger
     */
    private \Psr\Log\LoggerInterface $logger;

    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    private $customerSession;

    /**
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     * @param \Oleksandrk\RegularCustomer\Model\DiscountRegularRequestFactory $discountRequestFactory
     * @param \Oleksandrk\RegularCustomer\Model\ResourceModel\DiscountRegularRequest $discountRequestResource
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Customer\Model\Session $session
     */
    public function __construct(
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Oleksandrk\RegularCustomer\Model\DiscountRegularRequestFactory $discountRequestFactory,
        \Oleksandrk\RegularCustomer\Model\ResourceModel\DiscountRegularRequest $discountRequestResource,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Customer\Model\Session $session
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->discountRequestFactory = $discountRequestFactory;
        $this->discountRequestResource = $discountRequestResource;
        $this->request = $request;
        $this->storeManager = $storeManager;
        $this->formKeyValidator = $formKeyValidator;
        $this->logger = $logger;
        $this->customerSession = $session;
    }

    /**
     * Controller action
     *
     * @return Json
     */
    public function execute(): Json
    {
        /** @var DiscountRegularRequest $discountRequest */
        $discountRequest = $this->discountRequestFactory->create();

        if ($this->customerSession->isLoggedIn()) {
            try {
                $discountRequest->setProductId(((int) $this->request->getParam('product_id')) ?: null)
                    ->setName($this->request->getParam('name'))
                    ->setEmail($this->request->getParam('email'))
                    ->setStoreId($this->storeManager->getStore()->getId())
                    ->setCustomerId($this->customerSession->getCustomerId());

                $this->discountRequestResource->save($discountRequest);
                if ($this->request->getParam('product_id') == null) {
                    $message = __('Your general discount request has been submitted!');
                } else {
                    $message = __('You request for discount product %1 accepted for review!', (string)
                        $this->request->getParam('productName'));
                }
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                $message = __('Your request can\'t be sent. Please, contact us if you see this message.');
            }
        } else {
            $message = __('Please login to your account to participate in this discount program!');
        }

        return $this->jsonFactory->create()
            ->setData([
                'message' => $message
            ]);
    }

    /**
     * Create exception in case CSRF validation failed. Return null if default exception will suffice.
     *
     * @param RequestInterface $request
     * @return InvalidRequestException|null
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    /**
     * Perform custom request validation. Return null if default validation is needed.
     *
     * @param RequestInterface $request
     * @return bool
     */
    public function validateForCsrf(RequestInterface $request): bool
    {
        return $this->formKeyValidator->validate($request);
    }
}
