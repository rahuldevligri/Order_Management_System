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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .hidden {
            display: none;
        }
    </style>
    <script>
       function toggleFormSections() {
    const orderTypeElement = document.querySelector('input[name="order_type"]:checked');
    if (!orderTypeElement) {
        console.error('No order type selected');
        return;
    }

    const orderType = orderTypeElement.value;
    const ownSalesSection = document.getElementById('ownsales-section');
    const jobWorkSection = document.getElementById('jobwork-section');
    
    // Toggle visibility
    if (orderType === 'Ownsales') {
        ownSalesSection.style.display = 'block';
        jobWorkSection.style.display = 'none';
        // Remove required from Jobwork fields
        toggleRequiredFields(jobWorkSection, false);
        // Add required to Ownsales fields
        toggleRequiredFields(ownSalesSection, true);
    } else {
        ownSalesSection.style.display = 'none';
        jobWorkSection.style.display = 'block';
        // Remove required from Ownsales fields
        toggleRequiredFields(ownSalesSection, false);
        // Add required to Jobwork fields
        toggleRequiredFields(jobWorkSection, true);
        toggleFabricConstructionSection();  // Trigger visibility for jobwork
    }
}

function toggleFormSections() {
    const orderTypeElement = document.querySelector('input[name="order_type"]:checked');
    if (!orderTypeElement) {
        console.error('No order type selected');
        return;
    }

    const orderType = orderTypeElement.value;
    const ownSalesSection = document.getElementById('ownsales-section');
    const jobWorkSection = document.getElementById('jobwork-section');
    
    // Toggle visibility between own sales and job work
    if (orderType === 'Ownsales') {
        ownSalesSection.style.display = 'block';
        jobWorkSection.style.display = 'none';
        toggleRequiredFields(jobWorkSection, false);  // Remove required from job work section
        toggleRequiredFields(ownSalesSection, true);  // Add required to own sales section
    } else {
        ownSalesSection.style.display = 'none';
        jobWorkSection.style.display = 'block';
        toggleRequiredFields(ownSalesSection, false);  // Remove required from own sales section
        toggleRequiredFields(jobWorkSection, true);  // Add required to job work section
        toggleFabricConstructionSection();  // Handle job work specifics
    }
}

function toggleFabricConstructionSection() {
    const jobWorkType = document.querySelector('input[name="jobwork_type"]:checked');
    const fabricConstructionSection = document.getElementById('fabric_construction');
    const warpSizingSection = document.getElementById('warp_sizing');
    const sizingHiddenFields = document.getElementById('sizing_hidden_fields');
    
    if (!jobWorkType) return;

    // Show/hide fabric construction or warp sizing fields based on the job work type
    if (jobWorkType.value === 'Sizing + Weaving' || jobWorkType.value === 'Weaving') {
        fabricConstructionSection.classList.remove('hidden');
        warpSizingSection.classList.add('hidden');
        sizingHiddenFields.classList.remove('hidden');
        toggleRequiredFields(fabricConstructionSection, true);  // Required for weaving or sizing + weaving
        toggleRequiredFields(warpSizingSection, false);  // Not required
        toggleRequiredFields(sizingHiddenFields, true);  // Required for weaving or sizing + weaving
    } else if (jobWorkType.value === 'Sizing') {
        warpSizingSection.classList.remove('hidden');
        fabricConstructionSection.classList.add('hidden');
        sizingHiddenFields.classList.add('hidden');
        toggleRequiredFields(fabricConstructionSection, false);  // Not required
        toggleRequiredFields(warpSizingSection, true);  // Required for sizing
        toggleRequiredFields(sizingHiddenFields, false);  // Not required
    }
}

// Helper function to add or remove "required" attribute from all inputs in a section
function toggleRequiredFields(section, shouldBeRequired) {
    const inputs = section.querySelectorAll('input, select');
    inputs.forEach(input => {
        if (shouldBeRequired) {
            input.setAttribute('required', 'required');
        } else {
            input.removeAttribute('required');
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    // Set initial visibility and required fields
    toggleFormSections();
    toggleFabricConstructionSection();

    // Add event listeners to toggle sections when order type or job work type changes
    document.querySelectorAll('input[name="order_type"]').forEach(input => {
        input.addEventListener('change', toggleFormSections);
    });

    document.querySelectorAll('input[name="jobwork_type"]').forEach(input => {
        input.addEventListener('change', toggleFabricConstructionSection);
    });
});

    </script>
</head>
<body>
    <main>
        <section class="create-sales-order-form">
            <h2>Create New Sales Order</h2>
            <form id="sales-order-form" action="submit_sales_order.php" method="POST">
                <label for="order_type">Order Type:</label><br>
                <input type="radio" name="order_type" value="Ownsales" required> Own Sales
                <input type="radio" name="order_type" value="Jobwork" required> Job Work<br><br>

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
                        <?php foreach ($buyers_data as $row): ?>
                            <option value="<?= $row['buyer_id']; ?>"><?= $row['buyer_name']; ?></option>
                        <?php endforeach; ?>
                    </select><br>

                    <label for="date_of_confirmation">Date of Confirmation:</label>
                    <input type="date" name="date_of_confirmation" required><br>

                    <label for="agent_id">Agent Name:</label>
                    <select name="agent_id" required>
                        <?php foreach ($agents_data as $row): ?>
                            <option value="<?= $row['agent_id']; ?>"><?= $row['agent_name']; ?></option>
                        <?php endforeach; ?>
                    </select><br>

                    <!-- Additional Fabric Details for Own Sales -->
                    <!-- Rest of the fields for own sales here... -->
                </div>

                <!-- Job Work Section -->
                <div id="jobwork-section" style="display:none;">
                    <label for="order_quantity">Order Quantity (Meters):</label>
                    <input type="number" name="order_quantity" required><br>

                    <label for="jobwork_type">Job Work Type:</label><br>
                    <input type="radio" name="jobwork_type" value="Sizing" required> Sizing
                    <input type="radio" name="jobwork_type" value="Weaving" required> Weaving
                    <input type="radio" name="jobwork_type" value="Sizing + Weaving" required> Sizing + Weaving<br><br>

                    <label for="price_or_pickrate">Price / Pick Rate:</label>
                    <input type="number" name="price_or_pickrate" step="0.01" required><br>

                    <label for="payment_terms">Payment Terms:</label>
                    <input type="text" name="payment_terms" required><br>

                    <label for="buyer_id">Buyer Name:</label>
                    <select name="buyer_id" required>
                        <?php foreach ($buyers_data as $row): ?>
                            <option value="<?= $row['buyer_id']; ?>"><?= $row['buyer_name']; ?></option>
                        <?php endforeach; ?>
                    </select><br>

                    <label for="date_of_confirmation">Date of Confirmation:</label>
                    <input type="date" name="date_of_confirmation" required><br>

                    <label for="agent_id">Agent Name:</label>
                    <select name="agent_id" required>
                        <?php foreach ($agents_data as $row): ?>
                            <option value="<?= $row['agent_id']; ?>"><?= $row['agent_name']; ?></option>
                        <?php endforeach; ?>
                    </select><br>

                    <!-- Sizing Section (if only sizing is selected) -->
                    <div id="warp_sizing" class="hidden">
                        <label for="warp_count">Warp Count:</label>
                        <input type="number" name="warp_count"><br>

                        <label for="ends">Ends:</label>
                        <input type="number" name="ends"><br>
                    </div>

                    <!-- Fabric Construction Section (if weaving or sizing + weaving is selected) -->
                    <div id="fabric_construction" class="hidden">
                        <label for="epi">EPI (Ends per inch):</label>
                        <input type="number" name="epi"><br>

                        <label for="ppi">PPI (Picks per inch):</label>
                        <input type="number" name="ppi"><br>

                        <label for="ply">Ply:</label>
                        <input type="number" name="ply"><br>

                        <label for="width">Width (Inches):</label>
                        <input type="number" name="width"><br>

                        <label for="weave_type">Weave Type:</label>
                        <input type="text" name="weave_type"><br>

                        <label for="fibre_id">Fibre Type:</label>
                        <select name="fibre_id" required>
                            <?php foreach ($fibre_types_data as $row): ?>
                                <option value="<?= $row['fibre_id']; ?>"><?= $row['fibre_name']; ?></option>
                            <?php endforeach; ?>
                        </select><br>

                        <label for="selvedge_width">Selvedge Width (inches):</label>
                        <input type="number" name="selvedge_width"><br>

                        <label for="selvedge_id">Type of Selvedge:</label>
                        <select name="selvedge_id" required>
                            <?php foreach ($selvedge_types_data as $row): ?>
                                <option value="<?= $row['selvedge_id']; ?>"><?= $row['selvedge_name']; ?></option>
                            <?php endforeach; ?>
                        </select><br>

                        <label for="selvedge_weave">Selvedge Weave:</label>
                        <input type="text" name="selvedge_weave"><br>
                    </div>

                    <!-- Hidden Fields for Sizing + Weaving/Weaving -->
                    <div id="inspection_fields" class="hidden">
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
                    </div>

                    <!-- Common Fields -->
                    <label for="freight">Freight:</label>
                    <input type="text" name="freight" required><br>

                    <label for="invoice_address">Invoice Address:</label>
                    <input type="text" name="invoice_address" required><br>

                    <label for="delivery_address">Delivery Address:</label>
                    <input type="text" name="delivery_address" required><br>

                    <label for="commission">Commission:</label>
                    <input type="text" name="commission" required><br>
                </div>

                <button type="submit">Submit</button>
            </form>
        </section>
    </main>

    <script>
        document.getElementById('sales-order-form').addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            fetch('submit_sales_order.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('success')) {
                    Swal.fire('Success', 'Sales order created successfully!', 'success');
                } else {
                    Swal.fire('Error', 'Failed to create sales order. Try again!', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'An unexpected error occurred.', 'error');
            });
        });
    </script>
</body>
</html>
