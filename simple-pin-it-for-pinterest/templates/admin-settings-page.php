<?php
/**
 * Admin Settings Page Template
 * Bootstrap 5.3 based modern UI for Simple Pin It Button for Pinterest
 * 
 * This template provides a modern, responsive, and accessible settings interface
 * using Bootstrap 5.3 components and modern UI/UX principles.
 * 
 * @package Simple Pin It Button for Pinterest
 * @version 1.1
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Display settings errors/messages
settings_errors('pinsavepinterest_messages');
?>

<div class="pinsavepinterest-bootstrap-container">
    <!-- Header Section -->
    <div class="pinsavepinterest-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="pinsavepinterest-title mb-2">
                    <i class="bi bi-pinterest text-danger me-2"></i>
                    Simple Pin It Button for Pinterest
                </h1>
                <p class="pinsavepinterest-subtitle text-white mb-0" style="opacity: 0.9;">
                    Configure your Pinterest "Pin It" button settings with live preview
                </p>
            </div>
            
        </div>
    </div>

    <div class="row">
        <!-- Settings Form Column -->
        <div class="col-lg-8">
            <form method="post" action="" id="pinsavepinterest-settings-form" novalidate>
                <?php wp_nonce_field('pinsavepinterest-settings-group-options'); ?>
                
                <!-- Button Position Card -->
                <div class="pinsavepinterest-card mb-4">
                    <div class="pinsavepinterest-card-header">
                        <h5 class="pinsavepinterest-card-title mb-0">
                            <i class="bi bi-geo-alt me-2"></i>
                            Button Position
                        </h5>
                        <p class="pinsavepinterest-card-subtitle text-muted mb-0">
                            Choose where the Pin It button appears on your images
                        </p>
                    </div>
                    <div class="pinsavepinterest-card-body">
                        <div class="pinsavepinterest-position-selector">
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <div class="pinsavepinterest-position-option <?php echo $button_location === 'top-left' ? 'active' : ''; ?>" 
                                         tabindex="0" role="button" aria-label="Top Left Position">
                                        <input type="radio" name="pinsavepinterest_button_location" value="top-left" 
                                               id="pos-top-left" <?php checked($button_location, 'top-left'); ?>>
                                        <label for="pos-top-left" class="pinsavepinterest-position-label">
                                            <div class="pinsavepinterest-position-preview">
                                                <div class="pinsavepinterest-position-dot top-left"></div>
                                            </div>
                                            <span class="pinsavepinterest-position-text">Top Left</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="pinsavepinterest-position-option <?php echo $button_location === 'top-right' ? 'active' : ''; ?>" 
                                         tabindex="0" role="button" aria-label="Top Right Position">
                                        <input type="radio" name="pinsavepinterest_button_location" value="top-right" 
                                               id="pos-top-right" <?php checked($button_location, 'top-right'); ?>>
                                        <label for="pos-top-right" class="pinsavepinterest-position-label">
                                            <div class="pinsavepinterest-position-preview">
                                                <div class="pinsavepinterest-position-dot top-right"></div>
                                            </div>
                                            <span class="pinsavepinterest-position-text">Top Right</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="pinsavepinterest-position-option <?php echo $button_location === 'bottom-left' ? 'active' : ''; ?>" 
                                         tabindex="0" role="button" aria-label="Bottom Left Position">
                                        <input type="radio" name="pinsavepinterest_button_location" value="bottom-left" 
                                               id="pos-bottom-left" <?php checked($button_location, 'bottom-left'); ?>>
                                        <label for="pos-bottom-left" class="pinsavepinterest-position-label">
                                            <div class="pinsavepinterest-position-preview">
                                                <div class="pinsavepinterest-position-dot bottom-left"></div>
                                            </div>
                                            <span class="pinsavepinterest-position-text">Bottom Left</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="pinsavepinterest-position-option <?php echo $button_location === 'bottom-right' ? 'active' : ''; ?>" 
                                         tabindex="0" role="button" aria-label="Bottom Right Position">
                                        <input type="radio" name="pinsavepinterest_button_location" value="bottom-right" 
                                               id="pos-bottom-right" <?php checked($button_location, 'bottom-right'); ?>>
                                        <label for="pos-bottom-right" class="pinsavepinterest-position-label">
                                            <div class="pinsavepinterest-position-preview">
                                                <div class="pinsavepinterest-position-dot bottom-right"></div>
                                            </div>
                                            <span class="pinsavepinterest-position-text">Bottom Right</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appearance Settings Card -->
                <div class="pinsavepinterest-card mb-4">
                    <div class="pinsavepinterest-card-header">
                        <h5 class="pinsavepinterest-card-title mb-0">
                            <i class="bi bi-palette me-2"></i>
                            Appearance Settings
                        </h5>
                        <p class="pinsavepinterest-card-subtitle text-muted mb-0">
                            Customize the look and feel of your Pin It button
                        </p>
                    </div>
                    <div class="pinsavepinterest-card-body">
                        <div class="row g-4">
                            <!-- Pin Text - Moved to first position -->
                            <div class="col-md-6">
                                <label for="pinsavepinterest_pin_text" class="pinsavepinterest-form-label">
                                    <i class="bi bi-type me-1"></i>
                                    Button Text
                                </label>
                                <input type="text" 
                                       name="pinsavepinterest_pin_text" 
                                       id="pinsavepinterest_pin_text"
                                       class="pinsavepinterest-form-control" 
                                       value="<?php echo esc_attr($pin_text); ?>"
                                       placeholder="Save"
                                       maxlength="20"
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top" 
                                       title="Text displayed on the Pin It button (max 20 characters)">
                            </div>

                            <!-- Background Color -->
                            <div class="col-md-6">
                                <label for="pinsavepinterest_button_bg_color" class="pinsavepinterest-form-label">
                                    <i class="bi bi-paint-bucket me-1"></i>
                                    Background Color
                                </label>
                                <div class="pinsavepinterest-color-input-group">
                                    <input type="text" 
                                           name="pinsavepinterest_button_bg_color" 
                                           id="pinsavepinterest_button_bg_color"
                                           class="pinsavepinterest-form-control pinsavepinterest-color-input" 
                                           value="<?php echo esc_attr($button_bg_color); ?>"
                                           placeholder="#E60023"
                                           data-bs-toggle="tooltip" 
                                           data-bs-placement="top" 
                                           title="Enter a hex color code (e.g., #E60023)">
                                   
                                </div>
                                <div class="pinsavepinterest-form-text">
                                    Pinterest brand color: #E60023
                                </div>
                            </div>

                            <!-- Font Color -->
                            <div class="col-md-6">
                                <label for="pinsavepinterest_font_color" class="pinsavepinterest-form-label">
                                    <i class="bi bi-fonts me-1"></i>
                                    Text Color
                                </label>
                                <div class="pinsavepinterest-color-input-group">
                                    <input type="text" 
                                           name="pinsavepinterest_font_color" 
                                           id="pinsavepinterest_font_color"
                                           class="pinsavepinterest-form-control pinsavepinterest-color-input" 
                                           value="<?php echo esc_attr($font_color); ?>"
                                           placeholder="#ffffff"
                                           data-bs-toggle="tooltip" 
                                           data-bs-placement="top" 
                                           title="Enter a hex color code (e.g., #ffffff)">
                                    
                                </div>
                                <div class="pinsavepinterest-form-text">
                                    Recommended: #ffffff for dark backgrounds
                                </div>
                            </div>

                            <!-- Autohide Toggle -->
                            <div class="col-md-6">
                                <label class="pinsavepinterest-form-label mb-2">
                                    <i class="bi bi-eye me-1"></i>
                                    Visibility Behavior
                                </label>
                                <div class="pinsavepinterest-toggle-wrapper">
                                    <div class="pinsavepinterest-form-check pinsavepinterest-form-switch">
                                        <input type="checkbox" 
                                               name="pinsavepinterest_autohide" 
                                               id="pinsavepinterest_autohide"
                                               class="pinsavepinterest-form-check-input" 
                                               value="1" 
                                               <?php checked($autohide, 1); ?>
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="top" 
                                               title="Hide button until user hovers over the image">
                                        <label class="pinsavepinterest-form-check-label" for="pinsavepinterest_autohide">
                                            Show only on hover (Autohide)
                                        </label>
                                    </div>
                                    <div class="pinsavepinterest-form-text mt-1">
                                        When enabled, the button appears only when users hover over images
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pinsavepinterest-card">
                    <div class="pinsavepinterest-card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <button type="submit" name="submit" class="pinsavepinterest-btn pinsavepinterest-btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Save Settings
                                </button>
                              
                            </div>
                            <div class="pinsavepinterest-form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Changes will be applied to all images in your posts
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Live Preview Column -->
        <div class="col-lg-4">
            <div class="pinsavepinterest-sticky-preview">
                <!-- Live Preview Card -->
                <div class="pinsavepinterest-card pinsavepinterest-preview-card mb-4">
                    <div class="pinsavepinterest-card-header">
                        <h5 class="pinsavepinterest-card-title mb-0">
                            <i class="bi bi-eye me-2"></i>
                            Live Preview
                        </h5>
                        <p class="pinsavepinterest-card-subtitle text-muted mb-0">
                            See how your button will look
                        </p>
                    </div>
                    <div class="pinsavepinterest-card-body">
                        <div class="pinsavepinterest-preview-container">
                            <div class="pinsavepinterest-preview-image">
                                <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&h=300&q=80" 
                                     alt="Preview Image" 
                                     style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                                <a href="#" class="pinsavepinterest-preview-button <?php echo esc_attr($button_location); ?>" 
                                   style="background-color: <?php echo esc_attr($button_bg_color); ?>; color: <?php echo esc_attr($font_color); ?>; border-color: <?php echo esc_attr($button_bg_color); ?>;">
                                    <i class="bi bi-pinterest"></i>
                                    <span class="pinsavepinterest-preview-text"><?php echo esc_html($pin_text); ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Help & Tips Card -->
                <div class="pinsavepinterest-card pinsavepinterest-help-card">
                    <div class="pinsavepinterest-card-header">
                        <h5 class="pinsavepinterest-card-title mb-0">
                            <i class="bi bi-lightbulb me-2"></i>
                            Tips & Best Practices
                        </h5>
                    </div>
                    <div class="pinsavepinterest-card-body">
                        <div class="pinsavepinterest-tips-list">
                            <div class="pinsavepinterest-tip-item">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <div>
                                    <strong>Color Contrast:</strong> Ensure good contrast between background and text colors for accessibility.
                                </div>
                            </div>
                            <div class="pinsavepinterest-tip-item">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <div>
                                    <strong>Button Position:</strong> Top-right is most common, but test what works best for your content.
                                </div>
                            </div>
                            <div class="pinsavepinterest-tip-item">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <div>
                                    <strong>Autohide Feature:</strong> Reduces visual clutter while keeping functionality accessible.
                                </div>
                            </div>
                            <div class="pinsavepinterest-tip-item">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <div>
                                    <strong>Button Text:</strong> Keep it short and clear. "Save" or "Pin It" work well.
                                </div>
                            </div>
                        </div>
                        
                    
                        
                       
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Plugin Info Footer -->
    <div class="pinsavepinterest-footer mt-5 pt-4 border-top">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="pinsavepinterest-plugin-info">
                    <h6 class="mb-1">Simple Pin It Button for Pinterest</h6>
                    <p class="text-muted mb-0 small">
                        Version 1.2 | Developed by <a href="https://sohel.dev" target="_blank" rel="noopener">Sohel Rana</a>
                    </p>
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="pinsavepinterest-plugin-actions">
                    <a href="https://wordpress.org/support/plugin/simple-pin-it-for-pinterest/reviews/#new-post" 
                       target="_blank" 
                       rel="noopener" 
                       class="pinsavepinterest-btn pinsavepinterest-btn-sm pinsavepinterest-btn-outline-primary me-2">
                        <i class="bi bi-star me-1"></i>
                        Rate Plugin
                    </a>
                    <a href="https://sohel.dev/" 
                       target="_blank" 
                       rel="noopener" 
                       class="pinsavepinterest-btn pinsavepinterest-btn-sm pinsavepinterest-btn-outline-secondary">
                        <i class="bi bi-question-circle me-1"></i>
                        Get Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Additional template-specific styles */
.pinsavepinterest-sticky-preview {
    position: sticky;
    top: 32px; /* WordPress admin bar height */
}

.pinsavepinterest-preview-image {
    position: relative;
    display: block;
    width: 100%;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.pinsavepinterest-preview-image img {
    display: block;
    width: 100%;
    height: auto;
}

.pinsavepinterest-preview-button {
    position: absolute;
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    background-color: #E60023;
    color: #ffffff;
    text-decoration: none;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.2s ease;
    border: 2px solid #E60023;
    box-shadow: 0 2px 8px rgba(230, 0, 35, 0.3);
}

.pinsavepinterest-preview-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(230, 0, 35, 0.4);
    text-decoration: none;
    color: inherit;
}

.pinsavepinterest-preview-button.top-left {
    top: 12px;
    left: 12px;
}

.pinsavepinterest-preview-button.top-right {
    top: 12px;
    right: 12px;
}

.pinsavepinterest-preview-button.bottom-left {
    bottom: 12px;
    left: 12px;
}

.pinsavepinterest-preview-button.bottom-right {
    bottom: 12px;
    right: 12px;
}

.pinsavepinterest-preview-button i {
    font-size: 16px;
}

.pinsavepinterest-tips-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.pinsavepinterest-tip-item {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    font-size: 14px;
    line-height: 1.4;
}

.pinsavepinterest-help-link {
    color: var(--bs-primary);
    text-decoration: none;
    font-size: 14px;
    transition: color 0.2s ease;
}

.pinsavepinterest-help-link:hover {
    color: var(--bs-primary);
    text-decoration: underline;
}

@media (max-width: 991.98px) {
    .pinsavepinterest-sticky-preview {
        position: static;
    }
    
    .pinsavepinterest-header .d-flex {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .pinsavepinterest-header .d-flex > div:last-child {
        align-self: stretch;
    }
}
</style>

<script type="text/javascript">
jQuery(document).ready(function($) {
    'use strict';
    
    // Initialize the Pinterest plugin admin functionality
    if (typeof PinSavePinterest !== 'undefined') {
        PinSavePinterest.init();
        
        // Force an immediate preview update after initialization
        setTimeout(function() {
            PinSavePinterest.updatePreview();
        }, 200);
    } else {
        console.error('PinSavePinterest object not found. Make sure admin-settings.js is loaded.');
    }
    
    // Ensure WordPress color picker is available
    if (typeof $.fn.wpColorPicker === 'undefined') {
        console.warn('WordPress color picker not available, loading fallback...');
    }
    
    // ===== WORDPRESS COLOR PICKER INITIALIZATION =====
    // Initialize color pickers for Background Color and Text Color fields
    function initializeColorPickers() {
        
        // Color picker configuration
        const colorPickerConfig = {
            // Pinterest brand palette
            palettes: ['#E60023', '#ffffff', '#000000', '#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff'],
            // Immediate change event for real-time updates
            change: function(event, ui) {
                const $input = $(this);
                const newColor = ui.color.toString();
                
                // Update the input value
                $input.val(newColor).trigger('input');
                
                // Update color preview
                updateColorPreview($input);
                
                // Update live preview immediately
                updatePreviewInstantly();
            },
            // Clear event
            clear: function() {
                const $input = $(this);
                
                // Reset to default color
                const defaultColor = $input.data('default-color') || '#E60023';
                $input.val(defaultColor).trigger('input');
                
                // Update color preview
                updateColorPreview($input);
                
                // Update live preview immediately
                updatePreviewInstantly();
            }
        };
        
        // Initialize Background Color picker
        const $bgColorInput = $('#pinsavepinterest_button_bg_color');
        if ($bgColorInput.length && typeof $.fn.wpColorPicker !== 'undefined') {
            // Destroy existing picker if it exists
            if ($bgColorInput.hasClass('wp-color-picker')) {
                $bgColorInput.wpColorPicker('destroy');
            }
            
            try {
                $bgColorInput.wpColorPicker(colorPickerConfig);
            } catch (e) {
                // Fallback for color picker initialization errors
                $bgColorInput.on('input change', function() {
                    updateColorPreview($(this));
                    updatePreviewInstantly();
                });
            }
            
            // Additional event binding for immediate updates
            $bgColorInput.on('input change keyup paste', function() {
                updateColorPreview($(this));
                updatePreviewInstantly();
            });
        }
        
        // Initialize Text Color picker
        const $fontColorInput = $('#pinsavepinterest_font_color');
        if ($fontColorInput.length && typeof $.fn.wpColorPicker !== 'undefined') {
            // Destroy existing picker if it exists
            if ($fontColorInput.hasClass('wp-color-picker')) {
                $fontColorInput.wpColorPicker('destroy');
            }
            
            try {
                $fontColorInput.wpColorPicker(colorPickerConfig);
            } catch (e) {
                // Fallback for color picker initialization errors
                $fontColorInput.on('input change', function() {
                    updateColorPreview($(this));
                    updatePreviewInstantly();
                });
            }
            
            // Additional event binding for immediate updates
            $fontColorInput.on('input change keyup paste', function() {
                updateColorPreview($(this));
                updatePreviewInstantly();
            });
        }
        
        // Fallback for browsers without wpColorPicker
        if (typeof $.fn.wpColorPicker === 'undefined') {
            $('.pinsavepinterest-color-input').each(function() {
                const $input = $(this);
                $input.attr('type', 'color');
                $input.on('input change', function() {
                    updateColorPreview($input);
                    updatePreviewInstantly();
                });
            });
        }
    }
    
    // Function to update color preview
    function updateColorPreview($input) {
        const color = $input.val();
        const $preview = $input.siblings('.pinsavepinterest-color-preview');
        
        if (color && isValidColor(color)) {
            $preview.css('background-color', color);
            $input.removeClass('is-invalid');
        } else {
            $preview.css('background-color', '#ffffff');
            if (color) {
                $input.addClass('is-invalid');
            }
        }
    }
    
    // Function to validate color
    function isValidColor(color) {
        if (!color) return false;
        
        // Check hex color format
        if (/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(color)) {
            return true;
        }
        
        // Check named colors and other formats
        const testElement = document.createElement('div');
        testElement.style.color = color;
        return testElement.style.color !== '';
    }
    
    // Initialize color pickers after a short delay to ensure DOM is ready
    setTimeout(initializeColorPickers, 100);
    
    // ===== DIRECT LIVE PREVIEW EVENT HANDLERS =====
    // These handlers ensure immediate preview updates regardless of main JS timing
    
    // Function to update preview immediately
    function updatePreviewInstantly() {
        
        // Get current values
        const location = $('input[name="pinsavepinterest_button_location"]:checked').val() || 'top-right';
        const bgColor = $('input[name="pinsavepinterest_button_bg_color"]').val() || '#E60023';
        const fontColor = $('input[name="pinsavepinterest_font_color"]').val() || '#ffffff';
        const pinText = $('input[name="pinsavepinterest_pin_text"]').val() || 'Save';
        const autohide = $('input[name="pinsavepinterest_autohide"]').is(':checked');
        
        const $previewButton = $('.pinsavepinterest-preview-button');
        const $previewContainer = $('.pinsavepinterest-preview-image');
        
        if ($previewButton.length === 0) {
            return;
        }
        
        // Update position immediately
        $previewButton.removeClass('top-left top-right bottom-left bottom-right').addClass(location);
        
        // Update visual indicator for position selector
        $('.pinsavepinterest-position-option').removeClass('active');
        $(`.pinsavepinterest-position-option input[value="${location}"]`).closest('.pinsavepinterest-position-option').addClass('active');
        
        // Update colors
        if (isValidColor(bgColor)) {
            $previewButton.css({
                'background-color': bgColor,
                'border-color': bgColor
            });
        }
        
        if (isValidColor(fontColor)) {
            $previewButton.css('color', fontColor);
        }
        
        // Update text
        const $previewText = $previewButton.find('.pinsavepinterest-preview-text');
        if ($previewText.length > 0) {
            $previewText.text(pinText);
        }
        
        // Update autohide behavior
        if (autohide && $previewContainer.length > 0) {
            $previewContainer.addClass('pinsavepinterest-autohide');
            $previewButton.css({
                'opacity': '0.3',
                'transition': 'opacity 0.3s ease'
            });
            
            $previewContainer.off('mouseenter.template mouseleave.template').on({
                'mouseenter.template': function() {
                    $previewButton.css('opacity', '1');
                },
                'mouseleave.template': function() {
                    $previewButton.css('opacity', '0.3');
                }
            });
        } else if ($previewContainer.length > 0) {
            $previewContainer.removeClass('pinsavepinterest-autohide');
            $previewButton.css({
                'opacity': '1',
                'transition': 'opacity 0.3s ease'
            });
            $previewContainer.off('mouseenter.template mouseleave.template');
        }
        
        // Add visual feedback
        $previewButton.addClass('pinsavepinterest-preview-updated');
        setTimeout(() => {
            $previewButton.removeClass('pinsavepinterest-preview-updated');
        }, 300);
    }
    
    // Helper function to validate colors
    function isValidColor(color) {
        if (!color) return false;
        return /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(color);
    }
    
    // 1. POSITION SELECTOR HANDLERS - Immediate response to clicks
    $(document).on('click', '.pinsavepinterest-position-option', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $radio = $(this).find('input[type="radio"]');
        if ($radio.length && !$radio.is(':checked')) {
            
            // Uncheck all other radio buttons
            $('input[name="pinsavepinterest_button_location"]').prop('checked', false);
            
            // Check this radio button
            $radio.prop('checked', true);
            
            // Update preview immediately
            updatePreviewInstantly();
            
            // Trigger change event for other listeners
            $radio.trigger('change');
        }
    });
    
    // Handle direct radio button clicks
    $(document).on('change click', 'input[name="pinsavepinterest_button_location"]', function(e) {
        updatePreviewInstantly();
    });
    
    // Handle label clicks
    $(document).on('click', 'label[for^="pos-"]', function(e) {
        const forAttr = $(this).attr('for');
        if (forAttr) {
            const $radio = $('#' + forAttr);
            if ($radio.length && !$radio.is(':checked')) {
                $('input[name="pinsavepinterest_button_location"]').prop('checked', false);
                $radio.prop('checked', true);
                updatePreviewInstantly();
                $radio.trigger('change');
            }
        }
    });
    
    // 2. COLOR INPUT HANDLERS - Immediate response to color changes
    $(document).on('input change keyup paste', 'input[name="pinsavepinterest_button_bg_color"], input[name="pinsavepinterest_font_color"], .pinsavepinterest-color-input', function(e) {
        updatePreviewInstantly();
    });
    
    // 3. TEXT INPUT HANDLERS - Immediate response to text changes
    $(document).on('input keyup paste change', 'input[name="pinsavepinterest_pin_text"]', function(e) {
        updatePreviewInstantly();
    });
    
    // 4. AUTOHIDE TOGGLE HANDLERS - Immediate response to toggle changes
    $(document).on('change click', 'input[name="pinsavepinterest_autohide"]', function(e) {
        updatePreviewInstantly();
    });
    
    // 5. WORDPRESS COLOR PICKER HANDLERS - Handle wpColorPicker events
    $(document).on('wpcolorpickerchange wpcolorpickerclear', '.pinsavepinterest-color-input', function(e, ui) {
        setTimeout(updatePreviewInstantly, 50);
    });
    
    // Initialize preview after a short delay to ensure all elements are ready
    setTimeout(function() {
        updatePreviewInstantly();
    }, 500);
    
    // Handle form submission and show success message
    $('#pinsavepinterest-settings-form').on('submit', function(e) {
        // Let the form submit normally, but show success message after page reload
        // We'll use sessionStorage to persist the success state across page reload
        sessionStorage.setItem('pinsavepinterest_show_success', 'true');
    });
    
    // Check for success message on page load
    if (sessionStorage.getItem('pinsavepinterest_show_success') === 'true') {
        sessionStorage.removeItem('pinsavepinterest_show_success');
        showSuccessMessage();
    }
    
    // Function to show success message in bottom right
    function showSuccessMessage() {
        const successMessage = $('<div class="pinsavepinterest-success-toast">' +
            '<i class="bi bi-check-circle-fill me-2"></i>' +
            'Settings saved successfully!' +
            '</div>');
        
        $('body').append(successMessage);
        
        // Animate in
        setTimeout(() => {
            successMessage.addClass('show');
        }, 100);
        
        // Auto hide after 3 seconds
        setTimeout(() => {
            successMessage.removeClass('show');
            setTimeout(() => {
                successMessage.remove();
            }, 300);
        }, 3000);
    }
});
</script>

<style>
/* Enhanced preview feedback styles */
.pinsavepinterest-preview-updated {
    transform: scale(1.05) !important;
    box-shadow: 0 4px 20px rgba(230, 0, 35, 0.5) !important;
    transition: all 0.3s ease !important;
}

.pinsavepinterest-preview-button {
    transition: all 0.2s ease !important;
}

/* Enhanced autohide styles */
.pinsavepinterest-autohide .pinsavepinterest-preview-button {
    transition: opacity 0.3s ease !important;
}

/* Position selector active state enhancement */
.pinsavepinterest-position-option.active {
    background-color: rgba(230, 0, 35, 0.1) !important;
    border-color: #E60023 !important;
    transform: scale(1.02) !important;
}

.pinsavepinterest-position-option {
    transition: all 0.2s ease !important;
    cursor: pointer !important;
}

.pinsavepinterest-position-option:hover {
    background-color: rgba(230, 0, 35, 0.05) !important;
    transform: translateY(-1px) !important;
}

/* Color input enhancement */
.pinsavepinterest-color-input {
    transition: border-color 0.2s ease !important;
}

.pinsavepinterest-color-input:focus {
    border-color: #E60023 !important;
    box-shadow: 0 0 0 0.2rem rgba(230, 0, 35, 0.25) !important;
}

/* Text input enhancement */
input[name="pinsavepinterest_pin_text"]:focus {
    border-color: #E60023 !important;
    box-shadow: 0 0 0 0.2rem rgba(230, 0, 35, 0.25) !important;
}

/* Success toast message */
.pinsavepinterest-success-toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #28a745;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 9999;
    display: flex;
    align-items: center;
    font-weight: 500;
    transform: translateX(100%);
    opacity: 0;
    transition: all 0.3s ease;
}

.pinsavepinterest-success-toast.show {
    transform: translateX(0);
    opacity: 1;
}

.pinsavepinterest-success-toast i {
    font-size: 16px;
}

/* Improved toggle alignment */
.pinsavepinterest-toggle-wrapper {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.pinsavepinterest-form-check.pinsavepinterest-form-switch {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 0;
    min-height: 38px; /* Match input field height */
}

.pinsavepinterest-form-check-input {
    margin: 0 !important;
    flex-shrink: 0;
}

.pinsavepinterest-form-check-label {
    margin: 0 !important;
    font-weight: 500;
    color: #495057;
    cursor: pointer;
    user-select: none;
}

.pinsavepinterest-form-check-label:hover {
    color: #E60023;
}

/* Ensure consistent spacing with other form elements */
.pinsavepinterest-form-label.mb-2 {
    margin-bottom: 8px !important;
    display: block;
    font-weight: 600;
    color: #495057;
}
</style>