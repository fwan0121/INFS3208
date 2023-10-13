<section class="jumbotron text-left">
    <div class="container">
      <h1>Instructor</h1>
      <a class="btn btn-dark col-2" href="<?php echo base_url(); ?>course/addCourse">Add Couse</a>
    </div>
  </section>
  <section class="course-sec">
    <div class="container">
        <h4>All Courses</h4>
        <div class="row">
        <?php foreach ($courses as $course): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
              <a class='course_card' href="<?php echo base_url('course/courseDetail/' . $course['course_id']); ?>">
                <div class="card">
                    <img src="<?php echo base_url(); ?>assets/img/demo_img.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $course['title']; ?></h5>
                        <p class="card-text"><?php echo $course['course_brief']; ?></p>
                        <a href="<?php echo base_url("course/courseDetail/{$course['course_id']}"); ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
              </a>
            </div>
        <?php endforeach; ?>
        </div>
        
    </div>
  </section>