<?php
 
namespace Icube\Confirmpayment\Model\Resource;
 
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Confirmation extends AbstractDb
{
	protected function _construct()
    {
        $this->_init('icube_confirmpayment', 'confirmpayment_id');
    }
}