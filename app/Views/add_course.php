<section class="jumbotron text-left">
    <div class="container">
        <h2>Add Course</h2>
    </div>
</section>
<section class="course-sec">
    <div class="container">
        <form method="post" action="<?php echo base_url('course/addCourse'); ?>" enctype="multipart/form-data">
            <div class="form-group" style='color:red;'>
                <?php if (isset($error)): ?>
                    <?php echo $error; ?>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="filename1">File 1</label>
                <input type="file" class="form-control-file" id="filename" name="filenames[]" multiple>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select class="form-control" id="category" name="category_id" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="course_brief">Course Brief</label>
                <textarea class="form-control" id="course_brief" name="course_brief" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="num_of_chapter">Number of Chapters</label>
                <input type="number" class="form-control" id="num_of_chapter" name="num_of_chapter" required>
            </div>
            <div class="form-group">
                <label for="course_fee">Course Fee</label>
                <input type="number" class="form-control" id="course_fee" name="course_fee" required>
            </div>
            <div class="form-group">
                <label for="summary">Summary</label>
                <textarea class="form-control" id="summary" name="summary" rows="3"></textarea>
            </div>
            <div class="form-group">
                <div class="g-recaptcha" data-sitekey="6LcgoeglAAAAAJu8tLfukGu9LCSgdySC5ThaRxek"></div>
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            </div>
            <button type="submit" class="btn btn-primary">Add Course</button>
        </form>
    </div>
</section>
