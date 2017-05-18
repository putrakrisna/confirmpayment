<?php
 
namespace Icube\Confirmpayment\Model;

use Magento\Framework\Model\AbstractModel;
	 
class Confirmation extends AbstractModel
{
	protected function _construct()
	{
	    $this->_init('Icube\Confirmpayment\Model\Resource\Confirmation');
    }
}