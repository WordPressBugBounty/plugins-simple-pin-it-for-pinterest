<?php
/*
Plugin Name: Simple Pin It Button for Pinterest
Description: Simple Pin It Button for Pinterest is a lightweight WordPress plugin that allows you to add a "Pin It" button overlay to images in your posts. The button is fully customizable through the plugin settings page. Encourage your visitors to share your content on Pinterest with ease!
Version: 1.1
Author: Sohel Digital
Author URI: https://sohel.digital
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) exit;

function pinsavepinterest_enqueue_styles() {
    wp_enqueue_style(
        'pinsavepinterest-style',
        plugin_dir_url(__FILE__) . 'style.css',
        [],
        '1.0'
    );
}
add_action('wp_enqueue_scripts', 'pinsavepinterest_enqueue_styles');


function pinsavepinterest_enqueue_admin_scripts($hook) {
   
    if ($hook != 'settings_page_pin-it-or-save-it-button') {
        return;
    }
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_style('wp-color-picker');
    wp_add_inline_script('wp-color-picker', '
        (function($) {
            $(function() {
                $(".pinsavepinterest-color-field").wpColorPicker();
            });
        })(jQuery);
    ');
}
add_action('admin_enqueue_scripts', 'pinsavepinterest_enqueue_admin_scripts');


function pinsavepinterest_add_pin_it_button_to_images($content) {
    if (is_single()) {
        $pin_text = get_option('pinsavepinterest_pin_text', 'Save');
        $button_bg_color = get_option('pinsavepinterest_button_bg_color', '#dd0b0b');
        $font_color = get_option('pinsavepinterest_font_color', '#ffffff');
        $button_location = get_option('pinsavepinterest_button_location', 'top-left');
        $autohide = get_option('pinsavepinterest_autohide', false);

        
        $autohide_class = $autohide ? 'pinsavepinterest-autohide' : '';

        $pattern = '/<img(.*?)src=["\'](.*?)["\'](.*?)>/i';
        $content = preg_replace_callback($pattern, function ($matches) use ($pin_text, $button_bg_color, $font_color, $button_location, $autohide_class) {
            $image_url = $matches[2];
            $attachment_id = attachment_url_to_postid($image_url);
            $image_html = $attachment_id ? wp_get_attachment_image($attachment_id, 'full') : $matches[0];

            return '<div class="pin-it-container ' . esc_attr($autohide_class) . '" style="position:relative; display:inline-block;">' .
                $image_html .
                '<a class="pinsavepinterest-pin-it-button ' . esc_attr($button_location) . '" target="_blank" rel="noopener" title="' . esc_attr($pin_text) . '" 
                aria-label="' . esc_attr($pin_text) . '" href="https://pinterest.com/pin/create/bookmarklet/?media=' . esc_url($image_url) . '&url=' . get_permalink() . '&description=' . get_the_title() . '"
                style="background-color:' . esc_attr($button_bg_color) . '; color:' . esc_attr($font_color) . '; text-decoration:none; display:flex; align-items:center;">
                    <span class="pinsavepinterest-network-icon" style="display:flex; align-items:center; margin-right:5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="32" viewBox="0 0 23 32">
                            <path d="M0 10.656q0-1.92 0.672-3.616t1.856-2.976 2.72-2.208 3.296-1.408 3.616-0.448q2.816 0 5.248 1.184t3.936 3.456 1.504 5.12q0 1.728-0.32 3.36t-1.088 3.168-1.792 2.656-2.56 1.856-3.392 0.672q-1.216 0-2.4-0.576t-1.728-1.568q-0.16 0.704-0.48 2.016t-0.448 1.696-0.352 1.28-0.48 1.248-0.544 1.12-0.832 1.408-1.12 1.536l-0.224 0.096-0.16-0.192q-0.288-2.816-0.288-3.36 0-1.632 0.384-3.68t1.184-5.152 0.928-3.616q-0.576-1.152-0.576-3.008 0-1.504 0.928-2.784t2.368-1.312q1.088 0 1.696 0.736t0.608 1.824q0 1.184-0.768 3.392t-0.8 3.36q0 1.12 0.8 1.856t1.952 0.736q0.992 0 1.824-0.448t1.408-1.216 0.992-1.696 0.672-1.952 0.352-1.984 0.128-1.792q0-3.072-1.952-4.8t-5.12-1.728q-3.552 0-5.952 2.304t-2.4 5.856q0 0.8 0.224 1.536t0.48 1.152 0.48 0.832 0.224 0.544q0 0.48-0.256 1.28t-0.672 0.8q-0.032 0-0.288-0.032-0.928-0.288-1.632-0.992t-1.088-1.696-0.576-1.92-0.192-1.92z"></path>
                        </svg>
                    </span>
                    <span class="pinsavepinterest-pin-it-text" style="display:flex; align-items:center;">' . esc_html($pin_text) . '</span>
                </a>
            </div>';
        }, $content);
    }
    return $content;
}
add_filter('the_content', 'pinsavepinterest_add_pin_it_button_to_images');


function pinsavepinterest_create_menu() {
    add_options_page(
        'Simple Pin It Button',
        'Simple Pin It Button',
        'manage_options',
        'pin-it-or-save-it-button',
        'pinsavepinterest_settings_page'
    );
}
add_action('admin_menu', 'pinsavepinterest_create_menu');


function pinsavepinterest_settings_page() {
    ?>
    <div class="wrap">
        <h1>Simple Pin It Button for Pinterest</h1>
        <form method="post" action="options.php">
            <?php settings_fields('pinsavepinterest-settings-group'); ?>
            <?php do_settings_sections('pinsavepinterest-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Button Location</th>
                    <td>
                        <select name="pinsavepinterest_button_location">
                            <option value="top-left" <?php selected(get_option('pinsavepinterest_button_location'), 'top-left'); ?>>Top Left</option>
                            <option value="top-right" <?php selected(get_option('pinsavepinterest_button_location'), 'top-right'); ?>>Top Right</option>
                            <option value="bottom-left" <?php selected(get_option('pinsavepinterest_button_location'), 'bottom-left'); ?>>Bottom Left</option>
                            <option value="bottom-right" <?php selected(get_option('pinsavepinterest_button_location'), 'bottom-right'); ?>>Bottom Right</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Button Background Color</th>
                    <td><input type="text" name="pinsavepinterest_button_bg_color" class="pinsavepinterest-color-field" value="<?php echo esc_attr(get_option('pinsavepinterest_button_bg_color', '#dd0b0b')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Font Color</th>
                    <td><input type="text" name="pinsavepinterest_font_color" class="pinsavepinterest-color-field" value="<?php echo esc_attr(get_option('pinsavepinterest_font_color', '#ffffff')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Autohide (on hover)</th>
                    <td>
                        <input type="checkbox" name="pinsavepinterest_autohide" value="1" <?php checked(1, get_option('pinsavepinterest_autohide'), true); ?> /> Enable Autohide
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Pin It Text</th>
                    <td><input type="text" name="pinsavepinterest_pin_text" value="<?php echo esc_attr(get_option('pinsavepinterest_pin_text', 'PIN IT')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}


function pinsavepinterest_register_settings() {
    register_setting(
        'pinsavepinterest-settings-group',
        'pinsavepinterest_button_location',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    register_setting(
        'pinsavepinterest-settings-group',
        'pinsavepinterest_button_bg_color',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    register_setting(
        'pinsavepinterest-settings-group',
        'pinsavepinterest_font_color',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    register_setting(
        'pinsavepinterest-settings-group',
        'pinsavepinterest_autohide',
        array(
            'type'              => 'boolean',
            'sanitize_callback' => 'absint',
        )
    );
    register_setting(
        'pinsavepinterest-settings-group',
        'pinsavepinterest_pin_text',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
}
add_action('admin_init', 'pinsavepinterest_register_settings');