<?php
/* Template Name: Add cargo */
get_header();
?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        <style>
            .required-field::after {
                content: " *";
                color: red;
            }
            .form-container {
                max-width: 800px;
                margin: 30px auto;
                padding: 20px;
                background-color: #f8f9fa;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .section-title {
                border-bottom: 2px solid #0d6efd;
                padding-bottom: 5px;
                margin-bottom: 20px;
                color: #0d6efd;
            }
        </style>
    <div class="container form-container">
        <h2 class="text-center mb-4">Transport Request Form</h2>
        <form id="transportForm" novalidate>
            <!-- User Section (hidden if user is logged in) -->
            <input type="hidden" id="user" name="user" value="">

            <!-- Dates Section -->
            <div class="mb-4">
                <h4 class="section-title">Dates</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date_from" class="form-label required-field">Date From</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" required>
                        <div class="invalid-feedback">
                            Please provide a valid start date.
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="date_to" class="form-label required-field">Date To</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" required>
                        <div class="invalid-feedback">
                            Please provide a valid end date.
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date_from_plus" class="form-label">Flexible Date From (+ days)</label>
                        <input type="date" class="form-control" id="date_from_plus" name="date_from_plus">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="date_to_plus" class="form-label">Flexible Date To (+ days)</label>
                        <input type="date" class="form-control" id="date_to_plus" name="date_to_plus">
                    </div>
                </div>
            </div>

            <!-- Vehicle Information Section -->
            <div class="mb-4">
                <h4 class="section-title">Vehicle Information</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="vehicle_type" class="form-label required-field">Vehicle Type</label>
                        <select class="form-select" id="vehicle_type" name="vehicle_type" required>
                            <option value="" selected disabled>Select vehicle type</option>
                            <option value="Truck">Truck</option>
                            <option value="Van">Van</option>
                            <option value="Pickup">Pickup</option>
                            <option value="Trailer">Trailer</option>
                            <option value="Other">Other</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a vehicle type.
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="trailer" class="form-label">Trailer Type (if applicable)</label>
                        <select class="form-select" id="trailer" name="trailer">
                            <option value="" selected disabled>Select trailer type</option>
                            <option value="None">None</option>
                            <option value="Flatbed">Flatbed</option>
                            <option value="Refrigerated">Refrigerated</option>
                            <option value="Tanker">Tanker</option>
                            <option value="Lowboy">Lowboy</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Location Section -->
            <div class="mb-4">
                <h4 class="section-title">Locations</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="location_from" class="form-label required-field">Location From</label>
                        <input type="text" class="form-control" id="location_from" name="location_from" required>
                        <div class="invalid-feedback">
                            Please provide a starting location.
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="location_to" class="form-label required-field">Location To</label>
                        <input type="text" class="form-control" id="location_to" name="location_to" required>
                        <div class="invalid-feedback">
                            Please provide a destination location.
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="country_from" class="form-label">Country From</label>
                        <select class="form-select" id="country_from" name="country_from">
                            <option value="" selected disabled>Select country</option>
                            <option value="Serbia">Serbia</option>
                            <option value="Germany">Germany</option>
                            <option value="France">France</option>
                            <option value="Italy">Italy</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="country_to" class="form-label">Country To</label>
                        <select class="form-select" id="country_to" name="country_to">
                            <option value="" selected disabled>Select country</option>
                            <option value="Serbia">Serbia</option>
                            <option value="Germany">Germany</option>
                            <option value="France">France</option>
                            <option value="Italy">Italy</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="zip_from" class="form-label">ZIP Code From</label>
                        <input type="text" class="form-control" id="zip_from" name="zip_from">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="zip_to" class="form-label">ZIP Code To</label>
                        <input type="text" class="form-control" id="zip_to" name="zip_to">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="distance" class="form-label">Distance (km)</label>
                    <input type="text" class="form-control" id="distance" name="distance" placeholder="Approximate distance in kilometers">
                </div>
            </div>

            <!-- Cargo Information Section -->
            <div class="mb-4">
                <h4 class="section-title">Cargo Information</h4>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="weight" class="form-label">Weight (kg)</label>
                        <input type="text" class="form-control" id="weight" name="weight" placeholder="Approximate weight">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="length" class="form-label">Length (m)</label>
                        <input type="text" class="form-control" id="length" name="length" placeholder="Approximate length">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="height" class="form-label">Height (m)</label>
                        <input type="text" class="form-control" id="height" name="height" placeholder="Approximate height">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price (€)</label>
                    <input type="text" class="form-control" id="price" name="price" placeholder="Expected price in euros">
                </div>
            </div>

            <!-- Additional Information Section -->
            <div class="mb-4">
                <h4 class="section-title">Additional Information</h4>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Any additional information about the transport"></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-grid gap-2">
                <button class="btn btn-primary" type="submit">Submit Request</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Form Validation Script -->
    <script>
        (function() {
            'use strict';

            // Fetch the form we want to apply custom Bootstrap validation styles to
            const form = document.getElementById('transportForm');

            // Prevent submission if form is invalid
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);

            // Date validation - ensure date_to is after date_from
            const dateFrom = document.getElementById('date_from');
            const dateTo = document.getElementById('date_to');

            dateFrom.addEventListener('change', validateDates);
            dateTo.addEventListener('change', validateDates);

            function validateDates() {
                if (dateFrom.value && dateTo.value) {
                    const fromDate = new Date(dateFrom.value);
                    const toDate = new Date(dateTo.value);

                    if (fromDate > toDate) {
                        dateTo.setCustomValidity('End date must be after start date');
                        dateTo.classList.add('is-invalid');
                    } else {
                        dateTo.setCustomValidity('');
                        dateTo.classList.remove('is-invalid');
                    }
                }
            }

            // If you're in WordPress, you might want to populate the user field
            // This would depend on how you're handling user authentication
            // Example:
            // const userField = document.getElementById('user');
            // if (userField && wp_user_id) {
            //     userField.value = wp_user_id;
            // }
        })();
    </script>
<?php
get_footer();
?>