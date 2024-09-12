<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php wp_head(); ?>

</head>

<body>
    <header class="site-header">
        <div class="container">
            <h1 class="school-logo-text float-left">
                <?php
        $sitename = get_bloginfo('title');
        $fictional = explode(" ", $sitename);
        ?>
                <!-- <a href="#"><strong><?php echo $fictional[0]; ?></strong> <?php echo $fictional[1]; ?></a> -->
            </h1>

            <span class="js-search-trigger site-header__search-trigger"><i class="fa fa-search"
                    aria-hidden="true"></i></span>
            <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
            <div class="site-header__menu group menu-container">
                <?php
        wp_nav_menu(array(
          'theme_location' => 'primary_menu',
          'container'            => 'nav',
          'container_class'      => 'main-navigation',

        ));

        ?>
                <div class="site-header__util">
                    <a href="#" class="btn btn--small btn--orange float-left push-right">Login</a>
                    <a href="#" class="btn btn--small btn--dark-orange float-left">Sign Up</a>
                    <span class="search-trigger js-search-trigger"><i class="fa fa-search"
                            aria-hidden="true"></i></span>
                </div>
            </div>
        </div>
        <div class="overlay"></div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuTrigger = document.querySelector('.site-header__menu-trigger');
            const body = document.querySelector('body');

            menuTrigger.addEventListener('click', function() {
                body.classList.toggle('no-scroll');
                body.classList.toggle('mobile-menu-active'); // Add this line
            });
        });
        </script>
    </header>
</body>

</html>