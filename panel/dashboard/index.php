<?php
    session_start();
    ob_start();
    include_once __DIR__.'/../../common/global_private.php';
    if ($_SESSION['2FA_verified'] || !get_user_settings_security_2fa($_SESSION['id'])) {
        ?>
<!DOCTYPE html>
<html lang="en" class="dark:bg-neutral-700 dark:text-white">
    <head>
        <?php
            include_once __DIR__.'/../../common/panel/theme.php';
        $id = $_SESSION['id']; ?>
        <title><?php echo __('General:Saturn'); ?> <?php echo __('Panel:Panel'); ?></title>

        <?php
        include_once __DIR__.'/../../common/panel/vendors.php';
        if (isset($_GET['dismissNotif'])) {
            $nid = $_GET['dismissNotif'];
            update_notification_dismiss($nid);
            header('Location: '.CONFIG_INSTALL_URL.'/panel/dashboard');
        }
        if (isset($_GET['error'])) {
            $error = $_GET['error'];
            if ($error == 'permission') {
                $errorMsg = __('Error:Permissions');
            } elseif ($error == 'no_user') {
                $errorMsg = __('Error:UserNotFound');
            } else {
                $errorMsg = __('Error:Unknown');
            }
        }
        if (isset($_GET['warning'])) {
            $error = $_GET['warning'];
            if ($error == 'permission') {
                $errorMsg = __('Error:Permissions');
            } else {
                $errorMsg = __('Error:UnknownWarning');
            }
        }
        if (isset($_GET['acceptTerms']) && $_GET['acceptTerms'] == 'true') {
            update_user_accepted_terms($_SESSION['id'], true);
            echo alert('ERROR', __('Error:TryAgain'));
        } ?>

    </head>
    <body class="mb-14">
        <?php if (!get_user_accepted_terms($_SESSION['id']) && CONFIG_WELCOME_SCREEN) { ?>
        <div class="absolute top-0 bg-white dark:bg-neutral-800 z-50 ">
            <header class="bg-white dark:bg-neutral-900 shadow relative">
                <div class="py-6 px-4 sm:px-6 lg:px-8 flex w-full">
                    <h1 class="text-3xl font-bold leading-tight text-neutral-800 dark:text-neutral-200 flex-grow"><?php echo __('Panel:Welcome'); ?></h1>
                    <img src="<?php echo CONFIG_INSTALL_URL; ?>/storage/images/logo.png" class="h-8 w-auto" alt="<?php echo CONFIG_SITE_NAME; ?>">
                </div>
            </header>
            <form method="POST" action="index.php" class="py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-lg text-neutral-800 dark:text-neutral-200">
                    <?php echo __('Panel:Welcome_Greeting'); ?> <?php echo get_user_firstname($_SESSION['id']); ?>. <?php echo __('Panel:Welcome_Heading'); ?> <?php echo __('Panel:Welcome_Message'); ?><br>
                </p>
                <h2 class="mt-8 mb-2 text-2xl leading-tight text-neutral-800 dark:text-neutral-200"><?php echo __('Panel:Welcome_AboutYou'); ?></h2>
                <p class="text-lg text-neutral-800 dark:text-neutral-200">
                    <?php echo __('Panel:Welcome_AboutYou_Message_1'); ?> <?php echo get_user_username($_SESSION['id']); ?>. <?php echo __('Panel:Welcome_AboutYou_Message_2'); ?><br>
                    <br>
                    <div class="grid grid-cols-2">
                        <div>
                            <p class="text-lg text-neutral-800 dark:text-neutral-200">
                                <?php echo __('Panel:Welcome_AboutYou_Message_3'); ?> <?php echo get_user_role($_SESSION['id']); ?>. <?php echo __('Panel:Welcome_AboutYou_Message_4'); ?>
                            </p>
                            <ul class="list-disc ml-4 text-neutral-800 dark:text-neutral-200">
                                <li><?php echo __('Panel:Welcome_AboutYou_Permissions_Write'); ?></li>
                                <?php
                                if (get_user_roleID($_SESSION['id'] >= '3')) {
                                    ?>
                                    <li><?php echo __('Panel:Welcome_AboutYou_Permissions_Approve'); ?></li>
                                    <?php
                                }
            if (get_user_roleID($_SESSION['id'] >= '4')) {
                ?>
                                    <li><?php echo __('Panel:Welcome_AboutYou_Permissions_ManageWebsite'); ?></li>
                                    <li><?php echo __('Panel:Welcome_AboutYou_Permissions_ManageUsers'); ?></li>
                                    <?php
            } ?>
                                <li><?php echo __('Panel:Welcome_AboutYou_Permissions_TeamChat'); ?></li>
                                <li><?php echo __('Panel:Welcome_AboutYou_Permissions_More'); ?></li>
                            </ul>
                        </div>
                        <div class="md:block hidden">
                            <p class="text-lg text-neutral-800 dark:text-neutral-200 mx-10">
                                <?php echo __('Panel:Welcome_AboutYou_Message_5'); ?>
                            </p>
                            <div class="mx-10 rounded-md shadow-xl p-4 dark:bg-neutral-900">
                                <img class="rounded-full h-16 w-16 inline m-1" src="<?php echo get_user_profilephoto($_SESSION['id']); ?>" alt="<?php echo get_user_fullname($_SESSION['id']); ?>">
                                <h3 class="text-xl font-bold inline"><?php echo get_user_fullname($_SESSION['id']); ?></h3>
                                <p class="mt-4">
                                    <?php echo stripslashes(get_user_bio($_SESSION['id'])); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </p>
                <h2 class="mt-8 mb-2 text-2xl leading-tight text-neutral-800 dark:text-neutral-200"><?php echo __('Panel:Welcome_Settings'); ?></h2>
                <p class="text-lg text-neutral-800 dark:text-neutral-200">
                    <?php echo __('Panel:Welcome_Settings_Message'); ?>
                </p>
                <?php
                    if (!get_user_accepted_terms($_SESSION['id']) && CONFIG_WELCOME_SCREEN_SHOW_TERMS) {
                        $output = json_decode(file_get_contents(__DIR__.'/../../storage/terms.json'));
                        if ($output->data->termsandconditions != null) {
                            ?>
                <h2 class="mt-8 mb-2 text-2xl leading-tight text-neutral-800 dark:text-neutral-200"><?php echo __('Panel:Welcome_Terms'); ?></h2>
                <p class="text-lg text-neutral-800 dark:text-neutral-200">
                    <?php echo __('Panel:Welcome_Terms_Message_1'); ?><br>
                    <?php echo __('Panel:Welcome_Terms_Message_2'); ?>
                </p>
                <div class="w-full h-1/2 shadow-xl overflow-y-scroll text-lg text-<?php echo THEME_PANEL_COLOUR; ?>-700 border p-4 bg-<?php echo THEME_PANEL_COLOUR; ?>-100">
                    <?php echo $output->data->termsandconditions; ?>
                </div>
                <?php
                        }
                        if ($output->data->privacypolicy != null) {
                            ?>
                <h2 class="mt-8 mb-2 text-2xl leading-tight text-neutral-800 dark:text-neutral-200"><?php echo __('Panel:Welcome_Privacy'); ?></h2>
                <p class="text-lg text-neutral-800 dark:text-neutral-200">
                    <?php echo __('Panel:Welcome_Privacy_Message_1'); ?> <a href="https://www.saturncms.net/privacy" class="underline" rel="noopener" target="_blank">saturncms.net/privacy</a>, <?php echo __('Panel:Welcome_Privacy_Message_2'); ?><br>
                    <?php echo __('Panel:Welcome_Privacy_Message_3'); ?>
                </p>
                <div class="w-full h-1/2 shadow-xl overflow-y-scroll text-lg text-<?php echo THEME_PANEL_COLOUR; ?>-700 border p-4 bg-<?php echo THEME_PANEL_COLOUR; ?>-100">
                    <?php echo $output->data->privacypolicy; ?>
                </div>
                <?php
                        } ?>
                <?php
                    } ?>
                <h2 class="mt-8 mb-2 text-2xl leading-tight text-neutral-800 dark:text-neutral-200"><?php echo __('Panel:Welcome_LetsGo'); ?></h2>
                <p class="text-lg text-neutral-800 dark:text-neutral-200">
                    <?php echo __('Panel:Welcome_LetsGo_Message_1'); ?> <a href="mailto:<?php echo CONFIG_EMAIL_ADMIN; ?>" class="underline" rel="noopener"><?php echo CONFIG_EMAIL_ADMIN; ?></a> <?php echo __('Panel:Welcome_LetsGo_Message_2'); ?> <a href="https://docs.saturncms.net" class="underline" rel="noopener" target="_blank">https://docs.saturncms.net</a>
                </p>
                <a href="?acceptTerms=true" class="mt-10 hover:shadow-md group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-<?php echo THEME_PANEL_COLOUR; ?>-700 bg-<?php echo THEME_PANEL_COLOUR; ?>-100 hover:bg-<?php echo THEME_PANEL_COLOUR; ?>-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-<?php echo THEME_PANEL_COLOUR; ?>-500 transition-all duration-200">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-check" aria-hidden="true"></i>
                    </span>
                    <?php echo __('Panel:Welcome_Continue'); ?>
                </a>
            </form>
        </div>
        <?php
            exit;
        } ?>
        <?php include_once __DIR__.'/../../common/panel/navigation.php'; ?>

        <header class="bg-white text-gray-900 dark:bg-neutral-800 dark:text-white shadow dark:shadow-none relative <?php $notifCount = get_notification_count($_SESSION['id']);
        if ($notifCount > '0') {
            echo 'pb-28 md:pb-1';
        } ?>">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold leading-tight"><?php echo __('Panel:Dashboard'); ?></h1>
            </div>
            <?php if ($notifCount > '0') {
            echo'<a href="'.CONFIG_INSTALL_URL.'/panel/dashboard/?dismissNotif='.get_latest_notification_id($_SESSION['id']).'" class="m-1 bg-white dark:bg-neutral-700 rounded-lg dark:border-neutral-900 border-gray-300 border p-3 shadow-md absolute md:top-0 right-0 max-w-sm md:max-w-xl max-h-20 overflow-y-scroll">
                <div class="flex flex-row">
                    <span class="animate-pulse bg-blue-500 h-6 w-6 rounded-full relative text-center">
                        <i class="fas fa-info text-white self-center object-center py-1" aria-hidden="true"></i>
                    </span>
                    <div class="ml-2 mr-6">
                        <div class="flex w-full">
                            <span class="font-semibold flex-grow">'.get_latest_notification_title($_SESSION['id']).'</span>
                            <span class="text-red-500"><i class="fas fa-times text-red-500 self-center object-center py-1" aria-hidden="true"></i></span>
                        </div>
                        <span class="block text-gray-500 dark:text-white">'.get_latest_notification_content($_SESSION['id']).'</span>
                    </div>
                </div>
            </a>';
        } ?>
        </header>
        <section class="dark:bg-neutral-700 dark:text-white">
            <div class="max-w-7xl mx-auto py-6 px-2 sm:px-6 lg:px-8">
                <?php
                if (isset($errorMsg)) {
                    echo alert('ERROR', $errorMsg);
                    echo '<br>';
                    unset($errorMsg);
                }
        if (isset($warningMsg)) {
            echo alert('WARNING', $warningMsg);
            echo '<br>';
            unset($warningMsg);
        }
        if (CONFIG_DEBUG) {
            echo alert('WARNING', __('Error:DebugMode'));
            echo '<br>';
        }
        if (SATURN_BRANCH == 'dev') {
            echo alert('WARNING', __('Error:DevelopmentBuild'));
            echo '<br>';
        }
        if (get_user_roleID($_SESSION['id']) > 3) {
            if ((latest_version() != SATURN_VERSION) && CONFIG_UPDATE_CHECK) {
                echo '<br>
                        <div class="w-full mr-1 my-1 duration-300 transform bg-red-100 border-l-4 border-red-500 hover:-translate-y-2">
                            <div class="h-auto p-5 border border-l-0 rounded-r shadow-sm">
                                <h6 class="mb-2 font-semibold leading-5">'.__('Panel:Dashboard_Update').'</h6>
                                <p>'.__('General:Saturn').' '.latest_version().' '.__('Panel:Dashboard_Update_Message').'</p>';
                if (CONFIG_UPDATE_AUTO) {
                    echo '<p>'.__('Panel:Dashboard_Update_Automatic').'</p>';
                }
                echo '          <a href="'.CONFIG_INSTALL_URL.'/panel/admin" class="text-'.THEME_PANEL_COLOUR.'-500 hover:text-'.THEME_PANEL_COLOUR.'-400 underline">'.__('Panel:Dashboard_Update_Button').'</a>.
                            </div>
                        </div><br>';
            }
        } ?>

                <div class="grid md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-4">
                    <div class="flex-grow shadow-md hover:shadow-xl transition-shadow duration-200 rounded-2xl w-auto p-4 bg-white dark:bg-neutral-800">
                        <a href="<?php echo CONFIG_INSTALL_URL; ?>/panel/pages">
                            <div class="flex items-center">
                                <span class="bg-green-500 h-10 w-10 rounded-full relative text-center">
                                    <i class="far fa-file fa-lg text-white self-center object-center py-3" aria-hidden="true"></i>
                                </span>
                                <p class="text-2xl text-gray-700 dark:text-gray-50 ml-2">
                                    <?php echo __('Panel:Pages'); ?>
                                </p>
                            </div>
                            <div class="flex flex-col justify-start">
                                <p class="text-gray-800 text-4xl text-left dark:text-white font-bold my-4">
                                    <?php
                                        $result = mysqli_query($conn, 'SELECT * FROM `'.DATABASE_PREFIX.'pages` WHERE 1;');
        $rowCount = mysqli_num_rows($result);
        echo $rowCount; ?>
                                </p>
                            </div>
                        </a>
                    </div>

                    <div class="flex-grow shadow-md hover:shadow-xl transition-shadow duration-200 rounded-2xl w-auto p-4 bg-white dark:bg-neutral-800">
                        <a href="<?php echo CONFIG_INSTALL_URL; ?>/panel/articles">
                            <div class="flex items-center">
                                <span class="bg-green-500 h-10 w-10 rounded-full relative text-center">
                                    <i class="far fa-newspaper fa-lg text-white self-center object-center py-3" aria-hidden="true"></i>
                                </span>
                                <p class="text-2xl text-gray-700 dark:text-gray-50 ml-2">
                                    <?php echo __('Panel:Articles'); ?>
                                </p>
                            </div>
                            <div class="flex flex-col justify-start">
                                <p class="text-gray-800 text-4xl text-left dark:text-white font-bold my-4">
                                    <?php
                                        $result = mysqli_query($conn, 'SELECT * FROM `'.DATABASE_PREFIX."articles` WHERE `author_id` = '".$_SESSION['id']."';");
        $rowCount = mysqli_num_rows($result);
        echo $rowCount; ?>
                                </p>
                            </div>
                        </a>
                    </div>

                    <?php if (get_user_roleID($_SESSION['id']) > 2) { ?>
                    <div x-data="{ open: false }" class="flex-grow shadow-md hover:shadow-xl transition-shadow duration-200 rounded-2xl w-auto p-4 bg-white dark:bg-neutral-800">
                        <a @click="open = true" class="cursor-pointer">
                            <div class="flex items-center">
                                <span class="bg-green-500 h-10 w-10 rounded-full relative text-center">
                                    <i class="fas fa-search fa-lg text-white self-center object-center py-3" aria-hidden="true"></i>
                                </span>
                                <p class="text-2xl text-gray-700 dark:text-gray-50 ml-2">
                                    <?php echo __('Panel:Dashboard_PendingApprovals_Pending'); ?> <br class="md:hidden block"><?php echo __('Panel:Dashboard_PendingApprovals_Approvals'); ?>
                                </p>
                            </div>
                            <div class="flex flex-col justify-start">
                                <p class="text-gray-800 text-4xl text-left dark:text-white font-bold my-4">
                                    <?php
                                        $result = mysqli_query($conn, 'SELECT `content` FROM `'.DATABASE_PREFIX.'pages_pending` WHERE `content` IS NOT NULL;');
                                        $rows = mysqli_num_rows($result);

                                        $result2 = mysqli_query($conn, 'SELECT * FROM `'.DATABASE_PREFIX."articles` WHERE `status` = 'PENDING';");
                                        $rows2 = mysqli_num_rows($result2);

                                        $finalRows = $rows + $rows2;
                                        echo $finalRows;
                                    ?>
                                </p>
                            </div>
                        </a>
                        <?php echo display_modal('INFO', 'Approvals', __('Panel:Dashboard_PendingApprovals_Message_1').' '.$rows.' '.__('Panel:Dashboard_PendingApprovals_Message_2').' '.$rows2.' '.__('Panel:Dashboard_PendingApprovals_Message_2'), '<div class="bg-gray-50 dark:bg-neutral-600 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse flex">
                                        <a href="'.CONFIG_INSTALL_URL.'/panel/articles/approvals" class="flex-grow transition-all duration-200 hover:shadow-md cursor-pointer w-full flex items-center justify-center px-8 py-1 border border-transparent text-base font-medium rounded-md text-'.THEME_PANEL_COLOUR.'-700 bg-'.THEME_PANEL_COLOUR.'-100 hover:bg-'.THEME_PANEL_COLOUR.'-200 dark:hover:bg-neutral-700 dark:bg-neutral-800 dark:text-white md:py-1 md:text-rg md:px-10">
                                            '.__('Panel:Dashboard_PendingApprovals_Articles').'&nbsp;<span class="px-1 h-5 bg-red-500 rounded-full text-white text-sm">'.$rows2.'</span>
                                        </a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="'.CONFIG_INSTALL_URL.'/panel/pages/approvals" class="flex-grow transition-all duration-200 hover:shadow-md cursor-pointer w-full flex items-center justify-center px-8 py-1 border border-transparent text-base font-medium rounded-md text-'.THEME_PANEL_COLOUR.'-700 bg-'.THEME_PANEL_COLOUR.'-100 hover:bg-'.THEME_PANEL_COLOUR.'-200 dark:hover:bg-neutral-700 dark:bg-neutral-800 dark:text-white md:py-1 md:text-rg md:px-10">
                                            '.__('Panel:Dashboard_PendingApprovals_Pages').'&nbsp;<span class="px-1 h-5 bg-red-500 rounded-full text-white text-sm">'.$rows.'</span>
                                        </a>
                                    </div>');

                        unset($result, $result2, $rows, $rows2, $finalRows); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="grid md:grid-cols-2 grid-cols-1 gap-4 w-full">
                    <div class="w-full shadow-md hover:shadow-xl transition-shadow duration-200 rounded-xl p-4 bg-white dark:bg-neutral-800 overflow-hidden mt-4">
                        <div class="flex items-center">
                                <span class="bg-green-500 h-10 w-10 rounded-full relative text-center">
                                    <i class="fas fa-pencil-alt fa-lg text-white self-center object-center py-3" aria-hidden="true"></i>
                                </span>
                            <p class="text-2xl text-gray-700 dark:text-gray-50 ml-2">
                                <?php echo __('Panel:Dashboard_TopWriters'); ?>
                            </p>
                        </div>
                        <div class="flex space-x-4">
                            <?php
                            $result = $conn->query('SELECT `id`, `edits` FROM `'.DATABASE_PREFIX.'users_statistics` WHERE 1 ORDER BY edits DESC;');
        $data = $result->fetch_all();
        foreach ($data as $item) {
            ?>
                                <div class="flex-grow">
                                    <div class="flex flex-col items-center">
                                        <div class="relative">
                                            <a href="<?php echo get_user_profile_link($item[0]); ?>" class="block relative">
                                                <img alt="<?php echo get_user_fullname($item[0]); ?>" src="<?php echo get_user_profilephoto($item[0]); ?>" class="mx-auto object-cover rounded-full h-10 w-10 "/>
                                            </a>
                                        </div>
                                        <a href="<?php echo get_user_profile_link($item[0]); ?>" class="text-gray-600 dark:text-gray-400 text-xs mt-2">
                                            <?php echo get_user_fullname($item[0]); ?>
                                        </a>
                                        <a href="<?php echo get_user_profile_link($item[0]); ?>" class="mt-1 text-xs text-white bg-<?php
                                        if ($item[1] == '0') {
                                            echo 'red';
                                        } elseif ($item[1] < '6') {
                                            echo 'yellow';
                                        } else {
                                            echo 'green';
                                        } ?>-500 rounded-full p-1">
                                            <?php echo $item[1]; ?> <?php echo __('Panel:Edits'); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php
        } ?>
                        </div>
                    </div>
                    <?php if (get_user_roleID($id) > '2') { ?>
                    <div class="w-full flex-grow shadow-md hover:shadow-xl transition-shadow duration-200 rounded-xl p-4 bg-white dark:bg-neutral-800 overflow-hidden mt-4">
                        <div class="flex items-center">
                                <span class="bg-green-500 h-10 w-10 rounded-full relative text-center">
                                    <i class="fas fa-pencil-alt fa-lg text-white self-center object-center py-3" aria-hidden="true"></i>
                                </span>
                            <p class="text-2xl text-gray-700 dark:text-gray-50 ml-2">
                                <?php echo __('Panel:Dashboard_TopEditors'); ?>
                            </p>
                        </div>
                        <div class="flex space-x-4">
                            <?php
                            $result = $conn->query('SELECT `id`, `approvals` FROM `'.DATABASE_PREFIX.'users_statistics` WHERE 1 ORDER BY approvals DESC;');
                            $data = $result->fetch_all();
                            foreach ($data as $item) {
                                if (get_user_roleID($item[0]) > '2') {
                                    ?>
                                <div class="flex-grow">
                                    <div class="flex flex-col items-center">
                                        <div class="relative">
                                            <a href="<?php echo get_user_profile_link($item[0]); ?>" class="block relative">
                                                <img alt="<?php echo get_user_fullname($item[0]); ?>" src="<?php echo get_user_profilephoto($item[0]); ?>" class="mx-auto object-cover rounded-full h-10 w-10 "/>
                                            </a>
                                        </div>
                                        <a href="<?php echo get_user_profile_link($item[0]); ?>" class="text-gray-600 dark:text-gray-400 text-xs mt-2">
                                            <?php echo get_user_fullname($item[0]); ?>
                                        </a>
                                        <a href="<?php echo get_user_profile_link($item[0]); ?>" class="mt-1 text-xs text-white bg-<?php
                                        if ($item[1] == '0') {
                                            echo 'red';
                                        } elseif ($item[1] < '6') {
                                            echo 'yellow';
                                        } else {
                                            echo 'green';
                                        } ?>-500 rounded-full p-1">
                                            <?php echo $item[1]; ?> <?php echo __('Panel:Approvals'); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php
                                }
                            } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <?php display_dashboard_statistics($_SESSION['id']); ?>
                <div class="flex mt-4">
                    <div class="shadow-md rounded-xl w-full bg-white dark:bg-neutral-800 relative overflow-hidden">
                        <a class="w-full h-full block">
                            <div class="flex items-center justify-between px-4 py-6 space-x-4">
                                <div class="flex items-center">
                                    <span class="rounded-full relative pl-3 pr-2 py-2 bg-<?php echo THEME_PANEL_COLOUR; ?>-100 dark:bg-<?php echo THEME_PANEL_COLOUR; ?>-300">
                                        <i class="fa-solid fa-chart-line text-<?php echo THEME_PANEL_COLOUR; ?>-500 dark:text-<?php echo THEME_PANEL_COLOUR; ?>-700" aria-hidden="true"></i>
                                    </span>
                                    <p class="text-sm text-gray-700 dark:text-white ml-2 font-semibold">
                                        <?php echo __('Panel:Views_Page') ?> <?php $pageviews = get_user_statistics_views_pages($_SESSION['id']);
        echo $pageviews; ?>
                                    </p>
                                </div>
                                <div class="mt-6 md:mt-0 text-black dark:text-white font-bold text-xl">
                                    <span class="text-xs text-gray-400">
                                        <?php echo __('Panel:Views_WebsiteTotal') ?> <?php $totalpageviews = get_total_statistics_views_pages();
        echo $totalpageviews; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="w-full h-3 bg-gray-100 dark:bg-neutral-600 absolute bottom-0">
                                <div style="width:<?php if ($pageviews == 0) {
            echo 0;
        } else {
            echo($pageviews / $totalpageviews) * 100;
        } ?>%" class="h-full text-center text-xs text-white bg-<?php echo THEME_PANEL_COLOUR; ?>-400 dark:bg-<?php echo THEME_PANEL_COLOUR; ?>-900">
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="flex mt-4">
                    <div class="shadow-md rounded-xl w-full bg-white dark:bg-neutral-800 relative overflow-hidden">
                        <a class="w-full h-full block">
                            <div class="flex items-center justify-between px-4 py-6 space-x-4">
                                <div class="flex items-center">
                                    <span class="rounded-full relative pl-3 pr-2 py-2 bg-<?php echo THEME_PANEL_COLOUR; ?>-100 dark:bg-<?php echo THEME_PANEL_COLOUR; ?>-300">
                                        <i class="fa-solid fa-chart-line text-<?php echo THEME_PANEL_COLOUR; ?>-500 dark:text-<?php echo THEME_PANEL_COLOUR; ?>-700" aria-hidden="true"></i>
                                    </span>
                                    <p class="text-sm text-gray-700 dark:text-white ml-2 font-semibold">
                                        <?php echo __('Panel:Views_Article') ?> <?php $articleviews = get_user_statistics_views_articles($_SESSION['id']);
        echo $articleviews; ?>
                                    </p>
                                </div>
                                <div class="mt-6 md:mt-0 text-black dark:text-white font-bold text-xl">
                                    <span class="text-xs text-gray-400">
                                        <?php echo __('Panel:Views_WebsiteTotal') ?> <?php $totalarticleviews = get_total_statistics_views_articles();
        echo $totalarticleviews; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="w-full h-3 bg-gray-100 dark:bg-neutral-600 absolute bottom-0">
                                <div style="width:<?php if ($articleviews == 0) {
            echo 0;
        } else {
            echo($articleviews / $totalarticleviews) * 100;
        } ?>%" class="h-full text-center text-xs text-white bg-<?php echo THEME_PANEL_COLOUR; ?>-400 dark:bg-<?php echo THEME_PANEL_COLOUR; ?>-600">
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>
<?php
    } else {
        header('Location: '.CONFIG_INSTALL_URL.'/panel/account/signin/verify?type=2&username='.get_user_username($_SESSION['id']));
    }
    ob_end_flush();
?>