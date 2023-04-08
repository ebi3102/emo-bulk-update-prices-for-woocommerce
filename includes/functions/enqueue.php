<?php

/**
 * @package EWTGP
 * ========================
 * ADMIN ENQUEUE FUNCTIONS
 * ========================
 * Text Domain: emo_ewpu
 */


/*
 *Load styles and scripts
 *
 */
function emo_ewpu_scripts($hook){
    if($hook == 'toplevel_page_emo_ewpu_slug'){
        wp_register_style('emo_ewpu_admin_style', EMO_BUPW_URI . 'assets/css/style.admin.css', array(), '1.0.0', 'all');
        wp_enqueue_style('emo_ewpu_admin_style');
    }
}
