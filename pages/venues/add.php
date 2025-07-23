<?php
require_once '../../includes/header.php';
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

if (!is_logged_in() || !is_owner()) {
  redirect('/event-booking/pages/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $description = $_POST['description'];
  $address = $_POST['address'];
  $city = $_POST['city'];
  $capacity = $_POST['capacity'];
  $price_per_hour = $_POST['price_per_hour'];

  $image = '';
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../../uploads/';
    if (!is_dir($upload_dir))
      mkdir($upload_dir, 0777, true);
    $image_name = uniqid() . '-' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name);
    $image = 'uploads/' . $image_name;
  }

  $amenities = implode(',', $_POST['amenities']);

  $stmt = $conn->prepare("INSERT INTO venues (owner_id, name, description, address, city, capacity, price_per_hour, image, amenities) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("issssiiss", $_SESSION['user_id'], $name, $description, $address, $city, $capacity, $price_per_hour, $image, $amenities);

  if ($stmt->execute()) {
    $venue_id = $stmt->insert_id;

    foreach ($_POST['service_name'] as $key => $service_name) {
      if (!empty($service_name)) {
        $service_desc = $_POST['service_description'][$key];
        $service_price = $_POST['service_price'][$key];

        $stmt_service = $conn->prepare("INSERT INTO venue_services (venue_id, service_name, description, price) VALUES (?, ?, ?, ?)");
        $stmt_service->bind_param("isss", $venue_id, $service_name, $service_desc, $service_price);
        $stmt_service->execute();
      }
    }

    if (isset($_POST['tags'])) {
      foreach ($_POST['tags'] as $tag_id) {
        $stmt_venue_tags = $conn->prepare("INSERT INTO venue_tags (venue_id, tag_id) VALUES (?, ?)");
        $stmt_venue_tags->bind_param("ii", $venue_id, $tag_id);
        $stmt_venue_tags->execute();
      }
    }

    redirect('list.php');
  } else {
    echo "Error adding venue: " . $stmt->error;
  }
}
?>

<section class="add-venue-section">
  <div class="wrapper container">
    <h2 class="font-header-2">Add New Venue</h2>
    <form method="post" enctype="multipart/form-data" class="add-form">
      <input type="text" name="name" placeholder="Venue Name" required>
      <textarea name="description" placeholder="Description" required></textarea>
      <input type="text" name="address" placeholder="Address" required>
      <input type="text" name="city" placeholder="City" required>
      <input type="number" name="capacity" placeholder="Capacity" required>
      <input type="number" step="0.01" name="price_per_hour" placeholder="Price per hour" required>
      <input type="file" name="image">

      <h3>Amenities</h3>
      <div class="checkboxes">
        <?php
        $default_amenities = ['Air Conditioning', 'WiFi', 'Parking', 'Projector', 'Catering Kitchen'];
        foreach ($default_amenities as $amenity): ?>
          <label class="custom-checkbox">
            <input type="checkbox" name="amenities[]" value="<?php echo htmlspecialchars($amenity); ?>" />
            <span><?php echo htmlspecialchars($amenity); ?></span>
          </label>
        <?php endforeach; ?>
      </div>

      <h3>Tags</h3>
      <div class="checkboxes">
        <?php
        $stmt_tags = $conn->prepare("SELECT * FROM tags");
        $stmt_tags->execute();
        $result_tags = $stmt_tags->get_result();
        while ($tag = $result_tags->fetch_assoc()): ?>
          <label class="custom-checkbox">
            <input type="checkbox" name="tags[]" value="<?php echo htmlspecialchars($tag['id']); ?>" />
            <span><?php echo htmlspecialchars($tag['name']); ?></span>
          </label>
        <?php endwhile; ?>
      </div>

      <button type="submit">Add Venue</button>
    </form>
  </div>
</section>

<?php require_once '../../includes/footer.php'; ?>
