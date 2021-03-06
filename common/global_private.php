<?php

    ob_start();
    /* Load Configuration */
    require_once __DIR__.'/../config.php';
    require_once __DIR__.'/../storage/core_checksum.php';
    require_once __DIR__.'/../theme.php';
    // Saturn Info
    $saturnInfo = json_decode(file_get_contents(__DIR__.'/../assets/saturn.json'));
    define('SATURN_VERSION', $saturnInfo->{'saturn'}->{'version'});
    define('SATURN_BRANCH', $saturnInfo->{'saturn'}->{'branch'});
    define('SATURN_STORAGE_DIRECTORY', $saturnInfo->{'saturn'}->{'storagedir'});
    unset($saturnInfo);
    date_default_timezone_set(CONFIG_SITE_TIMEZONE);
    /* Important Functions */
    require_once __DIR__.'/processes/database/connect.php';
    require_once __DIR__.'/processes/security/security.php';
    require_once __DIR__.'/processes/errorHandler.php';
    set_error_handler('errorHandlerError', E_ERROR);
    set_error_handler('errorHandlerWarning', E_WARNING);
    /* Developer Tools */
    if (CONFIG_DEBUG) {
        error_reporting(E_ALL);
        log_console('SATURN][DEBUG', 'Debug Mode is ENABLED. This is NOT recommended in production environments. You can disable this in your site configuration settings.');
    }
    /* Database: Required Files */
    // Create
    require_once __DIR__.'/processes/database/create.php';
    // Delete
    require_once __DIR__.'/processes/database/delete/page.php';
    // Get
    require_once __DIR__.'/processes/database/get.php';
    // Update
    require_once __DIR__.'/processes/database/update.php';
    /* Required Files */
    require_once __DIR__.'/processes/resource_loader/resource_loader.php';
    require_once __DIR__.'/processes/email.php';
    require_once __DIR__.'/processes/link.php';
    require_once __DIR__.'/processes/themes.php';
    require_once __DIR__.'/processes/redirect.php';
    require_once __DIR__.'/panel/theme.php';
    /* GUI */
    require_once __DIR__.'/processes/gui/dashboard.php';
    require_once __DIR__.'/processes/gui/alerts.php';
    require_once __DIR__.'/processes/gui/modals.php';
    require_once __DIR__.'/processes/gui/user_profile.php';
    /* Authenticate Session */
    if (!isset($_SESSION['id'])) {
        internal_redirect('/panel/account/signin/?signedout=true');
        exit;
    } elseif (!isset($_SESSION['role_id'])) {
        internal_redirect('/panel/account/signin/?signedout=role');
        exit;
    } elseif (!isset($_SESSION['user_key']) || ($_SESSION['user_key'] != get_user_key($_SESSION['id']))) {
        internal_redirect('/panel/system/error/?err=gss2');
        exit;
    } else {
        $id = $_SESSION['id'];
        $uid = $_SESSION['id'];
    }
    if (get_user_roleID($uid) < 2) {
        internal_redirect('/panel/account/signin/?signedout=permission');
    }
    /* Require HTTPS */
    if ($_SERVER['HTTPS'] != 'on' && SECURITY_USE_HTTPS) {
        header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        exit();
    }
    /* Validate CCV */
    ccv_validate_all();
    if (!activation_validate()) {
        echo alert('INFO', 'Activation: Saturn is not activated, certain features may be unavailable. You can still use some features of Saturn unactivated. You can activate Saturn in your Admin Panel. <a href="https://docs.saturncms.net/'.SATURN_VERSION.'/warnings/#activation" class="underline text-xs text-black" target="_blank" rel="noopener">Get help.</a></h6>', true);
        log_console('SATURN][ACTIVATION', 'Saturn is not activated, certain features may be unavailable. You can still use some features of Saturn unactivated. You can activate Saturn in your Admin Panel.');
    }
    update_user_last_seen($_SESSION['id'], date('Y-m-d H:i:s'));
    if (get_announcement_panel_active()) {
        if (get_announcement_panel_link() != null && get_announcement_panel_link() != '') {
            echo alert(get_announcement_panel_type(), '<span class="underline">'.get_announcement_panel_title().':</span> '.get_announcement_panel_message().' - For more information <a href="'.get_announcement_panel_link().'" class="underline">please click here</a>.', true);
        } else {
            echo alert(get_announcement_panel_type(), '<span class="underline">'.get_announcement_panel_title().':</span> '.get_announcement_panel_message(), true);
        }
    }
    require_once __DIR__.'/processes/telemetry.php';
    if (CONFIG_SEND_DATA) {
        if (send_data() == 0) {
            alert('ERROR', 'Failed to connect to Saturn Link Stats Server.', true);
        }
    }
    ob_end_flush();
