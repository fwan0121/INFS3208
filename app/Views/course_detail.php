<section class="detail-sec">
    <div class="container">
        <div class="row">
            <div class="col-8">
                <h4><?php echo $course['title']; ?></h4>
                <p>
                    <?php echo $course['course_brief']; ?>
                </p>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">What you will learn</h5>
                        <p class="card-text"><?php echo $course['summary']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="img">
                        <img src="<?php echo base_url(); ?>assets/img/demo_img.png" class="card-img-top" alt="">
                    </div>
                    <div class="card-body">
                        <h4>$<?php echo $course['course_fee']; ?></h4>
                        <div class="row">
                            <div class="col-9">
                                <?php if ($isInCart): ?>
                                    <form action="<?php echo base_url('course/rmFromCartDetail/'.$course['course_id']); ?>" method="post">
                                        <button type="submit" name="course_id" class="btn btn-outline-dark col-12">Remove from Cart</button>
                                    </form>
                                <?php else: ?>
                                    <form action="<?php echo base_url('course/addToCart'); ?>" method="post">
                                        <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
                                        <button type="submit" class="btn btn-outline-dark col-12">Add to Cart</button>
                                    </form>
                                <?php endif; ?>
                                <?php if (session()->has('error')): ?>
                                    <div class="alert alert-danger"><?= session('error') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-dark">
                                    <i class="fa-regular fa-heart fa-lg"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <input type="button" value="Buy Now" class="btn btn-outline-dark col-12">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="content-sec">
    <div class="container">
        <h4>Course Content</h4>
        <div class="accordion" id="accordionExample">
        <div class="card">
            <div class="card-header" id="headingOne">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Collapsible Group Item #1
                </button>
            </h2>
            </div>     
            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
            <div class="card-body">
                Some placeholder content for the first accordion panel. This panel is shown by default, thanks to the <code>.show</code> class.
            </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingTwo">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                Collapsible Group Item #2
                </button>
            </h2>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
            <div class="card-body">
                Some placeholder content for the second accordion panel. This panel is hidden by default.
            </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingThree">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Collapsible Group Item #3
                </button>
            </h2>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
            <div class="card-body">
                And lastly, the placeholder content for the third and final accordion panel. This panel is hidden by default.
            </div>
            </div>
        </div>
        </div>             
    </div>
</section>

<section class="profile-sec">
    <div class="container">
        <h4>Instructor</h4><br>
        <div class="mb-3">
            <div class="row no-gutters">
                <div class="col-md-4  profile-img">
                    <img src="<?php echo base_url('writable/uploads/' . ($user['filename'] ?? 'default.png')); ?>" alt="Profile Image">
                </div>
                <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h5>
                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="comment-sec">
    <div class="container">
        <h4>Comments</h4>
        <?php foreach ($comments as $comment): ?>
            <div class="card" style="padding:5px 0 20px 0;">
                <div class="row">
                    <div class="col-2">
                        <div class="comment-pic">
                            <img src="https://fakeimg.pl/150x150/aaaaaa" alt="">
                        </div>
                    </div>
                    <div class="col-10">
                        <div class="card-body">
                            <h5 class="card-title">user_id: <?php echo $comment['user_id'] ?></h5>
                            <p class="card-text"><?php echo $comment['comment']?></p>
                        </div>  
                    </div>
                </div>
            </div>
            <br>
        <?php endforeach; ?>
        <form action="<?= base_url('course/addComment') ?>" method="post">
            <input type="hidden" name="course_id" value="<?= $course['course_id'] ?>">
            <textarea name='comment' class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Add your comment..."></textarea>
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</section>
