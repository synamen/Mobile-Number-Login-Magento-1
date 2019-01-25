<?php
class Mage_Adminhtml_Model_System_Config_Source_Diyoptions15106408654
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
		
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Numeric (0-9)')),
            array('value' => 2, 'label'=>Mage::helper('adminhtml')->__('Numeric (0-9) with special characters (+ () -)')),
        );
    }

}
