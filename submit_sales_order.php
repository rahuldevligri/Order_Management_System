<?php
include('dbconnect.php');
// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Function to escape input data
function escape_input($data, $conn) {
    return htmlspecialchars($conn->real_escape_string($data));
}

// Retrieve and escape POST data
$sales_order_no = escape_input($_POST['sales_order_no'], $conn);
$order_type = escape_input($_POST['order_type'], $conn);
$price = escape_input($_POST['price'], $conn);
$currency_type = escape_input($_POST['currency_type'], $conn);
$payment_terms = escape_input($_POST['payment_terms'], $conn);
$buyer_id = escape_input($_POST['buyer_id'], $conn);
$date_of_confirmation = escape_input($_POST['date_of_confirmation'], $conn);
$agent_id = escape_input($_POST['agent_id'], $conn);
$order_details = escape_input($_POST['order_details'], $conn);
$fibre_id = escape_input($_POST['fibre_id'], $conn);
$type_of_selvedge = escape_input($_POST['type_of_selvedge'], $conn);
$selvedge_id = escape_input($_POST['selvedge_id'], $conn);
$selvedge_width = escape_input($_POST['selvedge_width'], $conn);
$selvedge_weave = escape_input($_POST['selvedge_weave'], $conn);
$inspection_type = escape_input($_POST['inspection_type'], $conn);
$inspection_standard = escape_input($_POST['inspection_standard'], $conn);
$piece_length = escape_input($_POST['piece_length'], $conn);
$packing_type = escape_input($_POST['packing_type'], $conn);
$freight = escape_input($_POST['freight'], $conn);
$invoice_address = escape_input($_POST['invoice_address'], $conn);
$delivery_address = escape_input($_POST['delivery_address'], $conn);
$commission = escape_input($_POST['commission'], $conn);
$action = escape_input($_POST['action'], $conn);
$confirmed = escape_input($_POST['confirmed'], $conn);
$edit = escape_input($_POST['edit'], $conn);
$order_quantity = escape_input($_POST['order_qty'], $conn); // Make sure to match with form input
$order_status = escape_input($_POST['order_status'], $conn);

// Prepare SQL statement
$sql = "INSERT INTO salesorder (
    sales_order_no, order_type, price, currency_type, payment_terms,
    buyer_id, date_of_confirmation, agent_id, order_details, fibre_id,
    type_of_selvedge, selvedge_id, selvedge_width, selvedge_weave, inspection_type,
    inspection_standard, piece_length, packing_type, freight, invoice_address,
    delivery_address, commission, action, confirmed, edit, order_quantity
) VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
)";

// Initialize prepared statement
$stmt = $conn->prepare($sql);

// Check if prepare() failed
if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}

// Bind parameters
$stmt->bind_param(
    "sssssisisiiisssssssssssi", // Adjust type specifiers based on data
    $sales_order_no, $order_type, $price, $currency_type, $payment_terms,
    $buyer_id, $date_of_confirmation, $agent_id, $order_details, $fibre_id,
    $type_of_selvedge, $selvedge_id, $selvedge_width, $selvedge_weave, $inspection_type,
    $inspection_standard, $piece_length, $packing_type, $freight, $invoice_address,
    $delivery_address, $commission, $action, $confirmed, $edit, $order_quantity
);

// Execute the statement
if ($stmt->execute()) {
    echo "Data saved successfully.";
} else {
    echo "Error: " . htmlspecialchars($stmt->error);
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
