<?php

$pageName = "error-page";
http_response_code(404);
$title = "Not found";
include('pageTemplate/head.php') ?>
<header>
    <a href="<?php echo __BASEPATH__ ?>" id="logo"><h1>C&nbsp&nbsp&nbspke Master</h1></a>
</header>
<div id="wrapper" class="error-page">
    <main>
        <div class="mainbox">
            <div class="error-container">
                <h2 class="err">4</h2>
                <i class="err far fa-question-circle fa-spin"></i>
                <h2 class="err">4</h2>
            </div>
            <div class="msg">Maybe this page moved? Got deleted? Is hiding out in quarantine? Never existed in the first place?<p>Let's go <a href="<?php echo __BASEPATH__ ?>">home</a> and try from there.</p></div>
        </div>
<?php include('pageTemplate/footer.php') ?>
