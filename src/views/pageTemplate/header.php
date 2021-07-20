<?php
$userId = 0;
if (isset($_SESSION["user"])) {
    $userId = $_SESSION["user"]["id"];
}

?>
<header>
    <div id="hamburger">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <a href="<?php echo __BASEPATH__?>" id="logo"><h1>C&nbsp&nbsp&nbspke Master</h1></a>
    <nav>
        <ul>
<!--            <li  class="selected"><a href="index.html">Home</a></li>-->
            <?php

            if (isset($_is_loggedIn)&&isset($title) && $_is_loggedIn) {

                echo '
                 <li class="' . ($title =="Home"?"selected":"") . '"><a href="' . __BASEPATH__ . '">Home</a></li>
                 <li class="' . ($title =="ForYou"?"selected":"") . '"><a href="' . __BASEPATH__ ."/ForYou". '">Just For You</a></li>
                 <li class="' . ($title =="MyCakes"?"selected":"") . '"><a href="' . __BASEPATH__ ."/MyCakes". '">My Cakes</a></li>
                 <li class="' . ($title =="Dashboard"?"selected":"") . '"><a href="' . __BASEPATH__ ."/Dashboard". '">Cake Dashboard</a></li>
                ';
            }
            ?>

        </ul>

        <form action="<?php echo __BASEPATH__ ?>/" method="get" id="search">
            <input type="text" name="search">
            <i class="fas fa-search"></i>
        </form>
<!--        <i class="fas fa-user" id="update-profile-btn">-->

        <div class="user-status">
            <?php
            if (isset($_is_loggedIn) && $_is_loggedIn) {
                echo '
<div class="avatar-initials" data-name="' .$_SESSION["user"]["fullname"] . '" id="update-profile-btn">
                               
                            </div>
                             <span class="update-profile-tooltip-text">Edit profile</span>
                            <h4>Hello ' .$_SESSION["user"]["fullname"] . '</h4>
                            <a href="' . __BASEPATH__ . '/auth/logout">
                            <i class="fas fa-sign-out-alt logout-btn">
                                <span  class="sign-out-tooltip-text" id="logout-btn">LogOut</span>
                            </i></a>';
            } else {
                echo '<span id="login-page-btn"><a href="' . __BASEPATH__ . '/auth"><h4>Signup/Login</h4></a></span>';
            }
            ?>
        </div>

    </nav>
    <div class="min-action-btns">
        <?php
        if(isset($homePage) && $homePage && isset($_is_loggedIn) && $_is_loggedIn ){
            echo '
            <a href="' . __BASEPATH__ . '/Dashboard"><div id="btn1"><b>Draw Cake</b></div></a>
        <a href="' . __BASEPATH__ . '/CakeMaker"><div id="btn2"><b>Automatic Cake Maker</b></div></a>
            ';
        }else if(isset($cakePage) && $cakePage && isset($cake["id"])){
            if( $userId == $cake["UserId"]){
                echo '<div id="btn1"><b>Edit Cake</b></div>';
            }
            echo '
        <a href="' . __BASEPATH__ . '/BakingPage?cakeId=' . $cake["id"] . '"><div id="btn2"><b>Baking Page</b></div></a>
            ';
        }else{
            echo '
            <a href="' . __BASEPATH__ . '"><div id="btn1"><b>Back To Home</b></div></a>
            <div id="btn1"><b></b></div>
       
            ';
        }

        ?>
<!--        <div id="btn1"><b>Quick start</b></div>-->
<!--        <div id="btn2"><b>Personalize your experience</b></div>-->
        <div id="btn3" class="min-action-nav-btn"><i class="fas fa-plus"></i></div>
    </div>
</header>
<div id="wrapper">
    <main>
        <div id="modal-overlay"></div>
        <div id="loader"></div>