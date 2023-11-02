<?php
namespace ReesMcIvor\QuickViewProduct\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class View extends Action
{
    protected $resultJsonFactory;
    protected $productRepository;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        ProductRepositoryInterface $productRepository
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->productRepository = $productRepository;
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $sku = $this->getRequest()->getParam('sku');

        if (!$sku) {
            return $result->setData(['success' => false, 'message' => 'SKU not provided.']);
        }

        try {
            $product = $this->productRepository->get($sku);
            return $result->setData([
                'success' => true,
                'name' => $product->getName(),
                'image' => $product->getThumbnail(), // Get the image URL accordingly
                'description' => strip_tags($product->getDescription()),
            ]);
        } catch (NoSuchEntityException $e) {
            return $result->setData(['success' => false, 'message' => 'Product not found.']);
        }
    }
}
