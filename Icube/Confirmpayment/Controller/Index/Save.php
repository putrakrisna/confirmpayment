<?php 
namespace Icube\Confirmpayment\Controller\Index;
use Magento\Framework\App\Filesystem\DirectoryList;
 
class Save extends \Magento\Framework\App\Action\Action {
    /** @var  \Icube\ConfirmPayment\Model\ConfirmationFactory */
    protected $resultRedirectFactory;
    protected $fileUploaderFactory;
    protected $filesystem;
    protected $scopeConfig;
    protected $_transportBuilder;
    // protected $messageManager;
    const XML_PATH_EMAIL_RECIPIENT = 'contact/email/recipient_email';

    /**      
    * @param \Magento\Framework\App\Action\Context $context      
    * @param ConfirmationFactory $modelConfirmationFactory
    */
    public function __construct(
        \Magento\Framework\App\Action\Context $context, 
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory, 
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        // \Magento\Framework\Message\ManagerInterface $messageManager, 
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )     
    {
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->filesystem = $fileSystem;
        $this->_transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        // $this->messageManager = $messageManager;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $postData = $this->getRequest()->getPostValue();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_storeManager = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');
        $order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($postData['order_id']);
        if ($order->getId()) {
            try {

                $confirmationModel = $objectManager->create('Icube\Confirmpayment\Model\Confirmation');

                $confirmationModel->setOrderId($postData['order_id']);
                $confirmationModel->setPaymentFrom($postData['payment_from']);
                // $confirmationModel->setPaymentTo($postData['payment_to']);
                $confirmationModel->setAccountNumber($postData['account_number']);
                $confirmationModel->setHolderName($postData['holder_name']);
                $confirmationModel->setAmount($postData['amount']);
                $confirmationModel->setTransferDate($postData['transfer_date']);

                if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') 
                {
                    $uploader = $this->fileUploaderFactory->create(['fileId' => 'image']);
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(false);

                    $path = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)
                        ->getAbsolutePath('images/paymentconfirm/'.$postData['order_id'].'/');                  
                    $uploader->save($path);

                    $_storeManager = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');
                    $confirmationModel->setImage($_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'images/paymentconfirm/'.$postData['order_id'].'/'.preg_replace('/[^a-z0-9_\\-\\.]+/i', '_', $_FILES['image']['name']));
                }

                $confirmationModel->save();

                //change order status 
                $order->setState('new')->setStatus('payment_confirmed');
                $order->save();
                
                if($this->scopeConfig->getValue('sales_email/confirm_payment/enabled')){
                //     $postObject = new \Magento\Framework\DataObject();
                //     $postObject->setData($postData);
                    // $sendTo = $this->scopeConfig->getValue('sales_email/confirm_payment/send_to');
                    // $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE; 
                    // $transport = $this->_transportBuilder
                    //     ->setTemplateIdentifier($this->scopeConfig->getValue('sales_email/confirm_payment/template'))
                    //     ->setTemplateOptions(
                    //         [
                    //             'area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
                    //             'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    //         ]
                    //     )
                    //     ->setTemplateVars(['data' => []])
                    //     ->setFrom($this->scopeConfig->getValue('sales_email/confirm_payment/identity'))
                    //     ->addTo($sendTo)
                    //     ->setReplyTo($sendTo)
                    //     ->getTransport();

                    // $transport->sendMessage();
                }

                $this->messageManager->addSuccess( __('Your payment confirmation has been submited.') );
            } catch (Exception $e) {
                $this->messageManager->addError( __('Can not submit payment confirmation. Please try again later.') );
            }
        }
        else {
            $this->messageManager->addError( __('Your order ID '.$postData['order_id'].' is not found.') );
        }
        
        // $resultRedirect->setPath($this->_redirect->getRefererUrl().'/index/result');$this->_storeManager->getStore()->getBaseUrl()
        $resultRedirect->setPath($_storeManager->getStore()->getBaseUrl());
        return $resultRedirect;
    }   

    public function getConfig($path){
        return $this->scopeConfig->getValue(
                $path,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                );
    }
}