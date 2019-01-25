<?php
$installer = $this;
$installer->startSetup();

$installer->addAttribute("quote_address", "mobilenum", array("type"=>"varchar"));
$installer->addAttribute("order_address", "mobilenum", array("type"=>"varchar"));
$installer->endSetup();
	 