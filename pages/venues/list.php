<?php
require_once '../../includes/header.php';
require_once '../../includes/db.php';

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$capacity = isset($_GET['capacity']) ? intval($_GET['capacity']) : 0;
$start_date = isset($_GET['start_date']) ? $conn->real_escape_string($_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? $conn->real_escape_string($_GET['end_date']) : '';
$sort = isset($_GET['sort']) && $_GET['sort'] === 'asc' ? 'ASC' : 'DESC';
?>


<section class="list-hero-section">
  <div class="container wrapper">
    <div class="list-hero-text">
      <h1 class="font-header-1">Find Venues That Fit All Your Needs!</h1>
      <p>We bring together a wide variety of venues to help you find the perfect space for your event, meeting, or celebration.</p>
    </div>
    <form method="get" class="filter-form">
      <label>
        Search Venue
        <input type="text" name="search" placeholder="Search by tag, name, or city" size="35"
          value="<?= htmlspecialchars($search) ?>">
      </label>

      <label>
        Capacity
        <input type="number" name="capacity" min="1" size="5" value="<?= $capacity ?>">
      </label>

      <label>
        Start Date
        <input type="date" name="start_date" value="<?= $start_date ?>">
      </label>

      <label>
        End Date
        <input type="date" name="end_date" value="<?= $end_date ?>">
      </label>

      <label>
        Sort Price
        <select name="sort">
          <option value="desc" <?= $sort === 'DESC' ? 'selected' : '' ?>>High to Low</option>
          <option value="asc" <?= $sort === 'ASC' ? 'selected' : '' ?>>Low to High</option>
        </select>
      </label>

      <label>
        Sort Rating
        <select name="rating_sort">
          <option value="desc">High to Low</option>
          <option value="asc">Low to High</option>
        </select>
      </label>

      <button type="submit">Apply Filter</button>
    </form>
  </div>
</section>


<section class="results-section">
  <div class="container wrapper">
    <h2 class="font-header-2">Search Results</h2>

    <div class="venues-list">
      <?php
      $query = "SELECT v.*, COALESCE(ar.avg_rating, 0) AS avg_rating
                    FROM venues v
                    LEFT JOIN venue_tags vt ON v.id = vt.venue_id
                    LEFT JOIN tags t ON vt.tag_id = t.id
                    LEFT JOIN (SELECT venue_id, AVG(rating) AS avg_rating FROM reviews GROUP BY venue_id) AS ar ON v.id = ar.venue_id
                    WHERE 1=1";

      if (!empty($search)) {
        $query .= " AND (v.name LIKE '%$search%' OR v.city LIKE '%$search%' OR t.name LIKE '%$search%')";
      }

      if ($capacity > 0) {
        $query .= " AND v.capacity >= $capacity";
      }

      if (!empty($start_date) && !empty($end_date)) {
        $subquery = "(SELECT COUNT(*)
                            FROM availability a
                            WHERE a.venue_id = v.id
                            AND a.date BETWEEN '$start_date' AND '$end_date'
                            AND a.is_available = 0)";
        $query .= " AND NOT EXISTS $subquery";
      }

      $query .= " GROUP BY v.id";

      $orderBy = "v.price_per_hour $sort";

      if (isset($_GET['rating_sort'])) {
        $rating_sort = $_GET['rating_sort'] === 'asc' ? 'ASC' : 'DESC';
        $orderBy = "COALESCE(avg_rating, 0) $rating_sort";
      }

      $query .= " ORDER BY " . $orderBy;

      $result = $conn->query($query);
      if ($result->num_rows === 0): ?>
        <p class="no-venues">No venues available yet. Try a different filter...</p>
      <?php endif; ?>

      <?php while ($venue = $result->fetch_assoc()): ?>
        <div class="venue-card">
          <div class="image-container">
            <?php if ($venue['image']): ?>
              <img src="../../<?= $venue['image'] ?>" alt="<?= htmlspecialchars($venue['name']) ?>">
            <?php endif; ?>
          </div>

          <div class="content">
            <div class="content-col">
              <div class="title-bar">
                <h3><?= htmlspecialchars($venue['name']) ?></h3>
                <div class="stars">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="star <?= $i <= round($venue['avg_rating']) ? 'filled' : '' ?>">★</span>
                  <?php endfor; ?>
                  <?php
                  $review_count_query = $conn->query("SELECT COUNT(*) AS review_count FROM reviews WHERE venue_id = " . $venue['id']);
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
                <a href="view.php?id=<?= $venue['id'] ?>" class="link-btn">
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

<?php require_once '../../includes/footer.php'; ?>
