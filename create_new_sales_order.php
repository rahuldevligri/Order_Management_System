<?php
include('dbconnect.php');
include('navbar.php');

// Fetch data and store in arrays
$buyers = $conn->query("SELECT buyer_id, buyer_name FROM buyer");
$agents = $conn->query("SELECT agent_id, agent_name FROM agent");
$fibre_types = $conn->query("SELECT fibre_id, fibre_name FROM fibretype");
$selvedge_types = $conn->query("SELECT selvedge_id, selvedge_name FROM selvedgetype");

$buyers_data = $buyers->fetch_all(MYSQLI_ASSOC);
$agents_data = $agents->fetch_all(MYSQLI_ASSOC);
$fibre_types_data = $fibre_types->fetch_all(MYSQLI_ASSOC);
$selvedge_types_data = $selvedge_types->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Sales Order</title>
    <link rel="stylesheet" href="css/create_new_sales_order.css">
    <script>
        function toggleFormSections() {
            const orderType = document.querySelector('input[name="order_type"]:checked').value;
            const ownSalesSection = document.getElementById('ownsales-section');
            const jobWorkSection = document.getElementById('jobwork-section');
            const fabricConstructionSection = document.getElementById('fabric_construction_section');

            if (orderType === 'Ownsales') {
                ownSalesSection.style.display = 'block';
                jobWorkSection.style.display = 'none';
            } else {
                ownSalesSection.style.display = 'none';
                jobWorkSection.style.display = 'block';
            }
        }
        function toggleFabricConstructionSection() {
            const jobWorkType = document.querySelector('input[name="jobwork_type"]:checked').value;
            const fabricConstructionSection = document.getElementById('fabric_construction_section');

            if (jobWorkType === 'Sizing + Weaving' || jobWorkType === 'Weaving') {
                fabricConstructionSection.style.display = 'block';
            } else {
                fabricConstructionSection.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <main>
        <section class="create-sales-order-form">
            <h2>Create New Sales Order</h2>
            <form action="submit_sales_order.php" method="POST">
                <label for="order_type">Order Type:</label><br>
                <input type="radio" name="order_type" value="Ownsales" onclick="toggleFormSections()" required> Own Sales
                <input type="radio" name="order_type" value="Jobwork" onclick="toggleFormSections()" required> Job Work<br><br>

                <!-- Own Sales Section -->
                <div id="ownsales-section" style="display:none;">
                    <label for="order_qty">Order Qty (Meters):</label>
                    <input type="number" name="order_qty" required><br>

                    <label for="price">Price / Meter:</label>
                    <input type="number" name="price" step="0.01" required><br>

                    <label for="currency_type">Currency Type:</label>
                    <select name="currency_type" required>
                        <option value="INR">INR</option>
                        <option value="USD">USD</option>
                        <option value="Pound">Pound</option>
                    </select><br>

                    <label for="payment_terms">Payment Terms:</label>
                    <input type="text" name="payment_terms" required><br>

                    <label for="buyer_id">Buyer Name:</label>
                    <select name="buyer_id" required>
                        <?php if (!empty($buyers_data)): ?>
                            <?php foreach ($buyers_data as $row): ?>
                                <option value="<?= $row['buyer_id']; ?>"><?= $row['buyer_name']; ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option>No buyers found.</option>
                        <?php endif; ?>
                    </select><br>

                    <label for="date_of_confirmation">Date of Confirmation:</label>
                    <input type="date" name="date_of_confirmation" required><br>

                    <label for="agent_id">Agent Name:</label>
                    <select name="agent_id" required>
                        <?php if (!empty($agents_data)): ?>
                            <?php foreach ($agents_data as $row): ?>
                                <option value="<?= $row['agent_id']; ?>"><?= $row['agent_name']; ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option>No agents found.</option>
                        <?php endif; ?>
                    </select><br>

                    <label for="fabric_construction">Fabric Construction:</label><br>
                    <label for="epi">EPI:</label>
                    <input type="number" name="epi"><br>

                    <label for="ppi">PPI:</label>
                    <input type="number" name="ppi"><br>

                    <label for="ply">Ply:</label>
                    <input type="number" name="ply"><br>

                    <label for="width">Width (Inches):</label>
                    <input type="number" name="width"><br>

                    <label for="weave_type">Weave Type:</label>
                    <input type="text" name="weave_type"><br>

                    <label for="fibre_id">Fibre Type:</label>
                    <select name="fibre_id" required>
                        <?php if (!empty($fibre_types_data)): ?>
                            <?php foreach ($fibre_types_data as $row): ?>
                                <option value="<?= $row['fibre_id']; ?>"><?= $row['fibre_name']; ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option>No types found.</option>
                        <?php endif; ?>
                    </select><br>

                    <label for="selvedge_id">Selvedge Type:</label>
                    <select name="selvedge_id" required>
                        <?php if (!empty($selvedge_types_data)): ?>
                            <?php foreach ($selvedge_types_data as $row): ?>
                                <option value="<?= $row['selvedge_id']; ?>"><?= $row['selvedge_name']; ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option>No selvedge types found.</option>
                        <?php endif; ?>
                    </select><br>

                    <label for="selvedge_width">Selvedge Width (Inches):</label>
                    <input type="number" name="selvedge_width" step="0.01"><br>

                    <label for="selvedge_weave">Selvedge Weave:</label>
                    <input type="text" name="selvedge_weave"><br>

                    <label for="inspection_type">Inspection Type:</label>
                    <select name="inspection_type" required>
                        <option value="Third Party">Third Party</option>
                        <option value="Own Inspection">Own Inspection</option>
                    </select><br>

                    <label for="inspection_standard">Inspection Standard:</label>
                    <input type="text" name="inspection_standard" value="American 4-Point System" readonly><br>

                    <label for="piece_length">Piece Length:</label>
                    <input type="text" name="piece_length"><br>

                    <label for="packing_type">Packing Type:</label>
                    <select name="packing_type" required>
                        <option value="Bale">Bale</option>
                        <option value="Roll">Roll</option>
                    </select><br>

                    <label for="freight">Freight:</label>
                    <input type="text" name="freight"><br>

                    <label for="invoice_address">Invoice Address:</label>
                    <textarea name="invoice_address" required></textarea><br>

                    <label for="delivery_address">Delivery Address:</label>
                    <textarea name="delivery_address" required></textarea><br>

                    <label for="commission">Commission:</label>
                    <input type="text" name="commission"><br>
                </div>

                <!-- Job Work Section -->
                <div id="jobwork-section" style="display:none;">
                    <label for="order_quantity">Order Quantity (Meters):</label>
                    <input type="number" name="order_quantity" required><br>

                    <label for="jobwork_type">Job Work Type:</label><br>
                    <input type="radio" name="jobwork_type" value="Sizing" required> Sizing
                    <input type="radio" name="jobwork_type" value="Weaving" required> Weaving
                    <input type="radio" name="jobwork_type" value="Sizing + Weaving" required> Sizing + Weaving<br><br>

                    <label for="price">Price / Pick Rate:</label>
                    <input type="number" name="price" step="0.01" required><br>

                    <label for="currency_type">Currency Type:</label>
                    <select name="currency_type" required>
                        <option value="INR">INR</option>
                        <option value="USD">USD</option>
                        <option value="Pound">Pound</option>
                    </select><br>

                    <label for="payment_terms">Payment Terms:</label>
                    <input type="text" name="payment_terms" required><br>

                    <label for="buyer_id">Buyer Name:</label>
                    <select name="buyer_id" required>
                        <?php if (!empty($buyers_data)): ?>
                            <?php foreach ($buyers_data as $row): ?>
                                <option value="<?= $row['buyer_id']; ?>"><?= $row['buyer_name']; ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option>No buyers found.</option>
                        <?php endif; ?>
                    </select><br>

                    <label for="date_of_confirmation">Date of Confirmation:</label>
                    <input type="date" name="date_of_confirmation" required><br>

                    <label for="agent_id">Agent Name:</label>
                    <select name="agent_id" required>
                        <?php if (!empty($agents_data)): ?>
                            <?php foreach ($agents_data as $row): ?>
                                <option value="<?= $row['agent_id']; ?>"><?= $row['agent_name']; ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option>No agents found.</option>
                        <?php endif; ?>
                    </select><br>
                    <div id="fabric_construction_section">
                        <label for="fabric_construction">Fabric Construction:</label><br>
                        <label for="epi">EPI:</label>
                        <input type="number" name="epi"><br>

                        <label for="ppi">PPI:</label>
                        <input type="number" name="ppi"><br>

                        <label for="ply">Ply:</label>
                        <input type="number" name="ply"><br>

                        <label for="width">Width (Inches):</label>
                        <input type="number" name="width"><br>

                        <label for="weave_type">Weave Type:</label>
                        <input type="text" name="weave_type"><br>

                        <label for="fibre_id">Fibre Type:</label>
                        <select name="fibre_id" required>
                            <?php if (!empty($fibre_types_data)): ?>
                                <?php foreach ($fibre_types_data as $row): ?>
                                    <option value="<?= $row['fibre_id']; ?>"><?= $row['fibre_name']; ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option>No types found.</option>
                            <?php endif; ?>
                        </select><br>
                        <label for="selvedge_id">Selvedge Type:</label>
                        <select name="selvedge_id" required>
                            <?php if (!empty($selvedge_types_data)): ?>
                                <?php foreach ($selvedge_types_data as $row): ?>
                                    <option value="<?= $row['selvedge_id']; ?>"><?= $row['selvedge_name']; ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option>No selvedge types found.</option>
                            <?php endif; ?>
                        </select><br>
                        <label for="selvedge_width">Selvedge Width (Inches):</label>
                        <input type="number" name="selvedge_width" step="0.01"><br>
                        <label for="selvedge_weave">Selvedge Weave:</label>
                        <input type="text" name="selvedge_weave"><br>
                    </div>
                </div>
                <button type="submit">Submit Sales Order</button>
            </form>
        </section>
    </main>
</body>
</html>
