<?php
    session_start();
    ob_start();
    require_once __DIR__.'/../../common/global_private.php';
    require_once __DIR__.'/../../common/admin/global.php';
    ob_end_flush();

    $remoteVersion = file_get_contents('https://link.saturncms.net/?latest_version');

    if (((isset($_GET['update']) || CONFIG_UPDATE_AUTO) && CONFIG_UPDATE_CHECK) && $remoteVersion != SATURN_VERSION) {
        $downloadUrl = 'https://link.saturncms.net/update/'.$remoteVersion.'.zip';
        $downloadTo = 'update.zip';
        if (strpos($downloadUrl, 'saturncms.net') !== false) {
            $installFile = __DIR__.'/../../'.$downloadTo;
            echo $installFile;
            file_put_contents($installFile, fopen($downloadUrl, 'r'));
            $path = pathinfo(realpath($installFile), PATHINFO_DIRNAME);
            $archive = new ZipArchive();
            $res = $archive->open($installFile);
            if ($res) {
                $archive->extractTo($path);
                $archive->close();
                if (!unlink($installFile)) {
                    $complete = false;
                    $errorMsg = 'Saturn update error: Unable to delete the update file.';
                } else {
                    $complete = true;
                }
            } else {
                $complete = false;
                $errorMsg = 'Saturn update error: Unable to unzip the archive.';
            }
        } else {
            $complete = false;
            $errorMsg = 'Saturn update error: Halted download from untrusted URL. Attempted to download from: '.$downloadUrl;
        }

        if ($complete) {
            header('Location: /update.php');
            exit;
        }
    }
?><!DOCTYPE html>
<html lang="en">
    <head>
        <?php include __DIR__.'/../../common/panel/vendors.php'; ?>

        <title><?php echo CONFIG_SITE_NAME.' Admin Panel'; ?></title>
        <?php require __DIR__.'/../../common/panel/theme.php'; ?>

    </head>
    <body class="bg-gray-200">
        <?php require __DIR__.'/../../common/admin/navigation.php'; ?>

            <div class="px-8 py-4 w-full">
                <?php
                    if (isset($_GET['error'])) {
                        $error = checkInput('DEFAULT', $_GET['error']);
                        log_error('ERROR', $error);
                        echo alert('ERROR', $error).'<br>';
                    }
                    if (isset($errorMsg)) {
                        $error = checkInput('DEFAULT', $errorMsg);
                        log_error('ERROR', $error);
                        echo alert('ERROR', $error).'<br>';
                    }
                    if (isset($success)) {
                        $success = checkInput('DEFAULT', $success);
                        echo alert('SUCCESS', $success).'<br>';
                    }
                ?>

                <h1 class="text-gray-900 text-3xl">Admin Panel</h1>
                <br>
                <div class="flex w-full">
                    <div class="w-full mr-1 my-1 duration-300 transform bg-<?php echo THEME_PANEL_COLOUR; ?>-100 border-l-4 border-<?php echo THEME_PANEL_COLOUR; ?>-500 hover:-translate-y-2">
                        <div class="h-full p-5 border border-l-0 shadow-sm">
                            <h6 class="mb-2 font-semibold leading-5 text-<?php echo THEME_PANEL_COLOUR; ?>-700">
                                <?php
                                $result = mysqli_query($conn, 'SELECT `id` FROM `'.DATABASE_PREFIX.'pages` WHERE 1;');
                                $rows = mysqli_num_rows($result);
                                echo $rows;
                                unset($result, $rows);
                                ?> pages.
                            </h6>
                            <p class="mb-2 leading-5 text-<?php echo THEME_PANEL_COLOUR; ?>-700">
                                <?php
                                $result = mysqli_query($conn, 'SELECT `content` FROM `'.DATABASE_PREFIX.'pages_pending` WHERE `content` IS NOT NULL;');
                                $rows = mysqli_num_rows($result);
                                echo $rows;
                                unset($result, $rows);
                                ?> pending approval.
                            </p>
                        </div>
                    </div>
                    <div class="w-full mr-1 my-1 duration-300 transform bg-<?php echo THEME_PANEL_COLOUR; ?>-100 border-l-4 border-<?php echo THEME_PANEL_COLOUR; ?>-500 hover:-translate-y-2">
                        <div class="h-full p-5 border border-l-0 shadow-sm">
                            <h6 class="mb-2 font-semibold leading-5 text-<?php echo THEME_PANEL_COLOUR; ?>-700">
                                <?php
                                $result = mysqli_query($conn, 'SELECT `content` FROM `'.DATABASE_PREFIX.'articles` WHERE 1;');
                                $rows = mysqli_num_rows($result);
                                echo $rows;
                                unset($result, $rows);
                                ?> articles.
                            </h6>
                            <p class="mb-2 leading-5 text-<?php echo THEME_PANEL_COLOUR; ?>-700">
                                <?php
                                $result = mysqli_query($conn, 'SELECT `status` FROM `'.DATABASE_PREFIX."articles` WHERE `status` = 'PENDING';");
                                $rows = mysqli_num_rows($result);
                                echo $rows;
                                unset($result, $rows);
                                ?> pending publication.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex w-full">
                    <?php
                        $sql = 'SELECT `id` from `'.DATABASE_PREFIX.'users` WHERE 1';
                        $results = mysqli_query($conn, $sql);
                        $rows = mysqli_num_rows($results);
                        $sql = 'SELECT `id` from `'.DATABASE_PREFIX."users` WHERE `role_id` NOT IN ('0', '1');";
                        $results = mysqli_query($conn, $sql);
                        $activerows = mysqli_num_rows($results);
                        if ($activerows != 0) {
                            $colour = 'green';
                        } else {
                            $colour = 'red';
                        }
                        $sql = 'SELECT `id` from `'.DATABASE_PREFIX."users` WHERE `role_id` = '1';";
                        $results = mysqli_query($conn, $sql);
                        $pendingrows = mysqli_num_rows($results);
                        if ($pendingrows != 0) {
                            $colour = 'yellow';
                        }
                        $sql = 'SELECT `id` from `'.DATABASE_PREFIX."users` WHERE `role_id` = '0';";
                        $results = mysqli_query($conn, $sql);
                        $bannedrows = mysqli_num_rows($results);
                    ?>
                    <div class="w-full mr-1 my-1 duration-300 transform bg-<?php echo $colour; ?>-100 border-l-4 border-<?php echo $colour; unset($colour); ?>-500 hover:-translate-y-2">
                        <div class="h-full p-5 border border-l-0 shadow-sm">
                            <h6 class="mb-2 font-semibold leading-5"><?php echo $rows; ?> users.</h6>
                            <p><?php echo $activerows; ?> authorised users.</p>
                            <p><em><?php echo $pendingrows; ?> pending users.</em></p>
                            <p><em><?php echo $bannedrows; ?> restricted users.</em></p>
                        </div>
                    </div>
                    <?php
                    $phpversion = phpversion(); $badServer = 0;
                    if ($phpversion < '7.4.0') {
                        $badServer++;
                    }
                    if (PHP_OS != 'Linux' && PHP_OS != 'WINNT') {
                        $badServer++;
                    }

                    if ($badServer == 1) {
                        echo '<div class="w-full mr-1 my-1 duration-300 transform bg-yellow-100 border-l-4 border-yellow-500 hover:-translate-y-2">
                        <div class="h-full p-5 border border-l-0 shadow-sm">
                            <h6 class="mb-2 font-semibold leading-5">We recommend configuration changes to your server.</h6>
                            Please see the \'My Server\' section below.
                        </div>
                    </div>';
                    } elseif ($badServer == 2) {
                        echo '<div class="w-full mr-1 my-1 duration-300 transform bg-red-100 border-l-4 border-red-500 hover:-translate-y-2">
                        <div class="h-full p-5 border border-l-0 shadow-sm">
                            <h6 class="mb-2 font-semibold leading-5">We recommend configuration changes to your server.</h6>
                            PHP Version not Supported, OS Not Recommended, and Telemetry disabled.
                        </div>
                    </div>';
                    }

                    if (!CONFIG_SEND_DATA) {
                        echo '<div class="w-full mr-1 my-1 duration-300 transform bg-yellow-100 border-l-4 border-yellow-500 hover:-translate-y-2">
                        <div class="h-full p-5 border border-l-0 shadow-sm">
                            <h6 class="mb-2 font-semibold leading-5">Telemetry is disabled.</h6>
                            Telemetry helps us to collect important usage and debugging information. It\'s totally optional, but having it enabled helps us to make Saturn better for you.
                        </div>
                    </div>';
                    }
                    ?>
                    <?php if ($remoteVersion != SATURN_VERSION && CONFIG_UPDATE_CHECK) {
                        echo '
                    <div class="w-full mr-1 my-1 duration-300 transform bg-red-100 border-l-4 border-red-500 hover:-translate-y-2">
                        <div class="h-full p-5 border border-l-0 shadow-sm">
                            <h6 class="mb-2 font-semibold leading-5">An update is available.</h6>
                            It\'s recommended that you update your Saturn installation as soon as possible. You can see the release notes for this version in the \'News\' section below.<br>
                            <a href="?update=true" class="underline">Update now</a>.
                        </div>
                    </div>';
                    } ?>
                </div>
                <br>
                <?php
                $activation_key = file_get_contents('https://link.saturncms.net/?key_status='.CONFIG_ACTIVATION_KEY);
                $activation_key_url = file_get_contents('https://link.saturncms.net/?key_registered_url='.CONFIG_ACTIVATION_KEY);
                ?>
                <div class="md:flex">
                    <div class="md:flex-grow">
                        <div class="flex">
                            <h2 class="flex space-x-2 text-gray-900 text-2xl relative" x-data="{ tooltip: false }">
                                <span>Activation</span>
                                <?php if ($activation_key == '1' && $activation_key_url == $_SERVER['HTTP_HOST']) { ?>
                                <i class="fas fa-check text-green-500" aria-hidden="true" x-on:mouseover="tooltip = true" x-on:mouseleave="tooltip = false"></i>
                                <div class="mx-1 w-18" x-cloak x-show.transition.origin.top="tooltip">
                                    <div class="bg-black text-white text-xs rounded py-1 px-2 right-0 bottom-full opacity-75">
                                        Activated
                                    </div>
                                </div>
                                <?php } else { ?>
                                <i class="fas fa-times text-red-500" aria-hidden="true" x-on:mouseover="tooltip = true" x-on:mouseleave="tooltip = false"></i>
                                <div class="mx-1 w-22" x-cloak x-show.transition.origin.top="tooltip">
                                    <div class="bg-black text-white text-xs rounded py-1 px-2 right-0 bottom-full opacity-75">
                                        Not Activated
                                    </div>
                                </div>
                            <?php } ?>
                            </h2>
                        </div>
                        <p>
                            <?php
                            if ($activation_key == '1') {
                                echo '<u>Activation Key</u>: '.CONFIG_ACTIVATION_KEY.' <small class="text-green-500">Valid</small>';
                            } else {
                                echo '<u>Activation Key</u>: '.CONFIG_ACTIVATION_KEY.' <small class="text-red-500">Invalid</small>';
                            }
                            ?>
                            <br>
                            <?php
                            if ($activation_key_url == $_SERVER['HTTP_HOST']) {
                                echo '<u>Installed URL</u>: '.$_SERVER['HTTP_HOST'].' <small class="text-green-500">Valid</small>';
                            } else {
                                if (strpos($activation_key_url, 'Activation Error') !== false) {
                                    $activation_key_url = 'No URL Found';
                                }
                                echo '<u>Installed URL</u>: '.$_SERVER['HTTP_HOST'].' <small class="text-red-500">Invalid (Registered to '.$activation_key_url.')</small>';
                            }
                            ?>
                        </p>
                        <br>
                        <h2 class="text-gray-900 text-2xl">Your Server</h2>
                        <p>
                            <?php
                                if ($phpversion < '7.4.0') {
                                    echo '<u>PHP Version</u>: <span class="text-red-500">'.$phpversion.'</span> <small class="text-red-900">Recommended: 7.4.0</small>';
                                } else {
                                    echo '<u>PHP Version</u>: <span class="text-green-500">'.$phpversion.'</span>';
                                }
                                if (PHP_OS != 'Linux' && PHP_OS != 'WINNT') {
                                    echo '<br><u>Operating System</u>: <span class="text-red-500">'.PHP_OS.'</span> <small class="text-red-900">Recommended: Linux</small>';
                                } else {
                                    echo '<br><u>Operating System</u>: <span class="text-green-500">'.PHP_OS.'</span>';
                                }
                                if (!CONFIG_SEND_DATA) {
                                    echo '<br><u>Telemetry</u>: <span class="text-red-500">Disabled</span> <small class="text-red-900">Enabling telemetry helps us to make Saturn better for you. See website settings page if you\'d like to turn it on.</small>';
                                } else {
                                    echo '<br><u>Telemetry</u>: <span class="text-green-500">Enabled</span>';
                                }
                            ?>
                        </p>
                        <br>
                        <h2 class="text-gray-900 text-2xl">System Updates</h2>
                        <p>
                            <u>Latest Version:</u> <?php echo $remoteVersion; ?><br>
                            <u>Current Version:</u> <?php echo SATURN_VERSION; ?><br>
                            <?php if (!CONFIG_UPDATE_CHECK) { ?><p class="italic text-xs">Update checking is disabled. To update, re-enable it in your settings.</p><?php } ?>
                            <?php if (!CONFIG_UPDATE_AUTO) { ?><p class="italic text-xs">Automatic updating is disabled. To automatically update when a new version is found, re-enable it in your settings.</p><?php } ?>
                            <?php if ($remoteVersion != SATURN_VERSION) { ?>
                            <p class="italic underline"><a href="?update=true">Update now</a></p>
                            <?php } else { ?>
                            <p class="italic">You're up to date!</p>
                            <?php } ?>
                        </p>
                    </div>
                    <div class="md:h-screen h-auto md:w-1/3">
                        <br class="md:hidden block">
                        <h2 class="text-gray-900 text-2xl px-2">News</h2>
                        <iframe src="https://link.saturncms.net/news" class="h-1/2 border border-black rounded-md shadow-lg" title="News"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>