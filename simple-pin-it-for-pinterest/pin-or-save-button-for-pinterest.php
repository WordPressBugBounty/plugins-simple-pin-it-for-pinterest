<?php
/*
Plugin Name: Simple Pin It Button for Pinterest
Description: Simple Pin It Button for Pinterest is a lightweight WordPress plugin that allows you to add a "Pin It" button overlay to images in your posts. The button is fully customizable through the plugin settings page. Encourage your visitors to share your content on Pinterest with ease!
Version: 1.2
Author: Sohel Rana
Author URI: https://sohel.dev
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) exit;

function pinsavepinterest_enqueue_styles() {
    wp_enqueue_style(
        'pinsavepinterest-style',
        plugin_dir_url(__FILE__) . 'style.css',
        [],
        '1.2'
    );
}
add_action('wp_enqueue_scripts', 'pinsavepinterest_enqueue_styles');


function pinsavepinterest_enqueue_admin_scripts($hook) {
    if ($hook != 'toplevel_page_pin-it-or-save-it-button') {
        return;
    }
    
    // Enqueue Bootstrap CSS
    wp_enqueue_style(
        'pinsavepinterest-bootstrap',
        plugin_dir_url(__FILE__) . 'assets/css/bootstrap.min.css',
        [],
        '5.3.2'
    );
    
    // Enqueue Bootstrap Icons
    wp_enqueue_style(
        'pinsavepinterest-bootstrap-icons',
        plugin_dir_url(__FILE__) . 'assets/css/bootstrap-icons.css',
        [],
        '1.11.1'
    );
    
    // Enqueue custom admin CSS
    wp_enqueue_style(
        'pinsavepinterest-admin-custom',
        plugin_dir_url(__FILE__) . 'assets/css/admin-custom.css',
        ['pinsavepinterest-bootstrap', 'pinsavepinterest-bootstrap-icons'],
        '1.1'
    );
    
    // Enqueue WordPress color picker (fallback)
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_style('wp-color-picker');
    
    // Enqueue Bootstrap JavaScript
    wp_enqueue_script(
        'pinsavepinterest-bootstrap',
        plugin_dir_url(__FILE__) . 'assets/js/bootstrap.bundle.min.js',
        [],
        '5.3.2',
        true
    );
    
    // Enqueue custom admin JavaScript
    wp_enqueue_script(
        'pinsavepinterest-admin-settings',
        plugin_dir_url(__FILE__) . 'assets/js/admin-settings.js',
        ['jquery', 'wp-color-picker', 'pinsavepinterest-bootstrap'],
        '1.1',
        true
    );
    
    // Localize script for AJAX
    wp_localize_script('pinsavepinterest-admin-settings', 'pinsavepinterest_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('pinsavepinterest_nonce'),
        'plugin_url' => plugin_dir_url(__FILE__)
    ]);
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
    add_menu_page(
        'PIN BUTTON Settings',
        'PIN BUTTON',
        'manage_options',
        'pin-it-or-save-it-button',
        'pinsavepinterest_settings_page',
        'dashicons-pinterest',
        26
    );
}
add_action('admin_menu', 'pinsavepinterest_create_menu');


function pinsavepinterest_settings_page() {
    // Handle form submission
    if (isset($_POST['submit']) && isset($_POST['_wpnonce'])) {
        $nonce = sanitize_text_field(wp_unslash($_POST['_wpnonce']));
        if (wp_verify_nonce($nonce, 'pinsavepinterest-settings-group-options')) {
        // Validate and process form data
        $valid_locations = ['top-left', 'top-right', 'bottom-left', 'bottom-right'];
        $button_location = isset($_POST['pinsavepinterest_button_location']) ? sanitize_text_field(wp_unslash($_POST['pinsavepinterest_button_location'])) : 'top-right';
        if (!in_array($button_location, $valid_locations)) {
            $button_location = 'top-right';
        }
        
        $button_bg_color = isset($_POST['pinsavepinterest_button_bg_color']) ? sanitize_hex_color(wp_unslash($_POST['pinsavepinterest_button_bg_color'])) : '#E60023';
        if (empty($button_bg_color)) {
            $button_bg_color = '#E60023';
        }
        
        $font_color = isset($_POST['pinsavepinterest_font_color']) ? sanitize_hex_color(wp_unslash($_POST['pinsavepinterest_font_color'])) : '#ffffff';
        if (empty($font_color)) {
            $font_color = '#ffffff';
        }
        
        $pin_text = isset($_POST['pinsavepinterest_pin_text']) ? sanitize_text_field(wp_unslash($_POST['pinsavepinterest_pin_text'])) : 'Save';
        if (empty($pin_text) || strlen($pin_text) > 20) {
            $pin_text = 'Save';
        }
        
        $autohide = isset($_POST['pinsavepinterest_autohide']) ? 1 : 0;
        
        // Update options - track if any actual errors occur
        $errors = [];
        
        // Update each option and check for actual errors (not just unchanged values)
        $result1 = update_option('pinsavepinterest_button_location', $button_location);
        $result2 = update_option('pinsavepinterest_button_bg_color', $button_bg_color);
        $result3 = update_option('pinsavepinterest_font_color', $font_color);
        $result4 = update_option('pinsavepinterest_pin_text', $pin_text);
        $result5 = update_option('pinsavepinterest_autohide', $autohide);
               
        // Check if values were actually saved by comparing with current values
        $saved_location = get_option('pinsavepinterest_button_location');
        $saved_bg_color = get_option('pinsavepinterest_button_bg_color');
        $saved_font_color = get_option('pinsavepinterest_font_color');
        $saved_pin_text = get_option('pinsavepinterest_pin_text');
        $saved_autohide = get_option('pinsavepinterest_autohide');
        
        // Verify that the values match what we tried to save
        // Note: We use loose comparison for autohide since it might be stored as string
        if ($saved_location !== $button_location) $errors[] = 'button_location';
        if ($saved_bg_color !== $button_bg_color) $errors[] = 'button_bg_color';
        if ($saved_font_color !== $font_color) $errors[] = 'font_color';
        if ($saved_pin_text !== $pin_text) $errors[] = 'pin_text';
        if ($saved_autohide != $autohide) $errors[] = 'autohide';
        
        
        // Show appropriate message
        if (empty($errors)) {
            // Success message is handled by JavaScript toast in the template
            // No need for WordPress admin notice here
        } else {
            $error_msg = __('Error saving settings: ', 'simple-pin-it-for-pinterest') . implode(', ', $errors) . __('. Please try again.', 'simple-pin-it-for-pinterest');
            add_settings_error('pinsavepinterest_messages', 'pinsavepinterest_error', $error_msg, 'error');
        }
        }
    }
    
    // Get current values
    $button_location = get_option('pinsavepinterest_button_location', 'top-right');
    $button_bg_color = get_option('pinsavepinterest_button_bg_color', '#E60023');
    $font_color = get_option('pinsavepinterest_font_color', '#ffffff');
    $pin_text = get_option('pinsavepinterest_pin_text', 'Save');
    $autohide = get_option('pinsavepinterest_autohide', false);
    
    // Include the template
    $template_path = plugin_dir_path(__FILE__) . 'templates/admin-settings-page.php';
    if (file_exists($template_path)) {
        include $template_path;
    } else {
        // Fallback to basic HTML if template doesn't exist
        echo '<div class="wrap"><h1>Simple Pin It Button for Pinterest</h1><p>Settings template not found. Please check plugin installation.</p></div>';
    }
}


// AJAX endpoints for enhanced functionality
function pinsavepinterest_ajax_validate_settings() {
    // Verify nonce
    if (!isset($_POST['nonce'])) {
        wp_die('Security check failed');
    }
    $nonce = sanitize_text_field(wp_unslash($_POST['nonce']));
    if (!wp_verify_nonce($nonce, 'pinsavepinterest_nonce')) {
        wp_die('Security check failed');
    }
    
    $errors = [];
    
    // Validate button location
    $valid_locations = ['top-left', 'top-right', 'bottom-left', 'bottom-right'];
    $button_location = isset($_POST['button_location']) ? sanitize_text_field(wp_unslash($_POST['button_location'])) : '';
    if (!in_array($button_location, $valid_locations)) {
        $errors['button_location'] = 'Invalid button location';
    }
    
    // Validate colors
    $button_bg_color = isset($_POST['button_bg_color']) ? sanitize_text_field(wp_unslash($_POST['button_bg_color'])) : '';
    if (!empty($button_bg_color) && !preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $button_bg_color)) {
        $errors['button_bg_color'] = 'Invalid background color format';
    }
    
    $font_color = isset($_POST['font_color']) ? sanitize_text_field(wp_unslash($_POST['font_color'])) : '';
    if (!empty($font_color) && !preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $font_color)) {
        $errors['font_color'] = 'Invalid font color format';
    }
    
    // Validate pin text
    $pin_text = isset($_POST['pin_text']) ? sanitize_text_field(wp_unslash($_POST['pin_text'])) : '';
    if (empty($pin_text)) {
        $errors['pin_text'] = 'Pin text is required';
    } elseif (strlen($pin_text) > 20) {
        $errors['pin_text'] = 'Pin text must be 20 characters or less';
    }
    
    wp_send_json([
        'success' => empty($errors),
        'errors' => $errors
    ]);
}
add_action('wp_ajax_pinsavepinterest_validate_settings', 'pinsavepinterest_ajax_validate_settings');

function pinsavepinterest_ajax_preview_button() {
    // Verify nonce
    if (!isset($_POST['nonce'])) {
        wp_die('Security check failed');
    }
    $nonce = sanitize_text_field(wp_unslash($_POST['nonce']));
    if (!wp_verify_nonce($nonce, 'pinsavepinterest_nonce')) {
        wp_die('Security check failed');
    }
    
    $button_location = isset($_POST['button_location']) ? sanitize_text_field(wp_unslash($_POST['button_location'])) : 'top-right';
    $button_bg_color = isset($_POST['button_bg_color']) ? sanitize_hex_color(wp_unslash($_POST['button_bg_color'])) : '#E60023';
    $font_color = isset($_POST['font_color']) ? sanitize_hex_color(wp_unslash($_POST['font_color'])) : '#ffffff';
    $pin_text = isset($_POST['pin_text']) ? sanitize_text_field(wp_unslash($_POST['pin_text'])) : 'Save';
    $autohide = isset($_POST['autohide']) && sanitize_text_field(wp_unslash($_POST['autohide'])) === 'true';
    
    $preview_html = sprintf(
        '<a href="#" class="pinsavepinterest-preview-button %s" onclick="return false;" style="background-color: %s; color: %s; border-color: %s;">
            <i class="bi bi-pinterest"></i>
            <span class="pinsavepinterest-preview-text">%s</span>
        </a>',
        esc_attr($button_location),
        esc_attr($button_bg_color),
        esc_attr($font_color),
        esc_attr($button_bg_color),
        esc_html($pin_text)
    );
    
    wp_send_json([
        'success' => true,
        'html' => $preview_html,
        'autohide' => $autohide
    ]);
}
add_action('wp_ajax_pinsavepinterest_preview_button', 'pinsavepinterest_ajax_preview_button');

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