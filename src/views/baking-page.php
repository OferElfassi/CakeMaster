<?php

$scripts = '<script  src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>';
$pageName = "baking-page";
$cake = array();
if(isset($_cakes[0]))
    $cake = $_cakes[0];
include 'pageTemplate/head.php';
include 'pageTemplate/header.php';
if (isset($_is_loggedIn)&&$_is_loggedIn) include 'components/update-info-modal.php';
include 'components/message-modal.php' ?>
<section id="baking-details" class="object-details">
    <h1><?php echo $cake["name"] ?></h1>
    <img src="<?php echo $cake["image"] ?>" alt="">
    <div class="switch-btn"></div>
</section>
<section class="link-list" >
    <a href="#">
        <i class="fas fa-assistive-listening-systems"></i>
        <section>
            <h2>Virtual Assistant</h2>
            <p>Let's go bake! I'm right here with you all the way..</p>
        </section>
    </a>
    <a href="#">
        <i class="fas fa-birthday-cake"></i>
        <section>
            <h2>Find Bakery</h2>
            <p>Let's find a Bakery shop that will serve you your dessert</p>
        </section>
    </a>
    <a href="#">
        <i class="fas fa-share"></i>
        <section>
            <h2>Share</h2>
            <p>It's time to share the dessert with everyone!</p>
        </section>
    </a>
    <a href="#">
        <i class="fas fa-receipt"></i>
        <section>
            <h2>Ingredients</h2>
            <p>Wait, I want to check that I have all the products..</p>
        </section>
    </a>
</section>


<?php include('pageTemplate/footer.php') ?>
