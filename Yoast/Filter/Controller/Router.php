<?php
class Yoast_Filter_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    public function initControllerRouters($observer)
    {
        $front = $observer->getEvent()->getFront();

        $filter = new Yoast_Filter_Controller_Router();
        $front->addRouter('f', $filter);
    }

    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::app()->isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }

        $route = "f";
        $identifier = $request->getPathInfo();

        if (substr(str_replace("/", "",$identifier), 0, strlen($route)) != $route) {
            return false;
        }

        $identifier = substr_replace($request->getPathInfo(),'', 0, strlen("/" . $route. "/") );
        $identifier = str_replace('.html', '', $identifier);
        $identifier = str_replace('.htm', '', $identifier);
        $identifier = str_replace('.php', '', $identifier);

        if ($identifier[strlen($identifier)-1] == "/") {
            $identifier = substr($identifier,0,-1);
        }

        $identifier = explode('/', $identifier, 3);

        if (isset($identifier[1])&&isset($identifier[0])&&!isset($identifier[2])) {
            $request->setModuleName('f')
                ->setControllerName('index')
                ->setActionName('view')
                ->setParam('id', $identifier[0])
                ->setParam('v', $identifier[1]);

            return true;
        } else {
            return false;
        }
    }
}