<section class="login-sec">
    <div class="login-form">
    <?php echo form_open(base_url() . 'verify_email'); ?>
        <h2>Verify Email</h2>
        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required>
        </div>
        <div class="form-group">
            <label for="exampleInputVerificationCode">Verification Code</label>
            <input name="code" type="text" class="form-control" id="exampleInputVerificationCode" required>
        </div>
        <button type="submit" class="btn btn-dark col-12">Verify</button>
    <?php echo form_close(); ?>
    </div>
</section>


