jQuery(document).ready(function($) {
    // Učitavanje podataka o zemljama i gradovima
    $.getJSON(autocompleteData.json_url, function(data) {
        // Kreiranje mape za brže pretraživanje
        var countryMap = {};
        data.forEach(function(country) {
            countryMap[country.name] = {
                flag: country.flag,
                cities: country.cities
            };
        });

        // Funkcija za prikaz sugestija
        function displaySuggestions(term) {
            var results = [];
            
            // Pretraga zemalja
            Object.keys(countryMap).forEach(function(countryName) {
                if (countryName.toLowerCase().includes(term.toLowerCase())) {
                    results.push({
                        label: countryName,
                        category: 'Country',
                        flag: countryMap[countryName].flag
                    });
                }
            });

            // Pretraga gradova
            Object.keys(countryMap).forEach(function(countryName) {
                var cities = countryMap[countryName].cities;
                cities.forEach(function(city) {
                    if (city.toLowerCase().includes(term.toLowerCase())) {
                        results.push({
                            label: city + ' (' + countryName + ')',
                            category: 'City',
                            flag: countryMap[countryName].flag
                        });
                    }
                });
            });

            return results;
        }

        function updateFlag(inputId, flagHtml) {
            var input = $('#' + inputId);
            var wrapper = input.closest('.wpcf7-form-control-wrap');
            var flagContainer = wrapper.find('.flag-container');
            
            if (flagContainer.length === 0) {
                flagContainer = $('<div class="flag-container"></div>');
                wrapper.prepend(flagContainer);
            }
            
            flagContainer.html(flagHtml);
        }

        // Inicijalizacija autocomplete-a za country_from
        $("#country_from").autocomplete({
            source: function(request, response) {
                response(displaySuggestions(request.term));
            },
            minLength: 2,
            select: function(event, ui) {
                updateFlag('country_from', ui.item.flag);
                return false; // Prevent the default action
            }
        }).data("ui-autocomplete")._renderItem = function(ul, item) {
            return $("<li>")
                .append("<div class='autocomplete-item' style='display: flex; align-items: center;'>")
                .append("<div class='flag-container' style='display: inline-block;'>" + item.flag + "</div>")
                .append("<span class='label' style='display: inline-block;'>" + item.label + "</span>")
                .append("</div>")
                .appendTo(ul);
        };

        // Inicijalizacija autocomplete-a za country_to
        $("#country_to").autocomplete({
            source: function(request, response) {
                response(displaySuggestions(request.term));
            },
            minLength: 2,
            select: function(event, ui) {
                updateFlag('country_to', ui.item.flag);
                return false; // Prevent the default action
            }
        }).data("ui-autocomplete")._renderItem = function(ul, item) {
            return $("<li>")
                .append("<div class='autocomplete-item' style='display: flex; align-items: center;'>")
                .append("<div class='flag-container' style='display: inline-block;'>" + item.flag + "</div>")
                .append("<span class='label' style='display: inline-block;'>" + item.label + "</span>")
                .append("</div>")
                .appendTo(ul);
        };
        
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error('Error loading countries data:', textStatus, errorThrown);
        alert('Error loading country data: ' + textStatus);
    });
});