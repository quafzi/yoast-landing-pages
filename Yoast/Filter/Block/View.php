<?php
class Yoast_Filter_Block_View extends Mage_Catalog_Block_Product_List
{
    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
            $collection = Mage::getResourceModel('catalog/product_collection');
            Mage::getModel('catalog/layer')->prepareProductCollection($collection);

            if ($this->getValue() && $this->getAttribute()) {
                $collection->addAttributeToFilter($this->getAttribute(), $this->getValue());
            }
            $this->_productCollection = $collection;
        }
        return $this->_productCollection;
    }

    protected function _prepareLayout()
    {
        $b_title = $this->__($this->getAttribute().": ".$this->getLabel());
        $p_title = $this->__($this->getLabel()." - ".$this->getAttribute()." products");

        // add Home breadcrumb
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb('home', array(
                'label' => $this->__('Home'),
                'title' => $this->__('To homepage'),
                'link'  => Mage::getBaseUrl()
            ))->addCrumb('filter', array(
                'label' => $b_title,
                'title' => $b_title
            ));
        }
        $this->getLayout()->getBlock('head')->setTitle($p_title);
        return parent::_prepareLayout();
    }

    public function getCmsBlockHtml()
    {
        if (!$this->getData('cms_block_html')) {
            $ReplacementValues = array(" ","#","!","&",".");
            $html = $this->getLayout()->createBlock('cms/block')
                ->setBlockId('landing-'.$this->getAttribute().'-'.str_replace($ReplacementValues,"-",$this->getValue()))
                ->toHtml();
            $this->setData('cms_block_html', $html);
        }
        return $this->getData('cms_block_html');
    }

    public function getTitle()
    {
        return $this->__("All products ".$this->getLabel());
    }

    public function getLabel()
    {
        $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $this->getAttribute());
        foreach ( $attribute->getSource()->getAllOptions(true, true) as $option) {
            $attributeArray[$option['value']] = $option['label'];
        }

        if (array_key_exists($this->getValue(), $attributeArray)) {
            $label = $attributeArray[$this->getValue()];
        } else {
            $label = $this->getValue();
        }
        return $label;
    }

    public function getAttribute()
    {
        $attribute  = $this->getRequest()->getParam('id',0);
        $attribute = str_replace("-"," ",$attribute);
        return $attribute;
    }

    public function getValue()
    {
        $value = $this->getRequest()->getParam('v',0);
        $value = str_replace("-"," ",$value);
        return $value;
    }
}