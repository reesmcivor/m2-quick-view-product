<?php
namespace ReesMcIvor\QuickViewProduct\Controller\Index;

use Magento\Catalog\Helper\Image;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class View extends Action
{
    protected $resultJsonFactory;
    protected $productRepository;
    protected $imageHelper;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        ProductRepositoryInterface $productRepository,
        Image $imageHelper
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->productRepository = $productRepository;
        $this->imageHelper = $imageHelper;
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
            $image = $this->imageHelper->init($product, 'product_page_image_small')
                ->setImageFile($product->getSmallImage()) // image,small_image,thumbnail
                ->resize(380)
                ->getUrl();

            return $result->setData([
                'success' => true,
                'product' => [
                    'name' => $product->getName(),
                    'image' => $image,
                    'short_description' => $product->getShortDescription(),
                    'description' => $product->getDescription(),
                    'specifications' => $this->getProductSpecs($product)
                ]
            ]);
        } catch (NoSuchEntityException $e) {
            return $result->setData(['success' => false, 'message' => 'Product not found.']);
        }
    }

    protected function getProductSpecs( $product ) : array
    {
        $attributesData = [];
        foreach ($product->getAttributes() as $attribute) {
            if ($attribute->getIsVisibleOnFront() && $attribute->getFrontend()->getValue($product)) {
                $attributeCode = $attribute->getAttributeCode();
                $attributesData[$attributeCode] = [
                    'label' => $attribute->getStoreLabel(),
                    'value' => $attribute->getFrontend()->getValue($product)
                ];
            }
        }
        return $attributesData;
    }
}
