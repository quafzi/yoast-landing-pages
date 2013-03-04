<?php
class Yoast_Filter_Block_Result extends Mage_Catalog_Block_Product_List
{
    protected $_defaultToolbarBlock = 'Yoast_Filter/product_list_toolbar';
    protected $_toolbarBlock;

    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
            $collection = Mage::getResourceModel('catalog/product_collection');
            Mage::getSingleton('catalog/layer')->prepareProductCollection($collection);

            if ($this->getValue()) {
                $value = $this->getValue();
            } else{
                $value = $this->getRequest()->getParam('filterValue', 0);
            }

            if ($this->getCategory())
            {
                $categoryId = $this->getCategory();
            }
            else
            {
                $categoryId = $this->getRequest()->getParam('filterCategory', 0);
            }

            if ($this->getAttributeName()){
                $attribute = $this->getAttributeName();
                $collection->addAttributeToFilter($attribute, array('like' => '%'.$value.'%'));
            }

            $collection->addAttributeToSelect('attribute_set_ids');

            $_filters = Mage::getSingleton('Yoast_Filter/Layer')->getState()->getFilters();
            foreach ($_filters as $_filter) {
                if($_filter->getFilter()->getRequestVar() == "price") {
                    $arr = $_filter->getValue();
                    $max_value = $arr[0] * $arr[1];
                    $min_value = $max_value - $arr[1];

                    $collection->addAttributeToFilter($_filter->getFilter()->getRequestVar(), array('gt' => $min_value))
                        ->addAttributeToFilter($_filter->getFilter()->getRequestVar(), array('lt' => $max_value));
                } else if($_filter->getFilter()->getRequestVar() == "cat") {
                    $category = Mage::getModel('catalog/category')->load($_filter->getValue());
                    $collection->addCategoryFilter($category, true);
                } else {
                    $collection->addAttributeToFilter($_filter->getFilter()->getRequestVar(), $_filter->getValue());
                }

            }

            if ($categoryId) {
                $category = Mage::getModel('catalog/category')->load($categoryId);
                $collection->addCategoryFilter($category, true);
            }

            $this->_productCollection = $collection;
            Mage::getSingleton('Yoast_Filter/Layer')->setProductCollection($this->_productCollection);

            if ($this->getSortby()) {
                $this->setSortBy($this->getSortby());
            }

            if ($this->getDirection()) {
                $this->setDefaultDirection($this->getDirection());
            }

            if ($this->getCount()) {
                $toolbar = $this->getToolbarBlock();
                $limits = array_keys($this->getToolbarBlock()->getAvailableLimit());
                $limits[] = $this->getCount();
                array_filter($limits);
                sort($limits);
                foreach ($limits as $limit) {
                    $toolbar->addPagerLimit('grid', $limit)
                        ->addPagerLimit('list', $limit);
                }
                $toolbar->setDefaultGridPerPage((int)$this->getCount())
                    ->setDefaultListPerPage((int)$this->getCount());
            }

            $this->getToolbarBlock()->setVisible($this->getPager());
        }

        return $this->_productCollection;
    }

    public function getToolbarBlock()
    {
        if (is_null($this->_toolbarBlock)) {
            $this->_toolbarBlock = parent::getToolbarBlock();
        }
        return $this->_toolbarBlock;
    }
}