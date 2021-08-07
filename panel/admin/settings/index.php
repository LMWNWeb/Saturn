<?php
    session_start();
    ob_start();
    require_once __DIR__.'/../../../assets/common/global_private.php';

    if (isset($_POST['save'])) {
        $file = __DIR__.'/../../../config.php';

        $message = "<?php
    /*
     * Saturn BETA 1.0.0 Configuration File
     * Copyright (c) 2021 - Saturn Authors
     * saturncms.net
     *
     * You should not edit this file directly as it can cause errors to occur.
     * Please visit the Admin Panel's Website Settings page to change this file from there.
     *
     * For help visit docs.saturncms.net
     */

    /* General */
    const CONFIG_INSTALL_URL = '';
    const CONFIG_SITE_NAME = '".$_POST['site_name']."';
    const CONFIG_SITE_DESCRIPTION = '".$_POST['site_description']."';
    const CONFIG_SITE_KEYWORDS = '".$_POST['site_keywords']."';
    const CONFIG_SITE_CHARSET = '".$_POST['site_charset']."';
    const CONFIG_SITE_TIMEZONE = '".$_POST['site_timezone']."';
    /* Database */
    const DATABASE_HOST = '".$_POST['database_host']."';
    const DATABASE_NAME = '".$_POST['database_name']."';
    const DATABASE_USERNAME = '".$_POST['database_username']."';
    const DATABASE_PASSWORD = '".$_POST['database_password']."';
    const DATABASE_PORT = '".$_POST['database_port']."';
    const DATABASE_PREFIX = '".$_POST['database_prefix']."';
    /* Email */
    const CONFIG_EMAIL_ADMIN = '".$_POST['email_admin']."';
    const CONFIG_EMAIL_FUNCTION = '".$_POST['email_function']."';
    const CONFIG_EMAIL_SENDFROM = '".$_POST['email_sendfrom']."';
    /* Editing */
    const CONFIG_PAGE_APPROVALS = ".$_POST['page_approvals'].';
    const CONFIG_ARTICLE_APPROVALS = '.$_POST['article_approvals'].";
    const CONFIG_MAX_TITLE_CHARS = '".$_POST['max_title_chars']."';
    const CONFIG_MAX_PAGE_CHARS = '".$_POST['max_page_chars']."';
    const CONFIG_MAX_ARTICLE_CHARS = '".$_POST['max_article_chars']."';
    const CONFIG_MAX_REFERENCES_CHARS = '".$_POST['max_references_chars']."';
    /* Global Security System */
    const SECURITY_ACTIVE = ".$_POST['security_active'].';
    const LOGGING_ACTIVE = '.$_POST['security_logging'].";
    const SECURITY_MODE = '".$_POST['security_mode']."';
    /* Developer Tools */
    const CONFIG_DEBUG = ".$_POST['debug'].';';

        if (file_put_contents($file, $message, LOCK_EX) && ccv_reset()) {
            log_file('SATURN][SECURITY', get_user_fullname($_SESSION['id']).' updated Website Settings.');
            echo'<meta http-equiv="refresh" content="0; url=index.php/?successMsg=Website settings saved successfully. If an error message appears, refresh the page.">';
            exit;
        } else {
            echo'<meta http-equiv="refresh" content="0; url=index.php/?errorMsg=Unable to save website settings, an error occurred.">';
            exit;
        }
    }
    ob_end_flush();
?><!DOCTYPE html>
<html lang="en">
    <head>
        <?php include __DIR__.'/../../../assets/common/panel/vendors.php'; ?>

        <title>Settings - <?php echo CONFIG_SITE_NAME.' Admin Panel'; ?></title>
        <?php require __DIR__.'/../../../assets/common/panel/theme.php'; ?>
    </head>
    <body class="bg-gray-200">
        <?php require __DIR__.'/../../../assets/common/admin/navigation.php'; ?>

        <div class="px-8 py-4 w-full">
            <form class="w-full" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="mb-8 grid grid-cols-2">
                    <h1 class="text-gray-900 text-3xl">Settings</h1>
                    <input type="submit" name="save" value="Save" class="hover:shadow-lg cursor-pointer group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-<?php echo THEME_PANEL_COLOUR; ?>-700 bg-<?php echo THEME_PANEL_COLOUR; ?>-100 hover:bg-<?php echo THEME_PANEL_COLOUR; ?>-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 transition-all duration-200">
                </div>
            <?php
                if (isset($_GET['errorMsg'])) {
                    alert('ERROR', $_GET['errorMsg']);
                    unset($_GET['errorMsg']);
                }
                if (isset($_GET['successMsg'])) {
                    alert('SUCCESS', $_GET['successMsg']);
                    unset($_GET['successMsg']);
                }
            ?>
                <div class="mt-4">
                    <h2 class="text-gray-900 text-2xl pb-4 mb-1">General</h2>
                    <div class="grid grid-cols-2">
                        <label for="site_name">Site Name</label>
                        <input id="site_name" name="site_name" type="text" value="<?php echo CONFIG_SITE_NAME; ?>" required class="appearance-none rounded-t-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                    </div>
                    <div class="grid grid-cols-2">
                        <label for="site_description">Site Description</label>
                        <input id="site_description" name="site_description" type="text" value="<?php echo CONFIG_SITE_DESCRIPTION; ?>" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                    </div>
                    <div class="grid grid-cols-2">
                        <label for="site_keywords">Site Keywords</label>
                        <input id="site_keywords" name="site_keywords" type="text" value="<?php echo CONFIG_SITE_KEYWORDS; ?>" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                    </div>
                    <div class="grid grid-cols-2">
                        <label for="site_charset">Site Charset</label>
                        <select id="site_charset" name="site_charset" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                            <option disabled>UTF</option>
                            <option value="utf-8">UTF-8</option>
                            <option value="utf-8" selected>UTF-8 (utf8mb4) (Recommended)</option>
                            <option value="utf-16">UTF-16</option>
                            <option value="utf-32">UTF-32</option>
                            <option disabled>Others</option>
                            <option value="ascii">US ASCII</option>
                            <option value="unicode">Unicode (ucs2)</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2">
                        <label for="site_timezone">Site Timezone</label>
                        <select id="site_charset" name="site_charset" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                            <option value="Europe/London">Europe/London</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <h2 class="text-gray-900 text-2xl pb-4 mb-1">Database</h2>
                    <div class="grid grid-cols-2">
                        <label for="database_host">Database Host</label>
                        <input id="database_host" name="database_host" type="text" value="<?php echo DATABASE_HOST; ?>" required class="appearance-none rounded-t-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                    </div>
                    <div class="grid grid-cols-2">
                        <label for="database_name">Database Username</label>
                        <input id="database_name" name="database_name" type="text" value="<?php echo DATABASE_NAME; ?>" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                    </div>
                    <div class="grid grid-cols-2">
                        <label for="database_username">Database Username</label>
                        <input id="database_username" name="database_username" type="text" value="<?php echo DATABASE_USERNAME; ?>" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                    </div>
                    <div class="grid grid-cols-2">
                        <label for="database_password">Database Password</label>
                        <input id="database_password" name="database_password" type="password" value="<?php echo DATABASE_PASSWORD; ?>" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                    </div>
                    <div class="grid grid-cols-2">
                        <label for="database_port">Database Port</label>
                        <input id="database_port" name="database_port" type="text" value="<?php echo DATABASE_PORT; ?>" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                    </div>
                    <div class="grid grid-cols-2">
                        <label for="database_prefix">Database Prefix</label>
                        <input id="database_prefix" name="database_prefix" type="text" value="<?php echo DATABASE_PREFIX; ?>" required class="appearance-none rounded-b-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                    </div>
                </div>

                <div class="mt-4">
                    <h2 class="text-gray-900 text-2xl pb-4 mb-1">Email</h2>
                    <div class="grid grid-cols-2">
                        <label for="email_admin">Administrator's Email</label>
                        <input id="email_admin" name="email_admin" type="text" value="<?php echo CONFIG_EMAIL_ADMIN; ?>" required class="appearance-none rounded-t-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                    </div>
                    <div class="grid grid-cols-2">
                        <label for="email_function">Email Function</label>
                        <select id="email_function" name="email_function" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                            <option value="phpmail" selected>phpmail (Recommended)</option>
                            <option disabled>SMTP (Coming Soon)</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2">
                        <label for="email_sendfrom">Email Sendfrom</label>
                        <input id="email_sendfrom" name="email_sendfrom" type="text" value="<?php echo CONFIG_EMAIL_SENDFROM; ?>" required class="appearance-none rounded-b-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                    </div>
                </div>

                <div class="mt-4">
                    <h2 class="text-gray-900 text-2xl pb-4 mb-1">Pages and Articles</h2>
                    <div class="grid grid-cols-2">
                        <label for="page_approvals">Page Approvals</label>
                        <select id="page_approvals" name="page_approvals" required class="appearance-none rounded-t-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                            <option value="true"<?php if (CONFIG_PAGE_APPROVALS) {
                echo' selected';
            } ?>>True</option>
                            <option value="false"<?php if (!CONFIG_PAGE_APPROVALS) {
                echo' selected';
            } ?>>False</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2">
                        <label for="article_approvals">Article Approvals</label>
                        <select id="article_approvals" name="article_approvals" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                            <option value="true"<?php if (CONFIG_ARTICLE_APPROVALS) {
                echo' selected';
            } ?>>True</option>
                            <option value="false"<?php if (!CONFIG_ARTICLE_APPROVALS) {
                echo' selected';
            } ?>>False</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2">
                        <label for="max_title_chars">Maximum Title Length</label>
                        <input id="max_title_chars" name="max_title_chars" type="number" value="<?php echo CONFIG_MAX_TITLE_CHARS; ?>" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                    </div>
                    <div class="grid grid-cols-2">
                        <label for="max_page_chars">Maximum Page Content Length</label>
                        <input id="max_page_chars" name="max_page_chars" type="number" value="<?php echo CONFIG_MAX_PAGE_CHARS; ?>" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                    </div>
                    <div class="grid grid-cols-2">
                        <label for="max_article_chars">Maximum Article Content Length</label>
                        <input id="max_article_chars" name="max_article_chars" type="number" value="<?php echo CONFIG_MAX_ARTICLE_CHARS; ?>" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                    </div>
                    <div class="grid grid-cols-2">
                        <label for="max_references_chars">Maximum References Length</label>
                        <input id="max_references_chars" name="max_references_chars" type="number" value="<?php echo CONFIG_MAX_REFERENCES_CHARS; ?>" required class="appearance-none rounded-b-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                    </div>
                </div>

                <div class="mt-4">
                    <h2 class="text-gray-900 text-2xl pb-4 mb-1">Security System</h2>
                    <div class="grid grid-cols-2">
                        <label for="security_active">Security Active</label>
                        <select id="security_active" name="security_active" required class="appearance-none rounded-t-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                            <option value="true"<?php if (SECURITY_ACTIVE) {
                echo' selected';
            } ?>>True (Recommended)</option>
                            <option value="false"<?php if (!SECURITY_ACTIVE) {
                echo' selected';
            } ?>>False</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2">
                        <label for="security_logging">Logging Active</label>
                        <select id="security_logging" name="security_logging" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                            <option value="true"<?php if (CONFIG_ARTICLE_APPROVALS) {
                echo' selected';
            } ?>>True (Recommended)</option>
                            <option value="false"<?php if (!CONFIG_ARTICLE_APPROVALS) {
                echo' selected';
            } ?>>False</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2">
                        <label for="security_mode">Security Mode</label>
                        <select id="security_mode" name="security_mode" required class="appearance-none rounded-b-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                            <option value="clean"<?php if (SECURITY_MODE == 'clean') {
                echo' selected';
            } ?>>Clean (Recommended)</option>
                            <option value="halt"<?php if (SECURITY_MODE == 'halt') {
                echo' selected';
            } ?>>Halt</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <h2 class="text-gray-900 text-2xl pb-4 mb-1">Developer Tools</h2>
                    <div class="grid grid-cols-2">
                        <label for="debug">Debug Mode</label>
                        <select id="debug" name="debug" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:border-<?php echo THEME_PANEL_COLOUR; ?>-500 focus:z-10 sm:text-sm">
                            <option value="true"<?php if (CONFIG_DEBUG) {
                echo' selected';
            } ?>>True</option>
                            <option value="false"<?php if (!CONFIG_DEBUG) {
                echo' selected';
            } ?>>False</option>
                        </select>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-2">
                    <div></div>
                    <input type="submit" name="save" value="Save" class="hover:shadow-lg cursor-pointer group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-<?php echo THEME_PANEL_COLOUR; ?>-700 bg-<?php echo THEME_PANEL_COLOUR; ?>-100 hover:bg-<?php echo THEME_PANEL_COLOUR; ?>-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 transition-all duration-200">
                </div>
            </form>
        </div>
    </body>
</html>