<?php

namespace App\Controllers;
use App\Models\Course_model;

class Login extends BaseController
{
    public function index()
    {

        $data['error'] = "";
        $courseModel = model('App\Models\Course_model');
        $courses = $courseModel->getAll();
        // check whether the cookie is set or not, if set, prefill the form and check the checkbox
        if (isset($_COOKIE['user_id']) && isset($_COOKIE['password'])) {
            $data['user_id'] = $_COOKIE['user_id'];
            $data['password'] = $_COOKIE['password'];
            $data['remember'] = 'checked';
        } else {
            $data['user_id'] = '';
            $data['password'] = '';
            $data['remember'] = '';
        }
        // check the session
        $session = session();
        $userid = $session->get('user_id');
        $password = $session->get('password');
        if ($userid && $password) {
            echo view("template/proj_header");
            echo view("course", ['course' => $courses]);
            echo view("template/proj_footer");
        } else {
            echo view('template/proj_header');
            echo view('login', $data);
            echo view('template/proj_footer');
        }
    }

    public function check_login() {
        $data['error'] = "<div class=\"alert alert-danger\" role=\"alert\"> Incorrect username or password!! </div> ";
        $userid = $this->request->getPost('user_id');
        $password = $this->request->getPost('password');
        $model = model('App\Models\User_model');
        $check = $model->login($userid, $password);
        $if_remember = $this->request->getPost('remember');
        $courseModel = model('App\Models\Course_model');
        $courses = $courseModel->getAll();
        $verified = $model->is_email_verified($userid);
        if ($check) {
            $session = session();
            $session->set('user_id', $userid);
            $session->set('password', $password);

            if ($if_remember) {
                $token = bin2hex(random_bytes(32));
                $model->storeRememberToken($userid, $token);
                setcookie('remember_token', $token, time() + 86400 * 30, "/", $_SERVER['HTTP_HOST'], false, true);
                setcookie('user_id', $userid, time() + (86400 * 30), "/", $_SERVER['HTTP_HOST'], false, true);
                setcookie('password', $password, time() + (86400 * 30), "/", $_SERVER['HTTP_HOST'], false, true);
            } else {
                setcookie('remember_token', '', time() - 3600, '/', $_SERVER['HTTP_HOST'], false, true);
                setcookie('user_id', '', time() - 3600, '/', $_SERVER['HTTP_HOST'], false, true);
                setcookie('password', '', time() - 3600, '/', $_SERVER['HTTP_HOST'], false, true);
            }

            if ($verified) {
                echo view("template/proj_header");
                echo view("course", ['course' => $courses]);
                echo view("template/proj_footer");
            } else {
                $data['error'] = "<div class=\"alert alert-danger\" role=\"alert\"> Email is not verified. Please verify your email to log in. </div> ";
                $session->remove(['user_id', 'password']);
                echo view("template/proj_header");
                echo view('login', $data);
                echo view("template/proj_footer");
            }
        } else {
            $data['error'] = "<div class=\"alert alert-danger\" role=\"alert\"> Incorrect username or password!! </div> ";
            echo view("template/proj_header");
            echo view('login', $data);
            echo view("template/proj_footer");
        }

    }

    public function logout()
    {
        helper('cookie');
        $session = session();
        $session->destroy();
        //destory the cookie
        setcookie('remember_token', '', time() - 3600, '/');
        setcookie('user_id', '', time() - 3600, '/');
        setcookie('password', '', time() - 3600, '/');
        return redirect()->to(base_url('login'));
    }

}
