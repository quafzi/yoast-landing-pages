<?php
class Yoast_Filter_Model_Layer extends Mage_Catalog_Model_Layer
{
    public function getFilterableAttributes()
    {
        $setIds = $this->_getSetIds();
        if (!$setIds) {
            $setIds = array();
        }
        /* @var $collection Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Attribute_Collection */
        $collection = Mage::getResourceModel('catalog/product_attribute_collection')
            ->setItemObjectClass('catalog/resource_eav_attribute');

        $collection->getSelect()->distinct(true);
        $collection
            ->setAttributeSetFilter($setIds)
            ->addStoreLabel(Mage::app()->getStore()->getId())
            ->setOrder('position', 'ASC');
        $collection = $this->_prepareAttributeCollection($collection);
        $collection->load();

        return $collection;
    }

    public function setProductCollection($collection)
    {
        #$this->getCurrentCategory()->getId('1'); //Just set an arbritrary value
        $this->prepareProductCollection($collection);
        $this->_productCollections[$this->getCurrentCategory()->getId()] = $collection;
    }

    public function prepareProductCollection($collection)
    {
        $attributes = Mage::getSingleton('catalog/config')
            ->getProductAttributes();

        $collection->addAttributeToSelect($attributes)
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            //->addStoreFilter()
            ;
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        $collection->addUrlRewrite($this->getCurrentCategory()->getId());

        return $this;
    }
}