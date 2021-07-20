<?php
$pageName = "error-page";
http_response_code(500);
$title = "DB Error";
include('pageTemplate/head.php') ?>
<header>
    <a href="<?php echo __BASEPATH__ ?>" id="logo"><h1>C&nbsp&nbsp&nbspke Master</h1></a>
</header>
<div id="wrapper" class="error-page">

    <main>
        <div class="mainbox">
            <div class="error-container">
                <h2 class="err">5</h2>
                <i class="err far fa-question-circle fa-spin"></i>
                <i class="err far fa-question-circle fa-spin"></i>
            </div>
            <div class="msg"><?php if(isset($error))echo $error?></p></div>
        </div>
        <?php include('pageTemplate/footer.php') ?>
