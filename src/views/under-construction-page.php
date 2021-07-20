<?php
$pageName = "error-page";
http_response_code(503);
$title = "Under construction";
include('pageTemplate/head.php') ?>
<header>
    <a href="<?php echo __BASEPATH__ ?>" id="logo"><h1>C&nbsp&nbsp&nbspke Master</h1></a>
</header>
<div id="wrapper" class="construction">
    <main>
        <div class="mainbox">
            <div class="error-container">
                <h2 class="err">This Page is Under</h2>
                <h2 class="err">C<i class="err  fas fa-tools fa-spin"></i>nstruction</h2>
            </div>
            <div class="msg">We are currently working on something awesome. Stay tuned!<p>Let's go <a href="<?php echo __BASEPATH__ ?>">home</a></p></div>
        </div>
        <?php include('pageTemplate/footer.php') ?>
