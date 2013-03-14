<?php
class PageController extends Controller
{
    public function index()
    {
        $this->response->redirect(vh_link('hello/view/'
            . urlencode('The quick<br/>brown fox<br/>jumps over<br/>the lazy<br/>dog')));
    }
}
