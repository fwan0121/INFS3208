
    <section class="profile-sec">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-3">
                    <div class="profile-img">
                        <img src="<?php echo base_url('writable/uploads/' . ($user['filename'] ?? 'default.png')); ?>" alt="Profile Image">
                    </div>
                    <h4 style="text-indent: 45px;"><?php echo $user['user_id'] ?? 'N/A'; ?></h4>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="#!">Profile</a>
                    </nav>                      
                </div>
                <div class="col-9">

                    <div class="col-8">
                    <?php echo form_open_multipart(base_url() . 'profile/update_profile'); ?>
                        
                        <div class="form-group">
                            <h5>Basic</h5>
                            <input name="fname" type="text" class="form-control" placeholder="First Name" value="<?php echo $user['first_name'] ?? ''; ?>"><br>
                            <input name="lname" type="text" class="form-control" placeholder="Last Name" value="<?php echo $user['last_name'] ?? ''; ?>"><br>
                            <input name="phone" type="phone" class="form-control"placeholder="Phone" value="<?php echo $user['phone'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <h5>Photo</h5>
                            <div id="drop-zone" class="drop-zone">
                                <label for="fileInput" class="drop-zone-label">Drag and drop a file or click to choose a profile image</label>
                                <input name="userfile" type="file" class="drop-zone-input" id="fileInput">
                            </div>
                        </div>


                        <div class="form-group">
                            <h5>Email</h5>
                            <input name="email" type="email" class="form-control" aria-describedby="emailHelp" placeholder="Email" value="<?php echo $user['email'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <h5>Password</h5>
                            <input type="password" class="form-control" name="password" placeholder="Enter Current Password"><br>
                            <input type="newpwd" class="form-control" name="newpwd" placeholder="Enter New Password">
                        </div>
                        <div class="form-group">
                            <?php if (isset($error)): ?>
                                <?php echo $error; ?>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
