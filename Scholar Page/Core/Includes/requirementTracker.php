<?php
$requirements = [
    "scholar_narrative_reports" => "Narrative Reports",
    "scholar_load_expenses" => "Load Expenses",
    "scholar_book_expenses" => "Book Expenses",
    "scholar_thesis_expenses" => "Thesis Expenses",
    "scholar_certificate_of_registration" => "Certificate of Registration",
    "scholar_certificate_of_grade" => "Certificate of Grade"
];

// Get current month as a full name (e.g., "November")
$currentMonth = date('F');

// Function to check if there's data for the current month
function hasDataForCurrentMonth($tableName, $connection, $currentMonth) {
    // Prepare SQL query to check if there's a record in the current month
    $query = "SELECT COUNT(*) as count FROM $tableName WHERE report_month = ?";
    
    // Debugging: Print query and current month (can be removed later)
    echo "<!-- Query: $query, Current Month: $currentMonth -->";
    
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $currentMonth);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Debugging: Show result count (can be removed later)
    echo "<!-- Result Count for $tableName: {$result['count']} -->";

    return $result['count'] > 0;
}

// Function to determine if the month has changed
function hasMonthChanged($previousMonth) {
    return date('F') !== $previousMonth;
}

// Store the last month checked (you could store this in a session or database)
$previousMonth = $_SESSION['last_checked_month'] ?? date('F');

// Check if the month has changed
if (hasMonthChanged($previousMonth)) {
    // Reset the last checked month
    $_SESSION['last_checked_month'] = date('F');
}

?>