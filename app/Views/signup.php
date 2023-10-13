<section class="login-sec">
    <div class="login-form">
    <?php echo form_open(base_url() . 'signup/check_signup'); ?>
        <h2>Hello</h2>
        <div class="form-group">
            <label for="exampleInputUserid">Username</label>
            <input name ='user_id' type="text" class="form-control" id="exampleInputUserid" required="required">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input name = 'email' type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required="required">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input name = 'password' type="password" class="form-control" id="exampleInputPassword1" required="required">
        </div>
        <div class="form-group">
		<?php if (isset($error)): ?>
        	<?php echo $error; ?>
    	<?php endif; ?>
        </div>
        <button type="submit" class="btn btn-dark col-12">Sign up</button>
        <a style='visibility:hidden;' href="#">Forget password</a>
        <a style='visibility:hidden;' href="#">Don't have an account</a>
    <?php echo form_close(); ?>
    </div>
</section>