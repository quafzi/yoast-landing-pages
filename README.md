This extension allows to embed configurable lists of products in your CMS pages.
Just enter something like that in your CMS page content editor:

    {{block type="Yoast_Filter/result" name="cms_product_listing" template="catalog/product/list.phtml" category="207" count="3" sortby="name" direction="DESC" pager="1" }}

It will include a product list using the extension's block Yoast_Filter_Block_Result and the default template `catalog/product/list.phtml`.

But hey: There are some additional parameters supported by now:

* ``category``: specify the id of the category you want to list products of.
* ``count``: set default number of products to be shown.
* ``sortby`` and ``direction``: define the default attribute and direction to sort by
* ``pager``: enter "1" to show the pager or "0" to hide it
