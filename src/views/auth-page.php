<?php

$scripts = '<script  src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>';
$pageName = "auth-page";
$title = "Login/Signup";
 include 'pageTemplate/head.php' ?>
<header>
    <a href="<?php echo __BASEPATH__ ?>" id="logo"><h1>C&nbsp&nbsp&nbspke Master</h1></a>
</header>
<div id="wrapper">
    <main>
        <div id="modal-overlay"></div>
        <div id="loader"></div>
        <?php include 'components/message-modal.php' ?>
        <div id="login-form-container">
            <form action="" id="login-form">
                <h2>Login</h2>
                <div class="input-group">
                    <label for="username">username:</label>
                    <input type="text" name="username" id="username">
                </div>
                <div class="input-group">
                    <label for="password">password:</label>
                    <input type="password" name="password" id="password">
                </div>
                <input type="submit">
            </form>


            <form action="" id="signup-form">
                <h2>Signup</h2>

                <div class="input-group">
                    <label for="fullname">Full Name:</label>
                    <input type="text" name="fullname" id="fullname">
                </div>

                <div class="input-group">
                    <label for="nickname">Nickname:</label>
                    <input type="text" name="nickname" id="nickname">
                </div>

                <div class="input-group">
                    <label for="susername">Username:</label>
                    <input type="text" name="username" id="susername">
                </div>

                <div class="input-group">
                    <label for="spassword">Password:</label>
                    <input type="password" name="password" id="spassword">
                </div>

                <div class="input-group">
                    <label for="confirmpassword">Confirm Password:</label>
                    <input type="password" name="confirmpassword" id="confirmpassword">
                </div>

                <div class="preferences">
                    <div class="preferences-group">
                        <h3>Limitations</h3>
                        <?php
                        $count = 0;
                        foreach ($tags as $tag) {
                            if ($tag["type"] == "limitation" && $count <4) {
                                $count ++ ;
                                echo '
                                <label>
                            <input type="checkbox"  value="' . $tag["id"] . '"  name="' . $tag["name"] . '">
                           ' . $tag["name"] . '
                                </label>
                                
                                ';
                            }
                        }
                        ?>

                    </div>

                    <div class="preferences-group">
                        <h3>Preferences</h3>
                        <?php
                        $count = 0;
                        foreach ($tags as $tag) {
                            if ($tag["type"] == "preference" && $count <4) {
                                $count ++ ;
                                echo '
                                <label>
                            <input type="checkbox"  value="' . $tag["id"] . '"  name="' . $tag["name"] . '">
                           ' . $tag["name"] . '
                                </label>
                                
                                ';
                            }
                        }
                        ?>

                    </div>
                </div>
                <input type="submit" value="Signup">
            </form>
            <button id="switch-login-btn">Switch to Signup</button>
        </div>


    </main>
</div>

<?php include('pageTemplate/footer.php') ?>
