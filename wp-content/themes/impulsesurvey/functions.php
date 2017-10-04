<?php
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}

// add_filter('wp_nav_menu_items', 'add_login_logout_link', 10, 2);
// function add_login_logout_link($items, $args) {
//         ob_start();
//         wp_loginout('index.php');
//         $loginoutlink = ob_get_contents();
//         ob_end_clean();
//         $items .= '<li>'. $loginoutlink .'</li>';
//     return $items;
// }
?>