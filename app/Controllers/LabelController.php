<?php

namespace App\Controllers;

use Slim\View\Twig as View;
use App\Classes\AttributeGroup;
use App\Classes\Attribute;
use App\Classes\UserActivity;
use App\Classes\Product;
use App\Classes\Brand;
use App\Classes\Type;
use App\Classes\Order;
use DB;
use Respect\Validation\Validator as v;
use Carbon\Carbon as Carbon;
use App\Auth\Auth;

class LabelController extends Controller
{
    private $country_array = ['AU' => 'AUSTRALIA', 'NL' => 'Nederland', 'AL' => 'Albania', 'AD' => 'Andorra', 'AR' => 'Armenia', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BA' => 'Bosnia-Herzegovina', 'BG' => 'Bulgaria', 'HR' => 'Croatia', 'CY' => 'Cyprus', 'CZ' => 'Czech-Republic', 'DK' => 'Denmark', 'UK' => 'England', 'EE' => 'Estonia', 'FO' => 'Faroe-Islands', 'FI' => 'Finland', 'FR' => 'France', 'GE' => 'Georgia', 'DE' => 'Germany', 'GL' => 'Greenland', 'GR' => 'Greece', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IE' => 'Ireland', 'IT' => 'Italy', 'LV' => 'Latvia', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MK' => 'Macedonia', 'MT' => 'Malta', 'MD' => 'Moldovia', 'MC' => 'Monaco', 'NO' => 'Norway', 'PL' => 'Poland', 'PT' => 'Portugal', 'RO' => 'Romania', 'RU' => 'Russia', 'SM' => 'San-Marino', 'SC' => 'Scotland', 'SI' => 'Serbia-and-Montenegro', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'ES' => 'Spain', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'TR' => 'Turkey', 'UA' => 'Ukraine', 'GB' => 'United-Kingdom', 'VA' => 'Vatican-City', 'WL' => 'Wales', 'US' => 'United States of America', 'CL' => 'CHILE'];
    private $countryList = ['AF' => 'Afghanistan', 'AX' => 'Aland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua And Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BA' => 'Bosnia And Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'BN' => 'Brunei Darussalam', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos (Keeling) Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CG' => 'Congo', 'CD' => 'Congo, Democratic Republic', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'CI' => 'Cote D\'Ivoire', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands (Malvinas)', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island & Mcdonald Islands', 'VA' => 'Holy See (Vatican City State)', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran, Islamic Republic Of', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle Of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'KR' => 'Korea', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Lao People\'s Democratic Republic', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libyan Arab Jamahiriya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia, Federated States Of', 'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'AN' => 'Netherlands Antilles', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestinian Territory, Occupied', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russian Federation', 'RW' => 'Rwanda', 'BL' => 'Saint Barthelemy', 'SH' => 'Saint Helena', 'KN' => 'Saint Kitts And Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin', 'PM' => 'Saint Pierre And Miquelon', 'VC' => 'Saint Vincent And Grenadines', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome And Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia And Sandwich Isl.', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard And Jan Mayen', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syrian Arab Republic', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania', 'TH' => 'Thailand', 'TL' => 'Timor-Leste', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad And Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks And Caicos Islands', 'TV' => 'Tuvalu', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UM' => 'United States Outlying Islands', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VE' => 'Venezuela', 'VN' => 'Viet Nam', 'VG' => 'Virgin Islands, British', 'VI' => 'Virgin Islands, U.S.', 'WF' => 'Wallis And Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe', ];

    /**************************************************************************************************************************************************
     **********************************************************************(PrintPicklist)*************************************************************
     **************************************************************************************************************************************************/
    public function printPicklist($request, $response, $args)
    {
        $products = [];
        $orderitems = [];
        $sortedproducts = [];
        $pickUser = Auth::user_id();

        $orders = Order::AllWithoutOrderItems(10, $pickUser);
        Order::SetOrderData();
        foreach ($orders as $order) {
            $orderItems = DB::query('select id,product_id,count from order_item where order_id=%i', $order['id']);
            foreach ($orderItems as $orderitem) {
                if ($orderitem['product_id'] == 99999999) {
                    continue;
                }
                $products_id[] = $product = '';
                $location = '';
                $loc = '';
                $count = $orderitem['count'];
                $product = Order::$productsLocation[$orderitem['product_id']]['sku'];
                $location = Order::$productsLocation[$orderitem['product_id']]['location'];
                if ($location != ',,') {
                    $location = str_replace(',', '-', $location) . '|';
                }
                $itemAttributes = DB::query(
                    'SELECT  ' . Order::$table_attribute . ".name->>'$.nl' as title from " . Order::$table_item_attribute . '
                   left join ' . Order::$table_attribute . ' on ' . Order::$table_item_attribute . '.attribute_id = ' . Order::$table_attribute . '.id
                   where ' . Order::$table_item_attribute . '.order_item_id=%i',
                    $orderitem['id']
                );

                $attributes = '';

                foreach ($itemAttributes as $itemAttribute) {
                    $attributes .= $itemAttribute['title'];
                }
                $product = $location . $product . ' ' . $attributes;
                for ($i = 1; $i <= $count; $i++) {
                    array_push($products, $product);
                }
            }
        }
        $ret_array = [];
        foreach ($products as $value) {
            if (isset($ret_array[strtolower($value)])) {
                $ret_array[strtolower($value)]++;
            } else {
                $ret_array[strtolower($value)] = 1;
            }
        }
        $sortedproducts = $ret_array;
        ksort($sortedproducts);
        $returnarray = [];
        foreach ($sortedproducts as $item => $counted) {
            $item = explode('|', $item);
            $returnarray[$item[1]]['Count'] = $counted;
            $returnarray[$item[1]]['Location'] = $item[0];
        }

        $user = Auth::user();
        //    UserActivity::Record('Print Picklist ', '', 'Printpicklist ' + $returnarray);
        return $response->withJson(['return' => $returnarray, 'user_name' => ucfirst($user['name'])]);
    }

    /**************************************************************************************************************************************************
     *********************************************************************(Print Labels)***************************************************************
     **************************************************************************************************************************************************/
    public function printLabel($request, $response, $args)
    {
        $return = ['status' => 'Error', 'msg' => 'Order is niet gevonden !!'];
        if (($request->getParam('package') && $request->getParam('package') == 1) || $request->getParam('type') == 'box') {
            $return = $this->printBox($request->getParam('id'), $request->getParam('handtekening'), $request->getParam('package'), $request->getParam('type', ''));
        } elseif ($request->getParam('type') == 'post') {
            $return = $this->printPost($request->getParam('id'));
        }
        return $response->withJson($return);
    }

    /**************************************************************************************************************************************************
     *********************************************************************(Print Box)***************************************************************
     **************************************************************************************************************************************************/
    public function printBox($id = 0, $handtekening = 0, $package = 0, $type = 0)
    {
        if (!$id or $id == 0) {
            return ['status' => 'Error', 'msg' => 'Order Id is niet gevonden !!'];
        }
        $order = Order::GetSingle($id);
        $orderPayment = $order['payment'];
        $orderPaymentType = strtolower($orderPayment['type']);
        if (!$order) {
            return ['status' => 'Error', 'msg' => 'Order is niet gevonden !!'];
        }
        if ($order['status_id'] == 3) {
            return ['return' => 'Error', 'All ready sent'];
        } else {
            DB::query('delete from selektvracht where order_id =%i', $id);
        }
        $total = 0;
        $ptr = '';
        $TotalWeight = 0;
        $count = count($order['order_items']);
        foreach ($order['order_items'] as $order_item) {
            $total += $order_item['totalprice'];
            $TotalWeight += $order_item['product']['measurements']['weight'];
            // $TotalWeight += 1;
        }

        $name = '';

        if (isset($order['order_details']['address']['payment']['company'])) {
            $name .= $order['order_details']['address']['payment']['company'] . 'newline';
        } else {
            $name .= '' . 'newline';
        }
        if ($order['order_details']['address']['shipping']['firstname'] && $order['order_details']['address']['shipping']['firstname'] != '') {
            $name .= $order['order_details']['address']['shipping']['firstname'] . ' ' . $order['order_details']['address']['shipping']['lastname'] . 'newline';
        } else {
            if ($order['order_details']['address']['payment']['firstname'] && $order['order_details']['address']['payment']['firstname'] != '') {
                $name .= $order['order_details']['address']['payment']['firstname'] . ' ' . $order['order_details']['address']['payment']['lastname'] . 'newline';
            } else {
                $name .= $order['order_details']['address']['payment']['lastname'] . 'newline';
            }
        }
        if ($order['order_details']['address']['shipping']['city'] && $order['order_details']['address']['shipping']['city'] != '' && $order['order_details']['address']['shipping']['zipcode'] && $order['order_details']['address']['shipping']['zipcode'] != '') {
            $zip = strtoupper(str_replace(' ', '', $order['order_details']['address']['shipping']['zipcode']));
            $name .= $order['order_details']['address']['shipping']['street'] . ' ' . $order['order_details']['address']['shipping']['houseNumber'] . 'newline' . $zip . ' ' . $order['order_details']['address']['shipping']['city'] . 'newline';
            $CountryCode = $order['order_details']['address']['shipping']['countryCode'];
            $name .= $this->countryList[strtoupper($CountryCode)] . 'newline';
            $shippingCountry = strtoupper($CountryCode);
        } else {
            $zip = strtoupper(str_replace(' ', '', $order['order_details']['address']['payment']['zipcode']));
            $name .= $order['order_details']['address']['payment']['street'] . ' ' . $order['order_details']['address']['payment']['houseNumber'] . 'newline' . $zip . ' ' . $order['order_details']['address']['payment']['city'] . 'newline';
            $CountryCode = $order['order_details']['address']['payment']['countryCode'];
            $name .= $this->countryList[strtoupper($CountryCode)] . 'newline';
            $shippingCountry = strtoupper($CountryCode);
        }
        $orderValue = round($order['net_price'] * 100, 0);
        $addtoweight = 86;
        $shippingweight = $TotalWeight + $addtoweight;
        $countedProducts = $count;
        $barcode = '';
        $isPackage = $package;
        $now = date('U');
        $printer = $ptr;
        $order['labelPrinted'] = 1;
        $order['status_id'] = 15;
        DB::update('orders', ['status_id' => 15, 'labelPrinted' => 1], 'id=%i', $id);

        if ($shippingCountry == 'NL') {
            if (($orderValue > 4000 or $shippingweight > 250 or $isPackage == '1' or $type == 'box' or $handtekening == 1) && $orderPaymentType != 'payafter') {
                if (($isPackage == '1' && $orderValue < 4000 && $handtekening != 1) || ($handtekening != 1 && $type == 'box')) {
                    $verlader = '79099100';
                    $handT = '';
                } elseif ($orderValue > 4000 or $handtekening == 1) {
                    $verlader = '79099101';
                    $handT = 'HANDT';
                } elseif ($orderValue < 4000 && $shippingweight > 250 && $isPackage == '1') { // < 40 euro, zwaarder dan 250 gram, GEEN brievenbus zending
                    $verlader = '79099100';
                    $handT = '';
                } elseif ($orderValue < 4000 && $shippingweight > 250 && $isPackage == '0') { // < 40 euro, zwaarder dan 250 gram, brievenbus zending
                    $verlader = '39099104';
                    $handT = '';
                } else {
                    $handT = '';
                    $verlader = '39099104';
                }
                DB::insert('label_prints', ['order_id' => $order['id'], 'created_at' => Carbon::now()]);
                $id_last_label_prints = DB::insertId();
                DB::insert('selektvracht', ['order_id' => $order['id'], 'verlader' => $verlader, 'postcode' => $zip, 'created_at' => Carbon::now()]);
                $insertedId = DB::insertId();
                $barcode = '3SBDL1' . $insertedId;
                DB::update('selektvracht', ['barcode' => $barcode], 'id=%i', $insertedId);
                $order['tracktrace'] = $barcode;
                DB::update('orders', ['tracktrace' => $barcode], 'id=%i', $id);
            } elseif ($orderPaymentType == 'payafter') {
                $verlader = '79099103';
                $handtekening = 1;
                $handT = 'HANDT';
                DB::insert('selektvracht', ['order_id' => $order['id'], 'verlader' => $verlader, 'created_at' => Carbon::now()]);
                $insertedId = DB::insertId();
                $barcode = '3SBDL1' . $insertedId;
                DB::update('selektvracht', ['barcode' => $barcode, 'updated_at' => Carbon::now()], 'id=%i', $insertedId);
                DB::update('orders', ['tracktrace' => $barcode], 'id=%i', $id);
            } else {
                $barcode = $order['id'];
                return ['response' => 'Post'];
            }
        } else {
            $barcode = $order['id'];
            return ['response' => 'Post'];
        }
        $dt = Carbon::now();
        $dt = $dt->toDateString();
        $charCount = ((strlen($barcode)) * 11) + (2 * 13);
        $widthBarcode = $charCount * 4;
        $widthBarcode = round(((98 * 8) - $widthBarcode) / 2);
        $name = explode('newline', $name);
        $zpl = 'CT~~CD,~CC^~CT~
        ^XA~TA000~JSN^LT0^MNW^MTD^PON^PMN^LH0,0^JMA^PR4,4~SD15^JUS^LRN^CI0^XZ

        #Top123bestdeal logo
        ~DG000.GRF,02560,040,
        ,:::::::::::::::::O02E80H02E80I0KA80U02800AJA80V0280,I0H5I0J5H0J540H0L4V040H0L4W0H40,H01BB003BIBA0BJBH02AKA80T0A802ALAV0280,H0I5H015J505J5H01515I540T054015H515H5V0540,02EEF002E80AE8E802EC02ALAU0A802ALA80T0280,05I5H010H01504001540440J040T0H4H040J040U0H40,0BBFB0K03B80H01B802A0I02A02020P0A822A0J0A8002020O0280,054550K0150J0540540I015005I54005H5H015I540J05405J5J015140540,0E06F0K02E80I0EC02A0J0A82AJA80AHAH02AJAK0A82AJA80I0IA0A80,I0H5L0H5J01540440J0404L404H4H0L4K0H454K4J0I40440,I07B0K0BA0I0HB802A0I02A0AKA82AHAH02AJAK0A82AKAJ0IA8280,I0H5K0154005I5H0140I0151540H05410K0540140J05050I0150J0H14540,I06F0J06E800AEEC002ALA0A80H02A280J0A802A0J0A8A80H02A0K0A8280,I0H5K0H5H015H54004M4040J0I4K0H4H040J0I4K0404N40,I07B0I03BA001BIB822ALA0A80H02A2A020H0A802A0J0A8A80H02A0AKA8280,I0H5J0H5L0H5405M515K545J540540140J0545L515K54540,I06B0I0HEL01EE02ALA8ALA2AJA8A802A0J0A8ALA2AKA8A80,I0H5I01540L0H50440J0404L404J40440040J0N45440I0J40,I07B0H03B80L03B02A0J0A8ALA0AJA8A802A0J0A8ALA280I028280,I0H5I0540M0H50140J0515J5140I0154540140J050515I54140I054540,I06F0H0EC0M06A02A0J0A8A80O028A802A0J0A8A80J0280I028280,I0H5H0150N0H50440J04040P0J4H040J04040K0H4J0J40,I07B023B0N0BA02A0J0A8A80O028A802A0J0A8A80J02A0I0A8280,I0H5H0H5J0140015405H5151550550N01545401551515505510I0H5010154540,06AEHEA6EJE8AC06EC02ALA8AIAI02AJA8A802ALA82AHAI02AKA8A80,05J545K515J5H0M4504I4I0L4044004K45004H4J0L40440,03BIBA3BJB9BIBA002ALA02AHAI02AJA0A802ALAH0IAI01AJAB0280,05J545K505I5I015J510H0H5J0J514050015J510H01550I015I5H05,U02E8,,::::::::::::::::^XA

        #logo Place
        ^FT478,96^XG000.GRF,1,1^FS

        #darknes
        ^MD30

        #print speed
        ^PR1,2,2

        #Font
        ^CWZ,E:ARI000.TTF
        ^CWB,E:ARI001.TTF
        ^MMT
        ^PW783
        ^LL1279
        ^LS0

        #from
        ^FT41,152^AZN,25,24^FH\^FDafzender^FS
        ^FT215,152^AZN,27,24^FH\^FD123BestDeal BV^FS
        ^FT582,152^AZN,27,24^FH\^FDOrder: ' . $order['id'] . ' ^FS

        #line
        ^FO35,177^GB743,0,1^FS

        #returns
        ^FT41,215^AZN,25,24^FH\^FDretour^FS
        ^FT215,215^ABN,25,24^FH\^FD123BestDeal^FS
        ^FT215,245^AZN,25,24^FH\^FDMolenstraat 24 - 7491 BG Delden^FS
        ^FT215,275^AZN,25,24^FH\^FDsupport@123bestdeal.nl^FS
        ^FT660,316^BQN,2,4
        ^FDMA,123bestdeal.nl - ' . $order['id'] . '^FS

        #line
        ^FO35,300^GB743,0,2^FS

        #to
        ^FT41,338^AZN,25,24^FH\^FDaan^FS
        ^FT210,342^ABN,34,29^FH\^FDONTVANGER^FS
        ^FT215,392^AZN,28,29,TT0003M_^FH\^CI17^F8^FD' . $name[0] . '^FS^CI0
        ^FT215,427^AZN,28,29,TT0003M_^FH\^CI17^F8^FD' . $name[1] . '^FS^CI0
        ^FT215,462^AZN,28,29,TT0003M_^FH\^CI17^F8^FD' . $name[2] . '^FS^CI0
        ^FT215,497^AZN,28,29,TT0003M_^FH\^CI17^F8^FD' . $name[3] . '^FS^CI0
        ^FT215,532^AZN,28,29,TT0003M_^FH\^CI17^F8^FD' . $name[4] . '^FS^CI0

        #line
        ^FO35,557^GB743,0,1^FS

        #package_contents
        ^FT41,595^AZN,25,24^FH\^FDinhoud^FS
        ^FT215,595^AZN,25,24^FH\^FDGewicht (gr):^FS
        ^FT420,595^AZN,25,24^FH\^FD' . $TotalWeight . '^FS
        ^FT215,635^AZN,25,24^FH\^FDAantal artikelen:^FS
        ^FT420,635^AZN,25,24^FH\^FD' . $count . '^FS
        ^FT215,675^AZN,25,24^FH\^FDVerzenddatum:^FS
        ^FT420,675^AZN,28,28^FH\^FD' . $dt . '^FS

        #line
        ^FO35,700^GB743,0,1^FS

        #optional space for not at neighbors, evening-delivery, and so on
        #third space
        ^FT520,775^AZN,28,28^FH\^FD' . $handT . '^FS

        #second space
        #^FT260,775^AZN,28,28^FH\^FD^FS

        #first space
        #^FT41,775^AZN,28,28^FH\^FD^FS

        #vertical line
        ^FO215,710^GB0,116,3^FS

        #vertical line
        ^FO443,710^GB0,116,3^FS

        #line
        ^FO35,836^GB743,0,1^FS

        #barcode
        ^BY4,2,240
        ^FT' . $widthBarcode . ',1150
        ^BCN,,Y,N
        ^FD>:' . $barcode . '^FS


        ^PQ1,0,1,Y^XZ
        ^XA^ID000.GRF^FS^XZ';
        UserActivity::Record('DHL Label Printed Scanned: ' . $barcode, $order['id'], 'Orders');
        $zpl = str_replace("\r", '', $zpl);
        $zpl = str_replace("\n", '', $zpl);
        if (Auth::user_id() == 39) {
            $ip = ZEBRA_IP_1;
        } else {
            $ip = ZEBRA_IP_2;
        }
        return ['response' => 'True', 'name' => $name, 'msg' => 'We hebben de database bijgewerkt.', 'zpl' => $zpl, 'IP' => $ip];
    }

    /**************************************************************************************************************************************************
     *********************************************************************(Print Labels)***************************************************************
     **************************************************************************************************************************************************/
    public function printPost($id)
    {
        if (!$id or $id == 0) {
            return ['status' => 'Error', 'msg' => 'Order Id is niet gevonden !!'];
        }
        $order = Order::GetSingle($id);
        $orderPayment = $order['payment'];
        $orderPaymentType = strtolower($orderPayment['type']);
        if (!$order) {
            return ['status' => 'Error', 'msg' => 'Order is niet gevonden !!'];
        }
        if ($order['status_id'] == 3) {
            return ['return' => 'Error', 'All ready sent'];
        } else {
            DB::query('delete from selektvracht where order_id =%i', $id);
        }
        $total = 0;
        $ptr = '';
        $TotalWeight = 0;
        $count = count($order['order_items']);
        foreach ($order['order_items'] as $order_item) {
            $total += $order_item['totalprice'];
            $TotalWeight += $order_item['product']['measurements']['weight'];
            //$TotalWeight += 1;
        }
        $name = '';
        $order['order_details'] = $order['order_details'];
        if (isset($order['order_details']['address']['payment']['company']) && $order['order_details']['address']['payment']['company'] != '') {
            $name .= $order['order_details']['address']['payment']['company'] . "\n";
        }
        if ($order['order_details']['address']['shipping']['firstname'] && $order['order_details']['address']['shipping']['firstname'] != '') {
            $name .= $order['order_details']['address']['shipping']['firstname'] . ' ' . $order['order_details']['address']['shipping']['lastname'] . "\n";
        } else {
            if ($order['order_details']['address']['payment']['firstname'] && $order['order_details']['address']['payment']['firstname'] != '') {
                $name .= $order['order_details']['address']['payment']['firstname'] . ' ' . $order['order_details']['address']['payment']['lastname'] . "\n";
            } else {
                $name .= $order['order_details']['address']['payment']['lastname'] . "\n";
            }
        }
        if ($order['order_details']['address']['shipping']['city'] && $order['order_details']['address']['shipping']['city'] != '' && $order['order_details']['address']['shipping']['zipcode'] && $order['order_details']['address']['shipping']['zipcode'] != '') {
            $zip = strtoupper(str_replace(' ', '', $order['order_details']['address']['shipping']['zipcode']));
            $name .= $order['order_details']['address']['shipping']['street'] . ' ' . $order['order_details']['address']['shipping']['houseNumber'] . "\n" . $zip . ' ' . $order['order_details']['address']['shipping']['city'] . "\n";
            $CountryCode = $order['order_details']['address']['shipping']['countryCode'];
            $name .= $this->countryList[strtoupper($CountryCode)] . "\n";
            $shippingCountry = strtoupper($CountryCode);
        } else {
            $zip = strtoupper(str_replace(' ', '', $order['order_details']['address']['payment']['zipcode']));
            $name .= $order['order_details']['address']['payment']['street'] . ' ' . $order['order_details']['address']['payment']['houseNumber'] . "\n" . $zip . ' ' . $order['order_details']['address']['payment']['city'] . "\n";
            $CountryCode = $order['order_details']['address']['payment']['countryCode'];
            $name .= $this->countryList[strtoupper($CountryCode)] . "\n";
            $shippingCountry = strtoupper($CountryCode);
        }
        $orderValue = round($order['net_price'] * 100, 0);
        $addtoweight = 30;
        $shippingweight = $TotalWeight + $addtoweight;
        $countedProducts = $count;
        $barcode = '';
        $now = Carbon::now();
        $now = $now->toDateString();
        $printer = $ptr;
        $order['labelPrinted'] = 1;
        $order['status_id'] = 15;
        DB::update('orders', ['status_id' => 15, 'labelPrinted' => 1], 'id=%i', $id);
        if ((($orderValue > 4000 or $shippingweight > 250) && $shippingCountry == 'NL') || $orderPaymentType == 'payafter') {
            return ['response' => 'Box'];
        } else {
            $barcode = $order['id'];
        }
        $date = date('d-m-Y');
        $ptr = '450';
        //return response()->json(array('barcode' => $barcode, 'name' => $name, 'response' => 'Done', 'order_id' => $order->id, 'total' => $total, 'TotalWeight' => $TotalWeight, 'count' => $count, 'date' => $date, 'printer' => $ptr));
        return ['barcode' => $barcode, 'name' => $name, 'msg' => 'We hebben de database bijgewerkt.', 'response' => 'True', 'order_id' => $order['id'], 'total' => $total, 'TotalWeight' => $TotalWeight, 'count' => $count, 'date' => $date, 'printer' => $ptr];
    }

    /**************************************************************************************************************************************************
     *********************************************************************(Print parcel)***************************************************************
     **************************************************************************************************************************************************/
    public function printLabelParcel($request, $response, $args)
    {
        $order = $request->getParam('id');
        $URL = 'http://parcel.123bestdeal.nl/orders/create-shipment-new?order_id=' . $order . '&print=true';
        $Auth_Username = 'saemer';
        $Auth_Password = 'justfeed17';

        $data = ['order_id' => $order, 'print' => true];
        //make curl request
        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "{$Auth_Username}:{$Auth_Password}");
        $Output = curl_exec($ch);

        //get http code
        $HTTP_Code = curl_getinfo($ch, CURLINFO_HTTP_CODE);    //this is important to get the http code.

        curl_close($ch);
        $return = ['status' => 'True', 'msg' => 'Order is gevonden !!'];
        return $response->withJson($return);
    }
}
