<?php
class component_collections_country {
	/**
	 * @var debug_logger $logger
	 */
	protected debug_logger $logger;

	/**
	 * @var array $defaultCountry 
	 */
    private array $defaultCountry = [
		"AF"=>"Afghanistan",
		"AL"=>"Albania",
		"DZ"=>"Algeria",
		"AD"=>"Andorra",
		"AO"=>"Angola",
		"AG"=>"Antigua and Barbuda",
		"AR"=>"Argentina",
		"AM"=>"Armenia",
		"AW"=>"Aruba",
		"AU"=>"Australia",
		"AT"=>"Austria",
		"AZ"=>"Azerbaijan",
		"BS"=>"Bahamas",
		"BH"=>"Bahrain",
		"BD"=>"Bangladesh",
		"BB"=>"Barbados",
		"BY"=>"Belarus",
		"BE"=>"Belgium",
		"BZ"=>"Belize",
		"BJ"=>"Benin",
		"BM"=>"Bermuda",
		"BT"=>"Bhutan",
		"BO"=>"Bolivia",
		"BA"=>"Bosnia-Herzegovina",
		"BW"=>"Botswana",
		"BR"=>"Brazil",
		"VG"=>"British Virgin Islands",
		"BN"=>"Brunei",
		"BG"=>"Bulgaria",
		"BF"=>"Burkina Faso",
		"BI"=>"Burundi",
		"KH"=>"Cambodia",
		"CM"=>"Cameroon",
		"CA"=>"Canada",
		"CV"=>"Cape Verde",
		"KY"=>"Cayman Islands",
		"CF"=>"Central African Republic",
		"TD"=>"Chad",
		"CL"=>"Chile",
		"CN"=>"China",
		"CO"=>"Colombia",
		"KM"=>"Comoros",
		"CG"=>"Congo (Brazzaville)",
		"CD"=>"Congo (Democratic Rep.)",
		"CR"=>"Costa Rica",
		"CI"=>"Cote d'Ivoire",
		"HR"=>"Croatia",
		"CU"=>"Cuba",
		"CY"=>"Cyprus",
		"CZ"=>"Czech Republic",
		"DK"=>"Denmark",
		"DJ"=>"Djibouti",
		"DM"=>"Dominica",
		"DO"=>"Dominican Republic",
		"EC"=>"Ecuador",
		"EG"=>"Egypt",
		"SV"=>"El Salvador",
		"GQ"=>"Equatorial Guinea",
		"ER"=>"Eritrea",
		"EE"=>"Estonia",
		"ET"=>"Ethiopia",
		"FK"=>"Falkland Islands",
		"FO"=>"Faroe Islands",
		"FJ"=>"Fiji",
		"FI"=>"Finland",
		"FR"=>"France",
		"GF"=>"French Guiana",
		"PF"=>"French Polynesia",
		"GA"=>"Gabon",
		"GM"=>"Gambia",
		"GE"=>"Georgia",
		"DE"=>"Germany",
		"GH"=>"Ghana",
		"GI"=>"Gibraltar",
		"GR"=>"Greece",
		"GL"=>"Greenland",
		"GD"=>"Grenada",
		"GP"=>"Guadeloupe",
		"GT"=>"Guatemala",
		"GG"=>"Guernsey",
		"GN"=>"Guinea",
		"GW"=>"Guinea-Bissau",
		"GY"=>"Guyana",
		"HT"=>"Haiti",
		"HN"=>"Honduras",
		"HK"=>"Hong Kong",
		"HU"=>"Hungary",
		"IS"=>"Iceland",
		"IN"=>"India",
		"ID"=>"Indonesia",
		"IR"=>"Iran",
		"IQ"=>"Iraq",
		"IE"=>"Ireland",
		"IM"=>"Isle of Man",
		"IL"=>"Israel",
		"IT"=>"Italy",
		"JM"=>"Jamaica",
		"JP"=>"Japan",
		"JE"=>"Jersey",
		"JO"=>"Jordan",
		"KZ"=>"Kazakhstan",
		"KE"=>"Kenya",
		"KI"=>"Kiribati",
		"KV"=>"Kosovo",
		"KW"=>"Kuwait",
		"KG"=>"Kyrgyzstan",
		"LA"=>"Laos",
		"LV"=>"Latvia",
		"LB"=>"Lebanon",
		"LS"=>"Lesotho",
		"LR"=>"Liberia",
		"LY"=>"Libya",
		"LI"=>"Liechtenstein",
		"LT"=>"Lithuania",
		"LU"=>"Luxembourg",
		"MO"=>"Macau",
		"MK"=>"Macedonia",
		"MG"=>"Madagascar",
		"MW"=>"Malawi",
		"MY"=>"Malaysia",
		"MV"=>"Maldives",
		"ML"=>"Mali",
		"MT"=>"Malta",
		"MH"=>"Marshall Islands",
		"MQ"=>"Martinique",
		"MR"=>"Mauritania",
		"MU"=>"Mauritius",
		"YT"=>"Mayotte",
		"MX"=>"Mexico",
		"FM"=>"Micronesia",
		"MD"=>"Moldova",
		"MC"=>"Monaco",
		"MN"=>"Mongolia",
		"ME"=>"Montenegro",
		"MA"=>"Morocco",
		"MZ"=>"Mozambique",
		"MM"=>"Myanmar",
		"NA"=>"Namibia",
		"NR"=>"Nauru",
		"NP"=>"Nepal",
		"NL"=>"Netherlands",
		"NC"=>"New Caledonia",
		"NZ"=>"New Zealand",
		"NI"=>"Nicaragua",
		"NE"=>"Niger",
		"NG"=>"Nigeria",
		"KP"=>"North Korea",
		"NO"=>"Norway",
		"OM"=>"Oman",
		"PK"=>"Pakistan",
		"PW"=>"Palau",
		"PA"=>"Panama",
		"PG"=>"Papua New Guinea",
		"PY"=>"Paraguay",
		"PE"=>"Peru",
		"PH"=>"Philippines",
		"PL"=>"Poland",
		"PT"=>"Portugal",
		"PR"=>"Puerto Rico",
		"QA"=>"Qatar",
		"RE"=>"Reunion",
		"RO"=>"Romania",
		"RU"=>"Russia",
		"RW"=>"Rwanda",
		"BL"=>"Saint Barthelemy",
		"KN"=>"Saint Kitts and Nevis",
		"LC"=>"Saint Lucia",
		"MF"=>"Saint Martin",
		"PM"=>"Saint Pierre and Miquelon",
		"VC"=>"Saint Vincent and the Grenadines",
		"WS"=>"Samoa",
		"SM"=>"San Marino",
		"ST"=>"Sao Tome and Principe",
		"SA"=>"Saudi Arabia",
		"SN"=>"Senegal",
		"RS"=>"Serbia",
		"SC"=>"Seychelles",
		"SL"=>"Sierra Leone",
		"SG"=>"Singapore",
		"SK"=>"Slovakia",
		"SI"=>"Slovenia",
		"SB"=>"Solomon Islands",
		"SO"=>"Somalia",
		"ZA"=>"South Africa",
		"KR"=>"South Korea",
		"SS"=>"South Sudan",
		"ES"=>"Spain",
		"LK"=>"Sri Lanka",
		"SD"=>"Sudan",
		"SR"=>"Suriname",
		"SJ"=>"Svalbard",
		"SZ"=>"Swaziland",
		"SE"=>"Sweden",
		"CH"=>"Switzerland",
		"SY"=>"Syria",
		"TW"=>"Taiwan",
		"TJ"=>"Tajikistan",
		"TZ"=>"Tanzania",
		"TH"=>"Thailand",
		"TL"=>"Timor-Leste",
		"TG"=>"Togo",
		"TO"=>"Tonga",
		"TT"=>"Trinidad and Tobago",
		"TN"=>"Tunisia",
		"TR"=>"Turkey",
		"TM"=>"Turkmenistan",
		"TC"=>"Turks and Caicos",
		"TV"=>"Tuvalu",
		"UG"=>"Uganda",
		"UA"=>"Ukraine",
		"AE"=>"United Arab Emirates",
		"GB"=>"United Kingdom",
		"US"=>"United States",
		"UY"=>"Uruguay",
		"UZ"=>"Uzbekistan",
		"VU"=>"Vanuatu",
		"VA"=>"Vatican City",
		"VE"=>"Venezuela",
		"VN"=>"Vietnam",
		"WF"=>"Wallis et Futuna",
		"EH"=>"Western Sahara",
		"YE"=>"Yemen",
		"ZM"=>"Zambia",
		"ZW"=>"Zimbabwe"
	];

	/**
	 * @param array $config
	 * @param array $params
	 * @return array|bool
	 */
	public function fetchData(array $config, array $params = []) {
		if ($config['context'] === 'all') {
			switch ($config['type']) {
				case 'countries':
					$query = 'SELECT * FROM mc_country ORDER BY name_country';
					break;
				case 'towns':
					$query = 'SELECT mt.*, mc.iso_country 
							FROM mc_town mt
							LEFT JOIN mc_country mc on mc.id_country = mt.id_country
							ORDER BY name_tn';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetchAll($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'country':
					$query = 'SELECT * FROM mc_country WHERE id_country = :id';
					break;
				case 'town':
					$query = 'SELECT * FROM mc_town WHERE id_tn = :id';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetch($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		return false;
	}

	/**
	 * @return array
	 */
    public function getCountries(): array {
        $country = $this->defaultCountry;
        asort($country,SORT_STRING);
        return $country;
    }

	/**
	 * @return array
	 */
	public function getAllowedCountries(): array {
		$countries = [];
		$countriesData = $this->fetchData(['context'=>'all','type'=>'countries']);
		if(!empty($countriesData)) {
			foreach ($countriesData as $c) {
				//$arr[$c['iso_country']] = $c['name_country'];
				$countries[$c['id_country']] = [
					'id' => $c['id_country'],
					'iso' => $c['iso_country'],
					'name' => strtolower($c['name_country'])
				];
			}
		}
		return $countries;
	}

	/**
	 * @access public
	 * @static
	 * Retourne le tableau des pays
	 */
	public function getTown($id) {
		return $this->fetchData(['context'=>'one','type'=>'town'],['id' => $id]);
	}

    /**
     * @access public
     * @static
     * Retourne le tableau des pays
     */
    public function getTowns() {
        return $this->fetchData(['context'=>'all','type'=>'towns']);
    }
}