<?php
/**
 * sve operacije sa formama
 */

add_filter('uwp_country_list', 'uwp_country_list');
function uwp_country_list($country_list) {
	return array(
		'ad' => 'Andorra',
		'am' => 'Armenia (Հայաստան)',
		'at' => 'Austria (Österreich)',
		'az' => 'Azerbaijan (Azərbaycan)',
		'by' => 'Belarus (Беларусь)',
		'be' => 'Belgium (België)',
		'ba' => 'Bosnia and Herzegovina (Босна и Херцеговина)',
		'bg' => 'Bulgaria (България)',
		'hr' => 'Croatia (Hrvatska)',
		'cy' => 'Cyprus (Κύπρος)',
		'cz' => 'Czech Republic (Česká republika)',
		'dk' => 'Denmark (Danmark)',
		'ee' => 'Estonia (Eesti)',
		'fi' => 'Finland (Suomi)',
		'fr' => 'France',
		'ge' => 'Georgia (საქართველო)',
		'de' => 'Germany (Deutschland)',
		'gr' => 'Greece (Ελλάδα)',
		'hu' => 'Hungary (Magyarország)',
		'is' => 'Iceland (Ísland)',
		'ie' => 'Ireland',
		'it' => 'Italy (Italia)',
		'kz' => 'Kazakhstan (Казахстан)',
		'lv' => 'Latvia (Latvija)',
		'li' => 'Liechtenstein',
		'lt' => 'Lithuania (Lietuva)',
		'lu' => 'Luxembourg',
		'mk' => 'North Macedonia (Македонија)',
		'mt' => 'Malta',
		'md' => 'Moldova (Republica Moldova)',
		'mc' => 'Monaco',
		'me' => 'Montenegro (Crna Gora)',
		'nl' => 'Netherlands (Nederland)',
		'no' => 'Norway (Norge)',
		'pl' => 'Poland (Polska)',
		'pt' => 'Portugal',
		'ro' => 'Romania (România)',
		'ru' => 'Russia (Россия)',
		'sm' => 'San Marino',
		'rs' => 'Serbia (Србија)',
		'sk' => 'Slovakia (Slovensko)',
		'si' => 'Slovenia (Slovenija)',
		'es' => 'Spain (España)',
		'se' => 'Sweden (Sverige)',
		'ch' => 'Switzerland (Schweiz)',
		'tr' => 'Turkey (Türkiye)',
		'ua' => 'Ukraine (Україна)',
		'gb' => 'United Kingdom',
		'va' => 'Vatican City (Città del Vaticano)'
	);
}