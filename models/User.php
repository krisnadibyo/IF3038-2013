<?php
class User extends Model
{
    protected static $table = 'user';

    protected $id = null;
    protected $name = null;
    protected $username = null;
    protected $password = null;
    protected $email = null;
    protected $birthday = null;
    protected $avatar = null;
    protected $bio = null;
}
