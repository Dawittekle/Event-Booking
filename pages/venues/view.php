<?php
require_once '../../includes/header.php';
require_once '../../includes/db.php';
require_once '../../includes/auth.php';
require_once 'availability.php';

$venue_id = $_GET['id'];
$result = $conn->query("SELECT * FROM venues WHERE id = $venue_id");
$venue = $result->fetch_assoc();

$start_date = date('Y-m-d');
$end_date = date('Y-m-d', strtotime('+30 days'));
$availability = get_venue_availability($venue_id, $start_date, $end_date, $conn);

$events = [];
foreach ($availability as $date => $is_available) {
  $events[] = [
    'start' => $date,
    'end' => $date,
    'display' => 'background',
    'backgroundColor' => $is_available ? '#96fc71' : '#ff795e',
  ];
}
?>


<section class="view-venue-section">
  <div class="container wrapper">
    <div class="venue-card venue-card-lg">
      <div class="image-container">
        <?php if ($venue['image']): ?>
          <img src="../../<?= $venue['image'] ?>" alt="<?= htmlspecialchars($venue['name']) ?>">
        <?php endif; ?>
      </div>

      <div class="content">
        <div class="content-col">
          <div class="title-bar">
            <h3><?= htmlspecialchars($venue['name']) ?></h3>
            <?php
            $venue_id = $venue['id'];
            $review_query = $conn->query("SELECT AVG(rating) AS avg_rating FROM reviews WHERE venue_id = $venue_id");
            $review_result = $review_query->fetch_assoc();
            $avg_rating = round($review_result['avg_rating']);
            ?>
            <div class="stars">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <span class="star <?= $i <= $avg_rating ? 'filled' : '' ?>">★</span>
              <?php endfor; ?>
              <?php
              $review_count_query = $conn->query("SELECT COUNT(*) AS review_count FROM reviews WHERE venue_id = $venue_id");
              $review_count_result = $review_count_query->fetch_assoc();
              $review_count = $review_count_result['review_count'];
              ?>
              <span>(<?= $review_count ?>)</span>
            </div>
            <div class="venue-details">
              <div class="detail-item">
                <img src="../../assets/images/location.svg" alt="Location">
                <span><span style="font-weight: 600;">Address:</span> <?= htmlspecialchars($venue['address']) ?>,
                  Ethiopia</span>
              </div>
              <div class="detail-item">
                <img src="../../assets/images/capacity.svg" alt="Capacity">
                <span><span style="font-weight: 600;">Capacity:</span>
                  <?= htmlspecialchars($venue['capacity']) ?>+</span>
              </div>
              <div class="detail-item">
                <img src="../../assets/images/price.svg" alt="Price">
                <span><span style="font-weight: 600;">Price:</span> ETB
                  <?= number_format($venue['price_per_hour'], 2) ?>/hr</span>
              </div>
            </div>
          </div>
          <p class="description"><?= htmlspecialchars($venue['description']) ?></p>
          <p>
            <span style="font-weight: 600;">Amenities:</span> <?= htmlspecialchars($venue['amenities']) ?>
          </p>
          <div class="venue-tags">
            <?php
            $tag_query = "SELECT t.name FROM tags t INNER JOIN venue_tags vt ON t.id = vt.tag_id WHERE vt.venue_id = " . $venue['id'];
            $tag_result = $conn->query($tag_query);
            while ($tag = $tag_result->fetch_assoc()): ?>
              <span class="venue-tag"><?= htmlspecialchars($tag['name']) ?></span>
            <?php endwhile; ?>
          </div>

          <form action="../../bookings/confirm.php" method="get" class="book-form">
            <input type="hidden" name="venue_id" value="<?= $venue['id'] ?>">
            <label>Start Date: <input type="date" name="start_date" required></label>
            <label>End Date: <input type="date" name="end_date" required></label>
            <button type="submit">Book Now</button>
          </form>
        </div>
      </div>
      <div class="content-col venue-reviews">
        <h3 class="font-header-3">What Guests Are Saying</h3>
        <?php
        $review_query = $conn->query("SELECT r.*, u.name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.venue_id = $venue_id ORDER BY r.created_at DESC");
        if ($review_query->num_rows === 0): ?>
          <p>No reviews yet.</p>
        <?php endif; ?>

        <?php while ($review = $review_query->fetch_assoc()): ?>
          <div class="review-card">
            <div class="stars">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <span class="star <?= $i <= $review['rating'] ? 'filled' : '' ?>">★</span>
              <?php endfor; ?>
            </div>
            <p class="review-content"><?= htmlspecialchars($review['comment']) ?></p>
            <small>By <?= $review['name'] ?> on <?= date('F j, Y', strtotime($review['created_at'])) ?></small>
          </div>
        <?php endwhile; ?>


        <?php if (is_logged_in()): ?>
          <?php
          $already_reviewed = $conn->query("SELECT * FROM reviews WHERE venue_id = $venue_id AND user_id = " . $_SESSION['user_id']);
          if ($already_reviewed->num_rows === 0):
            ?>
            <h3 class="font-header-3">Leave Your Review</h3>
            <form action="../reviews/submit.php" method="post" class="review-form">
              <input type="hidden" name="venue_id" value="<?= $venue_id ?>">
              <label>Rating:
                <select name="rating" required style="font-size: 18px;">
                  <option value="5">★★★★★</option>
                  <option value="4">★★★★☆</option>
                  <option value="3">★★★☆☆</option>
                  <option value="2">★★☆☆☆</option>
                  <option value="1">★☆☆☆☆</option>
                </select>
              </label><br>
              <textarea name="comment" placeholder="Write your review..." required></textarea><br>
              <button type="submit">Submit Review</button>
            </form>
          <?php else: ?>
            <p>You have already reviewed this venue.</p>
          <?php endif; ?>
        <?php else: ?>
          <p><a href="/event-booking/pages/login.php" style="text-decoration: underline; color:rgb(66, 115, 0);">Login</a>
            to leave a review.</p>
        <?php endif; ?>
      </div>
      <div id='calendar' class="content-col"></div>

      <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
      <script>
        document.addEventListener('DOMContentLoaded', function () {
          var calendarEl = document.getElementById('calendar');

          var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
              left: 'prev,next today',
              center: 'title',
              right: ''
            },
            validRange: {
              start: new Date(),
              end: new Date(new Date().setDate(new Date().getDate() + 30))
            },
            events: <?php echo json_encode($events); ?>,
          });

          calendar.render();
        });
      </script>
    </div>

  </div>
</section>

<?php require_once '../../includes/footer.php'; ?>