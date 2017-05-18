<?php 
namespace Icube\Confirmpayment\Controller\Ajax;
 
class Validation extends \Magento\Framework\App\Action\Action {

    protected $resultJsonFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context, 
        \Magento\Framework\Controller\Result\JsonFactory    $resultJsonFactory
    )     
    {
        $this->resultJsonFactory            = $resultJsonFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        if ($this->getRequest()->isAjax())
        {
            $postData = $this->getRequest()->getPostValue();
            
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($postData['order_id']);

            $data = null;

            if($order->getId()){
                $paymentCode = $order->getPayment()->getMethodInstance()->getCode();
                
                if($paymentCode=='banktransfer'){
                    $data = [
                        'error'=>0,
                        'message'=>'Your order id is valid'];        
                }
                else {
                    $data = [
                        'error'=>1,
                        'message'=>'Your order is invalid'];
                }
            }
            return $result->setData($data);
        }
    }   
}