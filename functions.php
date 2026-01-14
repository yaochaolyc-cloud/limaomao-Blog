<?php
/**
 * Theme Name: limaomao
 * Author: limaomao810.com
 */

defined('ABSPATH') or die('No script kiddies please!');

// 加载主题选项页面
require_once get_template_directory() . '/admin/theme-options.php';

// 注册导航菜单
function limaomao_register_menus() {
    register_nav_menus([
        'primary' => __('Primary Menu', 'limaomao')
    ]);
}
add_action('init', 'limaomao_register_menus');

// 加载前端资源
function limaomao_enqueue_assets() {
    wp_enqueue_style('limaomao-style', get_stylesheet_uri(), [], '1.1');
    wp_enqueue_script('limaomao-js', get_template_directory_uri() . '/assets/js/theme.js', ['jquery'], '1.1', true);
    wp_enqueue_script('social-share-js', get_template_directory_uri() . '/assets/js/social-share.js', [], '1.1', true);

    $options = get_option('limaomao_theme_options', []);
    wp_localize_script('limaomao-js', 'Limaomao', [
        'mode' => $options['color_mode'] ?? 'auto',
        'share_enabled' => !empty($options['enable_social_share']),
        'share_platforms' => !empty($options['social_platforms']) ? $options['social_platforms'] : []
    ]);
}
add_action('wp_enqueue_scripts', 'limaomao_enqueue_assets');

// 主题支持
function limaomao_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list']);
    add_theme_support('align-wide');
    add_theme_support('custom-logo', ['height' => 60, 'width' => 180]);
}
add_action('after_setup_theme', 'limaomao_setup');

// SEO & Favicon（兼容无 Yoast）
function limaomao_custom_head_meta() {
    $options = get_option('limaomao_theme_options', []);
    if (!empty($options['custom_meta'])) {
        echo "\n<!-- Custom Meta -->\n";
        echo wp_kses($options['custom_meta'], []) . "\n";
    }

    if (!class_exists('WPSEO_Frontend')) {
        $title = $options['seo_title'] ?? get_bloginfo('name');
        $desc = $options['seo_description'] ?? get_bloginfo('description');
        $keywords = $options['seo_keywords'] ?? '';

        echo '<title>' . esc_html($title) . '</title>' . "\n";
        echo '<meta name="description" content="' . esc_attr($desc) . '">' . "\n";
        if ($keywords) {
            echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'limaomao_custom_head_meta', 1);

function limaomao_favicon() {
    $options = get_option('limaomao_theme_options', []);
    if (!empty($options['favicon_url'])) {
        echo '<link rel="icon" href="' . esc_url($options['favicon_url']) . '" type="image/x-icon">' . "\n";
    }
}
add_action('wp_head', 'limaomao_favicon');

// 回到顶部
function limaomao_add_back_to_top() {
    echo '<button id="back-to-top" title="回到顶部">↑</button>';
}
add_action('wp_footer', 'limaomao_add_back_to_top');

// 注册通知栏选项
function limaomao_add_notice_field() {
    register_setting('limaomao_theme_options', 'notice_enabled');
    register_setting('limaomao_theme_options', 'notice_content');
}
add_action('admin_init', 'limaomao_add_notice_field');