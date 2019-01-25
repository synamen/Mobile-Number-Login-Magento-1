<?php
class Synamen_Mobile_Model_Customer_Resource_Customer extends Mage_Customer_Model_Resource_Customer
{

	protected function _beforeSave(Varien_Object $customer)
    {
        parent::_beforeSave($customer);

        if (!$customer->getEmail()) {
            throw Mage::exception('Mage_Customer', Mage::helper('customer')->__('Customer email is required'));
        }

        $adapter = $this->_getWriteAdapter();
        $bind    = array('email' => $customer->getEmail());

        $select = $adapter->select()
            ->from($this->getEntityTable(), array($this->getEntityIdField()))
            ->where('email = :email');
        if ($customer->getSharingConfig()->isWebsiteScope()) {
            $bind['website_id'] = (int)$customer->getWebsiteId();
            $select->where('website_id = :website_id');
        }
        if ($customer->getId()) {
            $bind['entity_id'] = (int)$customer->getId();
            $select->where('entity_id != :entity_id');
        }

        $result = $adapter->fetchOne($select, $bind);
        if ($result) {
            throw Mage::exception(
                'Mage_Customer', Mage::helper('customer')->__('This customer email already exists'),
                Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS
            );
        }
       
        if($customer->getMobilenum()) {
            
        	$collection = Mage::getModel('customer/customer')->getCollection();
			$collection->addAttributeToFilter('mobilenum', array('eq' =>$customer->getMobilenum()));

			if($customer->getId()){
					$collection->addAttributeToFilter('entity_id', array('neq'=>$customer->getId()));
			}

						   
			if ($collection->getSize()) {
				throw Mage::exception(
					'Mage_Customer', Mage::helper('customer')->__('Mobile number already exists'), NULL
				);
			}
               
        }



        // set confirmation key logic
        if ($customer->getForceConfirmed()) {
            $customer->setConfirmation(null);
        } elseif (!$customer->getId() && $customer->isConfirmationRequired()) {
            $customer->setConfirmation($customer->getRandomConfirmationKey());
        }
        // remove customer confirmation key from database, if empty
        if (!$customer->getConfirmation()) {
            $customer->setConfirmation(null);
        }

        return $this;
    }
}
		