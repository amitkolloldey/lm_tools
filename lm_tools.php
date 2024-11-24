<?php
/*
 Plugin Name: LM Tools
 Plugin URI: https://lukeboxstudios.com
 Description: Studio Booking System with Stripe Payment Integration.
 Version: 1.0
 Author: Amit Kollol Dey
*/



function lm_tools_custom_booking_enqueue_scripts()
{
    // wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');

    // Enqueue custom JavaScript file with AJAX script
    wp_enqueue_script('custom-booking-ajax', plugin_dir_url(__FILE__) . 'js/custom-booking-ajax.js', array('jquery'), null, true);

    wp_localize_script(
        'custom-booking-ajax',
        'customBookingAjax',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('lm_tools_custom_booking_nonce'), // Create nonce here
        )
    );

}
add_action('wp_enqueue_scripts', 'lm_tools_custom_booking_enqueue_scripts');


function lm_tools_custom_booking_form()
{
    ob_start(); ?>
    <style>
        .sb_form {
            background-color: white;
            width: clamp(320px, 30%, 430px);
            margin: 0 auto;
            border: 1px solid #ccc;
            border-radius: 0.35rem;
            padding: 1.5rem;
            z-index: 1;
        }

        .sb_input-group {
            margin: 0.5rem 0;
        }

        .sb_form-step {
            display: none;
        }

        .sb_form-step.active {
            display: block;
            transform-origin: top;
            animation: animate .5s;
        }


        /* Button */
        .sb_btn-group {
            display: flex;
            justify-content: space-between;
        }

        .sb_btn {
            padding: 0.75rem;
            display: block;
            text-decoration: none;
            width: min-content;
            border-radius: 5px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }

        .sb_btn-next {
            background-color: var(--blue-color);
            color: white;
            float: right;
        }

        .sb_btn-prev {
            background-color: #777;
            color: #fff;
        }

        .sb_btn:hover {
            box-shadow: 0 0 0 2px #fff, 0 0 0 3px var(--blue-color);
        }

        textarea {
            resize: vertical;
        }

        /* Prefixes */

        .sb_input-box {
            display: flex;
            align-items: center;
            /* max-width: 300px; */
            background: #fff;
            border: 1px solid #a0a0a0;
            border-radius: 4px;
            padding-left: 0.5rem;
            overflow: hidden;
            font-family: sans-serif;
        }

        .sb_input-box .prefix {
            font-weight: 300;
            font-size: 14px;
            color: rgb(117, 114, 114);
        }

        .sb_input-box input {
            border: none;
            outline: none;
        }

        .sb_input-box:focus-within {
            border-color: #777;
        }

        /* End Prefixes */


        /* Progress bar */
        .sb_progress-bar {
            position: relative;
            display: inline-block;
            justify-content: space-between;
            counter-reset: step;
            margin-bottom: 30px;
            width: 100%;
            padding: 20px 0;
            text-align: center;
        }



        .sb_progress-bar::before,
        .sb_progress {
            content: "";
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            height: 4px;
            width: 100%;
            background-color: #eee;
            z-index: 9;
        }

        .sb_progress {
            background-color: var(--blue-color);
            width: 0;
            transition: .5s;
            background-color: #3f0bdd;
        }

        .sb_progress-step {
            width: 35px;
            height: 35px;
            background-color: #eee;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .sb_progress-step::before {
            counter-increment: step;
            content: counter(step);
        }

        .sb_progress-step::after {
            content: attr(data-title);
            position: absolute;
            top: calc(100% + 0.20rem);
            font-size: 0.85rem;
            color: black !important;
        }

        .sb_progress-step.active {
            background-color: var(--blue-color);
            color: white;
        }

        @keyframes animate {
            from {
                transform: scale(1, 0);
                opacity: 0;
            }

            to {
                transform: scale(1, 1);
                opacity: 1;
            }
        }

        form.sb_form {
            width: 100%;
            margin: 50px auto !important;
            max-width: 100%;
            padding: 0;
        }

        .calculation {
            padding: 10px;
            text-align: center;
        }

        .base_price h2 {
            font-size: 30px;
            color: #000;
            font-weight: bold;
            margin: 0;
        }

        .base_price h2 span {
            color: #777;
            font-size: 16px;
            display: block;
        }

        .base_price h2 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 30px;
            padding-top: 30px;
        }

        .other_details {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .other_details .left {
            margin-right: 50px;
        }

        .right {
            padding: 10px;
            border-radius: 50px;
            /*background: #e07d9d;*/
            color: #3f51b5;
        }

        .total_price {
            text-align: right;
            font-size: 16px;
            padding: 0px 0;
        }

        /*.price_calculation {*/
        /*    padding: 10px;*/
        /*}*/

        .total_price span {
            padding: 10px;
            /*background: #019687;*/
            display: inline-block;
            border-radius: 50px;
            color: #3f51b5;
        }

        .steps {
            display: inline-block;
            width: 100%;
        }

        .sb_progress-step {
            background-color: var(--blue-color);
            width: 33.0% !important;
            transition: .5s;
            height: auto !important;
            position: static !important;
            transform: none !important;
            display: inline-block;
        }

        .sb_progress-step.active {
            background: #3f0bdd;
            border-radius: 0;
        }

        .sb_progress-bar::before,
        .sb_progress {
            display: none;
        }

        .sb_progress-step {
            background: #ddd;
            border-radius: 0;
        }

        .sb_progress-step::after {
            font-size: 20px;
            font-weight: 600;
            left: 0;
            right: 0;
        }

        .sb_progress-step {
            position: relative !important;
        }

        .sb_form-step.active {
            padding: 20px;
            border-top: 1px solid #ddd;
        }

        .sb_form-step.active h3 {
            text-align: center;
            font-size: 18px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }


        .sb_input-group label {
            display: block;
            font-size: 16px;
        }

        .sb_input-group input,
        .sb_input-group select {
            padding: 10px;
            display: block;
            width: 94%;
            border-radius: 5px;
            border: 1px solid #ddd;
            max-width: 100%;
        }

        .sb_input-group {
            margin-bottom: 20px;
            width: 100%;
            display: inline-block;
        }

        .sb_btn.sb_btn-prev {
            background: #dd9933;
            border-radius: 5px;
            border: none;
            padding: 10px 20px;
            line-height: 30px;
        }

        .sb_btn-group {
            margin-top: 50px;
        }

        .sb_btn-next {
            background: #009688;
            padding: 10px 20px;
            line-height: 30px;
        }

        .sb_btn.sb_btn-complete {
            padding: 10px 30px;
            line-height: 30px;
            color: #09593c;
            font-weight: 700;
        }

        .notice {
            text-align: center;
            padding: 10px;
        }

        .notice p {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }

        .notice em {
            color: #777;
        }

        .sb_btn-group.first_btn {
            display: block;
            text-align: right !important;
        }

        .sb_btn-group.first_btn .sb_btn.sb_btn-next {
            float: none;
            display: inline-block;
        }

        .required-error {
            border-color: red !important;
        }

        .sb_input-group.first_step_input.checkboxes_slot label {
            display: inline-flex;
            padding: 5px;
        }

        .sb_input-group.first_step_input.checkboxes_slot {
            padding: 10px 0;
        }

        .sb_input-group.first_step_input.checkboxes_slot input {
            width: auto;
            margin-left: 10px;
        }

        .disabled-label {
            text-decoration: line-through;
        }

        div#notice span {
            display: block;
            color: #019687;
        }



        .checkbox-button {
            display: inline-block;
            padding: 5px;
            cursor: pointer;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .checkbox-button input {
            display: none;
            /* Hide the actual checkbox */
        }

        .checkbox-button.checked {
            background-color: #4CAF50;
            /* Green background for checked state */
            color: #fff;
            /* White text for checked state */
        }

        label.checkbox-button {
            background: #fff;
            color: #477b95;
            border: 1px solid #477b95;
        }

        .sb_input-group.first_step_input.checkboxes_slot {
            display: inline-block;
            text-align: left;
        }

        label.checkbox-button.disabled-label {
            background: #e07d9d;
            color: #fff;
            border: none;
            cursor: default;
        }

        label.checkbox-button {
            text-align: center;
            display: inline-block !important;
            margin: 5px;
        }

        .sb_input-group.first_step_input.checkboxes_slot {
            display: inline-block;
            text-align: center;
        }

        label.checkbox-button.disabled-label {
            background: #e07d9d;
            color: #fff;
            border: none;
            cursor: default;
        }

        label.checkbox-button {
            background: #fff;
            color: #3F51B5;
            border: 2px solid #3F51B5;
            cursor: pointer;
        }

        .checkbox-button.checked {
            background: #009688;
        }

        .sb_progress-step {
            margin: 10px;
            width: 26.5% !important;
        }

        .sb_form-step.active {
            box-shadow: none;
            outline: none;
            border-radius: 5px;
        }

        form.sb_form {
            border: 2px solid #3F51B5;
            border-radius: 5px;
        }

        .sb_input-group label {
            font-weight: bold;
            color: #3F51B5;
        }

        .sb_input-group input,
        .sb_input-group select {
            border-color: #3F51B5;
            border-width: 2px;
            font-size: 16px;
            text-transform: capitalize;
        }

        .first_step_input.checkboxes_slot {
            padding: 20px 0;
            border-top: 2px solid #3F51B5;
        }

        .sb_input-group input {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        form.sb_form {
            box-sizing: border-box;
        }

        ul.identification {
            list-style: none;
            margin: 0;
            padding: 10px 0;
            font-size: 14px;
            color: #000;
        }

        span.box {
            margin-right: 10px;
            height: 10px;
            width: 10px;
            display: inline-block;
            border: 1px solid transparent;
        }

        span.box.box1 {
            background: #fff;
            border: 1px solid #4051b5;
        }

        .box2 {
            background: #e07d9d;
        }

        .box3 {
            background: #019687;
        }

        div#notice {
            text-align: right;
        }

        ul.identification {
            list-style: none;
            margin: 0;
            padding: 10px 0;
            font-size: 14px;
            color: #4e558c;
        }

        span.box {
            margin-right: 10px;
            height: 10px;
            width: 10px;
            display: inline-block;
            border: 1px solid transparent;
        }

        span.box.box1 {
            background: #fff;
            border: 1px solid #4051b5;
        }

        .box2 {
            background: #e07d9d;
        }

        .box3 {
            background: #019687;
        }

        div#notice {
            text-align: right;
        }

        .sb_form-step.active h3 {
            color: #3F51B5;
            border-color: #009688;
        }

        .sb_progress-step::after {
            color: var(--wp-admin-theme-color-darker-20) !important;
        }

        .sb_progress-step.active {
            background: #3F51B5;
        }

        .notice p {
            color: #3f51b5;
            border-top: 1px solid;
            padding-top: 10px;
        }

        .sb_btn.sb_btn-complete {
            padding: 10px 30px;
            background: #009688;
            line-height: 30px;
            color: #fff;
            font-weight: 700;
        }

        .sb_btn {
            display: flex;
            align-items: center;
        }

        .sb_btn span {
            margin-right: 5px;
            margin-left: 5px;
            font-size: 24px;
            font-weight: bold;
        }

        .sb_btn-group.first_btn a.sb_btn.sb_btn-next {
            display: inline-flex;
        }

        p.req {
            color: #e07d9d;
            font-weight: bold;
        }

        .sb_btn {
            width: auto !important;
            color: #fff !important;
        }

        .sb_btn:hover {
            color: #fff;
        }

        form.sb_form {
            color: #3F51B5;
        }

        .sb_progress-step::after {
            font-size: 12px;
            font-weight: 600;
        }

        .sb_progress-step {
            background: none;
            border: 1px solid #3f51b5;
            border-radius: 5px;
            font-weight: bold;
        }

        .sb_progress-step.active {
            border-radius: 5px;
        }

        .sb_progress-step.active::after {
            color: #3f51b5 !important;
        }

        .total_price {
            padding: 10px 0;
        }

        .total_price {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .error-message-container.error {
            color: #d8613c;
            font-weight: bold;
            font-size: 18px;
            text-align: center;
        }

        .sb_btn {
            font-size: 18px;
            border: none;
            font-weight: bold;
        }

        label.checkbox-button.disabled-label {
            pointer-events: none;
            cursor: none;
        }

        .error_payment {
            margin-top: 10px;
            text-align: center;
            color: darkred;
        }

        .success_payment {
            color: green;
            text-align: center;
            margin-top: 10px;
        }

        /* Media query for tablet */
        @media only screen and (max-width: 768px) {
            .sb_progress-step::after {
                display: none;
            }

            .sb_progress-step.active {}

            .sb_progress-step {
                margin: 5px;
                width: 30% !important;
            }

            .sb_progress-bar {
                margin-bottom: 10px;
            }

            .total_price {
                padding: 10px 0;
                font-size: 20px;
            }

            .sb_form-step.active h3 {
                font-size: 24px;
                padding: 10px 0;
            }

            .sb_form-step label {
                font-size: 14px;
                margin-bottom: 10px;
            }

            .sb_input-group.first_step_input.checkboxes_slot {
                margin: 0;
            }

            input#pick_a_date {
                border: 1px solid #009688;
                outline: none;
            }

            .notice {
                font-size: 14px;
                margin-top: 20px;
            }

            .notice p {
                font-size: 14px;
            }
        }

        /* Media query for mobile */
        @media only screen and (max-width: 480px) {

            .sb_progress-step::after {
                display: none;
            }

            .sb_progress-step.active {}

            .sb_progress-step {
                margin: 5px;
                width: 30% !important;
            }

            .sb_progress-bar {
                margin-bottom: 10px;
            }

            .total_price {
                padding: 10px 0;
                font-size: 20px;
            }

            .sb_form-step.active h3 {
                font-size: 24px;
                padding: 10px 0;
            }

            .sb_form-step label {
                font-size: 14px;
                margin-bottom: 10px;
            }

            .sb_input-group.first_step_input.checkboxes_slot {
                margin: 0;
            }

            input#pick_a_date {
                border: 1px solid #009688;
                outline: none;
            }

            .notice {
                font-size: 14px;
                margin-top: 20px;
            }

            .notice p {
                font-size: 14px;
            }
        }


        /* End Progress bar */

        /* Add Experience Btn */
    </style>
    <!-- multistep form -->

    <form action="#"
        method="post"
        class="sb_form">

        <?php
        $studio_id = get_the_ID();
        // Replace 'lm_tools_custom_field' with the actual key used in your metabox.
        $base_price = get_post_meta($studio_id, 'base_price', true);
        $discount_hours = get_post_meta($studio_id, 'discount_hours', true);
        $discountPercentage = get_post_meta($studio_id, 'discount_percentage', true);

        ?>
        <div class="calculation">
            <div class="base_price">
                <h2>
                    <?php echo $base_price; ?> $ / hr
                    <span>2 Hours Minimum</span>
                </h2>
                <div class="other_details">
                    <div class="left">
                        <?php echo $discount_hours; ?>+ hours discount
                    </div>
                    <div class="right">
                        <?php echo $discountPercentage; ?>% off
                    </div>
                </div>
            </div>


        </div>
        <div class="price_calculation">
            <input type="hidden"
                id="studio_id"
                value="<?php echo $studio_id; ?>">
            <input type="hidden"
                id="hours"
                value="">
            <input type="hidden"
                id="base_price"
                value="<?php echo $base_price; ?>">
            <input type="hidden"
                id="discount_hours"
                value="<?php echo $discount_hours; ?>">
            <input type="hidden"
                id="discount_percentage"
                value="<?php echo $discountPercentage; ?>">
            <input type="hidden"
                id="total"
                value="">

            <div class="total_price">

            </div>
        </div>
        <!-- Progress Bar -->
        <div class="sb_progress-bar">
            <div class="sb_progress-step active"
                data-title="Time Slot"></div>
            <div class="sb_progress-step"
                data-title="Booking Details"></div>
            <div class="sb_progress-step"
                data-title="Payment"></div>
        </div>

        <div class="sb_form-step active">
            <h3>Date and Time Selection</h3>
            <div class="sb_input-group first_step_input datepicker-container">
                <label for="pick_a_date">Pick a Date</label>


                <div class="cal">
                    <input type="text"
                        name="pick_a_date[]"
                        id="pick_a_date"
                        required
                        placeholder="select date">

                </div>


            </div>
            <div class="sb_input-group"
                style="margin-bottom:0">
                <label for="">Select Time Slots</label>
            </div>

            <div class="  first_step_input checkboxes_slot">
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="8:00 AM"
                        id="CheckBox1"><span>8:00 AM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="9:00 AM"
                        id="CheckBox2"><span>9:00 AM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="10:00 AM"
                        id="CheckBox3"><span>10:00 AM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="11:00 AM"
                        id="CheckBox4"><span>11:00 AM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="12:00 PM"
                        id="CheckBox5"><span>12:00 PM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="1:00 PM"
                        id="CheckBox6"><span>1:00 PM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="2:00 PM"
                        id="CheckBox7"><span>2:00 PM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="3:00 PM"
                        id="CheckBox8"><span>3:00 PM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="4:00 PM"
                        id="CheckBox9"><span>4:00 PM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="5:00 PM"
                        id="CheckBox10"><span>5:00 PM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="6:00 PM"
                        id="CheckBox11"><span>6:00 PM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="7:00 PM"
                        id="CheckBox12"><span>7:00 PM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="8:00 PM"
                        id="CheckBox13"><span>8:00 PM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="9:00 PM"
                        id="CheckBox14"><span>9:00 PM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="10:00 PM"
                        id="CheckBox15"><span>10:00 PM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="11:00 PM"
                        id="CheckBox16"><span>11:00 PM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="12:00 AM"
                        id="CheckBox17"><span>12:00 AM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="1:00 AM"
                        id="CheckBox18"><span>1:00 AM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="2:00 AM"
                        id="CheckBox19"><span>2:00 AM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="3:00 AM"
                        id="CheckBox20"><span>3:00 AM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="4:00 AM"
                        id="CheckBox21"><span>4:00 AM</span></label>
                <label class="checkbox-button"><input type="checkbox"
                        name="timeslot"
                        value="5:00 AM"
                        id="CheckBox22"><span>5:00 AM</span></label>

            </div>
            <div class="error-message-container"></div>
            <p class="req">**Please select atleast 2 slots sequetially.</p>
            <ul class="identification">
                <li><span class="box box1"></span> <span class="avl">Available Slots</span></li>
                <li><span class="box box2"></span> <span class="avl">Already Booked Slots</span></li>
                <li><span class="box box3"></span> <span class="avl">Selected Slots</span></li>
            </ul>
            <div id="notice">

            </div>


            <div class="sb_btn-group first_btn">
                <button class="sb_btn sb_btn-next "
                    id="first_step">Next <span>&roarr;</span></button>
            </div>
        </div>

        <div class="sb_form-step ">
            <h3>Personal Details</h3>
            <div class="sb_input-group second_step_input">
                <label for="name">Full Name</label>
                <input type="text"
                    name="customer_name"
                    id="customer_name"
                    required
                    placeholder="Full Name">
            </div>
            <div class="sb_input-group second_step_input">
                <label for="email">Email</label>
                <input type="email"
                    name="customer_email"
                    id="customer_email"
                    required
                    placeholder="Email Address">
            </div>
            <div class="sb_input-group second_step_input">
                <label for="customer_phone_number">Phone Number</label>
                <input type="tel"
                    name="customer_phone_number"
                    id="customer_phone_number"
                    required
                    placeholder="Phone Number">
            </div>
            <div class="sb_btn-group ">
                <a class="sb_btn sb_btn-prev"><span>&loarr;</span> Previous</a>
                <a class="sb_btn sb_btn-next "
                    id="second_step">Next <span>&roarr;</span></a>
            </div>
        </div>


        <div class="sb_form-step ">
            <h3>Payment Information</h3>

            <div id="card-element"></div>
            <div class="error_payment"></div>
            <div class="success_payment"></div>
            <div class="sb_btn-group">
                <a class="sb_btn sb_btn-prev"><span>&loarr;</span> Previous</a>
                <a class="sb_btn sb_btn-complete"
                    id="final_submit"><span>&check;</span> Complete</a>
            </div>

            <div class="notice">
                <p>Instant Book</p>
                <em>After payment, your booking will be instantly confirmed. Free cancellation within 24 hours of
                    confirmation.
                </em>
            </div>
        </div>
    </form>

    <?php
    return ob_get_clean();
}


function lm_tools_custom_booking_shortcode()
{
    return lm_tools_custom_booking_form();
}
add_shortcode('lm_tools_custom_booking_form', 'lm_tools_custom_booking_shortcode');





// Enqueue custom script in the footer
function enqueue_lm_tools_custom_script()
{
    ?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        // Assuming you have the following PHP variables available
        function getHiddenFieldValue(id) {
            return document.getElementById(id).value;
        }

        $(document).ready(function () {
            // Function to update total price based on selected hours
            function updateTotalPrice() {
                var selectedCheckboxes = $('input[name="timeslot"]:checked');
                var selectedHours = selectedCheckboxes.length;
                // Get values from hidden fields
                var basePrice = getHiddenFieldValue('base_price');
                var discountHours = getHiddenFieldValue('discount_hours');
                var discountPercentage = getHiddenFieldValue('discount_percentage');
                basePrice = parseInt(basePrice) * selectedHours

                // Check if selected hours are 8 or more to apply discount
                if (selectedHours >= discountHours) {
                    var discountedAmount = (basePrice * discountPercentage) / 100;
                    var discountedPrice = basePrice - discountedAmount;
                    $('.total_price').html('Total Price: <span>$' + discountedPrice + '</span>');
                    $('#notice').html('<span class="selected_hours_notice">Your selected hours are: <strong>' + selectedHours + ' </strong></span><span  class="total_price_notice">Total: <strong>$' + discountedPrice + ' </strong></span>');
                    $('#hours').val(selectedHours)
                    $('#total').val(discountedPrice)
                } else {

                    $('.total_price').html('Total Price: <span>$' + basePrice + '</span>');
                    $('#notice').html('<span class="selected_hours_notice">Your selected hours are: <strong>' + selectedHours + ' </strong></span><span  class="total_price_notice">Total: <strong>$' + basePrice + ' </strong></span>');
                    $('#hours').val(selectedHours)
                    $('#total').val(basePrice)
                }
            }

            // Initial update on page load
            updateTotalPrice();

            // Bind the updateTotalPrice function to the change event of the select
            $('#pick_a_date, .checkboxes_slot input[name="timeslot"]').on('change', function () {
                updateTotalPrice();
            });
            $('#pick_a_date').datepicker({
                minDate: 1, // 0 means today, so it disables all past dates
                dateFormat: 'mm-dd-yy' // specify the desired date format
            });

            // Function to check for booking conflicts
            function checkBookingConflicts() {
                var selectedDate = $('#pick_a_date').val();
                var selectedID = $('#studio_id').val();
                var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';


                // Make an AJAX request to get existing bookings
                $.ajax({
                    url: ajaxurl, // This is a global variable in WordPress that holds the URL to admin-ajax.php
                    type: 'POST',
                    data: {
                        action: 'check_booking_conflicts',
                        selectedDate: selectedDate,
                        selectedID: selectedID
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        console.log(data);

                        // Disable checkboxes based on the received timeslots
                        disableCheckboxes(data.timeslots);


                    }
                });
            }
            // Function to disable checkboxes based on timeslots
            function disableCheckboxes(timeslots) {
                $('.checkboxes_slot input[type="checkbox"]').prop('disabled', false); // Reset all checkboxes

                // Loop through each timeslot array
                for (var i = 0; i < timeslots.length; i++) {
                    // Loop through each timeslot in the array
                    for (var j = 0; j < timeslots[i].length; j++) {
                        var valueToDisable = timeslots[i][j];
                        var $checkbox = $('.checkboxes_slot input[value="' + valueToDisable + '"]');
                        $checkbox.prop('disabled', true);
                        $checkbox.addClass('disabled');

                        $checkbox.closest('label').addClass('disabled-label');

                    }
                }
            }

            // Attach the checkBookingConflicts function to change events
            $('#pick_a_date, #start_time').on('change', function () {

                // Call checkBookingConflicts function
                checkBookingConflicts();

                // Uncheck all checkboxes when date is changed
                $('.checkboxes_slot input[type="checkbox"]').prop('checked', false);
                updateTotalPrice();
                $('.checkboxes_slot input').removeClass('disabled');
                $('.checked').removeClass('checked');

                $('.checkboxes_slot label').removeClass('disabled-label');

            });

            var Min = 1; // the minimum Check Box number
            var Max = 22; // the maximum Check Box number

            $('.checkbox-button').on('click', function () {
                var checkbox = $(this).find('input[name="timeslot"]');
                checkbox.prop('checked', !checkbox.prop('checked'));
                $(this).toggleClass('checked', checkbox.prop('checked'));
                var checkbox = $(this).find('input[name="timeslot"]');
                var cbId = checkbox.attr('id').replace('CheckBox', ''); // Extract the checkbox number

                CheckSequence(cbId);
            });

            function CheckSequence(cb) {
                for (i0 = Min; i0 <= Max; i0++) {
                    if ($('#CheckBox' + i0).prop('checked')) {
                        for (i1 = i0; i1 <= Max; i1++) {
                            if (!$('#CheckBox' + i1).prop('checked')) {
                                for (i2 = i1; i2 <= Max; i2++) {
                                    if ($('#CheckBox' + i2).prop('checked')) {
                                        // Display error message and uncheck the clicked checkbox
                                        $('.error-message-container').text('**Please select squential slots.').addClass('error');
                                        $('#first_step').prop("disabled", true);
                                        $('#CheckBox' + cb).prop('checked', false);
                                        return;
                                    }
                                }
                            }
                        }
                    }
                }
                $('#first_step').prop("disabled", false);
                // Remove error message and class if sequence is legal
                $('.error-message-container').text('').removeClass('error');
            }



        });

    </script>


    <script>

        const prevBtns = document.querySelectorAll(".sb_btn-prev");
        const nextBtns = document.querySelectorAll(".sb_btn-next");
        const first_step = document.getElementById("first_step");
        const progress = document.getElementById("sb_progress");
        const formSteps = document.querySelectorAll(".sb_form-step");
        const progressSteps = document.querySelectorAll(".sb_progress-step");



        let formStepsNum = 0;

        function updateFormSteps() {
            formSteps.forEach(formStep => {
                formStep.classList.remove("active");
            })
            formSteps[formStepsNum].classList.add("active");
        }

        function updateProgressBar() {
            progressSteps.forEach((progressStep, idx) => {
                if (idx < formStepsNum + 1) {
                    progressStep.classList.add("active");
                } else {
                    progressStep.classList.remove("active");
                }
            })

            const progressActive = document.querySelectorAll(".sb_progress-step.active");

        }


        first_step.addEventListener("click", function () {

            var isValid = validateFormfirststep();
            if (!isValid) {
                alert('Please fill in the required fields');

                return;
            }
            var invalidFields = $('.first_step_input :invalid');
            invalidFields.removeClass('required-error');
            formStepsNum++;
            updateFormSteps();
            updateProgressBar();
        })

        second_step.addEventListener("click", function () {

            var isValid = validateFormsecondstep();
            if (!isValid) {
                alert('Please fill in the required fields');

                return;
            }
            var invalidFields = $('.second_step_input :invalid');
            invalidFields.removeClass('required-error');
            formStepsNum++;
            updateFormSteps();
            updateProgressBar();
        })


        prevBtns.forEach(btn => {
            btn.addEventListener("click", function () {
                formStepsNum--;
                if (formStepsNum < 0) {
                    formStepsNum = 0;
                }
                updateFormSteps();
                updateProgressBar();
            })
        })

        function validateForm() {
            var invalidFields = $('.sb_input-group :invalid');
            invalidFields.addClass('required-error');
            return invalidFields.length === 0;
        }

        function validateFormfirststep() {
            // Validate input fields
            var invalidFields = $('.first_step_input :invalid');
            invalidFields.addClass('required-error');

            // Validate at least two checkboxes are checked
            var checkboxGroup = $('.checkboxes_slot input[type="checkbox"]');
            var checkedCheckboxes = checkboxGroup.filter(':checked');

            if (checkedCheckboxes.length < 2) {
                checkboxGroup.closest('fieldset').addClass('required-error');
            } else {
                checkboxGroup.closest('fieldset').removeClass('required-error');
            }

            return invalidFields.length === 0 && checkedCheckboxes.length >= 2;
        }


        function validateFormsecondstep() {
            var invalidFields = $('.second_step_input :invalid');
            invalidFields.addClass('required-error');
            return invalidFields.length === 0;
        }



    </script>
    <?php
}
add_action('wp_footer', 'enqueue_lm_tools_custom_script');



require 'vendor/autoload.php';
// Handle AJAX requests
function lm_tools_custom_booking_ajax_handler()
{


    // Verify nonce
    if (isset ($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'lm_tools_custom_booking_nonce')) {
        // Process and sanitize your data
        $studio_id = sanitize_text_field($_POST['studio_id']);
        $pick_a_date = sanitize_text_field($_POST['pick_a_date']);
        $hours = sanitize_text_field($_POST['hours']);
        $payment_method_id = sanitize_text_field($_POST['payment_method_id']);
        $total = sanitize_text_field($_POST['total']);
        $customer_name = sanitize_text_field($_POST['customer_name']);
        $customer_email = sanitize_email($_POST['customer_email']);
        $customer_phone_number = sanitize_text_field($_POST['customer_phone_number']);
        $selected_time_slots_json = sanitize_text_field($_POST['selected_time_slots']); // Assuming you have the JSON string
        $selected_time_slots_json = stripslashes($selected_time_slots_json);

        $selected_time_slots = json_decode($selected_time_slots_json);


        // Create a new studio booking post
        $post_id = wp_insert_post(
            array(
                'post_type' => 'studio-booking',
                'post_status' => 'publish',
                'post_title' => 'Booking: ' . $customer_name,
                // Add other post fields as needed
            )
        );

        update_field('studio_id', $studio_id, $post_id);
        update_field('pick_a_date', $pick_a_date, $post_id);
        update_field('hours', $hours, $post_id);
        update_field('total', $total, $post_id);
        update_field('customer_name', $customer_name, $post_id);
        update_field('customer_email', $customer_email, $post_id);
        update_field('customer_phone_number', $customer_phone_number, $post_id);
        update_field('payment_status', 'unpaid', $post_id);
        update_field('timeslot', $selected_time_slots, $post_id);

        $studio_name = get_the_title($post_id);
        // Set your Stripe secret key
        \Stripe\Stripe::setApiKey('sk_test_NdZYnlWRUI22cRJnhOvahfSS'); // Replace with your actual Stripe secret key

        try {
            // Create a PaymentIntent
            // Create a PaymentIntent

            $payment_intent = \Stripe\PaymentIntent::create([
                'amount' => $total * 100, // Amount should be in cents
                'currency' => 'usd', // Adjust currency as needed
                'payment_method_options' => [
                    'card' => [
                        'request_three_d_secure' => 'automatic'
                    ]
                ],
                'confirmation_method' => 'automatic',
                'payment_method' => $payment_method_id,
                'metadata' => [
                    'studio_id' => $studio_id,
                    'pick_a_date' => $pick_a_date,
                    'hours' => $hours,
                    'customer_name' => $customer_name,
                    'customer_email' => $customer_email,
                    'customer_phone_number' => $customer_phone_number,
                    'total' => $total,
                ],
            ]);
            $payment_intent_conf = $payment_intent->confirm([
                // Add any necessary parameters here, such as payment_method and return_url
                'payment_method' => $payment_method_id,
                'return_url' => site_url() . '/thank-you'
            ]);


            // Check if the amount received is zero or less
            if ($payment_intent_conf->status !== 'succeeded') {
                // Handle payment failure due to zero or negative amount received
                $error_response = array('success' => false, 'message' => 'Payment failed!');
                echo json_encode($error_response);
            } else {

                // You can save the $payment_intent->id in your database for future reference
                // For now, we'll just use it to confirm the payment on the client side
                $success_response = array('success' => true, 'clientSecret' => $payment_intent->client_secret);

                // Send emails with additional booking details
                send_customer_email($customer_email, $studio_name, $pick_a_date, $total, $selected_time_slots);
                send_admin_email($studio_name, $pick_a_date, $total, $selected_time_slots);
                update_post_meta($post_id, 'payment_status', 'paid');
                echo json_encode($success_response);
            }
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Handle Stripe API errors
            $error_response = array('success' => false, 'message' => 'Stripe Error: ' . $e->getMessage());
            echo json_encode($error_response);
        } catch (\Exception $e) {
            // Handle other exceptions
            $error_response = array('success' => false, 'message' => 'An unexpected error occurred: ' . $e->getMessage());
            echo json_encode($error_response);
        }

        // Always exit to prevent further execution
        wp_die();
    } else {
        // Invalid nonce
        echo json_encode(array('success' => false, 'message' => 'Nonce verification failed!'));

        // Always exit to prevent further execution
        wp_die();
    }
}
add_action('wp_ajax_lm_tools_custom_booking_ajax_handler', 'lm_tools_custom_booking_ajax_handler');
add_action('wp_ajax_nopriv_lm_tools_custom_booking_ajax_handler', 'lm_tools_custom_booking_ajax_handler');

function send_customer_email($customer_email,  $studio_name, $pick_a_date, $total, $selected_time_slots)
{ 
    $subject = 'Your Booking Confirmation';
    $message = 'Thank you for your booking. Here are your order details:' . "\n\n";
    $message .= 'Studio Title: ' . $studio_name . "\n";
    $message .= 'Booking Date: ' . $pick_a_date . "\n";
    $message .= 'Selected Time Slots: ' . implode(', ', $selected_time_slots) . "\n";
    $message .= 'Total Price: $' . $total . "\n\n";
    
    wp_mail($customer_email, $subject, $message);
}

function send_admin_email($studio_name, $pick_a_date, $total, $selected_time_slots)
{
    $admin_email = get_option('admin_email');
    $subject = 'New Booking: ' . $studio_name;
    $message = 'A new booking has been made. Here are the order details:' . "\n\n";
    $message .= 'Studio Title: ' . $studio_name . "\n";
    $message .= 'Pick a Date: ' . $pick_a_date . "\n";
    $message .= 'Total Price: $' . $total . "\n";
    $message .= 'Selected Time Slots: ' . implode(', ', $selected_time_slots) . "\n\n"; 

    wp_mail($admin_email, $subject, $message);
}



function check_booking_conflicts()
{
    $selectedDate = sanitize_text_field($_POST['selectedDate']);
    $selectedID = sanitize_text_field($_POST['selectedID']);

    // Query to check for conflicts in ACF fields
    $args = array(
        'post_type' => 'studio-booking',
        'posts_per_page' => -1, // Retrieve all matching records
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'studio_id',
                'value' => $selectedID,
                'compare' => '=',
            ),
            array(
                'key' => 'pick_a_date',
                'value' => $selectedDate,
                'compare' => '=',
            ),
        ),
    );

    $existingBookings = new WP_Query($args);

    $timeslots = array(); // Initialize an array to store timeslots

    if ($existingBookings->have_posts()) {
        while ($existingBookings->have_posts()):
            $existingBookings->the_post();
            $timeslot = get_post_meta(get_the_ID(), 'timeslot', true);
            $timeslots[] = $timeslot;
        endwhile;
        echo json_encode(
            array(
                'status' => 'conflict',
                'timeslots' => $timeslots,
            )
        );
    } else {
        echo json_encode(array('status' => 'no_conflict'));
    }

    wp_reset_postdata(); // Reset post data after the loop
    wp_die();

}

add_action('wp_ajax_check_booking_conflicts', 'check_booking_conflicts');
add_action('wp_ajax_nopriv_check_booking_conflicts', 'check_booking_conflicts');




// Add custom columns including "Payment Status"
function lm_tools_custom_studio_booking_columns($columns)
{
    // Add new columns
    $columns['pick_a_date'] = 'Pick a Date';
    $columns['total'] = 'Total';
    $columns['payment_status'] = 'Payment Status';

    return $columns;
}
add_filter('manage_studio-booking_posts_columns', 'lm_tools_custom_studio_booking_columns');

// Display content for custom columns
function lm_tools_custom_studio_booking_column_content($column, $post_id)
{
    switch ($column) {
        case 'pick_a_date':
            $pick_a_date = get_post_meta($post_id, 'pick_a_date', true);
            echo $pick_a_date;
            break;

        case 'total':
            $total = get_post_meta($post_id, 'total', true);
            echo $total;
            break;

        case 'payment_status':
            $payment_status = get_post_meta($post_id, 'payment_status', true);
            echo $payment_status === 'paid' ? 'Paid' : 'Unpaid';
            break;

        // Add more cases for additional columns

        default:
            break;
    }
}
add_action('manage_studio-booking_posts_lm_tools_custom_column', 'lm_tools_custom_studio_booking_column_content', 10, 2);
