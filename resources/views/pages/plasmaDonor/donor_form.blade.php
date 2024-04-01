@extends('layout.head')

@section('title', 'Donor Form - Mission Plasma')

@section('content')
<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        min-height: 100vh;
        position: relative;
        background-color: #f8f9fa;
    }

    .container {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        background-color: #fff;
        margin: 120px auto 50px;
        max-width: 600px;
        overflow-y: auto;
        max-height: calc(90vh - 160px);
    }

    h1 {
        margin-bottom: 20px;
        text-align: center;
    }

    #alert-msg {
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: bold;
    }

    .btn-primary {
        width: 100%;
    }

    @media (min-width: 576px) {
        .btn-primary {
            width: auto;
        }
    }

    .error-msg {
        color: red;
        display: none;
    }

    .is-invalid {
        border-color: red;
    }
</style>
<img src="/storage/images/blood-donation.jpg" alt="Background Image" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -1; opacity: 0.5;">
<div class="container mt-5">
    <h1>Donor Form</h1>
    <div id="alert-msg" class="alert" style="display: none;"></div>
    <form id="donor-form" action="{{ route('donor-submit') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="name">Name: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name" required>
            <div class="error-msg" id="name-error"></div>
        </div>
        <div class="form-group">
            <label>Gender: <span class="text-danger">*</span></label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
                    <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="female" required>
                    <label class="form-check-label" for="female">Female</label>
                </div>
            </div>
            <div class="error-msg" id="gender-error"></div>
        </div>
        <div class="form-group">
            <label for="age">Age: <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="age" name="age" required>
            <div class="error-msg" id="age-error"></div>
        </div>
        <div class="form-group">
            <label for="blood_group">Blood Group: <span class="text-danger">*</span></label>
            <select class="form-control" id="blood_group" name="blood_group" required>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
            </select>
            <div class="error-msg" id="blood_group-error"></div>
        </div>
        <div class="form-group">
            <label for="covid_positive_date">Date of Covid-19 positive: <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="covid_positive_date" name="covid_positive_date" required>
            <div class="error-msg" id="covid_positive_date-error"></div>
        </div>
        <div class="form-group">
            <label for="covid_negative_date">Date of Covid-19 negative: <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="covid_negative_date" name="covid_negative_date" required>
            <div class="error-msg" id="covid_negative_date-error"></div>
        </div>
        <!-- Country Dropdown -->
        <div class="form-group">
            <label for="country">Country: <span class="text-danger">*</span></label>
            <select class="form-control" id="country" name="country" required>
                <option value="">Select Country</option>
            </select>
            <div class="error-msg" id="country-error"></div>
        </div>
        <!-- State Dropdown -->
        <div class="form-group">
            <label for="state">State: <span class="text-danger">*</span></label>
            <select class="form-control" id="state" name="state" required>
                <option value="">Select State</option>
            </select>
            <div class="error-msg" id="state-error"></div>
        </div>
        <!-- City Dropdown -->
        <div class="form-group">
            <label for="city">City: <span class="text-danger">*</span></label>
            <select class="form-control" id="city" name="city" required>
                <option value="">Select City</option>
            </select>
            <div class="error-msg" id="city-error"></div>
        </div>
        <div class="form-group">
            <label for="phone_number">Phone Number: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="phone_number" name="phone_number">
            <div class="error-msg" id="phone_number-error">
            </div>
        </div>
        <button type="submit" id="submit-btn" class="btn btn-primary">Submit</button>
    </form>
</div>
<script>
    $(document).ready(function() {
        // Initialize datepicker for Covid-19 positive date
        $('#covid_positive_date').datepicker({
            dateFormat: 'yy-mm-dd',
            showOn: 'focus', // Show datepicker only on focus
            onSelect: function(selectedDate) {
                // Set the minDate for Covid-19 negative datepicker
                $('#covid_negative_date').datepicker('option', 'minDate', selectedDate);

                // Hide the error message and remove the is-invalid class
                $('#covid_positive_date-error').hide();
                $('#covid_positive_date').removeClass('is-invalid');
            }
        });

        // Initialize datepicker for Covid-19 negative date
        $('#covid_negative_date').datepicker({
            dateFormat: 'yy-mm-dd',
            showOn: 'focus', // Show datepicker only on focus
            beforeShowDay: function(date) {
                var positiveDate = $('#covid_positive_date').datepicker('getDate');
                if (positiveDate && date < positiveDate) {
                    return [false, 'disabled', 'Cannot select before positive date'];
                }
                return [true, ''];
            },
            onSelect: function(selectedDate) {
                // Hide the error message and remove the is-invalid class
                $('#covid_negative_date-error').hide();
                $('#covid_negative_date').removeClass('is-invalid');
            }
        });

        // Populate Country Dropdown
        $.ajax({
            url: "/api/countries",
            type: 'get',
            success: function(response) {
                $.each(response, function(key, value) {
                    $('#country').append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            }
        });

        // Handle Country Change
        $('#country').change(function() {
            var countryId = $(this).val();
            if (countryId) {
                // Populate State Dropdown
                $.ajax({
                    url: "/api/states/" + countryId,
                    type: 'get',
                    success: function(response) {
                        $('#state').html('<option value="">Select State</option>'); // Reset state dropdown
                        $('#city').html('<option value="">Select City</option>'); // Reset city dropdown
                        $.each(response, function(key, value) {
                            $('#state').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#state').html('<option value="">Select State</option>');
                $('#city').html('<option value="">Select City</option>');
            }
        });

        // Handle State Change
        $('#state').change(function() {
            var stateId = $(this).val();
            if (stateId) {
                // Populate City Dropdown
                $.ajax({
                    url: "/api/cities/" + stateId,
                    type: 'get',
                    success: function(response) {
                        $('#city').html('<option value="">Select City</option>'); // Reset city dropdown
                        $.each(response, function(key, value) {
                            $('#city').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#city').html('<option value="">Select City</option>');
            }
        });

        // Form Submission
        $('#donor-form').submit(function(e) {
            e.preventDefault(); // Prevent form submission

            // Reset previous error messages and invalid styles
            $('.error-msg').text('').hide();
            $('.form-control').removeClass('is-invalid');

            // Perform client-side validation
            var isValid = true;

            // Validate Name
            if ($('#name').val().trim() === '') {
                $('#name-error').text('Name is required').show();
                $('#name').addClass('is-invalid');
                isValid = false;
            }

            // Validate Gender
            if (!$('input[name="gender"]').is(':checked')) {
                $('#gender-error').text('Gender is required').show();
                isValid = false;
            }

            // Validate Age
            var age = parseInt($('#age').val().trim());
            if ($('#age').val().trim() === '' || isNaN(age) || age < 18 || age > 60) {
                $('#age-error').text('Age must be a number between 18 and 60').show();
                $('#age').addClass('is-invalid');
                isValid = false;
            }

            // Validate Blood Group
            if ($('#blood_group').val().trim() === '') {
                $('#blood_group-error').text('Blood group is required').show();
                $('#blood_group').addClass('is-invalid');
                isValid = false;
            }

            // Validate Date of Covid-19 positive
            if ($('#covid_positive_date').val().trim() === '') {
                $('#covid_positive_date-error').text('Date of Covid-19 positive is required').show();
                $('#covid_positive_date').addClass('is-invalid');
                isValid = false;
            }

            // Validate Date of Covid-19 negative
            if ($('#covid_negative_date').val().trim() === '') {
                $('#covid_negative_date-error').text('Date of Covid-19 negative is required').show();
                $('#covid_negative_date').addClass('is-invalid');
                isValid = false;
            }

            // Validate Country
            if ($('#country').val().trim() === '') {
                $('#country-error').text('Country is required').show();
                $('#country').addClass('is-invalid');
                isValid = false;
            }

            // Validate State
            if ($('#state').val().trim() === '') {
                $('#state-error').text('State is required').show();
                $('#state').addClass('is-invalid');
                isValid = false;
            }

            // Validate City
            if ($('#city').val().trim() === '') {
                $('#city-error').text('City is required').show();
                $('#city').addClass('is-invalid');
                isValid = false;
            }

            // Validate Phone Number
            if ($('#phone_number').val().trim() === '') {
                $('#phone_number-error').text('Phone number is required').show();
                $('#phone_number').addClass('is-invalid');
                isValid = false;
            }

            // If form is valid, submit the form
            if (isValid) {
                var formData = $(this).serialize();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'post',
                    data: formData,
                    success: function(response) {
                        $('#alert-msg').removeClass('alert-danger').addClass('alert-success').text('Form submitted successfully!').show();
                        $('#donor-form')[0].reset(); // Reset form fields
                        window.location.replace("{{ route('requests-list') }}"); // Redirect to requests-list
                    },
                    error: function(xhr, status, error) {
                        $('#alert-msg').removeClass('alert-success').addClass('alert-danger').text('Error submitting form. Please try again.').show();
                    }
                });
            }
        });
    });
</script>
@endsection