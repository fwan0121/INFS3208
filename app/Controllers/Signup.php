<?php

namespace App\Controllers;
use CodeIgniter\Email\Email;
use App\Models\User_model;

class Signup extends BaseController
{
    public function index()
    {
        $data['error'] = "";
        echo view("template/proj_header");
        echo view("signup");
        echo view("template/proj_footer");
    }

    public function check_signup() {
        helper('url');
        $data['error'] = [];
        $userid = $this->request->getPost('user_id');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        //default as student
        $role = 10;
        $model = model('App\Models\User_model');

         // Check password strength
        if (!$this->checkPasswordStrength($password)) {
            $errors[] = "<div class=\"alert alert-danger\" role=\"alert\">Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number.</div>";
        }
        // Check if the user already exists
        if ($model->userExists($userid, $email)) {
            $errors[] = "<div class=\"alert alert-danger\" role=\"alert\">User with this email or username already exists!</div>";
        }

        if (!empty($errors)) {
            $data['error'] = implode("\n", $errors);
            echo view("template/proj_header");
            echo view("signup", $data);
            echo view("template/proj_footer");
        } else {

            $verificationCode = md5(rand());
            $verificationLink = base_url(route_to('verify_email') . "?email=$email&code=$verificationCode");

            $data = [
                'verification_successful' => false,
            ];
            $check = $model->insertUser($userid, $email, $password, $role, $verificationCode);
            if ($check) {    
                $email_body = "Please click the following link to verify your email address: " . $verificationLink;
                $email_subject = "Email verification for your account";
                $this->send_email($email, $email_subject, $email_body, $verificationCode);            
                echo view("template/proj_header");
                echo view("verify_email_form");
                echo view("template/proj_footer");
            } else {
                $data['error'] = "<div class=\"alert alert-danger\" role=\"alert\"> check your input again!! </div> ";
                echo view("template/proj_header");
                echo view('signup', $data);
                echo view("template/proj_footer");
            }
        }

    }

    public function checkPasswordStrength(string $password): bool {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        // $specialChars = preg_match('@[^\w]@', $password);
        return $uppercase && $lowercase && $number && strlen($password) >= 8;
    }

    public function send_email($to, $subject, $message, $verificationCode) {
        $email = new Email();
        $emailConf = [
            'protocol' => 'smtp',
            'wordWrap' => true,
            'SMTPHost' => 'mailhub.eait.uq.edu.au',
            'SMTPPort' => 25
        ];
        $email->initialize($emailConf);
        $email->setFrom('account-zone@uqcloud.net');
        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($message);
        $email->setMessage($message . "\nVerification code: " . $verificationCode);

        if ($email->send()) {
            echo 'Email sent successfully.';
        } else {
            echo 'Email not sent. Error: ' . $email->printDebugger(['headers']);
        }
    }

    public function verify_email($verification_successful = false) {
        $email = $this->request->getVar('email');
        $code = $this->request->getVar('code');
        $model = new User_model();
        $user = $model->getUserByEmail($email);
        $data['verification_successful'] = false;

        if ($user) {
            if ($user['verification_code'] === $code) {
                $data = [
                    'is_email_verified' => 1,
                    'verification_code' => null,
                ];
                $model->updateUser($user['user_id'], $data);

                // Set verification_successful flag
                $data['verification_successful'] = true;
            }
        }

        // Render the view
        echo view("template/proj_header");
        echo view("verify_email", $data);
        echo view("template/proj_footer");
    }
    
    
    


}
