<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

if (!is_logged_in())
    redirect('/event-booking/pages/login.php');

$venue_id = $_POST['venue_id'];
$rating = intval($_POST['rating']);
$comment = $conn->real_escape_string($_POST['comment']);
$user_id = $_SESSION['user_id'];



?>

<div class="booking-confirm-hero">
    <div class="wrapper">
        <?php
        $booking_check = $conn->query("SELECT * FROM bookings WHERE venue_id = $venue_id AND user_id = $user_id");
        if ($booking_check->num_rows === 0) {
            die("You are not eligible to review this venue.");
        }

        $existing = $conn->query("SELECT * FROM reviews WHERE venue_id = $venue_id AND user_id = $user_id");
        if ($existing->num_rows > 0) {
            die("You have already reviewed this venue.");
        }

        $stmt = $conn->prepare("INSERT INTO reviews (venue_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $venue_id, $user_id, $rating, $comment);

        if ($stmt->execute()) {
            echo "<h2>Thank you for your review!</h2>";
            header("Location: ../venues/view.php?id=" . $venue_id);
            exit();
        } else {
            echo "<h2>Error submitting your review.</h2>";
        }
        ?>

        <a href="../venues/view.php?id=<?= $venue_id ?>">Back to Venue</a>
    </div>
</div>

<link rel="stylesheet" href="../../assets/css/bookings-confirm.css">
<?php require_once '../../includes/footer.php'; ?>
