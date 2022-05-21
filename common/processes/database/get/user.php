<?php

    function get_user_id($username)
    {
        $username = checkInput('DEFAULT', $username);

        if ($username != null) {
            global $conn;

            $query = 'SELECT `id` FROM `'.DATABASE_PREFIX."users` WHERE `username` = '".$username."'";
            $rs = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($rs);

            return $row['id'];
        } else {
            return 'Unknown';
        }
    }

    function get_user_username($id)
    {
        $id = checkInput('DEFAULT', $id);

        if ($id != null) {
            global $conn;

            $query = 'SELECT `username` FROM `'.DATABASE_PREFIX.'users` WHERE `id` = '.$id;
            $rs = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($rs);

            return $row['username'];
        } else {
            return 'Unknown';
        }
    }

    function get_user_firstname($id)
    {
        $id = checkInput('DEFAULT', $id);

        if ($id != null) {
            global $conn;

            $query = 'SELECT `first_name` FROM `'.DATABASE_PREFIX.'users` WHERE `id` = '.$id;
            $rs = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($rs);

            return $row['first_name'];
        } else {
            return 'Unknown';
        }
    }

    function get_user_lastname($id)
    {
        $id = checkInput('DEFAULT', $id);

        if ($id != null) {
            global $conn;

            $query = 'SELECT `last_name` FROM `'.DATABASE_PREFIX.'users` WHERE `id` = '.$id;
            $rs = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($rs);

            if (get_user_settings_privacy_abbreviate_surname($id)) {
                $lastname = $row['last_name'];

                return substr($lastname, 0);
            } else {
                return $row['last_name'];
            }
        } else {
            return 'Unknown';
        }
    }

    function get_user_fullname($id): string
    {
        $id = checkInput('DEFAULT', $id);

        if ($id != null) {
            global $conn;

            $query = 'SELECT `first_name`, `last_name` FROM `'.DATABASE_PREFIX.'users` WHERE `id` = '.$id;
            $rs = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($rs);

            if (get_user_settings_privacy_abbreviate_surname($id)) {
                $lastname = $row['last_name'];
                $lastname = substr($lastname, 0, 1);

                return $row['first_name'].' '.$lastname;
            } else {
                return $row['first_name'].' '.$row['last_name'];
            }
        } else {
            return 'Unknown';
        }
    }

    function get_user_email($id)
    {
        $id = checkInput('DEFAULT', $id);

        if ($id != null) {
            global $conn;

            $query = 'SELECT `email` FROM `'.DATABASE_PREFIX.'users` WHERE `id` = '.$id;
            $rs = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($rs);

            return $row['email'];
        } else {
            return 'Unknown';
        }
    }

    function get_user_role($id): string
    {
        $id = checkInput('DEFAULT', $id);

        if ($id != null) {
            global $conn;

            $query = 'SELECT `role_id` FROM `'.DATABASE_PREFIX.'users` WHERE `id` = '.$id;
            $rs = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($rs);
            $q = $row['role_id'];

            if ($q == '0') {
                $role = __('General:Restricted');
            } elseif ($q == '1') {
                $role = __('General:Pending');
            } elseif ($q == '2') {
                $role = __('General:Writer');
            } elseif ($q == '3') {
                $role = __('General:Editor');
            } elseif ($q == '4') {
                $role = __('General:Administrator');
            } elseif ($q == '-1') {
                $role = __('General:Deleted');
            } else {
                $role = __('General:Unknown');
            }
        } else {
            $role = __('General:Unknown');
        }

        return $role;
    }

    function get_user_roleID($id)
    {
        $id = checkInput('DEFAULT', $id);

        if ($id != null) {
            global $conn;

            $query = 'SELECT `role_id` FROM `'.DATABASE_PREFIX.'users` WHERE `id` = '.$id;

            $rs = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($rs);

            return $row['role_id'];
        } else {
            return 'Unknown';
        }
    }

    function get_user_bio($id)
    {
        $id = checkInput('DEFAULT', $id);

        if ($id != null) {
            global $conn;

            $query = 'SELECT `bio` FROM `'.DATABASE_PREFIX.'users` WHERE `id` = '.$id;

            $rs = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($rs);

            return $row['bio'];
        } else {
            return 'Unknown';
        }
    }

    function get_user_website($id)
    {
        $id = checkInput('DEFAULT', $id);

        if ($id != null) {
            global $conn;

            $query = 'SELECT `website` FROM `'.DATABASE_PREFIX.'users` WHERE `id` = '.$id;

            $rs = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($rs);

            return $row['website'];
        } else {
            return 'Unknown';
        }
    }

    function get_user_profilephoto($id)
    {
        $id = checkInput('DEFAULT', $id);

        if ($id != null) {
            global $conn;

            $query = 'SELECT `profile_photo` FROM `'.DATABASE_PREFIX.'users` WHERE `id` = '.$id;

            $rs = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($rs);

            return $row['profile_photo'];
        } else {
            return 'Unknown';
        }
    }

    function get_user_key($id)
    {
        $id = checkInput('DEFAULT', $id);

        if ($id != null) {
            global $conn;

            $query = 'SELECT `user_key` FROM `'.DATABASE_PREFIX.'users` WHERE `id` = '.$id;

            $rs = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($rs);

            return $row['user_key'];
        } else {
            return 'Unknown';
        }
    }

    function get_user_auth_code($id): string
    {
        $id = checkInput('DEFAULT', $id);

        if ($id != null) {
            global $conn;

            $query = 'SELECT `auth_code` FROM `'.DATABASE_PREFIX.'users` WHERE `id` = '.$id;

            $rs = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($rs);

            return $row['auth_code'];
        } else {
            return 'Unknown';
        }
    }

    function get_user_last_login_ip($id): string
    {
        $id = checkInput('DEFAULT', $id);

        if ($id != null) {
            global $conn;

            $query = 'SELECT `last_login_ip` FROM `'.DATABASE_PREFIX.'users` WHERE `id` = '.$id;
            $rs = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($rs);

            return $row['last_login_ip'];
        } else {
            return 'Unknown';
        }
    }

    function get_user_profile_link($id): string
    {
        $id = checkInput('DEFAULT', $id);

        if ($id != null) {
            return CONFIG_INSTALL_URL.'/panel/team/profile/?u='.get_user_username($id);
        } else {
            return 'Unknown';
        }
    }

    function get_user_email_exists($email): bool
    {
        $email = checkInput('DEFAULT', $email);

        if ($email != null) {
            global $conn;

            $query = 'SELECT `id` FROM `'.DATABASE_PREFIX."users` WHERE `email` = '".$email."'";

            $rs = mysqli_query($conn, $query);
            $rows = mysqli_num_rows($rs);

            if ($rows == 0) {
                $return = false;
            } else {
                $return = true;
            }

            return $return;
        } else {
            return 'Unknown';
        }
    }

    function get_username_exists($username): bool
    {
        $username = checkInput('DEFAULT', $username);

        if ($username != null) {
            global $conn;

            $query = 'SELECT `id` FROM `'.DATABASE_PREFIX."users` WHERE `username` = '".$username."'";

            $rs = mysqli_query($conn, $query);
            $rows = mysqli_num_rows($rs);

            if ($rows == 0) {
                $return = false;
            } else {
                $return = true;
            }

            return $return;
        } else {
            return 'Unknown';
        }
    }

    function get_user_last_seen($id): string
    {
        $id = checkInput('DEFAULT', $id);

        if ($id != null) {
            global $conn;

            $query = 'SELECT `last_seen` FROM `'.DATABASE_PREFIX.'users` WHERE `id` = '.$id;
            $rs = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($rs);

            return $row['last_seen'];
        } else {
            return 'Unknown';
        }
    }
