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
    global $emo_ewpu_plugin_url;
    if($hook == 'toplevel_page_emo_ewpu_slug'){
        wp_register_style('emo_ewpu_admin_style',$emo_ewpu_plugin_url.'assets/css/style.admin.css', array(), '1.0.0', 'all');
        wp_enqueue_style('emo_ewpu_admin_style');
    }
}
