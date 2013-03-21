<?php
class UploadController extends Controller
{
    private $uploadDir;

    protected function init()
    {
        $this->uploadDir = dirname(__FILE__) . '/../static/uploads/';
        Session::init();

        if (!Session::loggedIn() && Router::getAction() !== 'avatar') {
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

        $tmpfile = $_FILES['fileobj']['tmp_name'];
        $destfile = $this->uploadDir . 'test-' . $_FILES['fileobj']['name'];

        if (!move_uploaded_file($tmpfile, $destfile)) {
            throw new Exception("File upload error", 1);
            exit();
        }

        return $this->response->renderJson(array(
            'status' => 'success',
            'files' => $_FILES,
        ));
    }

    // POST /upload/avatar/<username> + multipart data
    public function avatar($username=null)
    {
        if (!$this->_isPOST() || !$username || !isset($_FILES['fileobj'])) {
            return $this->response->nullJson();
        }

        $tmpfile  = $_FILES['fileobj']['tmp_name'];
        $filename = $username . preg_replace('/^.*\.(.*)$/', '.$1', $_FILES['fileobj']['name']);
        $destfile = $this->uploadDir . 'avatar/' . $filename;

        if (!move_uploaded_file($tmpfile, $destfile)) {
            throw new Exception("File upload error", 1);
            exit();
        }

        return $this->response->renderJson(array(
            'status' => 'success',
            'filename' => $filename,
        ));
    }

    // POST /upload/attachment/<task_id> + multipart data
    public function attachment($task_id=null)
    {
        if (!$this->_isPOST() || !$task_id || !isset($_FILES['fileobj'])) {
            return $this->response->nullJson();
        }

        $tmpfile  = $_FILES['fileobj']['tmp_name'];
        $filename = $task_id . '-' . $_FILES['fileobj']['name'];
        $destfile = $this->uploadDir . 'attachment/' . $filename;

        if (!move_uploaded_file($tmpfile, $destfile)) {
            throw new Exception("File upload error", 1);
            exit();
        }

        return $this->response->renderJson(array(
            'status' => 'success',
            'filename' => $filename,
        ));
    }
}
