<section class="login-sec">
    <div class="login-form">
    <?php echo form_open(base_url() . 'login/forgot_password'); ?>
        <h2>Forgot Password</h2>
        <div class="form-group">
            <label for="exampleInputUserid">Email</label>
            <input name ='forgetpassword' type="email" class="form-control" id="exampleInputUserid" required="required">
        </div>
        <div class="form-group">
        <div class="form-group">
            <?php if (isset($error)): ?>
                <?php echo $error; ?>
            <?php endif; ?>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary btn-dark col-12">Submit</button>
		</div>
    <?php echo form_close(); ?>
    </div>
</section>
