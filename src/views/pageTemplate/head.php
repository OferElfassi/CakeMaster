<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if (isset($title)) {
            echo "CakeMaster - " . $title;
        } else {
            echo 'CakeMaster';
        } ?></title>
    <link rel="icon" type="image/png" sizes="32x32" href="public/images/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
    <link rel="stylesheet" href="public/css/style.css">
    <?php if (isset($styleSheets)) echo $styleSheets ?>
    <?php if (isset($scripts)) echo $scripts ?>
    <?php if (isset($pageName) && $pageName != "error-page") echo '
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    <script src="public/js/functions.js" ></script>
    <script src="public/js/main.js"></script>
    ' ?>
    <script>window.basePath = "<?php echo __BASEPATH__ ?>"</script>
    <?php if (isset($_is_loggedIn) && $_is_loggedIn) {
        echo '<script>window.isLoggedIn = true </script>';
    } else {
        echo '<script>window.isLoggedIn = false </script>';
    }
    ?>
</head>
<body id="<?php if (isset($pageName)) echo $pageName ?>">


