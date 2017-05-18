<?php
/**
 * Copyright © 2015 Icube.us. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Icube\Confirmpayment\Setup;

use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
{

    protected $blockFactory;

    public function __construct(
        BlockFactory $modelBlockFactory)
    {
        $this->blockFactory = $modelBlockFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        if (version_compare($context->getVersion(), '1.0.3', '<')) {

            /**
             * add cms static block
             */
            //------------------------------------------------------
            $cmsBlock = $this->createBlock()->load('email_intro_banktransfer', 'identifier');

            if (!$cmsBlock->getId()) {
                $cmsBlockContent = <<<EOD
<p>{{trans "Thank you for your order from "}}{{trans "Please do your payment to our Bank Account below and do confirmation payment "}}<a href='{{store url="confirmpayment/index"}}'>here</a>.{{trans "Once we receive your payment, your order will be processed."}}</p>
<p>Bank Mandiri<br>
    A/C No : 101-0006729257<br>
    An. PT Monica Hijau Lestari<br>
</p>
EOD;
                $cmsBlock = [
                    'title' => 'New order email intro for bank transfer payment',
                    'identifier' => 'email_intro_banktransfer',
                    'content' => $cmsBlockContent,
                    'is_active' => 1,
                    'stores' => 0,
                ];
                $this->createBlock()->setData($cmsBlock)->save();
            }

            //----------------------------------------------
            $cmsBlock = $this->createBlock()->load('email_intro_other', 'identifier');

            if (!$cmsBlock->getId()) {
                $cmsBlockContent = <<<EOD
<p>
    {{trans "Thank you for your order from "}}{{config path="general/store_information/name"|raw}}.
    {{trans "Once your package ships we will send you a tracking number."}}
    {{trans 'You can check the status of your order by '}}<a href="{{store url='customer/account/'}}">logging into your account</a>.
</p>
EOD;
                $cmsBlock = [
                    'title' => 'New order email intro for other payment',
                    'identifier' => 'email_intro_other',
                    'content' => $cmsBlockContent,
                    'is_active' => 1,
                    'stores' => 0,
                ];
                $this->createBlock()->setData($cmsBlock)->save();
            }
            //----------------------------------------------
            // bank account
            $cmsBlock = $this->createBlock()->load('bank_account_info', 'identifier');
            if (!$cmsBlock->getId()) {            
                $cmsBlockContent = <<<EOD
<p><strong>Bank Mandiri</strong><br>
    A/C No : 101-0006729257<br>
    An. PT Monica Hijau Lestari<br>
</p>
EOD;
                $cmsBlock = [
                    'title' => 'Bank Account Info',
                    'identifier' => 'bank_account_info',
                    'content' => $cmsBlockContent,
                    'is_active' => 1,
                    'stores' => 0,
                ];
                $this->createBlock()->setData($cmsBlock)->save();
            }
        }if (version_compare($context->getVersion(), '1.0.4', '<')) {

            /**
             * add cms static block
             */
            //------------------------------------------------------
            $cmsBlockContent = <<<EOD
<p>{{trans "Thank you for your order from "}}{{trans "Please do your payment to our Bank Account below and do confirmation payment "}}<a href='{{store url="confirmpayment"}}'>here</a>.{{trans "Once we receive your payment, your order will be processed."}}</p>
<p>Bank Mandiri<br>
    A/C No : 101-0006729527<br>
    An. PT Monica HijauLestari<br>
</p>
EOD;
            $cmsBlock = $this->createBlock()->load('email_intro_banktransfer', 'identifier');

            if (!$cmsBlock->getId()) {
                $cmsBlock = [
                    'title' => 'New order email intro for bank transfer payment',
                    'identifier' => 'email_intro_banktransfer',
                    'content' => $cmsBlockContent,
                    'is_active' => 1,
                    'stores' => 0,
                ];
                $this->createBlock()->setData($cmsBlock)->save();
            } else {
                $cmsBlock->setContent($cmsBlockContent)->save();
            }

            //----------------------------------------------
            // bank account     
            $cmsBlockContent = <<<EOD
<p><strong>Bank Mandiri</strong><br>
    A/C No : 101-0006729527<br>
    An. PT Monica HijauLestari<br>
</p>
EOD;
            $cmsBlock = $this->createBlock()->load('bank_account_info', 'identifier');
            if (!$cmsBlock->getId()) {       
                $cmsBlock = [
                    'title' => 'Bank Account Info',
                    'identifier' => 'bank_account_info',
                    'content' => $cmsBlockContent,
                    'is_active' => 1,
                    'stores' => 0,
                ];
                $this->createBlock()->setData($cmsBlock)->save();
            } else {
                $cmsBlock->setContent($cmsBlockContent)->save();
            }
        }
    }

    public function createBlock()
    {
        return $this->blockFactory->create();
    }
}
