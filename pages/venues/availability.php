<?php
function get_venue_availability($venue_id, $start_date, $end_date, $conn)
{
    $availability = [];
    $current_date = strtotime($start_date);
    $end_date_timestamp = strtotime($end_date);

    while ($current_date <= $end_date_timestamp) {
        $date = date('Y-m-d', $current_date);
        $result = $conn->query("SELECT is_available FROM availability WHERE venue_id = $venue_id AND date = '$date'");

        $is_available = true; 
        if ($row = $result->fetch_assoc()) {
            $is_available = (bool) $row['is_available'];
        }

        $availability[$date] = $is_available;
        $current_date = strtotime('+1 day', $current_date);
    }

    return $availability;
}
?>
<?php



