<?php

namespace App\Controllers;
use App\Models\User_model;
use Imagick;

class User extends BaseController
{
    public function index()
    {
        $data['error'] = "";
        $model = new User_model();
        $userid = session()->get('user_id');
        $user = $model->getUserById($userid);
        $data['user'] = $user;
        if ($userid) {
            echo view("template/proj_header");
            echo view("profile", $data);
            echo view("template/proj_footer");
        } else {
            $loginLink = base_url(route_to('login'));
            echo "Please Login in <a href='{$loginLink}'>{$loginLink}</a>";
        }
       
    }

    public function update_profile() {
        $model = new User_model();
        $userid = session()->get('user_id');
        $user = $model->getUserById($userid);
        $data['user'] = $user;
        $data['error'] = "";
        if($this->request->getMethod() == 'post') {
            $fname = $this->request->getVar('fname');
            $lname = $this->request->getVar('lname');
            $phone = $this->request->getVar('phone');
            $file = $this->request->getFile('userfile');
            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password');
            $newpwd = $this->request->getVar('newpwd');

            $data = [
                'first_name' => $fname,
                'last_name' => $lname,
                'phone' => $phone,
                'email' => $email
            ];
        
            if ($file->isValid() && !$file->hasMoved()) {
                $file->move(WRITEPATH . 'uploads');
                $filename = $file->getName();
                $data['filename'] = $filename; // Update the filename in the data array only when a new file is uploaded
                
                $watermarkPath = WRITEPATH . 'uploads/watermark01.png';
                if (!file_exists($watermarkPath)) {
                    throw new \Exception('Watermark file does not exist: ' . $watermarkPath);
                }
                // Add a watermark to the image
                try {
                    $image = new Imagick(WRITEPATH . 'uploads/' . $filename);
                    $watermark = new Imagick($watermarkPath);
                    // Position the watermark at the bottom-right corner of the image
                    $x = $image->getImageWidth() - $watermark->getImageWidth();
                    $y = $image->getImageHeight() - $watermark->getImageHeight();
            
                    $image->compositeImage($watermark, imagick::COMPOSITE_OVER, $x, $y);
                    $image->writeImage(WRITEPATH . 'uploads/' . $filename);
                } catch (ImagickException $e) {
                    echo 'Error: ' . $e->getMessage();
                } catch (\Exception $e) {
                    echo 'General Error: ' . $e->getMessage();
                }
            }
            

            if (!empty($newpwd)) {
                if ($newpwd == $password) {
                    $data['error'] = "New password cannot be the same as the current password";
                    echo view("template/proj_header");
                    echo view("profile", $data);
                    echo view("template/proj_footer");
                    return;
                }
                if ($model->login($user['user_id'], $password)) {
                    // $data['password'] = $newpwd;
                    $hashed_newpwd = password_hash($newpwd, PASSWORD_DEFAULT);
                    $data['password'] = $hashed_newpwd;
                } else {
                    $data['error'] = "Incorrect current password";
                    echo view("template/proj_header");
                    echo view("profile", $data);
                    echo view("template/proj_footer");
                    return;
                }
            }
            $model->updateUser($userid, $data);
            return redirect()->to(base_url('profile'));

        }
    }

    public function forgot_page() { 
        echo view("template/proj_header");
        return view("forgot_password");
        echo view("template/proj_footer");
    }

    public function forgot_password() {
        if ($this->request->getMethod() == 'post') {
            $email = $this->request->getVar('forgetpassword');
            $model = new User_model();
            $user = $model->getUserByEmail($email);
            
            if ($user && $user['email']) {
                // Generate token and set its expiration time
                $token = bin2hex(random_bytes(32));
                $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Save the token and expiration time in the database
                $model->updateUser($user['user_id'], [
                    'password_reset_token' => $token,
                    'password_reset_expires_at' => $expires_at,
                ]);
                // Instead of sending an email, display the reset link for demo purposes
                $resetLink = base_url(route_to('reset_password') . "?token={$token}");
                echo "Reset Link (for demo purposes): <a href='{$resetLink}'>{$resetLink}</a>";
            } else {
                // Show a success message regardless of whether the email was found in the database (haven't imeplement yet)
                echo "If the email address you entered is registered, you will receive a password reset link shortly.";

            }
        } else {
            echo view('template/proj_header');
            echo view('login');
            echo view('template/proj_footer');
        }
    }

    
    public function reset_password() {
        $model = new User_model();

        if ($this->request->getMethod() == 'post') {
            $token = $this->request->getVar('token');
            $password = $this->request->getVar('password');
            $user = $model->getUserByResetToken($token);
            if ($user && strtotime($user['password_reset_expires_at']) > time()) {
                // Reset the user's password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $model->updateUser($user['user_id'], [
                    'password' => $hashed_password,
                    'password_reset_token' => null,
                    'password_reset_expires_at' => null,
                ]);

                // Show a success message
                $loginpage = base_url(route_to('login'));
                echo "Your password has been reset successfully. You can now log in with your new password.
                back to login page  <a href='{$loginpage}'>{$loginpage}</a>";
            } else {
                // Show an error message if the token is invalid or expired
                echo "The password reset link is invalid or has expired. Please request a new password reset link.";
            }
        } else {
            $token = $this->request->getGet('token', FILTER_SANITIZE_STRING);
            // $token = $this->request->getGet('token');
            $user = $model->getUserByResetToken($token);

            if ($user && strtotime($user['password_reset_expires_at']) > time()) {
                $data['token'] = $token;
                echo view('template/proj_header');
                return view('reset_password', $data);
                echo view('template/proj_footer');
            } else {
                // echo "The password reset link is invalid or has expired. Please request a new password reset link.";
                return redirect()->to(base_url('login'))->with('error', 'The password reset link is invalid or has expired. Please request a new password reset link.');
            }
        }
    }




}
