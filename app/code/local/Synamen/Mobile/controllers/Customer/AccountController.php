<?php
require_once "Mage/Customer/controllers/AccountController.php";  
class Synamen_Mobile_Customer_AccountController extends Mage_Customer_AccountController{

    public function postDispatch()
    {
        parent::postDispatch();
        Mage::dispatchEvent('controller_action_postdispatch_adminhtml', array('controller_action' => $this));
    }


protected function _welcomeCustomer(Mage_Customer_Model_Customer $customer, $isJustConfirmed = false)
    {
        $this->_getSession()->addSuccess(
            $this->__('Thank you for registering with %s.', Mage::app()->getStore()->getFrontendName())
        );
        if ($this->_isVatValidationEnabled()) {
            // Show corresponding VAT message to customer
            $configAddressType =  $this->_getHelper('customer/address')->getTaxCalculationAddressType();
            $userPrompt = '';
            switch ($configAddressType) {
                case Mage_Customer_Model_Address_Abstract::TYPE_SHIPPING:
                    $userPrompt = $this->__('If you are a registered VAT customer, please click <a href="%s">here</a> to enter you shipping address for proper VAT calculation',
                        $this->_getUrl('customer/address/edit'));
                    break;
                default:
                    $userPrompt = $this->__('If you are a registered VAT customer, please click <a href="%s">here</a> to enter you billing address for proper VAT calculation',
                        $this->_getUrl('customer/address/edit'));
            }
            $this->_getSession()->addSuccess($userPrompt);
        }

        $customer->sendNewAccountEmail(
            $isJustConfirmed ? 'confirmed' : 'registered',
            '',
            Mage::app()->getStore()->getId()
        );

        $successUrl = $this->_getUrl('*/*/index', array('_secure' => true));
        if ($this->_getSession()->getBeforeAuthUrl()) {
            $successUrl = $this->_getSession()->getBeforeAuthUrl(true);
        }

        $successUrl = Mage::getBaseUrl()."customer/account"; 
        return $successUrl;
    }


    public function loginPostAction()
    {
        $session = $this->_getSession();
     
            $collection = Mage::getModel('customer/customer')->getCollection();
             $username = $this->getRequest()->getPost();
             $usermobile = $username['login']['username'];
            $website_id = Mage::app()->getWebsite()->getId();
            
                    
        if (!filter_var($usermobile, FILTER_VALIDATE_EMAIL) === false): 
                 $collection->addAttributeToFilter('email', array('eq' =>$username['login']['username']));
                                else:
                        
             $collection->addAttributeToFilter('mobilenum', array('eq' =>$username['login']['username']));
            endif;
            
            
            
            //$collection->addAttributeToFilter('mobile', array('eq' =>$username['login']['username']));
            $custData = $collection->getData();
            $email = trim($custData[0]['email']);
            $customerId = (int) trim($custData[0]['entity_id']);
            try{
                $authenticateUser = Mage::getModel('customer/customer')->setWebsiteId($website_id)->authenticate($email, $username['login']['password']);
            }catch( Exception $e ){
                $session->addError('Invalid Login Detail');
                $this->_redirect('customer/account');
            }
            
            
            try{
                if($authenticateUser && $customerId){
                    
                    $customer = Mage::getModel('customer/customer')->load($customerId);
                    $session->setCustomerAsLoggedIn($customer);
                    $message = $this->__('You are now logged in as %s', $customer->getName());
                    $session->addSuccess($message);
                    Mage::log($message);
            }else{
            throw new Exception ($this->__('The login attempt was unsuccessful. Some parameter is missing Or wrong data '));
            }
            }catch (Exception $e){
            $session->addError($e->getMessage());
            }
            $this->_loginPostRedirect();

            }

}
				