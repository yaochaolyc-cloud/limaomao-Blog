<?php if (post_password_required()) return; ?>

<div id="comments" class="comments-area">
    <?php if (have_comments()): ?>
        <h3><?php printf(_n('1 条评论', '%1$s 条评论', get_comments_number(), 'limaomao'), number_format_i18n(get_comments_number())); ?></h3>
        <ol class="comment-list">
            <?php
            wp_list_comments([
                'style' => 'ol',
                'short_ping' => true,
                'avatar_size' => 40,
                'format' => 'html5',
                'callback' => function($comment, $args, $depth) {
                    $GLOBALS['comment'] = $comment;
                    ?>
                    <li id="comment-<?php comment_ID(); ?>" <?php comment_class('comment'); ?>>
                        <div class="comment-author vcard">
                            <?php echo get_avatar($comment, 40); ?>
                            <cite class="fn"><?php comment_author_link(); ?></cite>
                        </div>
                        <div class="comment-metadata">
                            <?php comment_date(); ?> · <?php comment_time(); ?>
                        </div>
                        <div class="comment-content">
                            <?php comment_text(); ?>
                        </div>
                    </li>
                    <?php
                }
            ]);
            ?>
        </ol>
    <?php endif; ?>

    <?php if (comments_open()): ?>
        <div id="respond" class="comment-respond">
            <h3 id="reply-title" class="comment-reply-title">发表评论</h3>
            <?php comment_form([
                'title_reply' => '',
                'comment_notes_before' => '',
                'comment_notes_after' => '',
                'label_submit' => '提交评论',
                'class_submit' => 'submit'
            ]); ?>
        </div>
    <?php endif; ?>
</div>