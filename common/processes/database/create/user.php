<?php

function create_user($email, $firstname, $lastname, $password, $organisation): bool
{
    $email = checkInput('DEFAULT', $email);
    $firstname = checkInput('DEFAULT', $firstname);
    $lastname = checkInput('DEFAULT', $lastname);
    $organisation = checkInput('DEFAULT', $organisation);

    global $conn;

    $username = strtolower($firstname).'.'.strtolower($lastname);

    $query = 'INSERT INTO `'.DATABASE_PREFIX."users` (`id`, `username`, `first_name`, `last_name`, `email`, `password`, `user_key`, `last_login_ip`, `auth_code`, `role_id`, `first_login`, `bio`, `organisation`, `website`, `profile_photo`, `views`, `edits`, `approvals`) VALUES (NULL, '".$username."', '".$firstname."', '".$lastname."', '".$email."', '".$password."', '0', '0', NULL, '1', '1', NULL, '".$organisation."', NULL, 'data:image/png;base64,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', '0', '0', '0')";

    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        return false;
    }
}