<?php
namespace Icube\Confirmpayment\Block;

class Result extends \Magento\Framework\View\Element\Template
{
	public function _prepareLayout()
	{
		return parent::_prepareLayout();
	}

	public function getResult()
	{
		$om = \Magento\Framework\App\ObjectManager::getInstance();
		/** @var \Magento\Framework\Registry $registry */
		$registry = $om->get('Magento\Framework\Registry');
		$param = $registry->registry('param');
		if($param) 
		{
			$result = $this->__searching($param['orderid']);
		} else {
			$result == null;
		}
		
		return $result;
	}

	private function __searching($param){
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); /*instantiate model magento 2*/
      $order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($param);
      return $order;
  	}
}
?>