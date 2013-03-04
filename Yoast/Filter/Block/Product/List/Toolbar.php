<?php

/**
 * Yoast_Filter_Block_Product_List_Toolbar
 *
 * @uses Mage_Catalog_Block_Product_List_Toolbar
 * @author Thomas Birke <tbirke@netextreme.de>
 */
class Yoast_Filter_Block_Product_List_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar
{
    public function setLimit($limit)
    {
        $this->setData('_current_limit', $limit);
    }

    public function _toHtml()
    {
        if ($this->getVisible()) {
            return parent::_toHtml();
        }
    }
}
