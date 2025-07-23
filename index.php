<?php
require_once 'includes/header.php';
require_once 'includes/db.php';
?>


<section class="hero-section">
  <div class="wrapper container">
    <div class="hero-col-1">
      <h1 class="font-header-1">
        <span class="font-accent accent-text-1">hi</span> Venue Selection For All Events & Programs!
      </h1>
      <div class="hero-btns">
        <a href="/event-booking/pages/venues/list.php" class="btn-link">
          <button class="btn btn-primary cta-btn">
            <span>Find a Venue Now</span>
          </button>
        </a>
        <a href="/event-booking/pages/venues/add.php" class="btn-link">
          <button class="btn btn-secondary">
            <span>List Your Venue</span>
          </button>
        </a>
      </div>
    </div>
    <div class="hero-col-2">
      <div class="hero-box">
        <div class="img-wrapper">
          <img src="/event-booking/assets/images/hero-mini-photo.png" alt="Event Picture" />
          <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-circle-arrow"
            viewBox="0 0 16 16">
            <path fill-rule="evenodd"
              d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16zM11.354 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L10.293 7.5H5.5a.5.5 0 0 0 0 1h4.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
          </svg>
        </div>
        <div class="hero-text">
          <h2>Your Dream Venue Awaits</h2>
          <p>
            Find your perfect venue <span class="fw-bold">without stress</span>.
            At <span style="font-style: italic;">VenueEase</span>, we connect you with top venues so you can focus on
            making memories.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="featured-section" id="featured-venues">
  <div class="container wrapper">
    <h2 class="font-header-2">Featured Venues</h2>
    <div class="venues-list">
      <?php
      $query = "SELECT venues.*, AVG(reviews.rating) AS avg_rating FROM venues LEFT JOIN reviews ON venues.id = reviews.venue_id GROUP BY venues.id ORDER BY avg_rating DESC LIMIT 5";
      $result = $conn->query($query);
      if ($result->num_rows === 0): ?>
        <p>No venues available yet.</p>
      <?php endif; ?>

      <?php while ($venue = $result->fetch_assoc()): ?>
        <div class="venue-card">
          <div class="image-container">
            <?php if ($venue['image']): ?>
              <img src="<?= $venue['image'] ?>" alt="<?= htmlspecialchars($venue['name']) ?>">
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
                    <img src="assets/images/location.svg" alt="Location">
                    <span><span style="font-weight: 600;">Address:</span> <?= htmlspecialchars($venue['address']) ?>,
                      Ethiopia</span>
                  </div>
                  <div class="detail-item">
                    <img src="assets/images/capacity.svg" alt="Capacity">
                    <span><span style="font-weight: 600;">Capacity:</span>
                      <?= htmlspecialchars($venue['capacity']) ?>+</span>
                  </div>
                  <div class="detail-item">
                    <img src="assets/images/price.svg" alt="Price">
                    <span><span style="font-weight: 600;">Price:</span> ETB
                      <?= number_format($venue['price_per_hour'], 2) ?>/hr</span>
                  </div>
                </div>
              </div>
              <div class="venue-tags">
                <?php
                $tag_query = "SELECT t.name FROM tags t INNER JOIN venue_tags vt ON t.id = vt.tag_id WHERE vt.venue_id = " . $venue['id'];
                $tag_result = $conn->query($tag_query);
                while ($tag = $tag_result->fetch_assoc()): ?>
                  <span class="venue-tag"><?= htmlspecialchars($tag['name']) ?></span>
                <?php endwhile; ?>
              </div>
            </div>

            <div class="content-col">
              <p class="description"><?= htmlspecialchars($venue['description']) ?></p>

              <div class="view-details">
                <a href="pages/venues/view.php?id=<?= $venue['id'] ?>" class="link-btn">
                  <p class="link-text">View Details</p>
                  <svg class="arrow" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none">
                    <path stroke="#292A29" stroke-width=".5"
                      d="M7.703 7.92H9.05l-2.746 2.746a.573.573 0 0 0 0 .806l.408.409c.22.22.583.222.806 0l2.747-2.747v1.346c0 .314.255.57.57.57h.577c.316 0 .57-.259.57-.57V6.836a.57.57 0 0 0-.201-.433.57.57 0 0 0-.433-.201H7.703a.572.572 0 0 0-.57.57v.578c0 .314.256.57.57.57Zm5.508-2.948a5.752 5.752 0 1 1-8.136 8.134 5.752 5.752 0 0 1 8.136-8.134Z">
                    </path>
                  </svg>
                </a>
              </div>
            </div>

          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>


<?php require_once 'includes/footer.php'; ?>
