<?php

$styleSheets = '<link rel="stylesheet" type="text/css" href="public/assets/jquery.progressbar.css">';
$scripts = '<script  src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="public/assets/jquery.progressbar.js"></script>
    ';
$pageName = "cake-maker-page";
$userId = -1;
if (isset($_SESSION["user"])) {
    $userId = $_SESSION["user"]["id"];
}
 include 'pageTemplate/head.php';
 include 'pageTemplate/header.php';
 if (isset($_is_loggedIn)&&$_is_loggedIn) include 'components/update-info-modal.php';
 include 'components/message-modal.php' ?>

        <div class="form-header">
            <h1>Personalize Your Experience</h1>
            <div id="steps-container">
                <div id="steps"></div>
            </div>
        </div>

        <form id="cake-maker-form" data-userid="<?php echo $userId?>" >
            <section id="questions1" class="questions ">
                <article id="q1">
                    <h3>Dessert purpose:</h3>
                    <label for="q1r1">
                        <input type="radio" checked id="q1r1" name="tags" value="birthday">Birthday</label>
                    <label for="q1r2">
                        <input type="radio" id="q1r2" name="tags" value="wedding">Wedding</label>
                    <label for="q1r3">
                        <input type="radio" id="q1r3" name="tags" value="party">Party</label>
                    <label for="q1r3">
                        <input type="radio" id="q1r4" name="tags" value="forFun">Just for Fun</label>
                </article>
                <article id="q2">
                    <h3>Difficulty:</h3>
                    <label for="q2r1">
                        <input type="radio" checked id="q2r1" name="difficulty" value="1">Easy</label>
                    <label for="q2r2">
                        <input type="radio" id="q2r2" name="difficulty" value="2">Medium</label>
                    <label for="q2r3">
                        <input type="radio" id="q2r3" name="difficulty" value="3">Hard</label>
                </article>
                <article id="q3">
                    <h3>Time:</h3>
                    <label for="q3r1">
                        <input type="radio" checked id="q3r1" name="bakingTime" value="60">1 Hour Max</label>
                    <label for="q3r2">
                        <input type="radio" id="q3r2" name="bakingTime" value="90">1.5 Hour Max</label>
                    <label for="q3r3">
                        <input type="radio" id="q3r3" name="bakingTime" value="120">Above 2 hours</label>
                </article>
            </section>
            <section id="questions2" class="questions hide_section">
                <article id="q4">
                    <h3>Restrictions:</h3>
                    <label for="q4c1">
                        <input type="checkbox" id="q4c1" name="restrictions" value="vegan">Vegan</label>


                    <label for="q4c4">
                        <input type="checkbox" id="q4c4" name="restrictions" value="lowCalories">Low Calories</label>
                    <label for="q4c5">
                        <input type="checkbox" id="q4c5" name="restrictions" value="pregnant">Pregnant</label>
                    <label for="q4c6">
                        <input type="checkbox" id="q4c6" name="restrictions" value="eggFree">Egg-Free</label>

                    <label for="q4c7">
                        <input type="checkbox" id="q4c7" name="restrictions" value="glutenFree">Gluten-Free</label>
                    <label for="q4c8">
                        <input type="checkbox" id="q4c8" name="restrictions" value="nutFree">Nut-Free</label>
                    <label for="q4c9">
                        <input type="checkbox" id="q4c9" name="restrictions" value="lactose">Lactose-Free</label>
                </article>
                <article id="q5">
                    <label for="otherRestriction">Other:</label>
                    <input type="text" name="otherRestriction" id="otherRestriction">
                </article>
            </section>
            <section id="questions3" class="questions hide_section">
                <article id="q6">
                    <h3>Choose Your Favorite Desserts</h3>
                    <div class="image-picker-container">

                        <div class="picker-item">
                            <h4>Pie</h4>
                            <div class="img-container">
                                <img class="img-selection" src="<?php echo __BASEPATH__."/public/images/pie.png"?>" id="pie" alt="pie">
                            </div>
                        </div>

                        <div class="picker-item">
                            <h4>Custards</h4>
                            <img class="img-selection"  src="<?php echo __BASEPATH__."/public/images/custards.jpg"?>" id="custard" alt="custards">
                        </div>

                        <div class="picker-item">
                            <h4>Cupcakes</h4>
                            <img class="img-selection"  src="<?php echo __BASEPATH__."/public/images/cupcakes.jpg"?>" id="cupcake" alt="cupcakes">
                        </div>

                        <div class="picker-item">
                            <h4>Frozen Desserts</h4>
                            <img class="img-selection"  src="<?php echo __BASEPATH__."/public/images/frozen-desserts.jpg"?>" id="frozen" alt="frozen">
                        </div>

                        <div class="picker-item">
                            <h4>Chocolate Cakes</h4>
                            <img class="img-selection"  src="<?php echo __BASEPATH__."/public/images/chocolate-cakes.jpg"?>" id="chocolate" alt="chocolate">
                        </div>

                        <div class="picker-item">
                            <h4>Pastries</h4>
                            <img class="img-selection"  src="<?php echo __BASEPATH__."/public/images/pastries.jpg"?>" id="pastries" alt="pastries">
                        </div>

                    </div>
                </article>

            </section>

        </form>
        <section class="navigation">
            <button id="back-btn"><i class="fas fa-arrow-left"></i>Back</button>
            <button id="next-btn">Next<i class="fas fa-arrow-right"></i></button>
        </section>

<?php include('pageTemplate/footer.php') ?>