<?php

namespace ReesMcIvor\QuickViewProduct\Block;

use Magento\Framework\View\Element\Template;

class Button extends Template
{
    public string $sku = "";
    public string $buttonText = "";

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
