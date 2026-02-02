<?php
// Database connection
$servername = "localhost";
$username = "Admin";  // Ensure this matches your actual database username
$password = "Password123#";  // Ensure this matches your actual database password
$dbname = "eli_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$client_name = $_POST['client_name'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

// SQL query to fetch data based on the form inputs
$sql = "SELECT * FROM clients WHERE name = ? AND report_date BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("SQL error: " . $conn->error);
}

$stmt->bind_param("sss", $client_name, $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Process the data and generate the report
    $report_data = [];

    while ($row = $result->fetch_assoc()) {
        $report_data[] = $row;
    }

    // Save the report data in a text file
    $report_file = 'reports/report_' . time() . '.txt';
    $file_handle = fopen($report_file, 'w');
    
    foreach ($report_data as $data) {
        fwrite($file_handle, "Client: " . $data['name'] . "\n");
        fwrite($file_handle, "Report Date: " . $data['report_date'] . "\n");
        fwrite($file_handle, "Details: " . $data['details'] . "\n\n");
    }

    fclose($file_handle);

    // Save the report details in the reports table
    $sql = "INSERT INTO reports (client_name, start_date, end_date, report_file) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("SQL error: " . $conn->error);
    }

    $stmt->bind_param("ssss", $client_name, $start_date, $end_date, $report_file);
    $stmt->execute();

    echo "Report generated successfully. You can download it <a href='$report_file'>here</a>.";
} else {
    echo "No records found for the selected criteria.";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
