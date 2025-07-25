jQuery(document).ready(function($) {
    'use strict';
    
    // Initialize Select2 on all dropdowns
    function initializeSelect2() {
        $('#billing_governorate, #shipping_governorate, #billing_city, #shipping_city').select2({
            placeholder: tgc_ajax.select2_placeholder || 'Search...',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return tgc_ajax.no_results || 'No results found';
                }
            }
        });
    }
    
    // Function to update city dropdown
    function updateCityDropdown(governorate, targetField) {
        if (!governorate) {
            $(targetField).html('<option value="">' + (tgc_ajax.select_city || 'Select City') + '</option>');
            $(targetField).trigger('change');
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
                    $(targetField).trigger('change');
                } else {
                    $(targetField).html('<option value="">' + (tgc_ajax.error_loading || 'Error loading cities') + '</option>');
                    $(targetField).trigger('change');
                }
            },
            error: function() {
                $(targetField).html('<option value="">' + (tgc_ajax.error_loading || 'Error loading cities') + '</option>');
                $(targetField).trigger('change');
            }
        });
    }
    
    // Initialize Select2 when page loads
    initializeSelect2();
    
    // Handle governorate change for billing
    $('#billing_governorate').on('change', function() {
        var governorate = $(this).val();
        updateCityDropdown(governorate, '#billing_city');
    });
    
    // Handle governorate change for shipping
    $('#shipping_governorate').on('change', function() {
        var governorate = $(this).val();
        updateCityDropdown(governorate, '#shipping_city');
    });
    
    // Handle "ship to different address" checkbox
    $('input[name="ship_to_different_address"]').on('change', function() {
        if ($(this).is(':checked')) {
            // Re-initialize Select2 for shipping fields when they become visible
            setTimeout(function() {
                $('#shipping_governorate, #shipping_city').select2({
                    placeholder: tgc_ajax.select2_placeholder || 'Search...',
                    allowClear: true,
                    width: '100%',
                    language: {
                        noResults: function() {
                            return tgc_ajax.no_results || 'No results found';
                        }
                    }
                });
            }, 100);
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
    
    // Initialize on page load
    initializeCityDropdowns();
    
    // Client-side validation
    $('form.checkout').on('checkout_place_order', function() {
        var billingGovernorate = $('#billing_governorate').val();
        var billingCity = $('#billing_city').val();
        
        if (!billingGovernorate) {
            $('.woocommerce-error').remove();
            $('form.checkout').prepend('<div class="woocommerce-error">Please select a governorate.</div>');
            return false;
        }
        
        if (!billingCity) {
            $('.woocommerce-error').remove();
            $('form.checkout').prepend('<div class="woocommerce-error">Please select a city.</div>');
            return false;
        }
        
        return true;
    });
}); 