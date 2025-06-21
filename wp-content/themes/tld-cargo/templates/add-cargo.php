<?php
/* Template Name: Add cargo */
get_header();
?>
    <div class="container">
        <h2 class="text-center mb-4">Transport Request Form</h2>

        <form id="transportForm" novalidate>
            <!-- User Section (hidden if user is logged in) -->
            <input type="hidden" id="user" name="user" value="">


            <div class="row">
                <div class="col-md-6">
                    <div class="form-section">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 border-top txt-orange"></div>
                            <h3 class="px-3 txt-orange">UTOVAR</h3>
                            <div class="flex-grow-1 border-top txt-orange"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_from" class="form-label required-field">Date From</label>
                                <input type="date" class="form-control" id="date_from" name="date_from" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="location_from" class="form-label required-field">Location From</label>
                                <input type="text" class="form-control datepicker" id="location_from"
                                       name="location_from" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="zip_from" class="form-label">ZIP Code From</label>
                                <input type="text" class="form-control" id="zip_from" name="zip_from">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date_from_plus" class="form-label">Flexible Date From (+ days)</label>
                                <input type="date" class="form-control" id="date_from_plus" name="date_from_plus">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-section">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 border-top txt-orange"></div>
                            <h3 class="px-3 txt-orange">ISTOVAR</h3>
                            <div class="flex-grow-1 border-top txt-orange"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_to" class="form-label required-field">Date To</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="location_to" class="form-label required-field">Location To</label>
                                <input type="text" class="form-control datepicker" id="location_to" name="location_to"
                                       required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="zip_to" class="form-label">ZIP Code To</label>
                                <input type="text" class="form-control" id="zip_to" name="zip_to">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="date_to_plus" class="form-label">Flexible Date To (+ days)</label>
                                <input type="date" class="form-control" id="date_to_plus" name="date_to_plus">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Cargo Information Section -->
            <div class="mb-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 border-top txt-orange"></div>
                    <h3 class="px-3 txt-orange">Informacije o teretu</h3>
                    <div class="flex-grow-1 border-top txt-orange"></div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="weight" class="form-label">Weight (kg)</label>
                        <input type="text" class="form-control" id="weight" name="weight"
                               placeholder="Approximate weight">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="length" class="form-label">Length (m)</label>
                        <input type="text" class="form-control" id="length" name="length"
                               placeholder="Approximate length">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="height" class="form-label">Height (m)</label>
                        <input type="text" class="form-control" id="height" name="height"
                               placeholder="Approximate height">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="price" class="form-label">Price (€)</label>
                        <input type="text" class="form-control" id="price" name="price"
                               placeholder="Expected price in euros">
                    </div>
                </div>
            </div>

            <!-- Cargo Information Section -->
            <div class="mb-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 border-top txt-orange"></div>
                    <h3 class="px-3 txt-orange">Informacije o vozilu!</h3>
                    <div class="flex-grow-1 border-top txt-orange"></div>
                </div>
                <div class="row g-3">
                    <!-- Truck -->
                    <div class="col-6 col-md-4 col-lg">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="vehicle_type[]"
                                   id="vehicle_type_truck" value="Truck" required>
                            <label class="form-check-label" for="vehicle_type_truck">Truck</label>
                        </div>
                    </div>

                    <!-- Van -->
                    <div class="col-6 col-md-4 col-lg">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="vehicle_type[]" id="vehicle_type_van"
                                   value="Van">
                            <label class="form-check-label" for="vehicle_type_van">Van</label>
                        </div>
                    </div>

                    <!-- Pickup -->
                    <div class="col-6 col-md-4 col-lg">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="vehicle_type[]"
                                   id="vehicle_type_pickup" value="Pickup">
                            <label class="form-check-label" for="vehicle_type_pickup">Pickup</label>
                        </div>
                    </div>

                    <!-- Trailer -->
                    <div class="col-6 col-md-4 col-lg">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="vehicle_type[]"
                                   id="vehicle_type_trailer" value="Trailer">
                            <label class="form-check-label" for="vehicle_type_trailer">Trailer</label>
                        </div>
                    </div>

                    <!-- Other -->
                    <div class="col-6 col-md-4 col-lg">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="vehicle_type[]"
                                   id="vehicle_type_other" value="Other">
                            <label class="form-check-label" for="vehicle_type_other">Other</label>
                        </div>
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

                </div>
                <div class="mb-3">
                    <label for="distance" class="form-label">Distance (km)</label>
                    <input type="text" class="form-control" id="distance" name="distance"
                           placeholder="Approximate distance in kilometers">
                </div>
            </div>


            <!-- Additional Information Section -->
            <div class="mb-4">
                <h4 class="section-title">Additional Information</h4>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"
                              placeholder="Any additional information about the transport"></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-grid gap-2">
                <button class="btn btn-primary" type="submit">Submit Request</button>
            </div>
        </form>
    </div>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
	<script>
		$(document).ready(function(){
			$('.datepicker').datepicker({
				format: 'dd.mm.yyyy',  // Display format
				autoclose: true,
				todayHighlight: true,
				clearBtn: true,
				language: 'de'  // Optional: For European formatting
			});
		});
	</script>-->
<?php
get_footer();
?>