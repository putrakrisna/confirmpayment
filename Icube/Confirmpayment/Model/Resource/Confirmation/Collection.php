<?php
namespace Icube\Confirmpayment\Model\Resource\Confirmation;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
	protected function _construct()
    {
        $this->_init(
            'Icube\Confirmpayment\Model\Confirmation',
            'Icube\Confirmpayment\Model\Resource\Confirmation'
        );
    }
}