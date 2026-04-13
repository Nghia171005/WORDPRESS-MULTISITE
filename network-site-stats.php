<?php
/*
Plugin Name: Network Site Stats
Description: Thống kê các site trong Multisite
Network: true
*/

if (!defined('ABSPATH')) exit;

// Tạo menu trong Network Admin
add_action('network_admin_menu', function () {
    add_menu_page(
        'Site Stats',
        'Site Stats',
        'manage_network',
        'network-site-stats',
        'render_site_stats'
    );
});

// Hiển thị bảng thống kê
function render_site_stats() {
    ?>
    <div class="wrap">
        <h1>Network Site Stats</h1>
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Tên site</th>
                <th>URL</th>
                <th>Số bài viết</th>
                <th>Bài mới nhất</th>
            </tr>

            <?php
            $sites = get_sites();

            foreach ($sites as $site) {

                // chuyển sang site con
                switch_to_blog($site->blog_id);

                // đếm bài viết
                $post_count = wp_count_posts()->publish;

                // lấy bài mới nhất
                $last_post = get_posts([
                    'numberposts' => 1
                ]);

                $last_date = $last_post ? $last_post[0]->post_date : 'N/A';

                echo "<tr>";
                echo "<td>{$site->blog_id}</td>";
                echo "<td>" . get_bloginfo('name') . "</td>";
                echo "<td>" . get_site_url() . "</td>";
                echo "<td>{$post_count}</td>";
                echo "<td>{$last_date}</td>";
                echo "</tr>";

                // quay lại site chính
                restore_current_blog();
            }
            ?>
        </table>
    </div>
    <?php
}
