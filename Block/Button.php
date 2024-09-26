<?php

namespace ReesMcIvor\QuickViewProduct\Block;

use Magento\Framework\View\Element\Template;

class Button extends Template
{
    public mixed $product;
    public string $sku = "";
    public string $buttonText = "";
    private $productRepository;

    public function __construct(
        Template\Context $context,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        array $data = [],
    )
    {
        $this->productRepository = $productRepository;
        parent::__construct($context, $data);
    }

    public function getProductUrl()
    {
        // Get product by SKU
        $this->product = $this->productRepository->get($this->sku);
        return $this->product->getProductUrl();
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
