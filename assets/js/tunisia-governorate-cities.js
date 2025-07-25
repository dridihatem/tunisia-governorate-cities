jQuery(document).ready(function($) {
    'use strict';
    
    // Function to update city dropdown
    function updateCityDropdown(governorate, targetField) {
        if (!governorate) {
            $(targetField).html('<option value="">' + tgc_ajax.select_city || 'Select City' + '</option>');
            return;
        }
        
        $.ajax({
            url: tgc_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_cities',
                governorate: governorate,
                nonce: tgc_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    var options = '<option value="">' + (tgc_ajax.select_city || 'Select City') + '</option>';
                    $.each(response.data, function(key, value) {
                        options += '<option value="' + key + '">' + value + '</option>';
                    });
                    $(targetField).html(options);
                }
            },
            error: function() {
                console.error('Error loading cities');
            }
        });
    }
    
    // Handle governorate change for billing
    $(document.body).on('change', '#billing_governorate', function() {
        var governorate = $(this).val();
        updateCityDropdown(governorate, '#billing_city');
        
        // Clear city field when governorate changes
        $('#billing_city').val('');
    });
    
    // Handle governorate change for shipping
    $(document.body).on('change', '#shipping_governorate', function() {
        var governorate = $(this).val();
        updateCityDropdown(governorate, '#shipping_city');
        
        // Clear city field when governorate changes
        $('#shipping_city').val('');
    });
    
    // Handle "ship to different address" checkbox
    $(document.body).on('change', '#ship-to-different-address-checkbox', function() {
        if ($(this).is(':checked')) {
            // When shipping to different address is checked, copy billing governorate to shipping
            var billingGovernorate = $('#billing_governorate').val();
            if (billingGovernorate) {
                $('#shipping_governorate').val(billingGovernorate);
                updateCityDropdown(billingGovernorate, '#shipping_city');
            }
        }
    });
    
    // Handle billing governorate change when shipping to different address
    $(document.body).on('change', '#billing_governorate', function() {
        if ($('#ship-to-different-address-checkbox').is(':checked')) {
            var governorate = $(this).val();
            $('#shipping_governorate').val(governorate);
            updateCityDropdown(governorate, '#shipping_city');
        }
    });
    
    // Initialize city dropdowns on page load
    function initializeCityDropdowns() {
        var billingGovernorate = $('#billing_governorate').val();
        var shippingGovernorate = $('#shipping_governorate').val();
        
        if (billingGovernorate) {
            updateCityDropdown(billingGovernorate, '#billing_city');
        }
        
        if (shippingGovernorate) {
            updateCityDropdown(shippingGovernorate, '#shipping_city');
        }
    }
    
    // Run initialization after a short delay to ensure DOM is ready
    setTimeout(initializeCityDropdowns, 500);
    
    // Also run initialization when checkout form is updated
    $(document.body).on('updated_checkout', function() {
        setTimeout(initializeCityDropdowns, 100);
    });
    
    // Add validation for city field
    $(document.body).on('checkout_error', function() {
        // Re-initialize city dropdowns after checkout error
        setTimeout(initializeCityDropdowns, 100);
    });
    
    // Add custom validation
    $(document.body).on('checkout_place_order', function() {
        var billingGovernorate = $('#billing_governorate').val();
        var billingCity = $('#billing_city').val();
        var shippingGovernorate = $('#shipping_governorate').val();
        var shippingCity = $('#shipping_city').val();
        
        // Validate billing fields
        if (!billingGovernorate) {
            $('html, body').animate({
                scrollTop: $('#billing_governorate').offset().top - 100
            }, 500);
            return false;
        }
        
        if (!billingCity) {
            $('html, body').animate({
                scrollTop: $('#billing_city').offset().top - 100
            }, 500);
            return false;
        }
        
        // Validate shipping fields if shipping to different address
        if ($('#ship-to-different-address-checkbox').is(':checked')) {
            if (!shippingGovernorate) {
                $('html, body').animate({
                    scrollTop: $('#shipping_governorate').offset().top - 100
                }, 500);
                return false;
            }
            
            if (!shippingCity) {
                $('html, body').animate({
                    scrollTop: $('#shipping_city').offset().top - 100
                }, 500);
                return false;
            }
        }
    });
    
    // Add loading indicator
    function showLoading(field) {
        $(field).addClass('loading');
        $(field).prop('disabled', true);
    }
    
    function hideLoading(field) {
        $(field).removeClass('loading');
        $(field).prop('disabled', false);
    }
    
    // Enhanced updateCityDropdown with loading indicator
    function updateCityDropdownWithLoading(governorate, targetField) {
        if (!governorate) {
            $(targetField).html('<option value="">' + (tgc_ajax.select_city || 'Select City') + '</option>');
            return;
        }
        
        showLoading(targetField);
        
        $.ajax({
            url: tgc_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_cities',
                governorate: governorate,
                nonce: tgc_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    var options = '<option value="">' + (tgc_ajax.select_city || 'Select City') + '</option>';
                    $.each(response.data, function(key, value) {
                        options += '<option value="' + key + '">' + value + '</option>';
                    });
                    $(targetField).html(options);
                }
                hideLoading(targetField);
            },
            error: function() {
                console.error('Error loading cities');
                hideLoading(targetField);
                $(targetField).html('<option value="">' + (tgc_ajax.error_loading || 'Error loading cities') + '</option>');
            }
        });
    }
    
    // Replace the original updateCityDropdown calls with the enhanced version
    $(document.body).off('change', '#billing_governorate').on('change', '#billing_governorate', function() {
        var governorate = $(this).val();
        updateCityDropdownWithLoading(governorate, '#billing_city');
        $('#billing_city').val('');
    });
    
    $(document.body).off('change', '#shipping_governorate').on('change', '#shipping_governorate', function() {
        var governorate = $(this).val();
        updateCityDropdownWithLoading(governorate, '#shipping_city');
        $('#shipping_city').val('');
    });
}); 