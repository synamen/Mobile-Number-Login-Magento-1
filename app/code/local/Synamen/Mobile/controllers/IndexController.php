<?php
class Synamen_Mobile_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Customer Acoount"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("customer acoount", array(
                "label" => $this->__("Customer Acoount"),
                "title" => $this->__("Customer Acoount")
		   ));

      $this->renderLayout(); 
	  
    }
}