<section class="login-sec">
    <div class="login-form">
        <h2>Email Verification</h2>
        <?php if($verification_successful): ?>
            <div class="alert alert-success" role="alert">Your email has been verified successfully. You can now log in.</div>
        <?php else: ?>
            <div class="alert alert-danger" role="alert">Invalid verification code or email.</div>
        <?php endif; ?>
    </div>
</section>
