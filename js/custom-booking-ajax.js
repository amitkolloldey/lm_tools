jQuery(document).ready(function ($) {
    const appearance = {
        theme: 'flat',
        variables: {
            fontFamily: ' "Gill Sans", sans-serif',
            fontLineHeight: '1.5',
            borderRadius: '10px',
            colorBackground: '#F6F8FA',
            accessibleColorOnColorPrimary: '#262626'
        },
        rules: {
            '.Block': {
                backgroundColor: 'var(--colorBackground)',
                boxShadow: 'none',
                padding: '12px'
            },
            '.Input': {
                padding: '12px'
            },
            '.Input:disabled, .Input--invalid:disabled': {
                color: 'lightgray'
            },
            '.Tab': {
                padding: '10px 12px 8px 12px',
                border: 'none'
            },
            '.Tab:hover': {
                border: 'none',
                boxShadow: '0px 1px 1px rgba(0, 0, 0, 0.03), 0px 3px 7px rgba(18, 42, 66, 0.04)'
            },
            '.Tab--selected, .Tab--selected:focus, .Tab--selected:hover': {
                border: 'none',
                backgroundColor: '#fff',
                boxShadow: '0 0 0 1.5px var(--colorPrimaryText), 0px 1px 1px rgba(0, 0, 0, 0.03), 0px 3px 7px rgba(18, 42, 66, 0.04)'
            },
            '.Label': {
                fontWeight: '500'
            }
        }
    };

    var stripe = Stripe('pk_test_En0qw8rM8y1PNnTSI3CXU41I');
    var elements = stripe.elements({ appearance });

    var cardElement = elements.create('card');

    cardElement.mount('#card-element');


    var form = document.querySelector('.sb_form');

    function handleFormSubmission() {
        var isValid = validateForm();
        if (!isValid) {
            alert('Please fill in the required fields');
            return;
        }

        stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
        }).then(function (result) {
            if (result.error) {
                alert(result.error.message);
            } else {
                var nonce = customBookingAjax.nonce;
                var selectedTimeSlots = getSelectedTimeSlots();
                var selectedTimeSlotsjsonString = JSON.stringify(selectedTimeSlots);

                var formData = {
                    action: 'custom_booking_ajax_handler',
                    nonce: nonce,
                    studio_id: $('#studio_id').val(),
                    total: $('#total').val(),
                    pick_a_date: $('#pick_a_date').val(),
                    hours: $('#hours').val(),
                    customer_name: $('#customer_name').val(),
                    customer_email: $('#customer_email').val(),
                    customer_phone_number: $('#customer_phone_number').val(),
                    payment_method_id: result.paymentMethod.id,
                    selected_time_slots: selectedTimeSlotsjsonString
                };

                $.ajax({
                    type: 'POST',
                    url: customBookingAjax.ajaxurl,
                    data: formData,
                    success: function (response) {
                        console.log(response);
                        var data = JSON.parse(response);
                        if (data.success) {
                            $('.success_payment').text("Your Booking Has Been Confirmed!");
                            $('.error_payment').hide(); 
                            window.location.href = 'https://lukeboxstudios.com/thank-you/';
                        } else {
                            if (data.error) {
                                // Handle specific error condition
                                var errorMessage = "Payment failed: " + data.error;
                                $('.error_payment').text(errorMessage);
                            } else {
                                // Handle general error
                                $('.error_payment').text("Payment failed. Please try again later.");
                            }
                        }
                    },
                    error: function (error) {
                        alert("Payment failed. Please try again later.");
                        console.error(error);
                    }
                });

            }
        });
    }
    function getSelectedTimeSlots() {
        // Assuming your time slots are stored in an array
        var selectedTimeSlots = [];
        $('.checkboxes_slot input[name="timeslot"]:checked').each(function () {
            selectedTimeSlots.push($(this).val());
        });

        return selectedTimeSlots;
    }

    $('#final_submit').on('click', function (event) {
        event.preventDefault();
        handleFormSubmission();
    });

    function validateForm() {
        var invalidFields = $('.sb_input-group :invalid');
        invalidFields.addClass('required-error');
        return invalidFields.length === 0;
    }
});
