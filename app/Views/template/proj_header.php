<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/main.css">
    <script src="https://kit.fontawesome.com/695339d482.js" crossorigin="anonymous"></script>
    <title>Online Learning</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
          <a class="navbar-brand" href="<?php echo base_url(); ?>course">Online Learning</a>
          <ul class="navbar-nav mr-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                  Categories
                </a>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="#">Action</a>
                  <a class="dropdown-item" href="#">Another action</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
          </ul>
          <form class="form-inline mr-auto" action="<?php echo base_url(); ?>course/searchKeyword" method="get">
              <div style="position: relative;">
                  <input class="form-control" id="search-input" name="search" type="search" placeholder="Search" aria-label="Search">
                  <div id="autocomplete" style="display: none; position: absolute; top: 100%; left: 0; width: 100%; background-color: white; z-index: 100; border: 1px solid #ccc;"></div>
              </div>
              <button style="border-color: white;" class="btn btn-dark my-sm-0" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
          </form>


          <?php if (session()->get('user_id')) { ?>
            <a class="mr-4" href="#" style="color:white;" data-toggle="popover" data-placement="bottom" title="Cart">
                <i class="fas fa-shopping-cart"></i>
                <?php
                    $cartModel = new \App\Models\Cart_model();
                    $cartItems = $cartModel->getCartItems(session()->get('user_id'));
                    $cartCount = count($cartItems);
                ?>
                <?php if ($cartCount > 0) : ?>
                    <span class="badge badge-pill badge-danger"><?php echo $cartCount; ?></span>
                <?php endif; ?>
            </a>
            <div class="popover-cart-content d-none">
                <?php if ($cartCount > 0) : ?>
                    <div class="cart-items">
                        <?php foreach ($cartItems as $item) : ?>
                            <div class="cart-item">
                                <a class='detail' href="<?php echo base_url('course/courseDetail/' . $item['course_id']); ?>">
                                  <span><?php echo $item['title']; ?></span>
                                  <span>$ <?php echo $item['course_fee']; ?></span>
                                </a>
                                <a class= 'delete' href="<?php echo base_url(); ?>course/removeFromCart/<?php echo $item['course_id']; ?>"><i class="fas fa-trash-alt"></i></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
                $notificationModel = new \App\Models\Notification_model();
                $notifications = $notificationModel->getUserNotifications();
                $notificationCount = count($notifications);
            ?>
            <div class="dropdown" id="notificationDropdown">
                <a class="mr-4 dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:white;">
                    <i class="fa-regular fa-bell fa-lg"></i>
                    <?php if ($notificationCount > 0): ?>
                        <span class="badge badge-pill badge-danger"><?php echo $notificationCount; ?></span>
                    <?php endif; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationDropdown">
                  <div class="cart-items-wrapper" style='width:300px;'>
                    <?php foreach ($notifications as $notification): ?>
                      <div class="cart-items">
                        <?php if ($notificationCount > 0): ?>
                          <a style='color:black;' class='detail' href="<?php echo base_url('course/courseDetail/' . $notification['course_id']); ?>" onclick="markAsRead(<?php echo $notification['id']; ?>)">
                            <span><?php echo $notification['body']; ?></span>
                          </a>
                        <?php endif; ?>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
            </div>
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fa-regular fa-user fa-lg"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                  <a class="dropdown-item" href="<?php echo base_url(); ?>profile">Profile</a>
                  <!-- <a class="dropdown-item" href="<?php echo base_url(); ?>#">My Courses</a>s -->
                  <a class="dropdown-item" href="<?php echo base_url(); ?>course/instructorAllCourse">Instructor</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="<?php echo base_url(); ?>login/logout">Logout</a>
              </div>
            </div>
            <!-- <a class="btn btn-outline-light mr-2" href="<?php echo base_url(); ?>login/logout">Log out</a> -->
          <?php } else { ?>
            <a style="margin: 0 10px;" class="btn btn-outline-light" href="<?php echo base_url(); ?>login"> Login </a>
            <a style="margin: 0 10px;" class="btn btn-outline-light href="#" href="<?php echo base_url(); ?>signup">Sign up</a>
          <?php } ?>
        </div>
    </nav>

<main>