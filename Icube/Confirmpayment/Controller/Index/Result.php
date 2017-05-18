<?php 
namespace Icube\Confirmpayment\Controller\Index; 

class Result extends \Magento\Framework\App\Action\Action {
    
    public function execute()
    {
        $param = $this->getRequest()->getParams();
          $this->_view->loadLayout();
          $om = \Magento\Framework\App\ObjectManager::getInstance();
          /** @var \Magento\Framework\Registry $registry */
          $registry = $om->get('Magento\Framework\Registry');
          $registry->register('param', $param); 
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
}