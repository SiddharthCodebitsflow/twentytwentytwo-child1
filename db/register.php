<?php
if (wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
    $first_name = $_POST['name'];
    $user_email =  $_POST['email'];
    $user_pass =  $_POST['password'];
    $user = wp_create_user($first_name, $user_pass, $user_email);
    if (is_wp_error($user)) {
        echo $error = $user->get_error_message();
    } else {
        echo "You are registerd successfully";
    }
} else {
    echo "You are Wrong User";
}
exit();
