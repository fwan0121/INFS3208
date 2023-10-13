<?php

namespace App\Controllers;
use App\Models\Course_model;
use App\Models\User_model;
use App\Models\Comment_model;
use App\Models\Cart_model;
use App\Models\Category_model;
use App\Models\Notification_model;
use Pusher\Pusher;
require_once ROOTPATH . 'vendor/autoload.php';
use \ReCaptcha\ReCaptcha;

class Course extends BaseController
{
    protected $pusher;
    public function __construct() {
        $config = config('Pusher'); 
        $this->pusher = new Pusher(
            $config->appKey,
            $config->appSecret,
            $config->appId,
            [
                'cluster' => $config->cluster,
                'useTLS' => $config->useTLS,
            ]
        );
    }

    public function index()
    {
        $model = new Course_model();
        $offset = 0;
        $limit = 4;
        $data['page'] = 'course';
        $data['course'] = $model->getBatch($offset, $limit);
        echo view("template/proj_header");
        echo view("course", $data);
        echo view("template/course_footer");
    }


    public function loadMoreCourses() {
        $offset = $this->request->getVar('offset');
        $limit = 4;
        $searchKeyword = $this->request->getVar('search');
        $model = new Course_model();
        $courses = $model->getBatch($offset, $limit, $searchKeyword);
        return $this->response->setJSON($courses);
    }

    public function searchKeyword() {
    $searchQuery = $this->request->getVar('search');
    $model = new Course_model();
    $data['course'] = $model->searchCourses($searchQuery);
    $data['total_results'] = count($data['course']);
    $data['search_query'] = $searchQuery;

    echo view("template/proj_header");
    echo view("search_res", $data);
    echo view("template/proj_footer");
    }

    public function getAutoComplete() {
        $searchQuery = $this->request->getVar('search');
        $model = new Course_model();
        $autocomplete = $model->getAutoComplete($searchQuery);
        return $this->response->setJSON($autocomplete);
    }

    public function courseDetail($course_id) {
        $model = new Course_model();
        $user_model = new User_model();
        $comment_model = new Comment_model();
        $cart_model = new Cart_model();
        $data['course'] = $model->getCourseById($course_id);
        $data['user'] = $user_model->getUserById($data['course']['instructor_id']);
        $data['comments'] = $comment_model->getCommentsByCourseId($course_id);
        $session = session();
        $user_id = $session->get('user_id');
        $isInCart = false;
        if ($user_id) {
            $cart = $cart_model->getCartByUserAndCourse($user_id, $course_id);
            if (!empty($cart)) {
                $isInCart = true;
            }
        }
        $data['isInCart'] = $isInCart;
        echo view("template/proj_header");
        echo view("course_detail", $data);
        echo view("template/proj_footer");
        
    }

    public function addComment() {
        $session = session();
        // Check if the user is logged in.
        if ($session->has('user_id')) {
            $user_id = $session->get('user_id');
        } else {
            // If the user is not logged in, assign the default user
            $user_id = 'default';
        }
        
        $course_id = $this->request->getVar('course_id');
        $comment_text = $this->request->getVar('comment');
        $comment_model = new Comment_model();
        $comment_model->addComment([
            'user_id' => $user_id,
            'course_id' => $course_id,
            'comment' => $comment_text,
            'created_date' => date('Y-m-d H:i:s'),
            'update_date' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url("course/courseDetail/$course_id"));
    }

    public function addToCart() {
        $session = session();
        $user_id = $session->get('user_id');
        $course_id = $this->request->getVar('course_id');
        $model = new Course_model();
        $course = $model->getCourseById($course_id);
        $cart_model = new Cart_model();
        // check if user is not logged in
        if (!$session->get('user_id')) {
            $session->setFlashdata('error', 'You need to be logged in to add courses to the cart.');
            return redirect()->to(base_url('login'));
        }
        // check if the course is already in the cart
        $cart = $cart_model->getCartByUserAndCourse($user_id, $course_id);
        if (!empty($cart)) {
            // course is already in the cart, display message
            $session->setFlashdata('error', 'You have already added this course to the cart.');
            return redirect()->to(base_url("course/courseDetail/$course_id"));
        }

        $cart_model->addToCart($user_id, $course['course_id']);
        return redirect()->to(base_url("course/courseDetail/$course_id"));
    }

    public function removeFromCart($couse_id) {
        $session = session();
        $user_id = $session->get('user_id');
        $model = new Course_model();
        $course = $model->getCourseById($couse_id);
        $cart_model = new Cart_model();
        $cart_model->removeFromCart($user_id, $course['course_id']);
        return redirect()->to(base_url('course'));
    }

    public function rmFromCartDetail($couse_id) {
        $session = session();
        $user_id = $session->get('user_id');
        $model = new Course_model();
        $course = $model->getCourseById($couse_id);
        $cart_model = new Cart_model();
        $cart_model->removeFromCart($user_id, $course['course_id']);
        return redirect()->to(base_url("course/courseDetail/$couse_id"));
    }
    
    public function instructorAllCourse() {
        $session = session();
        $instructor_id = $session->get('user_id');
        $model = new Course_model();
        $courses = $model->getCoursesByInstructor($instructor_id);
        echo view("template/proj_header");
        echo view('instructor', ['courses' => $courses]);
        echo view("template/proj_footer");
    }

    public function addCourse() {
        $session = session();
        $user_id = $session->get('user_id');
        $data = [];
        $data['error'] = "";
        if ($this->request->getMethod() == 'post') {
            $recaptchaResponse = $this->request->getPost('g-recaptcha-response');
            $recaptcha = new \ReCaptcha\ReCaptcha("6LcgoeglAAAAAEqMpfbnJD1wtyEvKykndJoQ5l7u");
            $resp = $recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
                              ->verify($recaptchaResponse, $_SERVER['REMOTE_ADDR']);
    
            $rules = [
                'title' => 'required|min_length[3]',
                'category_id' => 'required',
                'num_of_chapter' => 'required|numeric',
                'course_fee' => 'required|numeric'
            ];

            $Files = $this->request->getFiles('filenames');
            $Count = count($Files);
            if ($Count > 2) {
                $data['error'] = 'You can upload up to 2 files only.';
            } else {
                $filenames = [];
                foreach ($Files as $file) {
                    if (is_array($file)) {
                        foreach ($file as $f) {
                            if (count($filenames) < 2 && $f->isValid() && !$f->hasMoved()) {
                                $f->move(WRITEPATH . 'uploads');
                                $filenames[] = $f->getName();
                            }
                        }
                    } else {
                        if (count($filenames) < 2 && $file->isValid() && !$file->hasMoved()) {
                            $file->move(WRITEPATH . 'uploads');
                            $filenames[] = $file->getName();
                        }
                    }
                }
                $data += [
                    'filename1' => $filenames[0] ?? null,
                    'filename2' => $filenames[1] ?? null,
                ];
            }
            
    
            if ($this->validate($rules) && $resp->isSuccess()) {
                $model = new Course_model();
                
                $courseData = [
                    'title' => $this->request->getVar('title'),
                    'category_id' => $this->request->getVar('category_id'),
                    'course_brief' => $this->request->getVar('course_brief'),
                    'instructor_id' => session()->get('user_id'),
                    'num_of_chapter' => $this->request->getVar('num_of_chapter'),
                    'course_fee' => $this->request->getVar('course_fee'),
                    'summary' => $this->request->getVar('summary'),
                    'filename1' => $data['filename1'] ?? '',
                    'filename2' => $data['filename2'] ?? '',
                    
                ];
            
    
                $model->insertCourse($courseData);
                $course_id = $model->insertID();
                $notificationModel = new Notification_model();
                $notifications = $notificationModel->getUserNotifications();
                $notiCount = count($notifications);
               // create a new notification
                $notification = [
                    'type' => 'course_added',
                    'data' => [
                        'user_id' => $courseData['instructor_id'],
                        'course_id' => $course_id,
                        'title' => $courseData['title'],
                        'body' => 'New course ' . $courseData['title'] . ' has been added!',
                        'count' => $notiCount + 1
                        ]
                ];
                 // add the notification to the database
                $notificationModel->addNotifications($courseData['instructor_id'], $course_id, $courseData['title'], $notification['data']['body']);
                // trigger the pusher event
                $this->pusher->trigger('notifications', 'new_notification', json_encode($notification));
                return redirect()->to(base_url("/course/instructorAllCourse"));
            } else if (!$resp->isSuccess()) {
                $data['error'] = "The reCAPTCHA was not checked. Please try again.";
            } else {
                $data['validation'] = $this->validator;
            }
        }
        
        $categoryModel = new Category_model();
        $data['categories'] = $categoryModel->getAll();
    
        echo view("template/proj_header");
        echo view('add_course', $data);
        echo view("template/proj_footer");
    }



    
}
