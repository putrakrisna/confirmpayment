<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 * By Icube
 */
namespace Icube\Confirmpayment\Block\Checkout\Onepage\Success;

class PaymentInfo extends \Magento\Framework\View\Element\Template
{
    protected $_ob;
    protected $_checkoutSession;
    protected $_orderFactory;
    protected $_objectManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        array $data = []
    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->_orderFactory = $orderFactory;
        parent::__construct($context, $data);
    }


    public function getOrder(){
        return $this->_checkoutSession->getLastRealOrder();
    }


    public function isBankTransfer(){
        // if($this->_getOrder()->)
        $order = $this->_checkoutSession->getLastRealOrder();
        $paymentCode = $order->getPayment()->getMethodInstance()->getCode();

        if($paymentCode == 'banktransfer'){
            return true;
        }else{
            return false;
        }
    }
}
