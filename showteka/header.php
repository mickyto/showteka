<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<html>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo get_bloginfo( 'description' ); ?>">
    <meta name="author" content="">
    <title><?php wp_title( '|', true, 'right' ); ?></title>

    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/jqueryui/jquery-ui.min.js"></script>
    <script type="text/javascript"
            src="<?php bloginfo('template_directory'); ?>/fancybox/jquery.fancybox.pack.js"></script>

    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/fancybox/jquery.fancybox.css" type="text/css"
          media="screen"/>
    <script src="<?php bloginfo('template_directory');?>/fancybox/main.js?version=5"></script>
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/jqueryui/jquery-ui.min.css">


    <?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
<div id="wrapper">
    <div id="header-bg"></div>
    <div class="header-content">
        <div id="header">
            <div class="b-logo">
                <a href="/">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="logo" width="180">
                </a>
            </div>
            <div class="b-share g-fz-11 addthis_toolbox addthis_share_btn">
                <a href="http://addthis.com/bookmark.php?v=250&amp;pubid=xa-4d77205a25e06608"
                   class="g-a-tdn addthis_button_compact"><strong class="g-ff-c g-fz-18 g-a-fake">Поделиться</strong><br>
                    <span>или добавить в избранное</span>
                </a>
                <script type="text/javascript"
                        src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4d77205a25e06608"></script>
                <div class="atclear"></div>
            </div>
            <div class="b-cart g-fz-11" id="cart_block">
                <input id="item-count" type="hidden" value="<?php echo WC()->cart->get_cart_contents_count(); ?>">

                <script>
                    var countValue = document.getElementById('item-count').value;
                    if (countValue != 0) {
                        var countElement = document.createElement('P');
                        var count = document.createTextNode(countValue);
                        countElement.appendChild(count);
                        document.getElementById("cart_block").appendChild(countElement);

                        var linkElement = document.createElement('A');
                        var linkText = document.createTextNode('Перейти в корзину');
                        linkElement.setAttribute('href', '<?php echo wc_get_cart_url(); ?>');
                        linkElement.setAttribute('id', 'cart-link');
                        linkElement.appendChild(linkText);
                        document.getElementById("cart_block").appendChild(linkElement);
                    }
                    else {
                        document.getElementById("cart_block").innerHTML = 'Ваша корзина пуста';
                    }
                </script>
            </div>
            <div class="b-phones">
                <div class="b-phone"><strong class="g-fz-20 g-ff-c"><a href="#call-step-1" class="g-a-fake fb-modal">Заказать
                    звонок</a></strong></div>
                <br>
                <div class="b-phones-i g-ff-c"><a href="tel:+7-495-542-1312" class="g-fz-40 phone-number"><span
                        class="code">+7 495 </span>542-13-12</a></div>
                <div class="b-phones-i g-ff-c"><a href="tel:+7-495-542-1312" class="g-fz-40 phone-number"><span
                        class="code">+7 495 </span>542-13-12</a></div>
            </div>
            <div class="b-menu">
                <?php wp_nav_menu(array('sort_column' => 'menu_order', 'container_class' => 'menu-header')); ?>
            </div>

            <div class="header-actions">
                <a href="/" id="to-index">
                    <span class="btn_inner purple-b">
                        <span class="triangle"></span>
                        Вернуться к выбору мероприятий
                    </span>
                </a>
                <div class="action-form">
                    <form role="search" method="get" id="searchform" action="/" >
                        <input type="text" class="search-action" value="<?php get_search_query() ?>" placeholder="Название мероприятия" name="s" id="s" />
                        <button id="searchsubmit" >Поиск</button>
                    </form>
                </div>
                <div class="action-form">
                    <form action="" method="post">
                        <input type="text" placeholder="Выберите дату" id="datepicker-u"/>
                        <input type="text" hidden name="selected_date" id="datepicker"/>
                        <button>Поиск</button>
                    </form>
                    <script src="<?php bloginfo('template_directory'); ?>/scripts/datepicker.js"></script>
                </div>
            </div>

            <div style="display: none;">
                <div class="g-modal" id="call-step-1">
                    <?php echo do_shortcode('[ninja_form id=1]'); ?>
                </div>
            </div>
        </div>
        <script>
            if (window.location.pathname == '/') {
                $('#header-bg').css('height', 600);
                $('#to-index').css('display', 'none');
            }
            else {
                $('#header-bg').css('height', 340).css('box-shadow', 'inset 0px -320px 74px -207px #fff')
            }
        </script>
