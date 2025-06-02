<?php

add_action('init', 'tld_acf_blocks_init');
function tld_acf_blocks_init(): void
{
    if (function_exists('acf_register_block_type')) {
        /** ==============================
         *
         * About Intro Block v1
         * ============================== */
        acf_register_block_type(array(
            'name' => 'about-intro-block-v1',
            'title' => 'About Intro Block v1',
            'description' => 'About section 1.',
            'category' => 'custom_theme',
            'mode' => 'preview',
            'supports' => array('align' => true, 'mode' => false, 'jsx' => true, 'anchor' => true),
            'render_template' => 'blocks/about-intro-block-v1/about-intro-block-v1.php',));
    }
}