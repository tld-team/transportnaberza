jQuery(document).ready(function($) {
    // Učitavanje podataka o zemljama i gradovima
    $.getJSON('../country/countries-cities.json', function(data) {
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

        // Inicijalizacija autocomplete-a
        $("#autocomplete").autocomplete({
            source: function(request, response) {
                response(displaySuggestions(request.term));
            },
            minLength: 2,
            select: function(event, ui) {
                // Ovde možete dodati radnju kada korisnik izabere neku sugestiju
                console.log('Selected:', ui.item);
            }
        }).data("ui-autocomplete")._renderItem = function(ul, item) {
            return $("<li>")
                .append("<div>")
                .append(item.flag)
                .append(" " + item.label)
                .appendTo(ul);
        };
    });
});