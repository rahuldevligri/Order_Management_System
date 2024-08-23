<?php
include('dbconnect.php');
include('navbar.php'); 

// Check if user_role is set; if not, set a default role or handle it appropriately
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'guest';

function canEdit($role, $confirmed) {
    // Directors can always edit, Marketing Team can only edit before the order is approved
    if ($role === 'Director') {
        return true;
    } elseif ($role === 'Marketing' && $confirmed !== 'Approved') {
        return true;
    }
    return false;
}

function canDelete($role) {
    // Only Directors can delete
    return $role === 'Director';
}

// Build SQL query with optional filters
$filters = [];
$sql = "SELECT 
            so.sales_order_no,
            b.buyer_name,
            so.date_of_confirmation,
            so.order_details,
            so.order_quantity,
            so.order_type,
            so.price,
            so.currency_type,
            so.confirmed,
            so.order_status
        FROM SalesOrder so
        LEFT JOIN Buyer b ON so.buyer_id = b.buyer_id
        WHERE 1=1";

// Apply filters if set
if (isset($_GET['buyer_name']) && !empty($_GET['buyer_name'])) {
    $buyer_name = $conn->real_escape_string($_GET['buyer_name']);
    $filters[] = "b.buyer_name LIKE '%$buyer_name%'";
}
if (isset($_GET['order_type']) && !empty($_GET['order_type'])) {
    $order_type = $conn->real_escape_string($_GET['order_type']);
    $filters[] = "so.order_type = '$order_type'";
}
if (isset($_GET['confirmed_status']) && !empty($_GET['confirmed_status'])) {
    $confirmed_status = $conn->real_escape_string($_GET['confirmed_status']);
    $filters[] = "so.confirmed = '$confirmed_status'";
}
if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
    $order_status = $conn->real_escape_string($_GET['order_status']);
    $filters[] = "so.order_status = '$order_status'";
}

// Append filters to the SQL query
if (count($filters) > 0) {
    $sql .= " AND " . implode(" AND ", $filters);
}

// Execute the query
$result = $conn->query($sql);

if (!$result) {
    die("Error executing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Order Management</title>
    <link rel="stylesheet" href="css/sales_order.css">
</head>
<body>
    <main>
        <?php if ($user_role === 'Director' || $user_role === 'Marketing'): ?>
            <a href="create_new_sales_order.php" class="create-new-order">Create New Sales Order</a>
        <?php endif; ?>
        <section class="sales-order-summary">
            <h2>Sales Order Summary</h2>
            <form method="GET" action="">
                <!-- Add filters for buyer name, order type, confirmed status, and order status -->
                <input type="text" name="buyer_name" placeholder="Filter by Buyer Name">
                <select name="order_type">
                    <option value="">Filter by Order Type</option>
                    <option value="Ownsales">Ownsales</option>
                    <option value="Jobwork">Jobwork</option>
                </select>
                <select name="confirmed_status">
                    <option value="">Filter by Confirmation Status</option>
                    <option value="Approved">Approved</option>
                    <option value="Pending">Pending</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
                <select name="order_status">
                    <option value="">Filter by Order Status</option>
                    <option value="Ongoing">Ongoing</option>
                    <option value="Completed">Completed</option>
                </select>
                <button type="submit">Apply Filters</button>
            </form>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Sales Order No</th>
                            <th>Buyer Name</th>
                            <th>Date of Confirmation</th>
                            <th>Order Details</th>
                            <th>Order Qty</th>
                            <th>Order Type</th>
                            <th>Price</th>
                            <th>Action</th>
                            <th>Confirmed</th>
                            <th>Order Status</th>
                            <th>Work Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['sales_order_no']); ?></td>
                                    <td><?php echo htmlspecialchars($row['buyer_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['date_of_confirmation']); ?></td>
                                    <td><?php echo htmlspecialchars($row['order_details'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($row['order_quantity']); ?></td>
                                    <td><?php echo htmlspecialchars($row['order_type']); ?></td>
                                    <td><?php echo htmlspecialchars($row['price'] . ' ' . $row['currency_type']); ?></td>
                                    <td>
                                        <a href="view_order.php?order_no=<?php echo htmlspecialchars($row['sales_order_no']); ?>">View</a>
                                        <?php if (canEdit($user_role, $row['confirmed'])): ?>
                                            <a href="edit_order.php?order_no=<?php echo htmlspecialchars($row['sales_order_no']); ?>">Edit</a>
                                        <?php endif; ?>
                                        <?php if (canDelete($user_role)): ?>
                                            <a href="delete_order.php?order_no=<?php echo htmlspecialchars($row['sales_order_no']); ?>" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['confirmed']); ?></td>
                                    <td><?php echo htmlspecialchars($row['order_status']); ?></td>
                                    <td>
                                        <a href="work_order.php?order_no=<?php echo htmlspecialchars($row['sales_order_no']); ?>">View Work Order</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11">No sales orders found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>

<?php
$conn->close(); // Close database connection
?>
