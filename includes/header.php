<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
	<title>Event Booking System</title>
	<link rel="stylesheet" href="/event-booking/assets/css/style.css">
	<?php
	$page = $_SERVER['REQUEST_URI'];
	$page_path = parse_url($page, PHP_URL_PATH);
	if ($page_path == '/event-booking/pages/venues/list.php') {
		echo '<link rel="stylesheet" href="/event-booking/assets/css/list.css">';
	} elseif ($page_path == '/event-booking/pages/venues/view.php') {
		echo '<link rel="stylesheet" href="/event-booking/assets/css/view.css">';
	} elseif ($page_path == '/event-booking/pages/login.php' || $page_path == '/event-booking/pages/register.php') {
		echo '<link rel="stylesheet" href="/event-booking/assets/css/auth.css">';
	} elseif ($page_path == '/event-booking/pages/dashboard.php') {
		echo '<link rel="stylesheet" href="/event-booking/assets/css/dashboard.css">';
	} elseif ($page_path == '/event-booking/pages/venues/add.php') {
		echo '<link rel="stylesheet" href="/event-booking/assets/css/add.css">';
	}
	?>
	<script src="/event-booking/assets/js/main.js" defer></script>
</head>

<body>

	<header>
		<div class="header-wrapper container">
			<a href="/event-booking" aria-label="Home" class="logo-link">
				<img src="/event-booking/assets/images/logo.svg" alt="" />
			</a>

			<div class="nav-items">
				<nav class="nav-menu">
					<ul role="list" class="nav-links">
						<li class="nav-link">
							<a href="/event-booking" aria-label="Home" data-page="home">Home</a>
						</li>
						<li class="nav-link">
							<?php
							$dashboard_url = isset($_SESSION['user_id']) ? "/event-booking/pages/dashboard.php" : "/event-booking/pages/login.php";
							?>
							<a href="<?php echo $dashboard_url; ?>" aria-label="Dashboard" data-page="dashboard">Dashboard</a>
						</li>
						<li class="nav-link">
							<a href="/event-booking/pages/venues/list.php" aria-label="Venues" data-page="venues">Venues</a>
						</li>
						<li class="nav-link">
							<a href="/event-booking/#featured-venues" aria-label="Featured" data-page="featured">Featured</a>
						</li>
					</ul>
				</nav>

				<div class="nav-btns">
					<?php if (isset($_SESSION['user_id'])): ?>
						<a href="/event-booking/logout.php" class="btn-link">
							<button class="btn btn-secondary-2">
								Logout
							</button>
						</a>
					<?php else: ?>
						<a href="/event-booking/pages/login.php" class="btn-link">
							<button class="btn btn-primary-2">
								Login
							</button>
						</a>
						<a href="/event-booking/pages/register.php" class="btn-link">
							<button class="btn btn-secondary-2">
								Register
							</button>
						</a>
					<?php endif; ?>
					<button class="nav-toggler" aria-label="Hamburger menu">
						<span></span>
					</button>
				</div>
			</div>
		</div>
	</header>
