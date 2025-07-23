<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

if (!is_logged_in() || !is_owner()) {
    redirect('/event-booking/pages/login.php');
}

$action = $_GET['action'];
$booking_id = $_GET['booking_id'];

$booking_query = "SELECT b.*, v.owner_id, v.id AS venue_id FROM bookings b JOIN venues v ON b.venue_id = v.id WHERE b.id = $booking_id";
$booking_result = $conn->query($booking_query);
$booking = $booking_result->fetch_assoc();

if (!$booking || $booking['owner_id'] != $_SESSION['user_id']) {
    die("You don't have permission to process this booking.");
}

if ($action === 'accept') {
    $update_booking_query = "UPDATE bookings SET status = 'confirmed' WHERE id = $booking_id";
    $conn->query($update_booking_query);

    $start_date = $booking['start_date'];
    $end_date = $booking['end_date'];
    $venue_id = $booking['venue_id'];

    $date = $start_date;
    while ($date <= $end_date) {
        $existing = $conn->query("SELECT * FROM availability WHERE venue_id = $venue_id AND date = '$date'");

        if ($existing->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE availability SET is_available = 0 WHERE venue_id = ? AND date = ?");
            $stmt->bind_param("is", $venue_id, $date);
        } else {
            $stmt = $conn->prepare("INSERT INTO availability (venue_id, date, is_available) VALUES (?, ?, 0)");
            $stmt->bind_param("is", $venue_id, $date);
        }

        $stmt->execute();
        $date = date('Y-m-d', strtotime($date . ' + 1 day'));
    }

    $reject_overlapping_query = "UPDATE bookings b JOIN venues v ON b.venue_id = v.id SET b.status = 'cancelled' WHERE v.owner_id = " . $_SESSION['user_id'] . " AND b.venue_id = " . $venue_id . " AND b.status = 'pending' AND b.id != " . $booking_id . " AND (b.start_date <= '" . $end_date . "' AND b.end_date >= '" . $start_date . "')";
    $conn->query($reject_overlapping_query);
} elseif ($action === 'reject') {
    $update_booking_query = "UPDATE bookings SET status = 'cancelled' WHERE id = $booking_id";
    if ($conn->query($update_booking_query) && $conn->affected_rows > 0) {
        echo 'Booking Cancelled successfully';
    } else {
        echo 'Failed to reject booking.';
    }
}

header('Location: /event-booking/pages/dashboard.php');
exit;

?>
