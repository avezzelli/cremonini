<?php
namespace zecchini;

/*** DEFINISCO ALCUNE VARIABILI GLOBALI ALL'INTERNO DELLA CLASSE ***/
define('FRM_ADD', 'form-add-');
define('FRM_DETAILS', 'form-details-');
define('FRM_SAVE', 'form-save-');
define('FRM_UPDATE', 'form-update-');
define('FRM_DELETE', 'form-delete-');
define('FRM_SEARCH', 'form-search-');
define('FRM_SEARCH_SUBMIT', 'form-search-submit');
define('FRM_SEARCH_RESET', 'form-search-reset');
define('COLONNA_ETICHETTA', 'col-sm-3');
define('COLONNA_CONTENUTO', 'col-sm-9');

/**
 * Description of PrinterView
 *
 * @author Alex
 */
class PrinterView {
    //put your code here
    private $mesi;
    private $giorni;
    private $annoCorrente;
    private $meseCorrente;
    private $giornoCorrente;
    private $settimanaCorrente;
    private $countries;
    private $province;
    private $operatore;
    
    function __construct() {
        $this->operatore = array(
            '<=' => '<=',
            '>=' => '>='
        );
        
        $this->mesi = array(
            '01' => 'Gennaio',
            '02' => 'Febbraio',
            '03' => 'Marzo',
            '04' => 'Aprile',
            '05' => 'Maggio',
            '06' => 'Giugno',
            '07' => 'Luglio',
            '08' => 'Agosto',
            '09' => 'Settembre',
            '10' => 'Ottobre',
            '11' => 'Novembre',
            '12' => 'Dicembre'
        );
        
        $this->giorni = array(
            '1'  => 'Lunedì',
            '2'  => 'Martedì',
            '3'  => 'Mercoledì',
            '4'  => 'Giovedì',
            '5'  => 'Venerdì',
            '6'  => 'Sabato',
            '7'  => 'Domenica'
        );
        
        $this->countries = array(
	'AF' => 'Afghanistan',
	'AX' => 'Aland Islands',
	'AL' => 'Albania',
	'DZ' => 'Algeria',
	'AS' => 'American Samoa',
	'AD' => 'Andorra',
	'AO' => 'Angola',
	'AI' => 'Anguilla',
	'AQ' => 'Antarctica',
	'AG' => 'Antigua And Barbuda',
	'AR' => 'Argentina',
	'AM' => 'Armenia',
	'AW' => 'Aruba',
	'AU' => 'Australia',
	'AT' => 'Austria',
	'AZ' => 'Azerbaijan',
	'BS' => 'Bahamas',
	'BH' => 'Bahrain',
	'BD' => 'Bangladesh',
	'BB' => 'Barbados',
	'BY' => 'Belarus',
	'BE' => 'Belgium',
	'BZ' => 'Belize',
	'BJ' => 'Benin',
	'BM' => 'Bermuda',
	'BT' => 'Bhutan',
	'BO' => 'Bolivia',
	'BA' => 'Bosnia And Herzegovina',
	'BW' => 'Botswana',
	'BV' => 'Bouvet Island',
	'BR' => 'Brazil',
	'IO' => 'British Indian Ocean Territory',
	'BN' => 'Brunei Darussalam',
	'BG' => 'Bulgaria',
	'BF' => 'Burkina Faso',
	'BI' => 'Burundi',
	'KH' => 'Cambodia',
	'CM' => 'Cameroon',
	'CA' => 'Canada',
	'CV' => 'Cape Verde',
	'KY' => 'Cayman Islands',
	'CF' => 'Central African Republic',
	'TD' => 'Chad',
	'CL' => 'Chile',
	'CN' => 'China',
	'CX' => 'Christmas Island',
	'CC' => 'Cocos (Keeling) Islands',
	'CO' => 'Colombia',
	'KM' => 'Comoros',
	'CG' => 'Congo',
	'CD' => 'Congo, Democratic Republic',
	'CK' => 'Cook Islands',
	'CR' => 'Costa Rica',
	'CI' => 'Cote D\'Ivoire',
	'HR' => 'Croatia',
	'CU' => 'Cuba',
	'CY' => 'Cyprus',
	'CZ' => 'Czech Republic',
	'DK' => 'Denmark',
	'DJ' => 'Djibouti',
	'DM' => 'Dominica',
	'DO' => 'Dominican Republic',
	'EC' => 'Ecuador',
	'EG' => 'Egypt',
	'SV' => 'El Salvador',
	'GQ' => 'Equatorial Guinea',
	'ER' => 'Eritrea',
	'EE' => 'Estonia',
	'ET' => 'Ethiopia',
	'FK' => 'Falkland Islands (Malvinas)',
	'FO' => 'Faroe Islands',
	'FJ' => 'Fiji',
	'FI' => 'Finland',
	'FR' => 'France',
	'GF' => 'French Guiana',
	'PF' => 'French Polynesia',
	'TF' => 'French Southern Territories',
	'GA' => 'Gabon',
	'GM' => 'Gambia',
	'GE' => 'Georgia',
	'DE' => 'Germany',
	'GH' => 'Ghana',
	'GI' => 'Gibraltar',
	'GR' => 'Greece',
	'GL' => 'Greenland',
	'GD' => 'Grenada',
	'GP' => 'Guadeloupe',
	'GU' => 'Guam',
	'GT' => 'Guatemala',
	'GG' => 'Guernsey',
	'GN' => 'Guinea',
	'GW' => 'Guinea-Bissau',
	'GY' => 'Guyana',
	'HT' => 'Haiti',
	'HM' => 'Heard Island & Mcdonald Islands',
	'VA' => 'Holy See (Vatican City State)',
	'HN' => 'Honduras',
	'HK' => 'Hong Kong',
	'HU' => 'Hungary',
	'IS' => 'Iceland',
	'IN' => 'India',
	'ID' => 'Indonesia',
	'IR' => 'Iran, Islamic Republic Of',
	'IQ' => 'Iraq',
	'IE' => 'Ireland',
	'IM' => 'Isle Of Man',
	'IL' => 'Israel',
	'IT' => 'Italy',
	'JM' => 'Jamaica',
	'JP' => 'Japan',
	'JE' => 'Jersey',
	'JO' => 'Jordan',
	'KZ' => 'Kazakhstan',
	'KE' => 'Kenya',
	'KI' => 'Kiribati',
	'KR' => 'Korea',
	'KW' => 'Kuwait',
	'KG' => 'Kyrgyzstan',
	'LA' => 'Lao People\'s Democratic Republic',
	'LV' => 'Latvia',
	'LB' => 'Lebanon',
	'LS' => 'Lesotho',
	'LR' => 'Liberia',
	'LY' => 'Libyan Arab Jamahiriya',
	'LI' => 'Liechtenstein',
	'LT' => 'Lithuania',
	'LU' => 'Luxembourg',
	'MO' => 'Macao',
	'MK' => 'Macedonia',
	'MG' => 'Madagascar',
	'MW' => 'Malawi',
	'MY' => 'Malaysia',
	'MV' => 'Maldives',
	'ML' => 'Mali',
	'MT' => 'Malta',
	'MH' => 'Marshall Islands',
	'MQ' => 'Martinique',
	'MR' => 'Mauritania',
	'MU' => 'Mauritius',
	'YT' => 'Mayotte',
	'MX' => 'Mexico',
	'FM' => 'Micronesia, Federated States Of',
	'MD' => 'Moldova',
	'MC' => 'Monaco',
	'MN' => 'Mongolia',
	'ME' => 'Montenegro',
	'MS' => 'Montserrat',
	'MA' => 'Morocco',
	'MZ' => 'Mozambique',
	'MM' => 'Myanmar',
	'NA' => 'Namibia',
	'NR' => 'Nauru',
	'NP' => 'Nepal',
	'NL' => 'Netherlands',
	'AN' => 'Netherlands Antilles',
	'NC' => 'New Caledonia',
	'NZ' => 'New Zealand',
	'NI' => 'Nicaragua',
	'NE' => 'Niger',
	'NG' => 'Nigeria',
	'NU' => 'Niue',
	'NF' => 'Norfolk Island',
	'MP' => 'Northern Mariana Islands',
	'NO' => 'Norway',
	'OM' => 'Oman',
	'PK' => 'Pakistan',
	'PW' => 'Palau',
	'PS' => 'Palestinian Territory, Occupied',
	'PA' => 'Panama',
	'PG' => 'Papua New Guinea',
	'PY' => 'Paraguay',
	'PE' => 'Peru',
	'PH' => 'Philippines',
	'PN' => 'Pitcairn',
	'PL' => 'Poland',
	'PT' => 'Portugal',
	'PR' => 'Puerto Rico',
	'QA' => 'Qatar',
	'RE' => 'Reunion',
	'RO' => 'Romania',
	'RU' => 'Russian Federation',
	'RW' => 'Rwanda',
	'BL' => 'Saint Barthelemy',
	'SH' => 'Saint Helena',
	'KN' => 'Saint Kitts And Nevis',
	'LC' => 'Saint Lucia',
	'MF' => 'Saint Martin',
	'PM' => 'Saint Pierre And Miquelon',
	'VC' => 'Saint Vincent And Grenadines',
	'WS' => 'Samoa',
	'SM' => 'San Marino',
	'ST' => 'Sao Tome And Principe',
	'SA' => 'Saudi Arabia',
	'SN' => 'Senegal',
	'RS' => 'Serbia',
	'SC' => 'Seychelles',
	'SL' => 'Sierra Leone',
	'SG' => 'Singapore',
	'SK' => 'Slovakia',
	'SI' => 'Slovenia',
	'SB' => 'Solomon Islands',
	'SO' => 'Somalia',
	'ZA' => 'South Africa',
	'GS' => 'South Georgia And Sandwich Isl.',
	'ES' => 'Spain',
	'LK' => 'Sri Lanka',
	'SD' => 'Sudan',
	'SR' => 'Suriname',
	'SJ' => 'Svalbard And Jan Mayen',
	'SZ' => 'Swaziland',
	'SE' => 'Sweden',
	'CH' => 'Switzerland',
	'SY' => 'Syrian Arab Republic',
	'TW' => 'Taiwan',
	'TJ' => 'Tajikistan',
	'TZ' => 'Tanzania',
	'TH' => 'Thailand',
	'TL' => 'Timor-Leste',
	'TG' => 'Togo',
	'TK' => 'Tokelau',
	'TO' => 'Tonga',
	'TT' => 'Trinidad And Tobago',
	'TN' => 'Tunisia',
	'TR' => 'Turkey',
	'TM' => 'Turkmenistan',
	'TC' => 'Turks And Caicos Islands',
	'TV' => 'Tuvalu',
	'UG' => 'Uganda',
	'UA' => 'Ukraine',
	'AE' => 'United Arab Emirates',
	'GB' => 'United Kingdom',
	'US' => 'United States',
	'UM' => 'United States Outlying Islands',
	'UY' => 'Uruguay',
	'UZ' => 'Uzbekistan',
	'VU' => 'Vanuatu',
	'VE' => 'Venezuela',
	'VN' => 'Viet Nam',
	'VG' => 'Virgin Islands, British',
	'VI' => 'Virgin Islands, U.S.',
	'WF' => 'Wallis And Futuna',
	'EH' => 'Western Sahara',
	'YE' => 'Yemen',
	'ZM' => 'Zambia',
	'ZW' => 'Zimbabwe',
        );
        
        $this->province = array(
            'AG' => 'Agrigento',
            'AL' => 'Alessandria',
            'AN' => 'Ancona',
            'AO' => 'Aosta',
            'AR' => 'Arezzo',
            'AP' => 'Ascoli Piceno',
            'AT' => 'Asti',
            'AV' => 'Avellino',
            'BA' => 'Bari',
            'BT' => 'Barletta-Andria-Trani',
            'BL' => 'Belluno',
            'BN' => 'Benevento',
            'BG' => 'Bergamo',
            'BI' => 'Biella',
            'BO' => 'Bologna',
            'BZ' => 'Bolzano',
            'BS' => 'Brescia',
            'BR' => 'Brindisi',
            'CA' => 'Cagliari',
            'CL' => 'Caltanissetta',
            'CB' => 'Campobasso',
            'CI' => 'Carbonia-Iglesias',
            'CE' => 'Caserta',
            'CT' => 'Catania',
            'CZ' => 'Catanzaro',
            'CH' => 'Chieti',
            'CO' => 'Como',
            'CS' => 'Cosenza',
            'CR' => 'Cremona',
            'KR' => 'Crotone',
            'CN' => 'Cuneo',
            'EN' => 'Enna',
            'FM' => 'Fermo',
            'FE' => 'Ferrara',
            'FI' => 'Firenze',
            'FG' => 'Foggia',
            'FC' => 'Forlì-Cesena',
            'FR' => 'Frosinone',
            'GE' => 'Genova',
            'GO' => 'Gorizia',
            'GR' => 'Grosseto',
            'IM' => 'Imperia',
            'IS' => 'Isernia',
            'SP' => 'La Spezia',
            'AQ' => 'L\'Aquila',
            'LT' => 'Latina',
            'LE' => 'Lecce',
            'LC' => 'Lecco',
            'LI' => 'Livorno',
            'LO' => 'Lodi',
            'LU' => 'Lucca',
            'MC' => 'Macerata',
            'MN' => 'Mantova',
            'MS' => 'Massa-Carrara',
            'MT' => 'Matera',
            'ME' => 'Messina',
            'MI' => 'Milano',
            'MO' => 'Modena',
            'MB' => 'Monza e della Brianza',
            'NA' => 'Napoli',
            'NO' => 'Novara',
            'NU' => 'Nuoro',
            'OT' => 'Olbia-Tempio',
            'OR' => 'Oristano',
            'PD' => 'Padova',
            'PA' => 'Palermo',
            'PR' => 'Parma',
            'PV' => 'Pavia',
            'PG' => 'Perugia',
            'PU' => 'Pesaro e Urbino',
            'PE' => 'Pescara',
            'PC' => 'Piacenza',
            'PI' => 'Pisa',
            'PT' => 'Pistoia',
            'PN' => 'Pordenone',
            'PZ' => 'Potenza',
            'PO' => 'Prato',
            'RG' => 'Ragusa',
            'RA' => 'Ravenna',
            'RC' => 'Reggio Calabria',
            'RE' => 'Reggio Emilia',
            'RI' => 'Rieti',
            'RN' => 'Rimini',
            'RM' => 'Roma',
            'RO' => 'Rovigo',
            'SA' => 'Salerno',
            'VS' => 'Medio Campidano',
            'SS' => 'Sassari',
            'SV' => 'Savona',
            'SI' => 'Siena',
            'SR' => 'Siracusa',
            'SO' => 'Sondrio',
            'TA' => 'Taranto',
            'TE' => 'Teramo',
            'TR' => 'Terni',
            'TO' => 'Torino',
            'OG' => 'Ogliastra',
            'TP' => 'Trapani',
            'TN' => 'Trento',
            'TV' => 'Treviso',
            'TS' => 'Trieste',
            'UD' => 'Udine',
            'VA' => 'Varese',
            'VE' => 'Venezia',
            'VB' => 'Verbano-Cusio-Ossola',
            'VC' => 'Vercelli',
            'VR' => 'Verona',
            'VV' => 'Vibo Valentia',
            'VI' => 'Vicenza',
            'VT' => 'Viterbo',
        );
              
        date_default_timezone_set('Europe/Rome');
        $this->annoCorrente = intval(date('Y'));
        $this->meseCorrente = date('m');
        $this->giornoCorrente = intval(date('d'));
                
        $date = new \DateTime($this->annoCorrente.'-'.$this->meseCorrente.'-'.$this->giornoCorrente);
        $this->settimanaCorrente = $date->format("W");
    }
    
    /**
     * La funzione stampa per comodità l'apertura del tag form
     * @param type $name
     */
    protected function printStartAddForm($name, $files=false){
        $html = '';
        if($files == true){        
            $html = 'enctype="multipart/form-data"';
        }
    ?>
        <form class="form-horizontal" role="form" action="<?php echo curPageURL() ?>" name="<?php echo FRM_ADD.$name ?>" method="POST" <?php echo $html ?> >   
    <?php
    }
    
    /**
     * La funzione stampa per comodita il bottone submit e la chiusura del tag form
     * @param type $name
     */
    protected function printEndAddForm($name){
    ?>
            <?php $this->printSubmitFormField(FRM_SAVE.$name, 'SALVA') ?>
        </form>
    <?php
    }
    
    protected function printStartDetailsForm($name, $files=false){
        $html = '';
        if($files == true){        
            $html = 'enctype="multipart/form-data"';
        }
    ?>
        <form class="form-horizontal" role="form" action="<?php echo curPageURL() ?>" name="<?php echo FRM_DETAILS.$name ?>" method="POST" <?php echo $html ?> >   
    <?php
    }
    
    protected function printEndDetailsForm($name, $disabled = null){
    ?>
            <?php if($disabled == null): ?>
                <?php $this->printUpdateDettaglio($name) ?>
                <?php $this->printDeleteDettaglio($name) ?>
            <?php endif; ?>
        </form>
    <?php    
    }
    
    protected function printStartSearchForm($name){
    ?>
        <form class="form-horizontal search-box" role="form" action="<?php echo curPageURL() ?>" name="<?php echo FRM_SEARCH.$name ?>" method="POST">
    <?php
    }
    
    protected function printEndSearchForm(){
        $this->printSubmitFormField(FRM_SEARCH_SUBMIT, 'RICERCA');
        $this->printSubmitFormField(FRM_SEARCH_RESET, 'PULISCI');
    ?>
        </form>
    <?php
    }
    
    
    /**
     * Funzione che stampa secondo canoni bootstrap un input text
     * @param type $nameField
     * @param type $label
     */
    protected function printTextFormField($nameField, $label, $required=false, $value=null, $disabled = null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = stripslashes($_POST[$nameField]);
            }
        }
        $dis = '';
        if($disabled == true){
            $dis = 'disabled';
        }
        
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="<?php echo COLONNA_CONTENUTO ?>">            
                <input class="form-control" type="text" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="<?php echo $value ?>" <?php echo $optRequired.' '.$dis ?> />
            </div>
        </div>
    <?php  
    }
    
    protected function printPasswordFormField($nameField, $label, $required=false, $value=null, $disabled = null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = stripslashes($_POST[$nameField]);
            }
        }
        $dis = '';
        if($disabled == true){
            $dis = 'disabled';
        }
        
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="<?php echo COLONNA_CONTENUTO ?>">            
                <input class="form-control" type="password" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="<?php echo $value ?>" <?php echo $optRequired.' '.$dis ?> />
            </div>
        </div>
    <?php  
    }
    
    protected function printSuggestTextFormField($nameField, $label, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = stripslashes($_POST[$nameField]);
            }
        }
        
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="<?php echo COLONNA_CONTENUTO ?>">            
                <input class="form-control" type="text" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="<?php echo $value ?>" <?php echo $optRequired ?> />
                <div class="suggerimenti"></div>
            </div>
        </div>
    <?php  
    }
    
    protected function printCheckBoxInlineFormField($nameField, $label, $array, $value=null){
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = stripslashes($_POST[$nameField]);
            }
        }
    ?>    
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="<?php echo COLONNA_CONTENUTO ?>"> 
                <?php foreach($array as $k => $v): ?>
                    <?php if ($value == $k): ?>
                        <div class="checkbox"><input name="<?php echo $nameField ?>" type="checkbox" value="<?php echo $k ?>" checked><?php echo $v ?></div>
                    <?php else: ?>                 
                        <div class="checkbox"><input name="<?php echo $nameField ?>" type="checkbox" value="<?php echo $k ?>"><?php echo $v ?></div>                        
                    <?php endif; ?>
                
                <?php endforeach; ?>
            </div>
        </div>
    <?php    
    }
    
    
    /**
     * Funzione che stampa una input text disabilitata
     * @param type $nameField
     * @param type $label
     * @param type $value
     */
    protected function printDisabledTextFormField($nameField, $label, $value){       
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="<?php echo COLONNA_CONTENUTO ?>">            
                <input class="form-control" type="text" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="<?php echo $value ?>" disabled />
            </div>
            <div class="clear"></div>
        </div>
    <?php  
    }
    
    
          
    /**
     * Funzione che stampa secondo i canoni bootstrap una textarea
     * @param type $nameField
     * @param type $label
     */
    protected function printTextAreaFormField($nameField, $label, $required=false, $value=null, $disabled = null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = stripslashes($_POST[$nameField]);
            }
        }
        $dis = '';
        if($disabled == true){
            $dis = 'disabled';
        }
        
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="<?php echo COLONNA_CONTENUTO ?>">    
                <textarea class="form-control" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="" <?php echo $optRequired.' '.$dis ?>><?php echo $value ?></textarea>           
            </div>
        </div>
    <?php      
        
    }
    
    protected function printDisabledTextAreaFormField($nameField, $label, $value){
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="<?php echo COLONNA_CONTENUTO ?>">    
                <textarea class="form-control" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="" disabled ><?php echo $value ?></textarea>           
            </div>
        </div>
    <?php       
    }
    
    protected function printNumberFormField($nameField, $label, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = stripslashes($_POST[$nameField]);
            }
        }  
       
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="<?php echo COLONNA_CONTENUTO ?>">            
                <input class="form-control" type="number" step="any" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="<?php echo $value ?>" <?php echo $optRequired ?> />
            </div>
        </div>
    <?php     
    }
    
    
    protected function printNumberFormFieldAddOn($addOn, $nameField, $label, $required=false, $value=null, $disabled = null ){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = stripslashes($_POST[$nameField]);
            }
        }  
        $dis = '';
        if($disabled == true){
            $dis = 'disabled';
        }
       
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="input-group <?php echo COLONNA_CONTENUTO ?>">
                <span class="input-group-addon" id="basic-addon1"><?php echo $addOn ?></span>
                <input class="form-control" aria-describedby="basic-addon1" type="number" step="any" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="<?php echo $value ?>" <?php echo $optRequired.' '.$dis ?> />
            </div>
        </div>
    <?php     
    }
    
    /**
     * Funzione che stampa secondo i canoni bootstrap una input email
     * @param type $nameField
     * @param type $label
     */
    protected function printEmailFormField($nameField, $label, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value==null){
            if(isset($_POST[$nameField])){
                $value = stripslashes($_POST[$nameField]);
            }
        }
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="<?php echo COLONNA_CONTENUTO ?>">            
                <input class="form-control" type="email" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="<?php echo $value ?>" <?php echo $optRequired ?> />
            </div>
        </div>
    <?php      
    }
    
    
    /**
     * Funzione che stampa secondo i canoni di bootstrap una input file upload
     * @param type $nameField
     * @param type $label
     * @param type $required
     */
    protected function printInputFileFormField($nameField, $label, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        
    ?>
        <div class="container-upload-file">
            <?php if($value != null): ?>
                <div class="file-caricato">
                    <a target="_blank" href="<?php echo $value ?>"><img style="display: block" src="<?php echo $value ?>" /> <?php echo $this->getNomeFile($value) ?> </a>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
                <div class="<?php echo COLONNA_CONTENUTO ?>">    
                    <input name="<?php echo $nameField ?>" type="file" class="file" <?php echo $optRequired ?> />
                </div>
            </div>
        </div>
    <?php
    }
    
    private function getNomeFile($url){        
        $uploads = wp_get_upload_dir();
        $temp = explode($uploads['baseurl'].'/', $url);        
        if(count($temp) > 1){
            $temp2 = explode('/', $temp[1]);            
            if(count($temp2) > 0){
                return $temp2[2];
            }
        }
        return '';
    }
    
    protected function getInputFileFormField($nameField, $label, $required=false){
        $html = '';
        
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        
        $html.= '<div class="form-group">';
        $html.= '<label class="control-label '.COLONNA_ETICHETTA.'" for="'.$nameField.'" >'.$label.'</label>';
        $html.= '<div class="'.COLONNA_CONTENUTO.'">';
        $html.= '<input name="'.$nameField.'" type="file" class="file" '.$optRequired.' />';
        $html.= '</div>';
        $html.= '</div>';
        
        return $html;
    }
    
    
    protected function printImage($label, $url){
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="<?php echo COLONNA_CONTENUTO ?>"> 
                <img id="<?php echo $label ?>" src="<?php echo $url ?>" alt="<?php echo $label ?>" class="img-responsive" />
            </div>
        </div>
    <?php
    }
    
    /**
     * Funzione che stampa a video una input hidden
     * @param type $nameField
     * @param type $value
     */
    protected function printHiddenFormField($nameField, $value){
    ?>
        <input class="input-hidden" type="hidden" name="<?php echo $nameField ?>" value="<?php echo $value ?>" />
    <?php
    }
    
    
    protected function printWpEditor($value, $nameField, $settings=false){
        if($settings == true){
            $settings = array( 'media_buttons' => true );
        }
        wp_editor($value, $nameField, $settings);
    }
    
    /**
     * Funzione che stampa secondo canoni bootstrap una select box
     * @param type $nameField
     * @param type $label
     * @param type $array
     */
    protected function printSelectFormField($nameField, $label, $array, $required=false, $value=null, $disabled = null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = stripslashes($_POST[$nameField]);
            }
        }
        $dis = '';
        if($disabled == true){
           $dis = 'disabled';
        }
        
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="<?php echo COLONNA_CONTENUTO ?>">
                <select name="<?php echo $nameField ?>" id="<?php echo $nameField ?>" <?php echo $optRequired.' '.$dis ?> >
                <!-- campo vuoto -->
                <option value=""></option>
                <?php
                    foreach($array as $k => $v){
                        if($value == $k){
                            echo '<option value="'.$k.'" selected="selected" >'.$v.'</option>';
                        }
                        else{
                            echo '<option value="'.$k.'">'.$v.'</option>';
                        }                        
                    }
                ?>
                </select>
            </div>
        </div>
    <?php      
    }
    
    protected function printChosenSelectFormField($nameField, $label, $array, $required=false, $value=null, $disabled = null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        
        if($disabled == true){
            $disabled = 'disabled';
        }
        
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = stripslashes($_POST[$nameField]);
            }
        }
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="<?php echo COLONNA_CONTENUTO ?>">
                <?php if($disabled == null): ?>
                    <select class="select chosen-<?php echo $nameField ?>"  name="<?php echo $nameField ?>" id="<?php echo $nameField ?>" <?php echo $optRequired ?> >
                    <!-- campo vuoto -->
                    <option value=""></option>
                    <?php
                        foreach($array as $k => $v){
                            if($value == $k){
                                echo '<option value="'.$k.'" selected >'.$v.'</option>';
                            }
                            else{
                                echo '<option value="'.$k.'">'.$v.'</option>';
                            }                        
                        }
                    ?>
                    </select>
                <?php else: ?>
                    <input type="text" disabled value="<?php echo $array[$value] ?>" >
                <?php endif; ?>
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function() {
                  jQuery('.chosen-<?php echo $nameField ?>').chosen();
                });
            </script>
            
        </div>
    <?php      
    }
    
    /**
     * Funzione che stampa secondo canoni bootstrap una select box
     * @param type $nameField
     * @param type $label
     * @param type $array
     */
    protected function printSelectFormFieldNoKey($nameField, $label, $array, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = stripslashes($_POST[$nameField]);
            }
        }
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="<?php echo COLONNA_CONTENUTO ?>">
                <select name="<?php echo $nameField ?>" id="<?php echo $nameField ?>" <?php echo $optRequired ?> >
                <!-- campo vuoto -->
                <option value=""></option>
                <?php
                    foreach($array as $k => $v){
                        if($value === $k){
                            echo '<option value="'.$k.'" selected >'.$v.'</option>';
                        }
                        else{
                            echo '<option value="'.$k.'">'.$v.'</option>';
                        }                        
                    }
                ?>
                </select>
            </div>
        </div>
    <?php      
    }
    
    
    protected function printMultiSelectAdvancedFormField($nameField, $label, $array, $required=false, $value=null, $disabled = null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = $_POST[$nameField];
            }
        }     
               
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>   
            <div class="<?php echo COLONNA_CONTENUTO ?>">
                <?php if($disabled == null): ?>
                    <select name="<?php echo $nameField ?>[]" id="<?php echo $nameField ?>" class="multiselect multiselect-<?php echo $nameField ?>" multiple="multiple" <?php echo $optRequired ?>>
                        <?php
                        if($value != null){
                            foreach($array as $k => $v){
                                $trovato = false;
                                foreach($value as $item){
                                    if($item == $k){
                                        $trovato = $k;
                                    }
                                }                            
                                if($trovato != false){
                                    echo '<option value="'.$k.'" selected >'.$v.'</option>';
                                }
                                else{
                                    echo '<option value="'.$k.'">'.$v.'</option>'; 
                                }
                            }
                        }
                        else{
                            foreach($array as $k => $v){
                                if($value == $k){
                                    echo '<option value="'.$k.'" selected >'.$v.'</option>';
                                }
                                else{
                                    echo '<option value="'.$k.'">'.$v.'</option>';
                                }                        
                            }
                        }

                    ?>
                    </select>
                    <script type="text/javascript">
                        jQuery(document).ready(function() {
                          jQuery('.multiselect-<?php echo $nameField ?>').multipleSelect({
                                filter: true  
                            });
                        });
                    </script>
                <?php else: ?>
                    <?php
                        $selected = '';
                        foreach($value as $item){
                            $selected.=$array[$item].', ';
                        }
                    ?>
                    <input type="text" disabled value="<?php echo $selected ?>" >
                <?php endif; ?>
            </div>
        </div>
    <?php    
    }
    
    protected function printMultiSelectFormField($nameField, $label, $array, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = stripslashes($_POST[$nameField]);
            }
        }       
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="<?php echo COLONNA_CONTENUTO ?>">
                <select multiple name="<?php echo $nameField ?>[]" id="<?php echo $nameField ?>" <?php echo $optRequired ?> >                             
                <?php
                    if($value != null){
                        foreach($array as $k => $v){
                            $trovato = false;
                            foreach($value as $item){
                                if($item == $k){
                                    $trovato = $k;
                                }
                            }                            
                            if($trovato != false){
                                echo '<option value="'.$k.'" selected >'.$v.'</option>';
                            }
                            else{
                                echo '<option value="'.$k.'">'.$v.'</option>'; 
                            }
                        }
                    }
                    else{
                        foreach($array as $k => $v){
                            if($value == $k){
                                echo '<option value="'.$k.'" selected >'.$v.'</option>';
                            }
                            else{
                                echo '<option value="'.$k.'">'.$v.'</option>';
                            }                        
                        }
                    }
                    
                ?>
                </select>
            </div>
        </div>
    <?php      
    }
    
    /**
     * Funzione che stampa secondo i canoni di bootstrap una input radio button
     * @param type $nameField
     * @param type $label
     * @param type $array
     * @param type $required
     * @param type $value
     */
    protected function printRadioFormField($nameField, $label, $array, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = stripslashes($_POST[$nameField]);
            }
        }
        
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
        <?php
            foreach($array as $k => $v){
                if($value == $k){
                    echo '<label class="radio-inline"><input type="radio" value="'.$k.'" name="'.$nameField.'" '.$optRequired.' checked>'.$v.'</label>';
                }
                else{
                    echo '<label class="radio-inline"><input type="radio" value="'.$k.'" name="'.$nameField.'" '.$optRequired.'>'.$v.'</label>';
                }
            }
        ?>
        
        
        </div>
    <?php
    }
    
    protected function printDatePickerFormField($nameField, $label, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = stripslashes($_POST[$nameField]);
            }
        }
        
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="<?php echo COLONNA_CONTENUTO ?>">            
                <input class="form-control datepicker-<?php echo $nameField ?>" type="text" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="<?php echo $value ?>" <?php echo $optRequired ?> />
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function() {
                  jQuery('.datepicker-<?php echo $nameField ?>').datepicker({
                        language: "it",
                        autoclose: true,
                        todayHighlight: true
                    });
                });
            </script>
        </div>
    <?php  
    }
    
    protected function printSearchDatePickerFormField($nameField, $label, $required=false, $value=null){
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = stripslashes($_POST[$nameField]);
            }
        }
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="<?php echo COLONNA_CONTENUTO ?>"> 
                <select style="float:left; margin-right: 10px" name="<?php echo $nameField ?>-operatore" id="<?php echo $nameField ?>-operatore" <?php echo $optRequired ?> >
                <!-- campo vuoto -->
                <option value=""></option>
                <?php
                    foreach($this->getOperatori() as $key => $value2){
                        if($value == $value2){
                            echo '<option value="'.$key.'" selected >'.$value2.'</option>';
                        }
                        else{
                            echo '<option value="'.$key.'">'.$value2.'</option>';
                        }                        
                    }
                ?>
                </select>
                <input style="float:left; width:80%" class="form-control datepicker-<?php echo $nameField ?>" type="text" id="<?php echo $nameField ?>" name="<?php echo $nameField ?>" value="<?php echo $value ?>" <?php echo $optRequired ?> />
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function() {
                  jQuery('.datepicker-<?php echo $nameField ?>').datepicker({
                        language: "it",
                        autoclose: true,
                        todayHighlight: true
                    });
                });
            </script>
        </div>
    <?php  
    }
    
    /**
     * Funzione che stampa un datepicker semplice sulla data di nascita
     * @param type $nameField
     * @param type $label
     * @param type $required
     * @param type $value
     */
    protected function printDateBirthdayFormField($nameField, $label, $required=false, $value=null){
        //il formato da assumere è yyyy-mm-dd
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        $date = array();
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = stripslashes($_POST[$nameField]);
            }
        }
        if($value != null){
            //spacco il valore
            $temp = explode('-', $value);
            if(count($temp) > 0){
                $date['d'] = intval($temp[2]);
                $date['m'] = $temp[1];
                $date['y'] = intval($temp[0]);
            }
        }
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="<?php echo COLONNA_CONTENUTO ?>">
                <!-- giorni -->
                <select class="<?php echo COLONNA_ETICHETTA ?>" name="<?php echo $nameField ?>-d" <?php echo $optRequired ?> >
                <?php
                    for($i=1; $i <= 31; $i++){
                        if($i == $date['d']){
                            echo '<option value="'.$i.'" selected >'.$i.'</option>';
                        }
                        else{
                            echo '<option value="'.$i.'">'.$i.'</option>';
                        }
                    }
                ?>
                </select>
                <!-- mesi -->
                <select class="col-sm-4" name="<?php echo $nameField ?>-m" <?php echo $optRequired ?> >
                    <?php
                        foreach($this->mesi as $k => $v){
                            if($date['m'] == $k){
                                echo '<option value="'.$k.'" selected >'.$v.'</option>';
                            }
                            else{
                                echo '<option value="'.$k.'" >'.$v.'</option>';
                            }
                        }
                    ?>
                </select>
                <!-- anni -->
                <select class="col-sm-3" name="<?php echo $nameField ?>-y" <?php echo $optRequired ?> >
                    <?php
                        for($i= ($this->annoCorrente-100); $i <= $this->annoCorrente; $i++){
                            if($date['y'] == $i){
                                echo '<option value="'.$i.'" selected>'.$i.'</option>';
                            }
                            else{
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            }
                        }
                    ?>
                </select>
            </div>
        </div>
    <?php
    }
    
    /**
     * Funzione che stampa un secondo tipo di datepicker che punta sulla data odierna di default
     * @param type $nameField
     * @param type $label
     * @param type $required
     * @param type $value
     */
    protected function printDateFormField($nameField, $label, $required=false, $value=null){
        //il formato da assumere è yyyy-mm-dd
        $optRequired = "";
        if($required == true){
            $optRequired = "required";
        }
        $date = array();
        if($value == null){
            if(isset($_POST[$nameField])){
                $value = stripslashes($_POST[$nameField]);
            }
        }
        if($value != null){
            //spacco il valore
            $temp = explode('-', $value);
            if(count($temp) > 0){
                $date['d'] = intval($temp[2]);
                $date['m'] = $temp[1];
                $date['y'] = intval($temp[0]);
            }
        }
        
    ?>
        <div class="form-group">
            <label class="control-label <?php echo COLONNA_ETICHETTA ?>" for="<?php echo $nameField ?>" ><?php echo $label ?></label>
            <div class="<?php echo COLONNA_CONTENUTO ?>">
                <!-- giorni -->
                <select class="<?php echo COLONNA_ETICHETTA ?>" name="<?php echo $nameField ?>-d" <?php echo $optRequired ?> >
                <?php
                    for($i=1; $i <= 31; $i++){
                        if(count($date) > 0){
                            //Se è un valore già ottenuto
                            if($i == $date['d']){
                                echo '<option value="'.$i.'" selected >'.$i.'</option>';
                            }
                            else{
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            }
                        }
                        else{
                            //altrimenti mostro il giorno corrente
                            if($i == intval($this->giornoCorrente)){
                                echo '<option value="'.$i.'" selected >'.$i.'</option>';
                            }
                            else{
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            }                            
                        }
                    }
                ?>
                </select>
                <!-- mesi -->
                <select class="col-sm-4" name="<?php echo $nameField ?>-m" <?php echo $optRequired ?> >
                    <?php
                        foreach($this->mesi as $k => $v){
                            if(count($date) > 0){
                                if($date['m'] == $k){
                                    echo '<option value="'.$k.'" selected >'.$v.'</option>';
                                }
                                else{
                                    echo '<option value="'.$k.'" >'.$v.'</option>';
                                }
                            }
                            else{
                                if($k == $this->meseCorrente){
                                    echo '<option value="'.$k.'" selected >'.$v.'</option>';
                                }
                                else{
                                    echo '<option value="'.$k.'" >'.$v.'</option>';
                                }                                
                            }
                        }
                    ?>
                </select>
                <!-- anni -->
                <select class="col-sm-3" name="<?php echo $nameField ?>-y" <?php echo $optRequired ?> >
                    <?php
                        for($i= ($this->annoCorrente-1); $i <= $this->annoCorrente+1; $i++){
                            if(count($date) > 0){                            
                                if($date['y'] == $i){
                                    echo '<option value="'.$i.'" selected>'.$i.'</option>';
                                }
                                else{
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                }
                            }
                            else{
                                if($i == $this->annoCorrente){
                                    echo '<option value="'.$i.'" selected>'.$i.'</option>';
                                }
                                else{
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                }                                    
                            }
                        }
                    ?>
                </select>
            </div>
        </div>
    <?php
    }
    
    
    protected function printSeachButton($nameField, $label){
    ?>
        <button type="button" class="btn <?php echo $nameField ?>"><?php echo $label ?></button>
    <?php
    }
    
    protected function printSubmitFormField($nameField, $label){
    ?>
        <input name="<?php echo $nameField ?>" type="submit" class="btn btn-success" value="<?php echo $label ?>" />
    <?php
    }
    
    
    protected function printActionDettaglio($nameField){
    ?>
        <input name="update-<?php echo $nameField ?>" type="submit" class="btn btn-primary" value="Aggiorna" />
        <input name="delete-<?php echo $nameField ?>" type="submit" class="btn btn-danger" value="Cancella" />
    <?php
    }
    
    protected function printDeleteDettaglio($nameField){
    ?>        
        <input name="<?php echo FRM_DELETE.$nameField ?>" type="submit" class="btn btn-danger" value="Elimina" />
    <?php    
    }
    
    protected function printUpdateDettaglio($nameField){
    ?>
        <input name="<?php echo FRM_UPDATE.$nameField ?>" type="submit" class="btn btn-primary" value="Aggiorna" />
    <?php    
    }
    
    protected function printButtonUrl($nameField, $url){
    ?>
        <a class="btn" href="<?php echo home_url().'/'.$url ?>"><?php echo $nameField ?></a>
    <?php    
    }
    
    static function printButtonUrl2($nameField, $url){
    ?>
        <a class="btn" href="<?php echo home_url().'/'.$url ?>"><?php echo $nameField ?></a>
    <?php    
    }
    
    
    /**
     * Stampa un box di messaggio di errore
     * @param type $message
     */
    protected function printErrorBoxMessage($message){
    ?>
        <div class="alert alert-danger">
            <strong>Errore! </strong> <?php echo $message ?>
        </div>
    <?php    
    }
    
    /**
     * La funzione restituisce il box message di errore con testo settato
     * @param type $field
     * @return type
     */
    protected function printErrorMessage01($field){
        return $this->printErrorBoxMessage('Campo '.$field.' mancante o non corretto');
    }
    
    /**
     * Stampa un box di messaggio di ok
     * @param type $message
     */
    protected function printOkBoxMessage($message){
    ?>
        <div class="alert alert-success">
            <strong>OK! </strong> <?php echo $message ?>
        </div>
    <?php    
    }
    
    /**
     * La funzione stampa il messaggio dopo aver cercato di salvare l'oggetto nel database
     * @param type $type
     * @param type $save
     * @return type
     */
    protected function printMessaggeAfterSave($type, $save){
        // save == -1 --> elemento già inserito
        // save == false --> errore
        // save > 0 --> salvataggio con successo
        
        if($save === -1){
            $this->printErrorBoxMessage($type.' già presente nel sistema.');            
        }
        else if($save === false){
            $this->printErrorBoxMessage('Errore nel salvataggio!');
        }
        else if($save > 0){
            $this->printOkBoxMessage($type.' salvata con successo!');
            unset($_POST);
        }
        
        return;        
    }
    
    /**
     * Stampa il messaggio dopo aver cercato di aggiornare l'oggetto nel database
     * @param type $update
     * @return type
     */
    protected function printMessageAfterUpdate($update){
        if($update > 0){
           $this->printOkBoxMessage('Aggiornamento avvenuto con successo!');
            unset($_POST);           
        }
        else if($update === false){
            $this->printErrorBoxMessage('Errore nell\'aggiornamento.');            
        }
        else if($update === 0){
            $this->printOkBoxMessage('Aggiornamento avvenuto con successo!');
            unset($_POST);           
        }
        return;
    }
    
    /**
     * Stampa il messaggio dopo aver cercato di elminare l'oggetto dal database
     * @param type $delete
     * @return type
     */
    protected function printMessageAfterDelete($delete){
        if($delete === true){
            $this->printOkBoxMessage('Eliminazione avvenuta con successo!');
            unset($_POST);            
        }
        else if($delete === false){
            $this->printErrorBoxMessage('Errore nella cancellazione');            
        }
        else if($delete == -1){
            $this->printErrorBoxMessage('Errore nella cancellazione. Associazione tra più elementi presente.');            
        } 
        return;
    }
    
    protected function printNoResults($type){
    ?>
        <p style="margin-top:30px">Non è stata trovata alcuna <?php echo $type ?></p>
    <?php
    }
    
    /**
     * Stampa una tabella di Bootstrap con effetto hover
     * @param type $header
     * @param type $bodyTable
     */
    protected function printTableHover($header, $bodyTable){
        //bodytable è un html del corpo della tabella
        //è diverso per ogni oggetto e viene descritto nelle classi view corrispettive
    ?>
        <table class="table table-hover">
            <thead>
                <tr>
    <?php
            foreach($header as $h){
    ?>                
                    <th><?php echo $h ?></th>
    <?php
            }
    ?>
                </tr>
            </thead>
            <tbody>
                <?php $this->printBodyTable($bodyTable) ?>
            </tbody>
        </table>
    <?php    
    }
    
    protected function printBodyTable($rows){
        foreach($rows as $colonne){
    ?>        
            <tr>
            <?php foreach($colonne as $colonna){ ?>        
                <td><?php echo $colonna ?> </td>
            <?php } ?>        
            </tr>
    <?php 
        }
    }
    
    /**
     * Restituisce un campo text in due modalità: non editabile ed editabile
     * @param type $formField
     * @param type $text
     * @param type $edit
     * @return type
     */
    protected function printTextField($formField, $text, $edit=false){
        
        $result = "";
        if($edit == true){
            //campo editabile 
           $result = '<input type="text" name="'.$formField.'" value="'.$text.'" />';
        }
        else{
            $result = $text;
        }
        
        return $result;
    }
    
    /**
     * Restituisce l'html per un piccolo form di aggiornamento di un campo
     * @param type $id
     * @param type $fieldUPDATE
     * @return string
     */
    protected function printUpdateFieldForm($id, $nameField, $fieldUPDATE){
        $html = "";
        $html.= '<form action="'.curPageURL().'" method="POST">';
        $html.= '<input type="hidden" name="id" value="'.$id.'" />';
        $html.= $fieldUPDATE;
        $html.= '<input type="submit" name="update-'.$nameField.'" class="btn btn-primary" value="AGGIORNA">';
        $html.= '</form>';
        
        return $html;
    }
    
    /**
     * Restituisce l'html per un piccolo form di cancellazione di un record dal database
     * @param type $id
     * @return string
     */
    protected function printDeleteForm($id, $nameField){
        $html = "";
        $html.= '<form action="'.curPageURL().'" method="POST">';
        $html.= '<input type="hidden" name="id" value="'.$id.'" />';        
        $html.= '<input type="submit" name="delete-'.$nameField.'" class="btn btn-danger" value="CANCELLA">';
        $html.= '</form>';
        
        return $html;
    }

    
    /**
     * La funzione controlla il valore di un campo obbligatorio e lo restituisce in caso di successo, false in caso di errore
     * @param type $nameField
     * @return boolean
     */
    protected function checkRequiredSingleField($nameField, $labelField){
       
        if(isset($_POST[$nameField]) && trim($_POST[$nameField]) != ''){
            return trim($_POST[$nameField]);
        }
        $this->printErrorBoxMessage('Campo '.$labelField.' mancante o non corretto.');
        return false;        
    }
    
    /**
     * La funzione controlla il valore di un campo e lo restitusce se questo è stato compilato
     * @param type $nameField
     * @return boolean
     */
    protected function checkSingleField($nameField){
        if(isset($_POST[$nameField]) && trim($_POST[$nameField]) != ''){
            return trim($_POST[$nameField]);
        }
        return false;
    }
    
    /**
     * La funzione controlla il file multimediale caricato e lo salva nella cartella uploads di wordpress
     * @param type $nameField
     * @return type
     */
    protected function checkUploadFileField($nameField){       
        $upload = null;
        if(isset($_FILES[$nameField])){
           if($_FILES[$nameField]['error'] == 0){
               //salvo il file su wp
               $upload = wp_upload_bits($_FILES[$nameField]["name"], null, file_get_contents($_FILES[$nameField]["tmp_name"]));
           }           
        }
        return $upload;
    }
    
    /**
     * La funzione controlla un multiple select field e restituisce i campi in un array
     * @param type $nameField
     * @return array
     */
    protected function checkMultipleSelectField($nameField){        
        if(isset($_POST[$nameField]) && count($_POST[$nameField]) > 0){
            $result = array();
            foreach($_POST[$nameField] as $item){
                array_push($result, $item);
            }
            return $result;
        }
        return false;
        
    }
    
    /**
     * La funzione controlla il valore di una data e lo restituisce se questo è stato compilato
     * @param type $nameField
     * @param type $labelField
     * @return boolean
     */
    protected function checkDateField($nameField, $labelField){
        if(isset($_POST[$nameField.'-d']) && isset($_POST[$nameField.'-m']) && isset($_POST[$nameField.'-y'])){
           //conversione in timestamp
            $d = "";
            if(intval($_POST[$nameField.'-d']) < 10){
                $d = '0'.$_POST[$nameField.'-d'];
            }
            else{
                 $d = ''.$_POST[$nameField.'-d'];
            }
            $m = $_POST[$nameField.'-m'];
            $y = $_POST[$nameField.'-y'];
            
            //$dtime = DateTime::createFromFormat("d/m/Y H:i", $d."/".$m."/".$y." 00:00");
            $timestamp = date($y.'-'.$m.'-'.$d);
            
            return $timestamp;
        }
        return false;
    }
    
    public function translateDate($date){       
        //la data si suddivide in nome del giorno, numero e mese        
        $temp = explode('-', $date);
        $giorno = $this->giorni[$temp[0]];
        $mese = $this->mesi[$temp[2]];
        
        return $giorno.' '.$temp[1].' '.$mese;
    }
    
    public function translateBirthDayDate($date){
        $temp = explode('-', $date);
        return $temp[2].' - '.$temp[1].' - '.$temp[0];
    }
    
    protected function printArraySuggestion($array){
        $array = array_unique($array);
        
        $count = 0;
        $html = "";
        foreach($array as $item){
            if($count < count($array) -1){
                $html.= '"'.$item.'",';
            }
            else{
                $html.= '"'.$item.'"';
            }
            $count++;
        }
        return $html;
    }
    
    
    public function getCountries(){
        return $this->countries;
    }
    
    protected function getProvince(){
        return $this->province;
    }
    
    /**
     * LA funzione pulisce i campi del form di ricerca
     */
    public function resetFieldsSearchBox(){
        if(isset($_POST['ricerca-reset'])){
            unset($_POST);
        }
    }
    
    protected function getOperatori(){
        return $this->operatore;
    }
    
    /**
     * La funzione restituisce il bottone dettaglio di un determinato elemento
     * @param type $url
     * @param type $id
     * @return type
     */
    protected function getDetailsButton($url, $id){   
        return '<a href="'.home_url().'/'.$url.'?ID='.$id.'" class="btn btn-info" role="button">Vedi dettagli</a>';    
    }
    
    protected function printTableResults($array){
        
    }
    

    protected function printNonAutorizzato(){
        return '<p class="error">Non sei autorizzato a vedere questa pagina</p>';
    }
        
}
