<?php $course = $course ?? []; ?>
  <section class="jumbotron text-center">
    <div class="container">
      <h1>Banner</h1>
      <img src="<?php echo base_url(); ?>assets/img/demo_img.png">
    </div>
  </section>
  <section class="course-sec">
    <div class="container">
        <h4>Hot Deals for learners</h4>
        <div class="row">
        <?php foreach ($course as $c): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
              <a class='course_card' href="<?php echo base_url('course/courseDetail/' . $c['course_id']); ?>">
                <div class="card">
                    <img src="<?php echo base_url(); ?>assets/img/demo_img.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $c['title']; ?></h5>
                        <p class="card-text"><?php echo $c['course_brief']; ?></p>
                    </div>
                </div>
              </a>
            </div>
        <?php endforeach; ?>
        </div>
        
    </div>
  </section>
