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
}
