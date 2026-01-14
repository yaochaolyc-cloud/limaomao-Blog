<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site">

    <header class="site-header">
        <div class="header-inner">
            <?php if (has_custom_logo()) : ?>
                <div class="site-logo"><?php the_custom_logo(); ?></div>
            <?php else : ?>
                <h1 class="site-title">
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
                </h1>
            <?php endif; ?>

            <div class="header-right">
                <?php
                if (has_nav_menu('primary')) :
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'container'      => 'nav',
                        'container_class' => 'main-navigation',
                        'menu_class'     => 'nav-menu'
                    ]);
                endif;
                ?>
                <!-- 搜索框：直接显示，不再隐藏 -->
                <div class="search-box">
                    <?php get_search_form(); ?>
                </div>
            </div>
        </div>
    </header>

    <?php
    // === 全局通知栏：在 header 之后、main 之前 ===
    $notice_options = get_option('limaomao_theme_options', []);
    if (!empty($notice_options['notice_enabled']) && !empty($notice_options['notice_content'])):
    ?>
    <div class="global-notice">
        <div class="notice-inner">
            <?php echo wp_kses_post($notice_options['notice_content']); ?>
        </div>
    </div>
    <?php endif; ?>

    <main class="site-main">