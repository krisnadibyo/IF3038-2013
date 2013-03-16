<?php
import('core.lib.Session');
import('core.lib.ViewHelper');

import('core.lib.Request');
import('core.lib.Response');

class Controller
{
    protected $request;
    protected $response;

    protected function init() {}

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->init();
    }
    
    // Extra functions (protected)
    ////
    protected function _isPOST()
    {
        if ($this->request->isPOST()) {
            return true;
        }

        return false;
    }

    protected function _isPOSTandHasData(&$data)
    {
        if (!$this->request->isPOST() || !$this->request->getParam('data')) {
            return false;
        }

        $data = json_decode($this->request->getParam('data'), true);
        if (!$data || !is_array($data)) {
            return false;
        }

        return true;
    }
}
