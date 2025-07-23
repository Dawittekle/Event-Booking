<?php
function redirect($url)
{
    header("Location: " . $url);
    exit();
}

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function is_owner()
{
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'owner';
}

function is_customer()
{
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'customer';
}
?>