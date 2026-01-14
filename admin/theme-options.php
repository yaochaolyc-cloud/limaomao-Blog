<?php
// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 添加主题设置菜单
function limaomao_add_admin_menu() {
    add_theme_page(
        'Limaomao 主题设置',
        'Limaomao 设置',
        'manage_options',
        'limaomao-theme-options',
        'limaomao_options_page'
    );
}
add_action('admin_menu', 'limaomao_add_admin_menu');

// 保存设置
function limaomao_save_options() {
    if (!current_user_can('manage_options')) {
        return;
    }
    if (!isset($_POST['limaomao_nonce']) || !wp_verify_nonce($_POST['limaomao_nonce'], 'limaomao_save')) {
        return;
    }

    $options = [];

    // 原有字段
    $fields = [
        'seo_title', 'seo_description', 'seo_keywords',
        'footer_copyright', 'custom_meta', 'favicon_url',
        'color_mode', 'enable_social_share'
    ];

    foreach ($fields as $field) {
        $options[$field] = isset($_POST[$field]) ? sanitize_text_field($_POST[$field]) : '';
    }

    // 社交平台（多选）
    $platforms = [];
    $valid_platforms = ['weibo', 'wechat', 'qq', 'twitter', 'facebook'];
    if (!empty($_POST['social_platforms']) && is_array($_POST['social_platforms'])) {
        foreach ($_POST['social_platforms'] as $p) {
            if (in_array($p, $valid_platforms)) {
                $platforms[] = sanitize_text_field($p);
            }
        }
    }
    $options['social_platforms'] = $platforms;

    // === 新增：全局通知栏字段 ===
    $options['notice_enabled'] = !empty($_POST['notice_enabled']) ? 1 : 0;
    // 允许简单 HTML（链接、图标等）
    $options['notice_content'] = !empty($_POST['notice_content']) ? wp_kses_post($_POST['notice_content']) : '';

    update_option('limaomao_theme_options', $options);

    // 成功提示
    add_action('admin_notices', function () {
        echo '<div class="notice notice-success is-dismissible"><p><strong>设置已保存！</strong></p></div>';
    });
}
add_action('admin_init', 'limaomao_save_options');

// 渲染设置页面
function limaomao_options_page() {
    $options = get_option('limaomao_theme_options', []);
    ?>
    <div class="wrap">
        <h1>Limaomao 主题设置</h1>
        <form method="post">
            <?php wp_nonce_field('limaomao_save', 'limaomao_nonce'); ?>

            <!-- 基本设置 -->
            <h2>基本设置</h2>
            <table class="form-table">
                <tr>
                    <th>站点标题（SEO）</th>
                    <td><input type="text" name="seo_title" value="<?php echo esc_attr($options['seo_title'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>站点描述（SEO）</th>
                    <td><textarea name="seo_description" rows="3" class="regular-text"><?php echo esc_textarea($options['seo_description'] ?? ''); ?></textarea></td>
                </tr>
                <tr>
                    <th>关键词（SEO）</th>
                    <td><input type="text" name="seo_keywords" value="<?php echo esc_attr($options['seo_keywords'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>Favicon URL</th>
                    <td><input type="url" name="favicon_url" value="<?php echo esc_url($options['favicon_url'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>自定义 Head Meta</th>
                    <td><textarea name="custom_meta" rows="4" class="large-text"><?php echo esc_textarea($options['custom_meta'] ?? ''); ?></textarea><br><small>例如百度验证代码</small></td>
                </tr>
            </table>

            <!-- 外观 -->
            <h2>外观</h2>
            <table class="form-table">
                <tr>
                    <th>颜色模式</th>
                    <td>
                        <select name="color_mode">
                            <option value="auto" <?php selected($options['color_mode'] ?? 'auto', 'auto'); ?>>自动（系统或时间）</option>
                            <option value="light" <?php selected($options['color_mode'] ?? 'auto', 'light'); ?>>日间模式</option>
                            <option value="dark" <?php selected($options['color_mode'] ?? 'auto', 'dark'); ?>>夜间模式</option>
                        </select>
                    </td>
                </tr>
            </table>

            <!-- 社交分享 -->
            <h2>社交分享</h2>
            <table class="form-table">
                <tr>
                    <th><label><input type="checkbox" name="enable_social_share" value="1" <?php checked(!empty($options['enable_social_share'])); ?>> 启用社交分享按钮</label></th>
                    <td></td>
                </tr>
                <tr>
                    <th>选择平台</th>
                    <td>
                        <?php
                        $selected = $options['social_platforms'] ?? [];
                        $platforms = [
                            'weibo' => '微博',
                            'wechat' => '微信',
                            'qq' => 'QQ',
                            'twitter' => 'Twitter',
                            'facebook' => 'Facebook'
                        ];
                        foreach ($platforms as $key => $label) {
                            echo '<label style="margin-right:15px;"><input type="checkbox" name="social_platforms[]" value="' . esc_attr($key) . '"' . (in_array($key, $selected) ? ' checked' : '') . '> ' . esc_html($label) . '</label>';
                        }
                        ?>
                    </td>
                </tr>
            </table>

            <!-- 页脚 -->
            <h2>页脚</h2>
            <table class="form-table">
                <tr>
                    <th>版权信息</th>
                    <td>
                        <textarea name="footer_copyright" rows="3" class="large-text"><?php echo esc_textarea($options['footer_copyright'] ?? '&copy; ' . date('Y') . ' <a href="https://limaomao810.com">limaomao810.com</a> 版权所有。'); ?></textarea>
                        <br><small>支持 HTML，例如链接</small>
                    </td>
                </tr>
            </table>

            <!-- === 新增：全局通知栏 === -->
            <h2>全局通知栏</h2>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label>
                            <input type="checkbox" name="notice_enabled" value="1" <?php checked(!empty($options['notice_enabled'])); ?>>
                            启用通知栏
                        </label>
                    </th>
                    <td>
                        <p class="description">启用后，将在导航栏下方显示一条灰色通知横幅。</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">通知内容</th>
                    <td>
                        <textarea name="notice_content" rows="3" class="large-text"><?php echo esc_textarea($options['notice_content'] ?? ''); ?></textarea>
                        <br><small>支持 HTML，例如：<code>&lt;a href="#"&gt;点击这里&lt;/a&gt; 或 &lt;span&gt;📢 重要通知&lt;/span&gt;</code></small>
                    </td>
                </tr>
            </table>

            <?php submit_button('保存设置'); ?>
        </form>
    </div>
    <?php
}
