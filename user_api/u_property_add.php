<?php
/**
 * user_api/u_property_add.php
 *
 * POST /user_api/u_property_add.php
 *
 * Create a new property listing.
 * Returns the newly created prop_id in the response body so the caller
 * can immediately proceed to upload a reel via /user_api/reels/create.php.
 *
 * Auth: Bearer token (preferred) or legacy uid body param (transition).
 */
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/auth.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    errorResponse('Method Not Allowed. Expected POST.', 405);
}

$data = json_decode(file_get_contents('php://input'), true) ?? [];

// Auth — prefer Bearer token, fall back to body uid
$auth_uid = requireAuth($data['uid'] ?? null);

$status = $data['status'] ?? '';
$title = $rstate->real_escape_string($data['title'] ?? '');
$address = $rstate->real_escape_string($data['address'] ?? '');
$description = $rstate->real_escape_string($data['description'] ?? '');
$ccount = $rstate->real_escape_string($data['ccount'] ?? '');
$facility = $data['facility'] ?? '';
$ptype = $data['ptype'] ?? '';
$beds = $data['beds'] ?? '';
$bathroom = $data['bathroom'] ?? '';
$sqft = $data['sqft'] ?? '';
$rate = $data['rate'] ?? '';
$latitude = $data['latitude'] ?? '';
$longtitude = $data['longtitude'] ?? '';
$mobile = $data['mobile'] ?? '';
$listing_date = date('Y-m-d H:i:s');
$price = $data['price'] ?? '';
$plimit = $data['plimit'] ?? '';
$country_id = $data['country_id'] ?? '';
$pbuysell = $data['pbuysell'] ?? '';
$party_allowed = !empty($data['party_allowed']) ? 1 : 0;
$party_cost = $data['party_cost'] ?? '';
$caution_fee = $data['caution_fee'] ?? '';

// Validate required fields
$requiredFields = compact(
    'status', 'title', 'address', 'description', 'ccount',
    'facility', 'ptype', 'beds', 'bathroom', 'sqft', 'rate',
    'latitude', 'mobile', 'price', 'plimit', 'country_id', 'pbuysell'
);

foreach ($requiredFields as $key => $val) {
    if ((string)$val === '') {
        errorResponse("Missing required field: {$key}.", 400, 'PROPERTY_MISSING_FIELDS');
    }
}

if ($party_allowed === 1 && empty($party_cost)) {
    errorResponse('party_cost is required when party_allowed is true.', 400, 'PROPERTY_MISSING_FIELDS');
}

// Save property image (base64 encoded)
$img = $data['img'] ?? '';
if (empty($img)) {
    errorResponse('Property image is required.', 400, 'PROPERTY_MISSING_FIELDS');
}

$img  = str_replace('data:image/png;base64,', '', $img);
$img  = str_replace(' ', '+', $img);
$imgData = base64_decode($img);
$path = 'images/property/' . uniqid() . '.png';
$fname = dirname(dirname(__FILE__)) . '/' . $path;
file_put_contents($fname, $imgData);

$field_values = [
    'image', 'status', 'title', 'price', 'address', 'facility',
    'description', 'beds', 'bathroom', 'sqrft', 'rate', 'ptype',
    'latitude', 'longtitude', 'mobile', 'city', 'listing_date',
    'add_user_id', 'pbuysell', 'country_id', 'plimit',
    'party_allowed', 'party_cost', 'caution_fee'
];

$data_values = [
    $path, $status, $title, $price, $address, $facility,
    $description, $beds, $bathroom, $sqft, $rate, $ptype,
    $latitude, $longtitude, $mobile, $ccount, $listing_date,
    $auth_uid, $pbuysell, $country_id, $plimit,
    $party_allowed, $party_cost, $caution_fee
];

$h     = new Estate();
$check = $h->restateinsertdata_Api($field_values, $data_values, 'tbl_property');

if (! $check) {
    errorResponse('Failed to create property. Please try again.', 500, 'PROPERTY_CREATE_FAILED');
}

$new_prop_id = (int)$rstate->insert_id;

successResponse('Property created successfully.', [
    'prop_id' => $new_prop_id,
]);