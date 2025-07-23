<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

if (!is_logged_in())
  redirect('/event-booking/pages/login.php');
?>


<?php if ($_SESSION['user_type'] === 'owner'): ?>
  <section class="dashboard-hero">
    <div class="container wrapper">
      <h1 class="font-header-1">Owner's Dashboard</h1>
      <p>Manage your venues and booking requests.</p>
      <a href="venues/add.php" class="btn-link"><button class="btn btn-primary">Add New Venue</button></a>
    </div>
  </section>
  <section class="dashboard-data">
    <div class="container wrapper">
      <form method="get" class="filter-form">
        <div class="filter-header">
          <h2 class="font-header-3">My Venues</h2>
          <p>Filter by Status</p>
        </div>
        <select name="status">
          <option value="all">All</option>
          <option value="pending">Pending</option>
          <option value="confirmed">Confirmed</option>
          <option value="cancelled">Cancelled</option>
        </select>
        <button type="submit">Filter</button>
      </form>

      <?php
      $owner_id = $_SESSION['user_id'];
      $status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
      $pending_bookings_query = "SELECT b.*, u.name AS user_name, v.name AS venue_name, b.status FROM bookings b JOIN users u ON b.user_id = u.id JOIN venues v ON b.venue_id = v.id WHERE v.owner_id = $owner_id";
      if ($status_filter != 'all') {
        $pending_bookings_query .= " AND b.status = '$status_filter'";
      }
      $pending_bookings_result = $conn->query($pending_bookings_query);

      if ($pending_bookings_result->num_rows > 0): ?>
        <table>
          <thead>
            <tr>
              <th>Venue Name</th>
              <th>Customer Name</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Request Date</th>
              <th>Total Price</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($booking = $pending_bookings_result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($booking['venue_name']) ?></td>
                <td><?= htmlspecialchars($booking['user_name']) ?></td>
                <td><?= htmlspecialchars($booking['start_date']) ?></td>
                <td><?= htmlspecialchars($booking['end_date']) ?></td>
                <td><?= htmlspecialchars($booking['created_at']) ?></td>
                <td>ETB <?= number_format($booking['total_price'], 2) ?></td>
                <td><?= htmlspecialchars($booking['status']) ?></td>
                <td>
                  <?php if ($booking['status'] == 'pending'): ?>
                    <a href="../bookings/process_booking.php?action=accept&booking_id=<?= $booking['id'] ?>"
                      class="btn-link"><button class="btn btn-primary-2">Accept</button></a>
                    <a href="../bookings/process_booking.php?action=reject&booking_id=<?= $booking['id'] ?>"
                      class="btn-link"><button class="btn btn-tertiary">Reject</button></a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>No pending booking requests.</p>
      <?php endif; ?>
    </div>
  </section>

<?php else: ?>
  <section class="dashboard-hero">
    <div class="container wrapper">
      <h1 class="font-header-1">Customer's Dashboard</h1>
      <p>Find the perfect venue for your event.</p>
      <a href="/event-booking/pages/venues/list.php" class="btn-link"><button class="btn btn-primary">Book a
          Venue</button></a>
      <a href="#my-bookings" class="btn-link"><button class="btn btn-tertiary">My Bookings</button></a>
    </div>
  </section>

  <section class="dashboard-data" id="my-bookings">
    <div class="container wrapper">
      <form method="get" class="filter-form">
        <div class="filter-header">
          <h2 class="font-header-3">My Bookings</h2>
          <p>Filter by Status</p>
        </div>
        <select name="status">
          <option value="all">All</option>
          <option value="pending">Pending</option>
          <option value="confirmed">Confirmed</option>
          <option value="cancelled">Cancelled</option>
        </select>
        <button type="submit">Filter</button>
      </form>
      <?php
      $user_id = $_SESSION['user_id'];
      $status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
      $booking_query = "SELECT b.*, v.name AS venue_name FROM bookings b JOIN venues v ON b.venue_id = v.id WHERE b.user_id = $user_id";
      if ($status_filter != 'all') {
        $booking_query .= " AND b.status = '$status_filter'";
      }
      $booking_result = $conn->query($booking_query);
      if ($booking_result->num_rows > 0): ?>
        <table>
          <thead>
            <tr>
              <th>Venue Name</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Request Date</th>
              <th>Total Price</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($booking = $booking_result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($booking['venue_name']) ?></td>
                <td><?= htmlspecialchars($booking['start_date']) ?></td>
                <td><?= htmlspecialchars($booking['end_date']) ?></td>
                <td><?= htmlspecialchars($booking['created_at']) ?></td>
                <td>ETB <?= number_format($booking['total_price'], 2) ?></td>
                <td><?= htmlspecialchars($booking['status']) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>No bookings found.</p>
      <?php endif; ?>
    </div>
  </section>

<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>