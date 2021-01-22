<?php

namespace Hibrido\Task2\Block\Frontend;

use Magento\Framework\View\Element\Template;

class ButtonColor extends Template
{
    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    public function getButtonColor()
    {
        return $this->_scopeConfig->getValue('hibrido_theme/buttons/color', 'stores', $this->_storeManager->getStore());
    }
}
