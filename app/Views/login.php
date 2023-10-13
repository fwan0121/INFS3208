<section class="login-sec">
    <div class="login-form">
    <?php echo form_open(base_url() . 'login/check_login'); ?>
        <h2>Login</h2>
        <div class="form-group">
            <label for="exampleInputUserid">Username</label>
            <input name ='user_id' type="text" class="form-control" id="exampleInputUserid" required="required" value="<?php echo isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : ''; ?>">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input name = 'password' type="password" class="form-control" id="exampleInputPassword1" required="required" value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['password'] : ''; ?>">
        </div>
        <div class="form-group">
            <?php if (isset($error)): ?>
                <?php echo $error; ?>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-dark col-12">Log in</button>
        </div>
        <div class="clearfix">
            <label class="float-left form-check-label">
                <input type="checkbox" <?php echo isset($_COOKIE['remember_token']) ? 'checked' : ''; ?> name="remember" > Remember me
            </label>
            <input type="hidden" name="remember_token" value="<?php echo isset($_COOKIE['remember_token']) ? $_COOKIE['remember_token'] : ''; ?>">
            <a href="<?php echo base_url(); ?>login/forgot_page" class="float-right">Forgot Password?</a>

            <a href="<?php echo base_url(); ?>signup" class="float-right">Don't have an account</a>

        </div>
    <?php echo form_close(); ?>
    </div>
    <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Forgot Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo form_open(base_url() . 'login/forgot_password'); ?>
                    <div class="form-group">
                        <label for="inputPassword">Please input your email to reset your password</label>
                        <input name="forgetpassword" type="text" class="form-control" id="inputPassword">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</section>

