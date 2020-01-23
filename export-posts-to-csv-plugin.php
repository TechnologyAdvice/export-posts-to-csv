<?php
   /*
   Plugin Name: Export Posts to CSV
   description: Adds button to 'All Posts' page to export post titles, URLs, Category, and Published Date to a CSV file with one click
   Version: 1.0.0
   Author: Dan
   Author URI: http://technologyadvice.com
   License: GPL2
   GitHub Plugin URI: https://github.com/warofthesun/export-posts-to-csv
   */
?>
<?php

add_action( 'manage_posts_extra_tablenav', 'admin_post_list_top_export_button', 20, 1 );
function admin_post_list_top_export_button( $which ) {
    global $typenow;

    if ( 'post' === $typenow && 'top' === $which ) {
        ?>
        <input type="submit" name="export_all_posts" id="export_all_posts" class="button button-primary" value="Export All Posts to CSV" />
        <?php
    }
}

add_action( 'init', 'func_export_all_posts' );
function func_export_all_posts() {
    if(isset($_GET['export_all_posts'])) {
        $arg = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => -1,
            );

        global $post;
        $arr_post = get_posts($arg);
        if ($arr_post) {

            header('Content-type: text/csv');
            header('Content-Disposition: attachment; filename="ta_post_export.csv"');
            header('Pragma: no-cache');
            header('Expires: 0');

            $file = fopen('php://output', 'w');

            fputcsv($file, array('Post Title', 'URL', 'Post Category', 'Publish Date'));

            foreach ($arr_post as $post) {
                setup_postdata($post);
                $category = get_the_category();
                $firstCategory = $category[0]->cat_name;
                fputcsv($file, array(get_the_title(), get_the_permalink(), $firstCategory, get_the_date()));
            }

            exit();
        }
    }
}

?>
