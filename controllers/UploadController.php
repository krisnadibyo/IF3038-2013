<?php
class UploadController extends Controller
{
    private $uploadDir;

    protected function init()
    {
        $this->uploadDir = dirname(__FILE__) . '/../static/uploads/';
        Session::init();

        if (!Session::loggedIn() && !$this->request->getParam('magicauth')) {
            $this->response->renderJson('Not Authenticated!');
            exit();
        }
    }

    // POST /upload/test + multipart data
    public function test()
    {
        if (!$this->_isPOST()) {
            return $this->response->nullJson();
        }

        /* print_r($_FILES); */
        $tmpfile = $_FILES['fileobj']['tmp_name'];
        $destfile = $this->uploadDir . 'test-' . $_FILES['fileobj']['name'];

        if (!move_uploaded_file($tmpfile, $destfile)) {
            throw new Exception("File upload error", 1);
            exit();
        }

        return $this->response->renderJson(array('status' => 'success'));
    }

    // POST /upload/avatar/<username> + multipart data
    public function avatar($username=null)
    {
        if (!$this->_isPOST() || !$username || !isset($_FILES['fileobj'])) {
            return $this->response->nullJson();
        }

        $tmpfile  = $_FILES['fileobj']['tmp_name'];
        $destfile = $this->uploadDir . 'avatar/' . $username . preg_replace('/^.+\.(.+)$/', '.$1', $_FILES['fileobj']['name']);

        if (!move_uploaded_file($tmpfile, $destfile)) {
            throw new Exception("File upload error", 1);
            exit();
        }

        return $this->response->renderJson(array('status' => 'success'));
    }

    // POST /upload/attachment/<task_id> + multipart data
    public function attachment($task_id=null)
    {
        if (!$this->_isPOST() || !$task_id || !isset($_FILES['fileobj'])) {
            return $this->response->nullJson();
        }

        $tmpfile  = $_FILES['fileobj']['tmp_name'];
        $destfile = $this->uploadDir . 'attachment/' . $task_id . '-' . $_FILES['fileobj']['name'];

        if (!move_uploaded_file($tmpfile, $destfile)) {
            throw new Exception("File upload error", 1);
            exit();
        }

        return $this->response->renderJson(array('status' => 'success'));
    }
}
