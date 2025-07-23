<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';

if (!is_logged_in())
    redirect('/event-booking/pages/login.php');

$venue_id = $_GET['venue_id'];
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];
$start = new DateTime($start_date);
$end = new DateTime($end_date);
$interval = $start->diff($end);
$total_days = $interval->days + 1;

$dates_query = "SELECT * FROM availability WHERE venue_id = $venue_id AND date BETWEEN '$start_date' AND '$end_date'";
$dates_result = $conn->query($dates_query);

$available = true;

while ($row = $dates_result->fetch_assoc()) {
    if (!$row['is_available']) {
        $available = false;
        break;
    }

}

?>
<div class="booking-confirm-hero">
    <div class="wrapper">
        <?php
        if (!$available) {
            echo "<p>This venue is not available for the selected dates.</p>";
        } else {
            $venue = $conn->query("SELECT * FROM venues WHERE id = $venue_id")->fetch_assoc();
            $total_price = $total_days * 24 * $venue['price_per_hour'];

            if (!is_customer()) {
                echo "<h2 class='font-header-2'>You need to be logged in as a customer to book a venue.</h2>";
            } else {
                $stmt = $conn->prepare("INSERT INTO bookings (venue_id, user_id, start_date, end_date, total_price) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("iissd", $venue_id, $_SESSION['user_id'], $start_date, $end_date, $total_price);
                $stmt->execute();

                echo "<h2 class='font-header-2'>Booking Confirmed!</h2>";
                echo "<p>Total Price: $" . number_format($total_price, 2) . "</p>";
            }
        }
        ?>
        <a class="btn-link" href="../pages/dashboard.php"><button class="btn btn-tertiary">Go to Dashboard</button></a>
    </div>
</div>

<link rel="stylesheet" href="../assets/css/bookings-confirm.css">
<link rel="stylesheet" href="../assets/css/style.css">
<?php require_once '../includes/footer.php'; ?>