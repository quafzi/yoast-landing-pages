<?php
class Yoast_Filter_Block_Layer_View extends Mage_Catalog_Block_Layer_View
{
    public function getLayer()
    {
        return Mage::getSingleton('Yoast_Filter/Layer');
    }
}