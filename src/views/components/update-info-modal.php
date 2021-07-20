<section class="modal update-profile" id="update-profile-modal">
    <div class="modal-header">
        <h3>Update Profile</h3>
        <i class="far fa-window-close" id="close-modal-btn"></i>
    </div>
    <div class="modal-body">
        <button class="accordion">Update personal info <i class="fas fa-plus"></i></button>
        <div class="panel">
            <form id="personal-info-form">
                <div class="form-input">
                    <label for="fullname">Full Name:</label>
                    <input type="text" name="fullname" id="fullname"
                           value="<?php echo $_SESSION["user"]["fullname"] ?>">
                </div>

                <div class="form-input">
                    <label for="nickname">Nickname:</label>
                    <input type="text" name="nickname" id="nickname"
                           value="<?php echo $_SESSION["user"]["nickname"] ?>">
                </div>

                <input type="submit" class="btn modal-btn btn-success">
            </form>
        </div>

        <button class="accordion">Update preferences <i class="fas fa-plus"></i></button>
        <div class="panel">
            <form id="preferences-form">
                <div>
                <?php
                foreach ($tags as $tag) {
                    $checked = "";
                    if (!is_bool(array_search($tag["id"], array_column($_SESSION["user"]["tags"], 'TagId')))) {
                        $checked = 'checked';
                    }
                    echo '
                    
                    <div class="form-input">
                    <input type="checkbox" id="' . $tag["name"] . '" name="' . $tag["name"] . '" value="' . $tag["id"] . '"' . "$checked" . '
                       >
                    <label for="' . $tag["name"] . '"> ' . $tag["name"] . '</label><br>
                    </div>
                    ';
                }
                ?>
                </div>
                <input type="submit" class="btn modal-btn btn-success">
            </form>
        </div>

        <button class="accordion">Change Password <i class="fas fa-plus"></i></button>
        <div class="panel">
            <form id="change-password-form">
                <div class="form-input">
                    <label for="password">New Password:</label>
                    <input type="password" name="password" id="password">
                </div>

                <div class="form-input">
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" name="confirmPassword" id="confirmPassword">
                </div>

                <input type="submit" class="btn modal-btn btn-success">
            </form>
        </div>
    </div>
    <button class="btn btn-alert" id="delete-account-btn" data-userid="<?php echo $_SESSION["user"]["id"] ?>">Delete my account</button>
</section>