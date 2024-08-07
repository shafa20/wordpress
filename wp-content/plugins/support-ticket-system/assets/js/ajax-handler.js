// //user live message use seen ajax
// jQuery(document).ready(function($) {
//     // Adjust the URL as needed
//     var ajaxUrl = ajax_object.ajax_url; // This is set in the localized script

//     // Reload content every 2 seconds
//     setInterval(function() {
//         $.ajax({
//             url: ajaxUrl,
//             type: 'POST',
//             data: {
//                 action: 'fetch_replies', // The action hook that will be handled in PHP
//                 ticket_id: ajax_object.ticket_id // Pass the ticket ID
//             },
//             success: function(response) {
//                 $('.messages').html(response);
//             },
//             error: function(xhr, status, error) {
//                 console.error('Error fetching replies:', error);
//             }
//         });
//     }, 2000); // 2000 milliseconds = 2 seconds
// });

// // user reply use ajax

jQuery(document).ready(function($) {
    // $('#reply').on('keydown', function(e) {
    //     if (e.key === 'Enter' && !e.shiftKey) {
    //         e.preventDefault(); // Prevent default form submission on Enter key press
    //     }
    // });
    $('#reply').on('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault(); // Prevent default form submission on Enter key press
            const textarea = $(this);
            const value = textarea.val();
            textarea.val(value + '\n'); // Append newline character
        }
    });

    $('#reply-form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this); // Serialize the form data
        formData.append('action', 'sts_handle_ticket_reply'); // Add the action parameter

        var $submitButton = $('#submit-button');
        $submitButton.prop('disabled', true); // Disable the submit button

        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url, // WordPress AJAX URL
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    var chatBody = $('.messages');
                    chatBody.empty(); // Clear the chat body

                    var previousDate = null;

                    // Loop through the returned replies and append them to the chat body
                    response.data.forEach(function(reply) {
                        var messageDate = reply.replied_at.split(' ')[0]; // Get the date part only

                        // Add date separator if it's a new date
                        if (previousDate !== messageDate) {
                            var dateHtml = '<div class="line-38"><div class="date">' + messageDate + '</div></div>';
                            chatBody.append(dateHtml);
                            previousDate = messageDate;
                        }

                        var messageClass = reply.user_role.includes('administrator') ? 'in-coming' : 'outgoing-message';
                        var replyHtml = '<div class="message-item ' + messageClass + '">';
                        replyHtml += '<div class="message-content">';
                        if (reply.image_url) {
                            var imageName = reply.image_url.split('/').pop(); 
                            replyHtml += '<div class="lightbox-trigger message-image" data-image="' + encodeURIComponent(reply.image_url) + '">' + imageName + '</div>'; 
                        }
                        replyHtml += '<p>' + reply.message + '</p>';
                        replyHtml += '<div class="message-item-footer"><span class="time">' + reply.replied_at + '</span></div>';
                        replyHtml += '</div></div>';
                        chatBody.append(replyHtml);
                    });

                    $('#reply').val(''); // Clear the text input field
                    $('#fileInput').val(''); // Clear the file input field
                    $('#imagePreview').hide(); // Hide the image preview

                    // Scroll the chat body to the bottom after images are loaded
                    var scrollbarContainer = $('.scrollbar-container');
                    var images = chatBody.find('img');
                    var imagesLoaded = 0;

                    images.each(function() {
                        $(this).on('load', function() {
                            imagesLoaded++;
                            if (imagesLoaded === images.length) {
                                scrollbarContainer.scrollTop(scrollbarContainer.prop("scrollHeight"));
                            }
                        }).on('error', function() {
                            imagesLoaded++;
                            if (imagesLoaded === images.length) {
                                scrollbarContainer.scrollTop(scrollbarContainer.prop("scrollHeight"));
                            }
                        });
                    });

                    // Fallback for when there are no images
                    if (images.length === 0) {
                        scrollbarContainer.scrollTop(scrollbarContainer.prop("scrollHeight"));
                    }

                    // Re-enable the submit button
                    $submitButton.prop('disabled', false);

                    // Reinitialize lightbox triggers
                    initializeLightboxTriggers();
                } else {
                    alert('Error: ' + response.data.message);
                    $submitButton.prop('disabled', false);
                    console.log(response.data); // For debugging
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
                console.error('Error: ' + error); // For debugging
            },
            complete: function() {
                $submitButton.prop('disabled', false); // Re-enable the submit button after the request completes
            }
        });
    });

    function initializeLightboxTriggers() {
        document.querySelectorAll('.lightbox-trigger').forEach(function(element) {
            element.addEventListener('click', function() {
                var imageSrc = decodeURIComponent(element.getAttribute('data-image'));
                var tempLink = document.createElement('a');
                tempLink.href = imageSrc;
                tempLink.setAttribute('data-lightbox', 'mygallery');
                tempLink.style.display = 'none';
                document.body.appendChild(tempLink);
                tempLink.click();
                document.body.removeChild(tempLink);
            });
        });
    }

    // Initialize lightbox triggers on document ready
    initializeLightboxTriggers();
});


//correct

// jQuery(document).ready(function($) {
//     $('#reply').on('keydown', function(e) {
//         if (e.key === 'Enter' && !e.shiftKey) {
//             e.preventDefault(); // Prevent default form submission on Enter key press
//         }
//     });

//     $('#reply-form').on('submit', function(e) {
//         e.preventDefault(); // Prevent the default form submission

//         var formData = new FormData(this); // Serialize the form data
//         formData.append('action', 'sts_handle_ticket_reply'); // Add the action parameter

//         var $submitButton = $('#submit-button');
//         $submitButton.prop('disabled', true); // Disable the submit button

//         $.ajax({
//             type: 'POST',
//             url: ajax_object.ajax_url, // WordPress AJAX URL
//             data: formData,
//             contentType: false,
//             processData: false,
//             success: function(response) {
//                 if (response.success) {
//                     var chatBody = $('.messages');
//                     chatBody.empty(); // Clear the chat body

//                     var previousDate = null;

//                     // Loop through the returned replies and append them to the chat body
//                     response.data.forEach(function(reply) {
//                         var messageDate = reply.replied_at.split(' ')[0]; // Get the date part only

//                         // Add date separator if it's a new date
//                         if (previousDate !== messageDate) {
//                             var dateHtml = '<div class="line-38"><div class="date">' + messageDate + '</div></div>';
//                             chatBody.append(dateHtml);
//                             previousDate = messageDate;
//                         }

//                         var messageClass = reply.user_role.includes('administrator') ? 'in-coming' : 'outgoing-message';
//                         var replyHtml = '<div class="message-item ' + messageClass + '">';
//                         replyHtml += '<div class="message-content">';
//                         // if (reply.image_url) {
//                         //     replyHtml += '<div class="message-image"><img src="' + reply.image_url + '" alt="Image" /></div>';
//                         // }
//                         if (reply.image_url) {
//                             var imageName = reply.image_url.split('/').pop(); 
//                             replyHtml += '<div class="lightbox-trigger message-image" data-image="' + encodeURIComponent(imageName) + '">' + imageName + '</div>'; 
//                         }
//                         replyHtml += '<p>' + reply.message + '</p>';
//                         replyHtml += '<div class="message-item-footer"><span class="time">' + reply.replied_at + '</span></div>';
//                         replyHtml += '</div></div>';
//                         chatBody.append(replyHtml);
//                     });

//                     $('#reply').val(''); // Clear the text input field
//                     $('#fileInput').val(''); // Clear the file input field
//                     $('#imagePreview').hide(); // Hide the image preview

//                     // Scroll the chat body to the bottom after images are loaded
//                     var scrollbarContainer = $('.scrollbar-container');
//                     var images = chatBody.find('img');
//                     var imagesLoaded = 0;

//                     images.each(function() {
//                         $(this).on('load', function() {
//                             imagesLoaded++;
//                             if (imagesLoaded === images.length) {
//                                 scrollbarContainer.scrollTop(scrollbarContainer.prop("scrollHeight"));
//                             }
//                         }).on('error', function() {
//                             imagesLoaded++;
//                             if (imagesLoaded === images.length) {
//                                 scrollbarContainer.scrollTop(scrollbarContainer.prop("scrollHeight"));
//                             }
//                         });
//                     });

//                     // Fallback for when there are no images
//                     if (images.length === 0) {
//                         scrollbarContainer.scrollTop(scrollbarContainer.prop("scrollHeight"));
//                     }
//                     $submitButton.prop('disabled', false);
//                 } else {
//                     //alert('Error sending reply. Please try again.');
//                     alert('Error: ' + response.data.message);
//                     $submitButton.prop('disabled', false); 
//                     console.log(response.data); // For debugging
//                 }
//             },
//             error: function(xhr, status, error) {
//                 // alert('Error: ' + response.data.message);
//                 // console.error('Error: ' + error); // For debugging
//             },
//             complete: function() {
//                 console.error('AJAX Error: ' + error); 
//                 $submitButton.prop('disabled', false); // Re-enable the submit button after the request completes
//             }
//         });
//     });
// });


// jQuery(document).ready(function($) {
//     // Prevent form submission when Enter key is pressed in the input field
//     $('#reply').on('keydown', function(e) {
//         if (e.key === 'Enter' && !e.shiftKey) {
//             e.preventDefault(); // Prevent default form submission on Enter key press
//         }
//     });
//     // Handle form submission via AJAX
//     $('#reply-form').on('submit', function(e) {
//         e.preventDefault(); // Prevent the default form submission
//         var formData = new FormData(this); // Serialize the form data
//         formData.append('action', 'sts_handle_ticket_reply'); // Add the action parameter
//         var $submitButton = $('#submit-button');
//         $submitButton.prop('disabled', true); // Disable the submit button
//         $.ajax({
//             type: 'POST',
//             url: ajax_object.ajax_url, // WordPress AJAX URL
//             data: formData,
//             contentType: false,
//             processData: false,
//             success: function(response) {
//                 if (response.success) {
//                     var chatBody = $('.messages');
//                     chatBody.empty(); // Clear the chat body
//                     var previousDate = null;
//                     // Loop through the returned replies and append them to the chat body
//                     response.data.forEach(function(reply) {
//                         var messageDate = reply.replied_at.split(' ')[0]; // Get the date part only
//                         // Add date separator if it's a new date
//                         if (previousDate !== messageDate) {
//                             var dateHtml = '<div class="line-38"><div class="date">' + messageDate + '</div></div>';
//                             chatBody.append(dateHtml);
//                             previousDate = messageDate;
//                         }
//                         var messageClass = reply.user_role.includes('administrator') ? 'in-coming' : 'outgoing-message';
//                         var replyHtml = '<div class="message-item ' + messageClass + '">';
//                         replyHtml += '<div class="message-content">';
//                         // Display image name if available
//                         if (reply.image_url) {
//                             var imageName = reply.image_url.split('/').pop();
//                             replyHtml += '<div class="message-image">' + imageName + '</div>';
//                         }
//                         replyHtml += '<p>' + reply.message + '</p>';
//                         replyHtml += '<div class="message-item-footer"><span class="time">' + reply.replied_at + '</span></div>';
//                         replyHtml += '</div></div>';
//                         chatBody.append(replyHtml);
//                     });
//                     $('#reply').val(''); // Clear the text input field
//                     $('#fileInput').val(''); // Clear the file input field
//                     $('#imagePreview').hide(); // Hide the image preview
//                     // Scroll the chat body to the bottom
//                     var scrollbarContainer = $('.scrollbar-container');
//                     scrollbarContainer.scrollTop(scrollbarContainer.prop("scrollHeight"));
//                     $submitButton.prop('disabled', false);
//                 } else {
//                     alert('Error: ' + response.data.message);
//                     $submitButton.prop('disabled', false);
//                     console.log(response.data); // For debugging
//                 }
//             },
//             error: function(xhr, status, error) {
//                 console.error('AJAX Error: ' + error);
//                 $submitButton.prop('disabled', false); // Re-enable the submit button after the request completes
//             }
//         });
//     });
// });

