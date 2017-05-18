<?php
/**
 */
namespace Icube\Confirmpayment\Block\Adminhtml\Order\View\Tab;
use Magento\Customer\Controller\RegistryConstants;
// use Magento\Ui\Component\Layout\Tabs\TabInterface;
/**
 * Customer account form block
 */
class Confirmation extends \Magento\Sales\Block\Adminhtml\Order\AbstractOrder implements
    \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    /**
     * Retrieve order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->_coreRegistry->registry('current_order');
    }

    public function getConfirmation($orderIncrement)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $confirmationModel = $objectManager->create('Icube\Confirmpayment\Model\Confirmation')->load($orderIncrement,'order_id');
        return $confirmationModel;
    }

    /**
     * Retrieve source model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getSource()
    {
        return $this->getOrder();
    }
    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Payment Confirmation');
    }
    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Payment Confirmation');
    }
    /**
     * @return bool
     */
    public function canShowTab()
    {
        if ($this->getOrder()->getPayment()->getMethodInstance()->getCode() == \Magento\OfflinePayments\Model\Banktransfer::PAYMENT_METHOD_BANKTRANSFER_CODE) {
            return true;
        }
        return false;
    }
 
    /**
     * @return bool
     */
    public function isHidden()
    {
        if ($this->getOrder()->getPayment()->getMethodInstance()->getCode() == \Magento\OfflinePayments\Model\Banktransfer::PAYMENT_METHOD_BANKTRANSFER_CODE) {
            return false;
        }
        return true;
    }
    /**
     * Tab should be loaded trough Ajax call
     *
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return true;
    }
}