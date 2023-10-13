<section class="login-sec">
    <div class="login-form">
    <h2>Reset Password</h2>
    <!-- <form method="post" action="<?= base_url('login/reset_password') ?>"> -->
    <form method="post" action="<?= base_url(route_to('reset_password')) ?>">
        <input type="hidden" name="token" value="<?= $token ?>">
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your new password" required>
        </div>
        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>

    </div>
</section>
