<section class="course-sec">
    <div class="container">
        <h3><?php echo $total_results; ?> Results for "<?php echo htmlspecialchars($search_query); ?>"</h3>
        <div class="text-right">
            <nav class="nav filter">
                <i class="nav-link fa-solid fa-filter"></i>
                <a class="nav-link" href="#">Latest</a>
                <a class="nav-link" href="#">Price: Low to High</a>
                <a class="nav-link" href="#">Price: High to Low</a>
                <a class="nav-link" href="#">Rate</a>
            </nav>
        </div>
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
