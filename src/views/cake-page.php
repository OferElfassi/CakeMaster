<?php

$styleSheets = '<link rel="stylesheet" type="text/css" href="public/assets/jquery-comments.css">';
$scripts = '<script  src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="public/assets/jquery.star-rating-svg.min.js"></script>
    <script type="text/javascript" src="public/assets/jquery-comments.js"></script>
    ';
$pageName = "cake-page";
$cakePage = true;
$userId = -1;
$userFullname = "";
$cake = array();
if (isset($_SESSION["user"])) {
    $userId = $_SESSION["user"]["id"];
    $userFullname = $_SESSION["user"]["fullname"];
}

if (isset($_cakes[0])) {
    $cake = $_cakes[0];
}
 include 'pageTemplate/head.php';
 include 'pageTemplate/header.php';
 if (isset($_is_loggedIn) && $_is_loggedIn) include 'components/update-info-modal.php';
 include 'components/message-modal.php' ?>


<section id="actions">
    <?php
    if ($userId == $cake["UserId"])
        echo "<div class='action-btn' id='edit-cake-btn'><p>Edit Cake</p></div>"
    ?>
    <a href="<?php echo __BASEPATH__ . "/BakingPage?cakeId=" . $cake["id"] ?>" class="action-btn"><p>Baking page</p></a>
</section>
<button id="delete-cake" class="btn btn-alert">Delete this cake <i class="far fa-trash-alt"></i></button>

<section id="recipe-info">
    <section class="side">

        <section>
            <div><p><b id="cake-bakingTime-value"><?php echo $cake["bakingTime"] ?></b>
                <div class="edit-container">
                    <form class="edit-input" id="cake-bakingTime-input">
                        <input type="number" min="1" name="bakingTime" value="<?php echo $cake["bakingTime"] ?>">
                    </form>
                    <div class="edit-btns-container">
                        <i class="fas fa-edit edit-field-btn" id="cake-bakingTime-submit"
                           data-for="cake-bakingTime"></i>
                        <i class="fas fa-window-close " id="cake-bakingTime-cancel" data-for="cake-bakingTime"></i>
                    </div>
                </div>
                </p>
            </div>
            <h4>Minutes</h4>
        </section>
        <section>


            <div><p><b id="cake-difficulty-value"><?php
                        switch ($cake["difficulty"]) {
                            case 1:
                                echo "Easy";
                                break;
                            case 2:
                                echo "Medium";
                                break;
                            case 3:
                                echo "Hard";
                        }
                        ?></b>
                <div class="edit-container">
                    <form class="edit-input" id="cake-difficulty-input">
                        <select name="difficulty">
                            <option value="1">Easy</option>
                            <option value="2">Medium</option>
                            <option value="3">Hard</option>
                        </select>
                    </form>

                    <div class="edit-btns-container">
                        <i class="fas fa-edit edit-field-btn" id="cake-difficulty-submit"
                           data-for="cake-difficulty"></i>
                        <i class="fas fa-window-close " id="cake-difficulty-cancel" data-for="cake-difficulty"></i>
                    </div>
                </div>
                </p></div>


            <h4>Difficulty</h4>
        </section>
        <section>

            <div><p><b id="cake-servings-value"><?php echo $cake["servings"] ?></b>
                <div class="edit-container">
                    <form class="edit-input" id="cake-servings-input">
                        <input type="text" name="servings" value="<?php echo $cake["servings"] ?>"/>
                    </form>
                    <div class="edit-btns-container">
                        <i class="fas fa-edit edit-field-btn" id="cake-servings-submit" data-for="cake-servings"></i>
                        <i class="fas fa-window-close " id="cake-servings-cancel" data-for="cake-servings"></i>
                    </div>
                </div>
                </p></div>


            <h4>Servings</h4>
        </section>
    </section>

    <section class="content">
        <section class="title">
            <img id="title-image" src="<?php echo $cake["image"] ?>" alt="main cake">


            <h1>
                <b class="editable" id="cake-name-value"><?php echo $cake["name"] ?></b>
                <form class="edit-input" id="cake-name-input">
                            <textarea name="name" cols="10" rows="3"
                            ><?php echo $cake["name"] ?></textarea>
                </form>
                <div class="edit-btns-container">
                    <i class="fas fa-edit edit-field-btn" id="cake-name-submit" data-for="cake-name"></i>
                    <i class="fas fa-window-close " id="cake-name-cancel" data-for="cake-name"></i>
                </div>

            </h1>


            <div class="user-name">
                <i class="fas fa-user"></i>
                <h3><?php echo $cake["nickname"] ?></h3>
            </div>

        </section>

        <section class="details">

            <p id="cake-description-value"><?php echo $cake["description"] ?></p>
            <div class="edit-container">
                <form class="edit-input" id="cake-description-input">
                    <textarea required name="description" id="description" cols="50"
                              rows="3"><?php echo $cake["description"] ?></textarea>
                </form>
                <div class="edit-btns-container">
                    <i class="fas fa-edit edit-field-btn" id="cake-description-submit" data-for="cake-description"></i>
                    <i class="fas fa-window-close " id="cake-description-cancel" data-for="cake-description"></i>
                </div>
            </div>


            <a href="#recipe">Jump To Recipe <i class="fas fa-arrow-down"></i></a>
            <img id="main-image" src="<?php echo $cake["image"] ?>" alt="main cake">
        </section>

        <section class="recipe" id="recipe">
            <h2><?php echo $cake["name"] . " Recipe" ?> </h2>
            <h3>Ingredients</h3>

            <div class="edit-container">
                <form class="edit-input" id="cake-ingredients-input">
                            <textarea name="ingredients" cols="35" rows="10"><?php
                                $ingredientsArr = explode("|", $cake["ingredients"]);
                                foreach ($ingredientsArr as $ingredient) {
                                    if (!empty($ingredient))
                                        echo "$ingredient" . "\xA";
                                }
                                ?>

                            </textarea>
                </form>
                <div class="edit-btns-container">
                    <i class="fas fa-edit edit-field-btn" id="cake-ingredients-submit" data-for="cake-ingredients"></i>
                    <i class="fas fa-window-close " id="cake-ingredients-cancel" data-for="cake-ingredients"></i>
                </div>
            </div>
            <ul id="cake-ingredients-value"><?php
                $ingredientsArr = explode("|", $cake["ingredients"]);
                foreach ($ingredientsArr as $ingredient) {
                    if (!empty($ingredient))
                        echo "<li>$ingredient</li>";
                }
                ?>
            </ul>


            <h3>Recipe</h3>


            <div class="edit-container">
                <form class="edit-input" id="cake-recipe-input">
                            <textarea name="recipe" cols="45" rows="10"><?php
                                $recipeArr = explode("|", $cake["recipe"]);
                                foreach ($recipeArr as $index => $recipeItem) {
                                    if (!empty($recipeItem))
                                        echo "$index)  $recipeItem" . "\xA";
                                }
                                ?>
                            </textarea>
                </form>
                <div class="edit-btns-container">
                    <i class="fas fa-edit edit-field-btn" id="cake-recipe-submit" data-for="cake-recipe"></i>
                    <i class="fas fa-window-close " id="cake-recipe-cancel" data-for="cake-recipe"></i>
                </div>
            </div>
            <ul id="cake-recipe-value">
                <?php
                $recipeArr = explode("|", $cake["recipe"]);
                foreach ($recipeArr as $recipeItem) {
                    if (!empty($recipeItem))
                        echo "<li>$recipeItem</li>";
                }
                ?>
            </ul>


        </section>
    </section>
</section>

<section class="related">
    <div class="section-title">
        <h3>Related</h3>
        <span class="line"></span>
    </div>
    <section class="horizontal-gallery">
        <?php
        foreach ($cake["related"] as $index => $related)
            if ($index < 4) {
                echo '<div><a href="' . __BASEPATH__ . '?cakeId=' . $related["id"] . '"><div><img src="' . $related["image"] . '" alt="Fruit cake"></a>
<div class="stars" data-rating="' . $related["rating"] . '" data-id="' . $related["id"] . '" data-path="' . __BASEPATH__ . '"></div>
</div></div>';
            }
        ?>
    </section>

</section>
<div class="section-title">
    <h3>Comments</h3>
    <span class="line"></span>
</div>
<div id="comments-container" data-userid="<?php echo $userId ?>" data-username="<?php echo $userFullname ?>"></div>

<?php include('pageTemplate/footer.php') ?>
