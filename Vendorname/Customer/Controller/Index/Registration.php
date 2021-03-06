<?php
namespace Vendorname\Customer\Controller\Index;

class Registration extends \Magento\Framework\App\Action\Action
{
	public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Directory\Model\RegionFactory $regionColFactory,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {        
        $this->regionColFactory = $regionColFactory;
        $this->resultJsonFactory = $resultJsonFactory;
		$this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

	public function execute()
	{
		$this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();

        $result  = $this->resultJsonFactory->create();
        $regions = $this->regionColFactory->create()->getCollection()->addFieldToFilter('country_id',$this->getRequest()->getParam('country'));
        return $result->setData(['success' => true,'value'=>$regions->getData()]);
	}
}