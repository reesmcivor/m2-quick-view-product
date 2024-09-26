<?php

namespace ReesMcIvor\QuickViewProduct\Block;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\View\Element\Template;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;

class Button extends Template
{
    public mixed $product;
    public string $sku = "";
    public string $buttonText = "";
    private $productRepository;
    private $configurableProductType;

    public function __construct(
        Template\Context $context,
        ProductRepositoryInterface $productRepository,
        Configurable $configurableProductType,
        array $data = [],
    )
    {
        $this->productRepository = $productRepository;
        $this->configurableProductType = $configurableProductType;
        parent::__construct($context, $data);
    }

    public function getProductUrl()
    {
        // Load the product by SKU
        $product = $this->productRepository->get($this->sku);
        $parentIds = $this->configurableProductType->getParentIdsByChild($product->getId());

        if (!empty($parentIds)) {
            $parentProduct = $this->productRepository->getById($parentIds[0]);
            return $parentProduct->getProductUrl();
        }

        return $product->getProductUrl();

    }

    public function setSku( $sku = '' )
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setButtonText( $buttonText )
    {
        $this->buttonText = $buttonText;
    }

    public function getButtonText()
    {
        return $this->buttonText;
    }
}
