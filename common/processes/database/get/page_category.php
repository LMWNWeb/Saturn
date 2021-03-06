<?php

    function get_page_category_name($id)
    {
        $id = checkInput('DEFAULT', $id);

        global $conn;

        $query = 'SELECT `name` FROM `'.DATABASE_PREFIX."pages_categories` WHERE `id` = '".$id."'";
        $rs = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($rs);

        return $row['name'];
    }

    function get_category_id_from_name($name)
    {
        $name = checkInput('DEFAULT', $name);

        global $conn;

        $query = 'SELECT `id` FROM `'.DATABASE_PREFIX."pages_categories` WHERE `name` = '".$name."'";
        $rs = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($rs);

        return $row['id'];
    }

    function get_page_category_id($id): string
    {
        $id = checkInput('DEFAULT', $id);

        global $conn;

        $query = 'SELECT `category_id` FROM `'.DATABASE_PREFIX."pages` WHERE `id` = '".$id."'";
        $rs = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($rs);

        return $row['category_id'];
    }

    function get_page_category_homepage($id): int
    {
        $id = checkInput('DEFAULT', $id);

        global $conn;

        $query = 'SELECT `homepage_id` FROM `'.DATABASE_PREFIX."pages_categories` WHERE `id` = '".$id."'";
        $rs = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($rs);

        return $row['homepage_id'];
    }
