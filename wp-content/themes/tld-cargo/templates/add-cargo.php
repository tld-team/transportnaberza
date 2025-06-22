<?php
/* Template Name: Add cargo */
get_header();
?>
    <div class="container">
        <h2 class="text-center mb-4">Transport Request Form</h2>

        <?php
        echo do_shortcode('[contact-form-7 id="ec669bd" title="Add New Cargo"]');
        ?>

<hr>

        <form id="transportForm" novalidate>
            <!-- User Section (hidden if user is logged in) -->
            <input type="hidden" id="user" name="user" value="">


            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="form-section">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 border-top txt-orange"></div>
                            <h3 class="px-3 txt-orange">UTOVAR</h3>
                            <div class="flex-grow-1 border-top txt-orange"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_from" class="form-label required-field">Datum od</label>
                                <input type="date" class="form-control" id="date_from" name="date_from" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="location_from" class="form-label required-field">Mjesto od</label>
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
                            
                            <div class="col-md-12 mb-3">
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
                                <label for="date_to" class="form-label required-field">Datum do</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="location_to" class="form-label required-field">Mjesto do</label>
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
                            <div class="col-md-12 mb-3">
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
                    </div>
                </div>
            </div>


            <!-- Cargo Information Section -->
            <div class="mb-5">
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
                    <div class="col-md-2 mb-3">
                        <label for="length" class="form-label">Length (m)</label>
                        <input type="text" class="form-control" id="length" name="length"
                               placeholder="Approximate length">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="height" class="form-label">Height (m)</label>
                        <input type="text" class="form-control" id="height" name="height"
                               placeholder="Approximate height">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="price" class="form-label">Price (€)</label>
                        <input type="text" class="form-control" id="price" name="price"
                               placeholder="Expected price in euros">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="distance" class="form-label">Distance (km)</label>
                        <input type="text" class="form-control" id="distance" name="distance"
                               placeholder="Approximate distance in kilometers">
                    </div>  
                </div>
            </div>

            <!-- Cargo Information Section -->
            <div class="mb-5">
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

            <!-- Vehicle Type Section -->
            <div class="mb-5">                
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 border-top txt-orange"></div>
                    <h3 class="px-3 txt-orange">Izaberite nadgradnju prikolice!</h3>
                    <div class="flex-grow-1 border-top txt-orange"></div>
                </div>
                <div class="row g-3">
                    <!-- Prva kolona (4 stavke) -->
                    <div class="col-6 col-lg">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="trailer_autopodizac" value="Autopodizač">
                            <label class="form-check-label" for="trailer_autopodizac">Autopodizač</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="trailer_cerada" value="Cerada">
                            <label class="form-check-label" for="trailer_cerada">Cerada</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="vehicle_type_coil_korito" value="Coil korito">
                            <label class="form-check-label" for="vehicle_type_coil_korito">Coil korito</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="vehicle_type_jambo" value="Jambo">
                            <label class="form-check-label" for="vehicle_type_jambo">Jambo</label>
                        </div>
                    </div>

                    <!-- Druga kolona (4 stavke) -->
                    <div class="col-6 col-lg">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="trailer_autotransporter" value="Autotransporter">
                            <label class="form-check-label" for="trailer_autotransporter">Autotransporter</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="trailer_cisterna" value="Cisterna">
                            <label class="form-check-label" for="trailer_cisterna">Cisterna</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="trailer_hladnjaca" value="Hladnjača">
                            <label class="form-check-label" for="trailer_hladnjaca">Hladnjača</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="trailer_kiper" value="Kiper">
                            <label class="form-check-label" for="trailer_kiper">Kiper</label>
                        </div>
                    </div>

                    <!-- Treća kolona (4 stavke) -->
                    <div class="col-6 col-lg">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="trailer_kontejner" value="Kontejner">
                            <label class="form-check-label" for="trailer_kontejner">Kontejner</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="trailer_mega" value="Mega">
                            <label class="form-check-label" for="trailer_mega">Mega</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="trailer_platforma" value="Platforma">
                            <label class="form-check-label" for="trailer_platforma">Platforma</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="trailer_sanducar" value="Sandučar">
                            <label class="form-check-label" for="trailer_sanducar">Sandučar</label>
                        </div>
                    </div>

                    <!-- Četvrta kolona (4 stavke) -->
                    <div class="col-6 col-lg">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="trailer_labudica" value="Labudica">
                            <label class="form-check-label" for="trailer_labudica">Labudica</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="trailer_otvoreni_kamion" value="Otvoren kamion">
                            <label class="form-check-label" for="trailer_otvoreni_kamion">Otvoren kamion</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="trailer_pokretni_pod" value="Pokretni pod">
                            <label class="form-check-label" for="trailer_pokretni_pod">Pokretni pod</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="trailer_silos" value="Silos">
                            <label class="form-check-label" for="trailer_silos">Silos</label>
                        </div>
                    </div>

                    <!-- Peta kolona (4 stavke) -->
                    <div class="col-6 col-lg">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="trailer_specijalan_kamion" value="Specijalan kamion">
                            <label class="form-check-label" for="trailer_specijalan_kamion">Specijalan kamion</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="trailer_tautlajner" value="Tautlajner">
                            <label class="form-check-label" for="trailer_tautlajner">Tautlajner</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="trailer_tegljac" value="Tegljač">
                            <label class="form-check-label" for="trailer_tegljac">Tegljač</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="trailer[]" id="trailer_termo" value="Termo">
                            <label class="form-check-label" for="trailer_termo">Termo</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information Section -->
            <div class="mb-4">
                <h4 class="section-title">Dodatne informacije</h4>
                <div class="mb-3">
                    <label for="description" class="form-label">Opis</label>
                    <textarea class="form-control" id="description" name="description" rows="2"
                              placeholder="Dodatne informacije"></textarea>
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