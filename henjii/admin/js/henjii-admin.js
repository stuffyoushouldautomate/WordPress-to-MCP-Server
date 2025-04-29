/**
 * Admin JavaScript for Henjii MCP Server
 * 
 * Handles all admin interactions including theme switching, API testing,
 * and dynamic UI elements.
 */

(function($) {
    'use strict';

    // API Testing Feature
    function initApiTesting() {
        const methodButtons = $('.henjii-api-test-method-button');
        const addParamButton = $('.henjii-api-test-add-param');
        const testButton = $('.henjii-api-test-button');
        const clearButton = $('.henjii-api-test-clear');
        const resultContainer = $('.henjii-api-test-result');
        const endpointSelect = $('#henjii-api-test-endpoint');
        const paramsContainer = $('.henjii-api-test-params-container');
        
        // Method selection
        methodButtons.on('click', function() {
            methodButtons.removeClass('active');
            $(this).addClass('active');
        });
        
        // Add parameter row
        addParamButton.on('click', function() {
            const paramRow = `
                <div class="henjii-api-test-param-row">
                    <input type="text" class="henjii-form-input" placeholder="Parameter name">
                    <input type="text" class="henjii-form-input" placeholder="Value">
                    <button type="button" class="henjii-api-test-param-remove">×</button>
                </div>
            `;
            paramsContainer.append(paramRow);
        });
        
        // Remove parameter row
        $(document).on('click', '.henjii-api-test-param-remove', function() {
            $(this).closest('.henjii-api-test-param-row').remove();
        });
        
        // Clear form
        clearButton.on('click', function() {
            endpointSelect.val('');
            paramsContainer.empty();
            resultContainer.hide();
        });
        
        // Test API request
        testButton.on('click', function() {
            const endpoint = endpointSelect.val();
            const method = $('.henjii-api-test-method-button.active').data('method');
            const params = {};
            
            // Collect parameters
            $('.henjii-api-test-param-row').each(function() {
                const inputs = $(this).find('input');
                const paramName = $(inputs[0]).val();
                const paramValue = $(inputs[1]).val();
                
                if (paramName && paramValue) {
                    params[paramName] = paramValue;
                }
            });
            
            // Show loading state
            testButton.prop('disabled', true).text('Testing...');
            
            // Make the API request
            $.ajax({
                url: henjiiAdmin.apiBaseUrl + endpoint,
                type: method,
                data: method === 'GET' ? params : JSON.stringify(params),
                contentType: method !== 'GET' ? 'application/json' : 'application/x-www-form-urlencoded',
                dataType: 'json',
                success: function(response, status, xhr) {
                    displayApiResult(true, xhr.status, response);
                },
                error: function(xhr) {
                    let errorResponse;
                    try {
                        errorResponse = JSON.parse(xhr.responseText);
                    } catch (e) {
                        errorResponse = { error: xhr.responseText || 'Unknown error' };
                    }
                    displayApiResult(false, xhr.status, errorResponse);
                },
                complete: function() {
                    testButton.prop('disabled', false).text('Test Request');
                }
            });
        });
        
        // Display API test results
        function displayApiResult(success, statusCode, data) {
            const statusClass = success ? 'success' : 'error';
            const statusText = success ? 'Success' : 'Error';
            const formattedJson = JSON.stringify(data, null, 2);
            
            const resultHtml = `
                <div class="henjii-api-test-result-header">
                    <span>Status: <span class="henjii-api-test-result-status ${statusClass}">${statusCode} ${statusText}</span></span>
                    <button type="button" class="henjii-button henjii-button-small henjii-api-test-copy">Copy</button>
                </div>
                <div class="henjii-api-test-result-content">
                    <pre>${formattedJson}</pre>
                </div>
            `;
            
            resultContainer.html(resultHtml).show();
            
            // Copy result to clipboard
            $('.henjii-api-test-copy').on('click', function() {
                const tempTextarea = $('<textarea>');
                $('body').append(tempTextarea);
                tempTextarea.val(formattedJson).select();
                document.execCommand('copy');
                tempTextarea.remove();
                
                const originalText = $(this).text();
                $(this).text('Copied!');
                setTimeout(() => {
                    $(this).text(originalText);
                }, 2000);
            });
        }
    }

    // Tabs Navigation
    function initTabs() {
        const tabs = $('.henjii-tab');
        const tabContents = $('.henjii-tab-content');
        
        tabs.on('click', function() {
            const target = $(this).data('target');
            
            tabs.removeClass('active');
            $(this).addClass('active');
            
            tabContents.hide();
            $(`#${target}`).show();
            
            // Save active tab to localStorage
            localStorage.setItem('henjii-active-tab', target);
        });
        
        // Set initial active tab
        const activeTab = localStorage.getItem('henjii-active-tab');
        if (activeTab) {
            $(`.henjii-tab[data-target="${activeTab}"]`).click();
        } else {
            tabs.first().click();
        }
    }

    // Copy to clipboard functionality
    function initCopyButtons() {
        $('.henjii-copy-button').on('click', function() {
            const textToCopy = $($(this).data('target')).text();
            const tempTextarea = $('<textarea>');
            
            $('body').append(tempTextarea);
            tempTextarea.val(textToCopy).select();
            document.execCommand('copy');
            tempTextarea.remove();
            
            const originalText = $(this).text();
            $(this).text('Copied!');
            setTimeout(() => {
                $(this).text(originalText);
            }, 2000);
        });
    }

    // Toggle sections
    function initToggles() {
        $('.henjii-toggle-section-header').on('click', function() {
            const section = $(this).closest('.henjii-toggle-section');
            section.toggleClass('collapsed');
            section.find('.henjii-toggle-section-content').slideToggle(200);
        });
    }

    // API Key Generation
    function initApiKeyGeneration() {
        $(document).on('click', '.henjii-generate-api-key', function() {
            var $btn = $(this);
            $btn.prop('disabled', true).text('Generating...');
            $.post(henjiiAdmin.ajaxUrl, {
                action: 'henjii_generate_api_key',
                _ajax_nonce: henjiiAdmin.nonce
            }, function(response) {
                if (response.success && response.data && response.data.api_key) {
                    // Add new key to the list
                    var keyHtml = '<div class="henjii-api-key-row">' +
                        '<input type="text" class="henjii-form-input" value="' + response.data.api_key + '" readonly>' +
                        '<button type="button" class="henjii-button henjii-button-secondary henjii-copy-button" data-target="#api-key-new">Copy</button>' +
                        '<button type="button" class="henjii-button henjii-button-secondary henjii-delete-api-key">Delete</button>' +
                        '</div>';
                    $('.henjii-api-keys .henjii-api-key-row:last').after(keyHtml);
                    // Remove 'No API keys' message if present
                    $('.henjii-api-keys p:contains("No API keys")').remove();
                } else {
                    alert('Failed to generate API key.');
                }
            }).fail(function() {
                alert('Failed to generate API key.');
            }).always(function() {
                $btn.prop('disabled', false).text('Generate New API Key');
            });
        });
    }

    // Initialize all components
    $(document).ready(function() {
        initApiTesting();
        initTabs();
        initCopyButtons();
        initToggles();
        initApiKeyGeneration();
    });

})(jQuery);
