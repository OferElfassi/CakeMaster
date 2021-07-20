<?php

$scripts = '<script  src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.rawgit.com/nnattawat/flip/master/dist/jquery.flip.min.js"></script>
    <script src="public/assets/jquery.star-rating-svg.min.js"></script>
     
    
    ';
$homePage = true;
$pageName = "home-page";
include 'pageTemplate/head.php';
include 'pageTemplate/header.php';
if (isset($_is_loggedIn) && $_is_loggedIn) include 'components/update-info-modal.php';
include 'components/message-modal.php' ?>



    <?php if (isset($_is_loggedIn) && $_is_loggedIn)
        echo '<section id="actions"><a href="' . __BASEPATH__ . "/CakeMaker" . '" class="action-btn"><p>Automatic Cake Maker</p></a>
                <a href="' . __BASEPATH__ . "/Dashboard" . '" class="action-btn"><p>Draw New Cake</p></a></section>'

    ?>


<section id="object-gallery">

    <?php
    if (isset($_cakes))
        foreach ($_cakes as $cake) {

            echo '
                    <article class="obj-container" id="obj1">
                <a href="' . __BASEPATH__ . '?cakeId=' . $cake["id"] . '">
                        <section class="obj-card" >
                            <div class="front">

                                <img src="' . $cake["image"] . '" loading="lazy" alt="Cake image">
                                ';
            if (isset($cake["tags"])) {
                echo '<div class="badges">';
                foreach ($cake["tags"] as $tag) {
                    echo '<div data-id="' . $tag["id"] . '" class="badge"><h4>' . $tag["name"] . '</h4></div>';
                }
                echo '</div>';
            }

            echo '   </div>
                            <div class="back">
                                <img src="' . $cake["image3d"] . '" loading="lazy" alt="3d Cake image">
                            </div>
                        </section>
                    </a>
                    <section class="obj-info">
                        <div>
                            <div>
                                <i class="fas fa-user"></i>
                                <p>' . $cake["nickname"] . '</p>
                            </div>

                            <div class="stars" data-rating="' . $cake["rating"] . '" data-id="' . $cake["id"] . '" data-path="' . __BASEPATH__ . '"></div>
                        </div>
                        <div>
                            <h3>' . $cake["name"] . '</h3>
                            <span class="flip"></span>
                        </div>
                    </section>
                    
                </article>
                    ';
        }
    ?>


</section>

<?php include('pageTemplate/footer.php') ?>

