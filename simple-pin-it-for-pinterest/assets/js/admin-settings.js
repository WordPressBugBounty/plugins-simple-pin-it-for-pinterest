/**
 * Simple Pin It Button for Pinterest - Admin Settings JavaScript
 * Enhanced functionality, validation, and live preview
 * 
 * This file provides interactive functionality for the plugin settings page,
 * including live preview, form validation, and enhanced user experience.
 */

(function($) {
    'use strict';

    // Plugin namespace
    const PinSavePinterest = {
        
        // Configuration
        config: {
            previewImageUrl: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&h=300&q=80',
            ajaxUrl: ajaxurl || '/wp-admin/admin-ajax.php',
            nonce: pinsavepinterest_ajax.nonce || ''
        },

        // Initialize the plugin
        init: function() {
            
            // Initialize in specific order for optimal functionality
            this.bindEvents();
            this.initializePreview();
            
            // Delay color picker initialization to ensure DOM is ready
            setTimeout(() => {
                this.initializeColorPickers();
                // Force an initial preview update after color pickers are ready
                setTimeout(() => {
                    this.updatePreview();
                }, 100);
            }, 100);
            
            this.initializeValidation();
            this.initializeTooltips();
            this.initializeDarkMode();
            this.initializeAccessibility();
            
            // Add a final initialization check
            this.performInitializationCheck();
        },
        
        // Perform initialization check to ensure everything is working
        performInitializationCheck: function() {
            const checks = {
                positionOptions: $('.pinsavepinterest-position-option').length > 0,
                colorInputs: $('.pinsavepinterest-color-input').length > 0,
                previewButton: $('.pinsavepinterest-preview-button').length > 0,
                pinTextInput: $('#pinsavepinterest_pin_text').length > 0,
                autohideCheckbox: $('#pinsavepinterest_autohide').length > 0
            };
            
        },

        // Bind event handlers
        bindEvents: function() {
            const self = this;

            // Position selector - handle both radio button changes and clicks on the option containers
            $(document).on('change', 'input[name="pinsavepinterest_button_location"]', function() {
                const newPosition = $(this).val();
                self.updatePositionVisual(newPosition);
                self.updatePreview();
            });

            // Handle clicks on position option containers - immediate update
            $(document).on('click', '.pinsavepinterest-position-option', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const $radio = $(this).find('input[type="radio"]');
                if ($radio.length && !$radio.is(':checked')) {
                    
                    // Uncheck all other radio buttons
                    $('input[name="pinsavepinterest_button_location"]').prop('checked', false);
                    
                    // Check this radio button
                    $radio.prop('checked', true);
                    
                    // Immediately update preview
                    const newPosition = $radio.val();
                    self.updatePositionVisual(newPosition);
                    self.updatePreview();
                    
                    // Trigger change event for other listeners
                    $radio.trigger('change');
                }
            });

            // Handle clicks on position labels - immediate update
            $(document).on('click', '.pinsavepinterest-position-label, label[for^="pos-"]', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                let $radio = $(this).find('input[type="radio"]');
                if ($radio.length === 0) {
                    // If radio is not inside label, find it by the for attribute or in parent container
                    const forAttr = $(this).attr('for');
                    if (forAttr) {
                        $radio = $('#' + forAttr);
                    } else {
                        $radio = $(this).closest('.pinsavepinterest-position-option').find('input[type="radio"]');
                    }
                }
                
                if ($radio.length && !$radio.is(':checked')) {
                    
                    // Uncheck all other radio buttons
                    $('input[name="pinsavepinterest_button_location"]').prop('checked', false);
                    
                    // Check this radio button
                    $radio.prop('checked', true);
                    
                    // Immediately update preview
                    const newPosition = $radio.val();
                    self.updatePositionVisual(newPosition);
                    self.updatePreview();
                    
                    // Trigger change event for any other listeners
                    $radio.trigger('change');
                }
            });

            // Color pickers - multiple event types for comprehensive coverage
            $(document).on('change input keyup paste', '.pinsavepinterest-color-input', function() {
                const $input = $(this);
                const newColor = $input.val();
                self.updateColorPreview($input);
                self.updatePreview();
            });

            // Additional fallback for color inputs that might not have the class
            $(document).on('change input keyup paste', 'input[name="pinsavepinterest_button_bg_color"], input[name="pinsavepinterest_font_color"]', function() {
                const $input = $(this);
                const newColor = $input.val();
                self.updateColorPreview($input);
                self.updatePreview();
            });

            // Text input - immediate updates on any text change
            $(document).on('input keyup paste change', 'input[name="pinsavepinterest_pin_text"]', function() {
                const $input = $(this);
                const newText = $input.val();
                self.validatePinText($input);
                self.updatePreview();
            });

            // Autohide toggle - immediate response
            $(document).on('change click', 'input[name="pinsavepinterest_autohide"]', function() {
                const $input = $(this);
                const isChecked = $input.is(':checked');
                self.updatePreview();
            });

            // Form submission
            $(document).on('submit', '#pinsavepinterest-settings-form', function(e) {
                if (!self.validateForm()) {
                    e.preventDefault();
                    return false;
                }
                self.showSaveIndicator();
            });

            // Reset buttons
            $(document).on('click', '.pinsavepinterest-reset-btn', function(e) {
                e.preventDefault();
                self.showResetConfirmation();
            });

            // Export/Import buttons
            $(document).on('click', '.pinsavepinterest-export-btn', function(e) {
                e.preventDefault();
                self.exportSettings();
            });

            $(document).on('click', '.pinsavepinterest-import-btn', function(e) {
                e.preventDefault();
                $('#pinsavepinterest-import-file').click();
            });

            $(document).on('change', '#pinsavepinterest-import-file', function() {
                self.importSettings(this.files[0]);
            });

            // Dark mode toggle
            $(document).on('click', '.pinsavepinterest-dark-mode-toggle', function(e) {
                e.preventDefault();
                self.toggleDarkMode();
            });

            // Keyboard navigation for position selector
            $(document).on('keydown', '.pinsavepinterest-position-option', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    $(this).find('input[type="radio"]').prop('checked', true).trigger('change');
                }
            });
        },

        // Initialize color pickers
        initializeColorPickers: function() {
            const self = this;
            
            $('.pinsavepinterest-color-input').each(function() {
                const $input = $(this);
                const $preview = $input.siblings('.pinsavepinterest-color-preview');
                
                // Set initial color
                self.updateColorPreview($input);
                
                // Initialize WordPress color picker if available
                if (typeof $.fn.wpColorPicker !== 'undefined') {
                    
                    // Destroy existing color picker if it exists
                    if ($input.hasClass('wp-color-picker')) {
                        $input.wpColorPicker('destroy');
                    }
                    
                    $input.wpColorPicker({
                        // Immediate change event for real-time updates
                        change: function(event, ui) {
                            $input.val(ui.color.toString()).trigger('input');
                            self.updateColorPreview($input);
                            self.updatePreview();
                        },
                        // Clear event
                        clear: function() {
                            $input.val('').trigger('input');
                            self.updateColorPreview($input);
                            self.updatePreview();
                        },
                        // Palette change event for instant updates
                        palettes: ['#E60023', '#ffffff', '#000000', '#ff0000', '#00ff00', '#0000ff']
                    });
                    
                    // Additional event binding for WordPress color picker wrapper
                    const $wpColorPicker = $input.closest('.wp-picker-container');
                    if ($wpColorPicker.length) {
                        // Bind to the actual color picker input changes
                        $wpColorPicker.find('.wp-color-picker').on('input change', function() {
                            const newColor = $(this).val();
                            $input.val(newColor);
                            self.updateColorPreview($input);
                            self.updatePreview();
                        });
                        
                        // Bind to palette clicks for instant updates
                        $wpColorPicker.find('.wp-picker-default, .wp-color-result').on('click', function() {
                            setTimeout(function() {
                                const newColor = $input.val();
                                self.updateColorPreview($input);
                                self.updatePreview();
                            }, 50);
                        });
                    }
                } else {
                    // Fallback for browsers that support color input
                    $input.attr('type', 'color');
                    
                    // Bind fallback events
                    $input.on('input change', function() {
                        self.updateColorPreview($input);
                        self.updatePreview();
                    });
                }
            });
        },

        // Update color preview
        updateColorPreview: function($input) {
            const color = $input.val();
            const $preview = $input.siblings('.pinsavepinterest-color-preview');
            
            if (color && this.isValidColor(color)) {
                $preview.css('background-color', color);
                $input.removeClass('is-invalid');
            } else {
                $preview.css('background-color', '#ffffff');
                if (color) {
                    $input.addClass('is-invalid');
                }
            }
        },

        // Initialize live preview
        initializePreview: function() {
            const self = this;
            
            // Check if preview elements exist, if not create them
            if ($('.pinsavepinterest-preview-button').length === 0) {
                const previewHtml = `
                    <div class="pinsavepinterest-preview-container">
                        <div class="pinsavepinterest-preview-image">
                            <img src="${this.config.previewImageUrl}" alt="Preview Image" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                            <a href="#" class="pinsavepinterest-preview-button top-right" onclick="return false;">
                                <i class="bi bi-pinterest"></i>
                                <span class="pinsavepinterest-preview-text">Save</span>
                            </a>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                This preview updates in real-time as you change settings above
                            </small>
                        </div>
                    </div>
                `;
                $('.pinsavepinterest-preview-card .pinsavepinterest-card-body').html(previewHtml);
            }
            
            // Initialize position visual state
            const currentPosition = $('input[name="pinsavepinterest_button_location"]:checked').val() || 'top-right';
            this.updatePositionVisual(currentPosition);
            
            // Initial preview update
            this.updatePreview();
        },

        // Update live preview
        updatePreview: function() {
            
            
            // Get current values from form
            const location = $('input[name="pinsavepinterest_button_location"]:checked').val() || 'top-left';
            const bgColor = $('#pinsavepinterest_button_bg_color').val() || '#e60023';
            const fontColor = $('#pinsavepinterest_font_color').val() || '#ffffff';
            const pinText = $('#pinsavepinterest_pin_text').val() || 'Pin It';
            const autohide = $('#pinsavepinterest_autohide').is(':checked');
            
            
            
            
            // Find preview elements
            const $previewButton = $('.pinsavepinterest-preview-button');
            const $previewContainer = $('.pinsavepinterest-preview-container');
            
            if ($previewButton.length && $previewContainer.length) {
                // Update position
                $previewContainer.removeClass('position-top-left position-top-right position-bottom-left position-bottom-right position-center-left position-center-right');
                $previewContainer.addClass('position-' + location);
                
                
                // Update colors and text
                $previewButton.css({
                    'background-color': bgColor,
                    'color': fontColor
                }).text(pinText);
                
                
                
                
                
                // Handle autohide functionality
                if (autohide) {
                    $previewContainer.addClass('autohide-enabled');
                    if (!$previewContainer.hasClass('preview-hovered')) {
                        $previewButton.css('opacity', '0.3');
                    }
                    
                } else {
                    $previewContainer.removeClass('autohide-enabled');
                    $previewButton.css('opacity', '1');
                    
                }
                
                
            }
        },

        // Update position visual indicator
        updatePositionVisual: function(position) {
            $('.pinsavepinterest-position-option').removeClass('active');
            $(`.pinsavepinterest-position-option input[value="${position}"]`).closest('.pinsavepinterest-position-option').addClass('active');
        },

        // Initialize form validation
        initializeValidation: function() {
            // Real-time validation for all inputs
            $('.pinsavepinterest-form-control').on('blur', function() {
                const $input = $(this);
                const fieldName = $input.attr('name');
                
                switch (fieldName) {
                    case 'pinsavepinterest_button_bg_color':
                    case 'pinsavepinterest_font_color':
                        this.validateColor($input);
                        break;
                    case 'pinsavepinterest_pin_text':
                        this.validatePinText($input);
                        break;
                }
            }.bind(this));
        },

        // Validate color input
        validateColor: function($input) {
            const color = $input.val();
            const isValid = this.isValidColor(color);
            
            $input.toggleClass('is-invalid', !isValid);
            
            const $feedback = $input.siblings('.invalid-feedback');
            if (!isValid && $feedback.length === 0) {
                $input.after('<div class="invalid-feedback">Please enter a valid hex color (e.g., #E60023)</div>');
            } else if (isValid && $feedback.length > 0) {
                $feedback.remove();
            }
            
            return isValid;
        },

        // Validate pin text
        validatePinText: function($input) {
            const text = $input.val();
            const maxLength = 20;
            const isValid = text.length > 0 && text.length <= maxLength;
            
            $input.toggleClass('is-invalid', !isValid);
            
            // Update character counter
            let $counter = $input.siblings('.pinsavepinterest-char-counter');
            if ($counter.length === 0) {
                $counter = $('<div class="pinsavepinterest-char-counter pinsavepinterest-form-text"></div>');
                $input.after($counter);
            }
            
            $counter.text(`${text.length}/${maxLength} characters`);
            $counter.toggleClass('text-danger', text.length > maxLength);
            
            const $feedback = $input.siblings('.invalid-feedback');
            if (!isValid && $feedback.length === 0) {
                if (text.length === 0) {
                    $input.after('<div class="invalid-feedback">Pin text is required</div>');
                } else if (text.length > maxLength) {
                    $input.after('<div class="invalid-feedback">Pin text must be 20 characters or less</div>');
                }
            } else if (isValid && $feedback.length > 0) {
                $feedback.remove();
            }
            
            return isValid;
        },

        // Validate entire form
        validateForm: function() {
            let isValid = true;
            
            // Validate color inputs
            $('.pinsavepinterest-color-input').each((index, element) => {
                if (!this.validateColor($(element))) {
                    isValid = false;
                }
            });
            
            // Validate pin text
            const $pinTextInput = $('input[name="pinsavepinterest_pin_text"]');
            if (!this.validatePinText($pinTextInput)) {
                isValid = false;
            }
            
            // Show validation summary
            if (!isValid) {
                this.showValidationError('Please correct the errors above before saving.');
            }
            
            return isValid;
        },

        // Check if color is valid hex
        isValidColor: function(color) {
            if (!color) return false;
            return /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(color);
        },

        // Show save indicator
        showSaveIndicator: function() {
            const $submitBtn = $('.pinsavepinterest-btn-primary[type="submit"]');
            const originalText = $submitBtn.text();
            
            $submitBtn.prop('disabled', true).html('<i class="bi bi-arrow-clockwise"></i> Saving...');
            
            // Reset after 2 seconds (WordPress will handle the actual save)
            setTimeout(() => {
                $submitBtn.prop('disabled', false).text(originalText);
            }, 2000);
        },

        // Show validation error
        showValidationError: function(message) {
            // Remove existing alerts
            $('.pinsavepinterest-alert').remove();
            
            const alertHtml = `
                <div class="pinsavepinterest-alert alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            
            $('.pinsavepinterest-bootstrap-container').prepend(alertHtml);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                $('.pinsavepinterest-alert').fadeOut();
            }, 5000);
        },

        // Show success message
        showSuccessMessage: function(message) {
            // Remove existing alerts
            $('.pinsavepinterest-alert').remove();
            
            const alertHtml = `
                <div class="pinsavepinterest-alert alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            
            $('.pinsavepinterest-bootstrap-container').prepend(alertHtml);
            
            // Auto-dismiss after 3 seconds
            setTimeout(() => {
                $('.pinsavepinterest-alert').fadeOut();
            }, 3000);
        },

        // Show reset confirmation
        showResetConfirmation: function() {
            if (confirm('Are you sure you want to reset all settings to their default values? This action cannot be undone.')) {
                this.resetSettings();
            }
        },

        // Reset settings to defaults
        resetSettings: function() {
            $('input[name="pinsavepinterest_button_location"][value="top-right"]').prop('checked', true);
            $('input[name="pinsavepinterest_button_bg_color"]').val('#E60023');
            $('input[name="pinsavepinterest_font_color"]').val('#ffffff');
            $('input[name="pinsavepinterest_pin_text"]').val('Save');
            $('input[name="pinsavepinterest_autohide"]').prop('checked', false);
            
            // Update all visual elements
            this.updatePreview();
            $('.pinsavepinterest-color-input').each((index, element) => {
                this.updateColorPreview($(element));
            });
            this.updatePositionVisual('top-right');
            
            this.showSuccessMessage('Settings have been reset to default values.');
        },

        // Export settings
        exportSettings: function() {
            const settings = {
                button_location: $('input[name="pinsavepinterest_button_location"]:checked').val(),
                button_bg_color: $('input[name="pinsavepinterest_button_bg_color"]').val(),
                font_color: $('input[name="pinsavepinterest_font_color"]').val(),
                pin_text: $('input[name="pinsavepinterest_pin_text"]').val(),
                autohide: $('input[name="pinsavepinterest_autohide"]').is(':checked'),
                exported_at: new Date().toISOString(),
                plugin_version: '1.1'
            };
            
            const dataStr = JSON.stringify(settings, null, 2);
            const dataBlob = new Blob([dataStr], {type: 'application/json'});
            
            const link = document.createElement('a');
            link.href = URL.createObjectURL(dataBlob);
            link.download = `pin-it-button-settings-${new Date().toISOString().split('T')[0]}.json`;
            link.click();
            
            this.showSuccessMessage('Settings exported successfully!');
        },

        // Import settings
        importSettings: function(file) {
            if (!file) return;
            
            const reader = new FileReader();
            reader.onload = (e) => {
                try {
                    const settings = JSON.parse(e.target.result);
                    
                    // Validate imported settings
                    if (this.validateImportedSettings(settings)) {
                        this.applyImportedSettings(settings);
                        this.showSuccessMessage('Settings imported successfully!');
                    } else {
                        this.showValidationError('Invalid settings file. Please check the file format.');
                    }
                } catch (error) {
                    this.showValidationError('Error reading settings file. Please ensure it\'s a valid JSON file.');
                }
            };
            
            reader.readAsText(file);
        },

        // Validate imported settings
        validateImportedSettings: function(settings) {
            const requiredFields = ['button_location', 'button_bg_color', 'font_color', 'pin_text'];
            const validLocations = ['top-left', 'top-right', 'bottom-left', 'bottom-right'];
            
            // Check required fields
            for (const field of requiredFields) {
                if (!(field in settings)) {
                    return false;
                }
            }
            
            // Validate specific fields
            if (!validLocations.includes(settings.button_location)) return false;
            if (!this.isValidColor(settings.button_bg_color)) return false;
            if (!this.isValidColor(settings.font_color)) return false;
            if (typeof settings.pin_text !== 'string' || settings.pin_text.length > 20) return false;
            
            return true;
        },

        // Apply imported settings
        applyImportedSettings: function(settings) {
            $(`input[name="pinsavepinterest_button_location"][value="${settings.button_location}"]`).prop('checked', true);
            $('input[name="pinsavepinterest_button_bg_color"]').val(settings.button_bg_color);
            $('input[name="pinsavepinterest_font_color"]').val(settings.font_color);
            $('input[name="pinsavepinterest_pin_text"]').val(settings.pin_text);
            $('input[name="pinsavepinterest_autohide"]').prop('checked', settings.autohide || false);
            
            // Update all visual elements
            this.updatePreview();
            $('.pinsavepinterest-color-input').each((index, element) => {
                this.updateColorPreview($(element));
            });
            this.updatePositionVisual(settings.button_location);
        },

        // Initialize tooltips
        initializeTooltips: function() {
            // Add tooltips to form elements
            $('[data-bs-toggle="tooltip"]').each(function() {
                if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                    new bootstrap.Tooltip(this);
                }
            });
        },

        // Initialize dark mode
        initializeDarkMode: function() {
            // Check for saved dark mode preference
            const darkMode = localStorage.getItem('pinsavepinterest-dark-mode') === 'true';
            if (darkMode) {
                this.enableDarkMode();
            }
        },

        // Toggle dark mode
        toggleDarkMode: function() {
            const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            
            if (isDark) {
                this.disableDarkMode();
            } else {
                this.enableDarkMode();
            }
        },

        // Enable dark mode
        enableDarkMode: function() {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            localStorage.setItem('pinsavepinterest-dark-mode', 'true');
            $('.pinsavepinterest-dark-mode-toggle i').removeClass('bi-moon').addClass('bi-sun');
        },

        // Disable dark mode
        disableDarkMode: function() {
            document.documentElement.setAttribute('data-bs-theme', 'light');
            localStorage.setItem('pinsavepinterest-dark-mode', 'false');
            $('.pinsavepinterest-dark-mode-toggle i').removeClass('bi-sun').addClass('bi-moon');
        }
    };

    // Initialize accessibility features
        initializeAccessibility: function() {
            // Add skip links
            this.addSkipLinks();
            
            // Enhance keyboard navigation
            this.enhanceKeyboardNavigation();
            
            // Add ARIA labels and descriptions
            this.addAriaLabels();
            
            // Manage focus for screen readers
            this.manageFocus();
            
            // Add live regions for dynamic content
            this.addLiveRegions();
        },

        // Add skip links for keyboard navigation
        addSkipLinks: function() {
            const skipLinksHtml = `
                <div class="pinsavepinterest-skip-links">
                    <a href="#pinsavepinterest-main-content" class="pinsavepinterest-skip-link">Skip to main content</a>
                    <a href="#pinsavepinterest-settings-form" class="pinsavepinterest-skip-link">Skip to settings form</a>
                    <a href="#pinsavepinterest-preview" class="pinsavepinterest-skip-link">Skip to preview</a>
                </div>
            `;
            
            $('.pinsavepinterest-bootstrap-container').prepend(skipLinksHtml);
            
            // Add IDs to target elements
            $('.pinsavepinterest-bootstrap-container').attr('id', 'pinsavepinterest-main-content');
            $('#pinsavepinterest-settings-form').attr('id', 'pinsavepinterest-settings-form');
            $('.pinsavepinterest-preview-card').attr('id', 'pinsavepinterest-preview');
        },

        // Enhance keyboard navigation
        enhanceKeyboardNavigation: function() {
            // Arrow key navigation for position selector
            $(document).on('keydown', '.pinsavepinterest-position-option', function(e) {
                const $current = $(this);
                const $options = $('.pinsavepinterest-position-option');
                const currentIndex = $options.index($current);
                let nextIndex = currentIndex;
                
                switch (e.key) {
                    case 'ArrowRight':
                    case 'ArrowDown':
                        e.preventDefault();
                        nextIndex = (currentIndex + 1) % $options.length;
                        break;
                    case 'ArrowLeft':
                    case 'ArrowUp':
                        e.preventDefault();
                        nextIndex = (currentIndex - 1 + $options.length) % $options.length;
                        break;
                    case 'Home':
                        e.preventDefault();
                        nextIndex = 0;
                        break;
                    case 'End':
                        e.preventDefault();
                        nextIndex = $options.length - 1;
                        break;
                    default:
                        return;
                }
                
                $options.eq(nextIndex).focus();
            });
            
            // Escape key to close modals/dropdowns
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    // Close any open tooltips
                    $('.tooltip').remove();
                    
                    // Close any open alerts
                    $('.pinsavepinterest-alert .btn-close').click();
                }
            });
        },

        // Add ARIA labels and descriptions
        addAriaLabels: function() {
            // Position selector
            $('.pinsavepinterest-position-selector').attr({
                'role': 'radiogroup',
                'aria-labelledby': 'position-label'
            });
            
            // Add position label ID
            $('.pinsavepinterest-card-title:contains("Button Position")').attr('id', 'position-label');
            
            // Color inputs
            $('input[name="pinsavepinterest_button_bg_color"]').attr({
                'aria-describedby': 'bg-color-help',
                'aria-label': 'Button background color in hex format'
            });
            
            $('input[name="pinsavepinterest_font_color"]').attr({
                'aria-describedby': 'font-color-help',
                'aria-label': 'Button text color in hex format'
            });
            
            // Pin text input
            $('input[name="pinsavepinterest_pin_text"]').attr({
                'aria-describedby': 'pin-text-help',
                'aria-label': 'Text displayed on the Pin It button'
            });
            
            // Autohide checkbox
            $('input[name="pinsavepinterest_autohide"]').attr({
                'aria-describedby': 'autohide-help',
                'aria-label': 'Hide button until user hovers over image'
            });
            
            // Add help text IDs
            $('.pinsavepinterest-form-text').each(function(index) {
                const $this = $(this);
                const $input = $this.siblings('input, select');
                if ($input.length > 0) {
                    const helpId = 'help-text-' + index;
                    $this.attr('id', helpId);
                    $input.attr('aria-describedby', helpId);
                }
            });
        },

        // Manage focus for better accessibility
        manageFocus: function() {
            // Focus management for form submission
            $(document).on('submit', '#pinsavepinterest-settings-form', function() {
                // Announce form submission to screen readers
                this.announceToScreenReader('Saving settings...');
            }.bind(this));
            
            // Focus management for validation errors
            $(document).on('invalid', '.pinsavepinterest-form-control', function() {
                const $input = $(this);
                setTimeout(() => {
                    $input.focus();
                    this.announceToScreenReader('Please correct the error in ' + $input.attr('aria-label') || $input.attr('name'));
                }.bind(this), 100);
            }.bind(this));
            
            // Focus trap for modals (if any)
            this.setupFocusTrap();
        },

        // Add live regions for dynamic content announcements
        addLiveRegions: function() {
            // Add live region for status messages
            const liveRegionHtml = `
                <div id="pinsavepinterest-live-region" 
                     class="pinsavepinterest-sr-only" 
                     aria-live="polite" 
                     aria-atomic="true">
                </div>
                <div id="pinsavepinterest-live-region-assertive" 
                     class="pinsavepinterest-sr-only" 
                     aria-live="assertive" 
                     aria-atomic="true">
                </div>
            `;
            
            $('body').append(liveRegionHtml);
        },

        // Announce messages to screen readers
        announceToScreenReader: function(message, assertive = false) {
            const regionId = assertive ? '#pinsavepinterest-live-region-assertive' : '#pinsavepinterest-live-region';
            const $region = $(regionId);
            
            // Clear previous message
            $region.text('');
            
            // Add new message after a brief delay to ensure it's announced
            setTimeout(() => {
                $region.text(message);
            }, 100);
            
            // Clear message after announcement
            setTimeout(() => {
                $region.text('');
            }, 3000);
        },

        // Setup focus trap for modal dialogs
        setupFocusTrap: function() {
            // This would be used if we had modal dialogs
            // For now, we'll prepare the structure for future use
            this.focusableElements = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
        },

        // Enhanced validation with accessibility
        validateFormAccessible: function() {
            let isValid = true;
            const errors = [];
            
            // Validate color inputs
            $('.pinsavepinterest-color-input').each((index, element) => {
                const $input = $(element);
                if (!this.validateColor($input)) {
                    isValid = false;
                    const fieldName = $input.attr('aria-label') || $input.attr('name');
                    errors.push(`${fieldName}: Invalid color format`);
                }
            });
            
            // Validate pin text
            const $pinTextInput = $('input[name="pinsavepinterest_pin_text"]');
            if (!this.validatePinText($pinTextInput)) {
                isValid = false;
                errors.push('Pin text: Required and must be 20 characters or less');
            }
            
            // Show accessible validation summary
            if (!isValid) {
                this.showAccessibleValidationSummary(errors);
                this.announceToScreenReader(`Form has ${errors.length} error${errors.length > 1 ? 's' : ''}. Please review and correct.`, true);
            }
            
            return isValid;
        },

        // Show accessible validation summary
        showAccessibleValidationSummary: function(errors) {
            // Remove existing summary
            $('.pinsavepinterest-validation-summary').remove();
            
            const errorList = errors.map(error => `<li>${error}</li>`).join('');
            const summaryHtml = `
                <div class="pinsavepinterest-validation-summary" role="alert" aria-labelledby="validation-heading">
                    <h4 id="validation-heading">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Please correct the following errors:
                    </h4>
                    <ul>
                        ${errorList}
                    </ul>
                </div>
            `;
            
            $('.pinsavepinterest-bootstrap-container').prepend(summaryHtml);
            
            // Focus the validation summary
            $('.pinsavepinterest-validation-summary').focus();
        },

        // Enhanced success message with accessibility
        showAccessibleSuccessMessage: function(message) {
            // Remove existing alerts
            $('.pinsavepinterest-alert').remove();
            
            const alertHtml = `
                <div class="pinsavepinterest-alert alert alert-success alert-dismissible fade show" 
                     role="alert" 
                     aria-labelledby="success-heading">
                    <h4 id="success-heading" class="alert-heading">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Success
                    </h4>
                    <p class="mb-0">${message}</p>
                    <button type="button" 
                            class="btn-close" 
                            data-bs-dismiss="alert" 
                            aria-label="Close success message">
                    </button>
                </div>
            `;
            
            $('.pinsavepinterest-bootstrap-container').prepend(alertHtml);
            
            // Announce to screen readers
            this.announceToScreenReader(message);
            
            // Focus the alert for screen readers
            $('.pinsavepinterest-alert').focus();
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                $('.pinsavepinterest-alert').fadeOut();
            }, 5000);
        },

        // Enhanced error message with accessibility
         showAccessibleErrorMessage: function(message) {
             // Remove existing alerts
             $('.pinsavepinterest-alert').remove();
             
             const alertHtml = `
                 <div class="pinsavepinterest-alert alert alert-danger alert-dismissible fade show" 
                      role="alert" 
                      aria-labelledby="error-heading">
                     <h4 id="error-heading" class="alert-heading">
                         <i class="bi bi-exclamation-triangle-fill me-2"></i>
                         Error
                     </h4>
                     <p class="mb-0">${message}</p>
                     <button type="button" 
                             class="btn-close" 
                             data-bs-dismiss="alert" 
                             aria-label="Close error message">
                     </button>
                 </div>
             `;
             
             $('.pinsavepinterest-bootstrap-container').prepend(alertHtml);
             
             // Announce to screen readers
             this.announceToScreenReader(message, true);
             
             // Focus the alert for screen readers
             $('.pinsavepinterest-alert').focus();
         }
    };

    // Make PinSavePinterest globally available
    window.PinSavePinterest = PinSavePinterest;
    
    // WordPress compatibility: Only auto-initialize if not in WordPress admin
    if (typeof ajaxurl === 'undefined' || !ajaxurl.includes('wp-admin')) {
        // Initialize when document is ready (for standalone usage)
        $(document).ready(function() {
            PinSavePinterest.init();
        });
    }

})(jQuery);