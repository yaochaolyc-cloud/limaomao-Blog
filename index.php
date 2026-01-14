<?php get_header(); ?>

<main class="site-main">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="entry-meta">
                        <?php echo get_the_date(); ?> · 
                        <?php echo get_comments_number(); ?> 条评论
                    </div>
                </header>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <?php if (is_single()) : ?>
                    <?php
                    $options = get_option('limaomao_theme_options', []);
                    if (!empty($options['enable_social_share']) && !empty($options['social_platforms'])) :
                        $platforms = $options['social_platforms'];
                        ?>
                        <div class="social-share">
                            <span>分享：</span>
                            <?php foreach ($platforms as $p) : ?>
                                <a href="#" class="share-btn <?php echo esc_attr($p); ?>" data-type="<?php echo esc_attr($p); ?>">
                                    <?php
                                    $names = [
                                        'weibo' => '微博',
                                        'wechat' => '微信',
                                        'qq' => 'QQ',
                                        'twitter' => 'Twitter',
                                        'facebook' => 'Facebook'
                                    ];
                                    echo esc_html($names[$p] ?? ucfirst($p));
                                    ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php comments_template(); ?>
                <?php endif; ?>
            </article>
            <?php
        endwhile;
    else :
        echo '<p>暂无文章。</p>';
    endif;
    ?>
</main>

<footer class="site-footer">
    <?php
    $options = get_option('limaomao_theme_options', []);
    $copyright = $options['footer_copyright'] ?? '&copy; ' . date('Y') . ' <a href="https://limaomao810.com">limaomao810.com</a> 版权所有。';
    echo wp_kses_post($copyright);
    ?>
</footer>

<?php wp_footer(); ?>
</body>
</html>