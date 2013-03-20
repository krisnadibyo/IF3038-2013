<?php
class User extends Model
{
    protected static $table = 'tbl_user';

    protected $id = null;
    protected $name = null;
    protected $username = null;
    protected $password = null;
    protected $email = null;
    protected $birthday = null;
    protected $avatar = null;
    protected $bio = null;

    public static function getOneByUsername($username)
    {
        return self::getOne(array(
            'where' => array(
                array('username', '=', $username),
            ),
        ));
    }

    public function validate($boolReturn=false, $editUser=false)
    {
        $error = array();

        // name
        if (!$this->name) {
            $error['name'][] = 'Required';
        }

        // username
        if (!$this->username) {
            $error['username'][] = 'Required';
        } else if (!$editUser && User::getOneByUsername($this->username) != null) {
            $error['username'][] = 'Already taken';
        }

        // password
        if (!$this->password) {
            $error['password'][] = 'Required';
        }

        // email
        if (!$this->email) {
            $error['email'][] = 'Required';
        } else if (!$editUser && User::getOne(array('where' => array(array('email', '=', $this->email)))) != null) {
            $error['email'][] = 'Already exist';
        }

        // birthday
        if (!$this->birthday) {
            $error['birthday'][] = 'Required';
        } else {
            $dateCorrect = preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $this->birthday, $m);
            if (!$dateCorrect) {
                $error['birthday'][] = 'Bad format; Must be in YYYY-MM-DD';
            } else {
                if ((int) $m[2] > 12 || (int) $m[2] < 1 || (int) $m[3] > 31 || (int) $m[3] < 1) {
                    $error['birthday'][] = 'Bad date';
                }
            }
        }

        if ($boolReturn) {
            return $error === array() ? true : false;
        }

        return $error;
    }
}
