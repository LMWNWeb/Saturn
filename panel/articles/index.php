<?php session_start(); ?><!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            include_once(__DIR__.'/../../assets/common/global_private.php');
            include_once(__DIR__ . '/../../assets/common/panel/vendors.php');
            include_once(__DIR__.'/../../assets/common/panel/theme.php');
            include_once(__DIR__.'/../../assets/common/processes/pages.php');
            include_once(__DIR__.'/../../assets/common/processes/gui/pages.php');
        ?>
        <title>Articles - Saturn Panel</title>

        <?php
            if(isset($_GET['error'])) {
                $error = $_GET['error'];
                if ($error == 'permission') {
                    $errorMsg = "Error: You do not have the required permissions to do that.";
                } else if ($error == 'new') {
                    $errorMsg = "Error: There was a problem creating a new article.";
                } else {
                    $errorMsg = "Error: An unknown error occurred.";
                }
            }

            if(CONFIG_ARTICLE_APPROVALS) {
                $yellowMsg = '<span class="text-yellow-500">Yellow:</span> Pending Approval<br>';
            } else {
                $yellowMsg = '<span class="text-yellow-500">Yellow:</span> Pending Publication<br>';
            }

            $key = '<div class="text-xs text-left absolute bottom-2 left-0 h-16 w-30 p-2 bg-gray-50 rounded">
                                                                <span class="text-red-500">Red:</span> No Content<br>
                                                                '.$yellowMsg.'
                                                                <span class="text-green-500">Green:</span> Currently Live<br>
                                                                <i>You can edit pending pages.</i>
                                                            </div>';

            if(isset($_POST['publish'])) {
                $id = checkInput('DEFAULT', $_GET['id']);
                if(get_article_author_id($id) == $_SESSION['id']) {
                    if(update_article_status($id, 'PUBLISHED')) {
                        $successMsg = 'Article published.';
                    } else {
                        $errorMsg = 'An error occurred.';
                    }
                } else {
                    $errorMsg = 'You can\'t publish articles that you don\'t own.';
                }
            }

            if(isset($_POST['delete'])) {
                $id = checkInput('DEFAULT', $_GET['id']);
                if(get_article_author_id($id) == $_SESSION['id']) {
                    if(update_article_status($id, 'DELETED') && update_article_content($id, '') && update_article_references($id, '')) {
                        $successMsg = 'Article deleted.';
                    } else {
                        $errorMsg = 'An error occurred.';
                    }
                } else {
                    $errorMsg = 'You can\'t delete articles that you don\'t own.';
                }
            }

            if(isset($_POST['hide'])) {
                $id = checkInput('DEFAULT', $_GET['id']);
                if(get_article_author_id($id) == $_SESSION['id']) {
                    if(update_article_status($id, 'UNPUBLISHED')) {
                        $successMsg = 'Article hidden.';
                    } else {
                        $errorMsg = 'An error occurred.';
                    }
                } else {
                    $errorMsg = 'You can\'t hide articles that you don\'t own.';
                }
            }

            if(isset($_POST['savesettings'])) {
                $id = checkInput('DEFAULT', $_GET['id']);
                $author = checkInput('DEFAULT', $_POST['users']);
                if(get_article_author_id($id) == $_SESSION['id']) {
                    if(update_article_author($id, $author)) {
                        $successMsg = 'Article settings updated.';
                    } else {
                        $errorMsg = 'An error occurred.';
                    }
                } else {
                    $errorMsg = 'You can\'t change settings for articles that you don\'t own.';
                }
            }

            ?>

    </head>
    <body class="mb-8">
        <?php include_once(__DIR__.'/../../assets/common/panel/navigation.php'); ?>

        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold leading-tight text-gray-900">Articles</h1>
            </div>
        </header>

        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <?php
            if(isset($errorMsg)){
                alert('ERROR',$errorMsg);
            }
            unset($errorMsg);
            if(isset($successMsg)){
                alert('SUCCESS',$successMsg);
            }
            unset($successMsg);
            ?>
            <div class="px-4 py-6 sm:px-0">
                <?php
                $role = get_user_roleID($_SESSION['id']);
                $i=1;
                $article = get_article_title($i);
                while($article != null) {
                    if(get_article_author_id($i) == $_SESSION['id'] && get_article_status($i) != 'DELETED') {
                        echo '            <div>
                                <div name="' . $article . '" id="' . $article . '">
                                    <div class="flex-0 relative pt-1 mb-2">
                                        <div class="flex items-center justify-between">
                                            <h1 class="text-xl font-bold leading-tight text-gray-900 mr-2">' . $article . '</h1>
                                            <div>';
                        if (get_article_status($i) == 'UNPUBLISHED') {
                            $statusColour = 'red';
                            $status = 'Unpublished';
                        } else if (get_article_status($i) == 'PENDING') {
                            $statusColour = 'yellow';
                            $status = 'Pending';
                        } else if (get_article_status($i) == 'REJECTED') {
                            $statusColour = 'red';
                            $status = 'Rejected';
                        } else if (get_article_status($i) == 'PUBLISHED') {
                            $statusColour = 'green';
                            $status = 'Published';
                        } else {
                            $statusColour = 'gray';
                            $status = 'Unknown Status';
                        }
                        echo '<span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-' . $statusColour . '-500 bg-' . $statusColour . '-200">' . $status . '</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-2 flex-grow flex w-30 h-8 space-x-2">
                                        <a href="' . CONFIG_INSTALL_URL . '/panel/articles/editor/?articleID=' . $i . '" class="hover:shadow-lg cursor-pointer w-full flex items-center justify-center px-8 py-1 border border-transparent text-base font-medium rounded-md text-' . THEME_PANEL_COLOUR . '-700 bg-' . THEME_PANEL_COLOUR . '-100 hover:bg-' . THEME_PANEL_COLOUR . '-200 transition-all duration-200 md:py-1 md:text-rg md:px-10 h-full">
                                            <i class="fas fa-pencil-alt" aria-hidden="true"></i>&nbsp;Edit
                                        </a>
                                        ';
                        if ($status != 'Published' && CONFIG_ARTICLE_APPROVALS === false) {
                            echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '/?id=' . $i . '" method="post" x-data="{ open: false }">
                                            <a @click="open = true" class="hover:shadow-lg cursor-pointer w-full flex items-center justify-center px-8 py-1 border border-transparent text-base font-medium rounded-md text-' . THEME_PANEL_COLOUR . '-700 bg-' . THEME_PANEL_COLOUR . '-100 hover:bg-' . THEME_PANEL_COLOUR . '-200 transition-all duration-200 md:py-1 md:text-rg md:px-10 h-full">
                                                <i class="fas fa-upload" aria-hidden="true"></i>&nbsp;Publish
                                            </a>
                                            ' . display_modal('green', 'Publish Article: ' . $article, 'Are you sure you want to publish this article?', '<div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse flex">
                                    <input type="submit" id="publish" name="publish" value="Publish Article" class="transition-all duration-200 hover:shadow-lg cursor-pointer w-full flex items-center justify-center px-8 py-1 border border-transparent text-base font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 md:py-1 md:text-rg md:px-10">
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <a @click="open=false" class="flex-grow transition-all duration-200 hover:shadow-lg cursor-pointer w-full flex items-center justify-center px-8 py-1 border border-transparent text-base font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 md:py-1 md:text-rg md:px-10">Cancel</a>
                                </div>') . '
                                        </form>';
                        } else if ($status != 'Published' && CONFIG_ARTICLE_APPROVALS === true) {
                            echo '<a @click="open = true" class="hover:shadow-lg cursor-pointer w-full flex items-center justify-center px-8 py-1 border border-transparent text-base font-medium rounded-md text-' . THEME_PANEL_COLOUR . '-700 bg-' . THEME_PANEL_COLOUR . '-100 hover:bg-' . THEME_PANEL_COLOUR . '-200 transition-all duration-200 md:py-1 md:text-rg md:px-10 h-full">
                                            <i class="fas fa-upload" aria-hidden="true"></i>&nbsp;Request Publication
                                        </a>';
                        } else if ($status == 'Published') {
                            echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '/?id=' . $i . '" method="post" x-data="{ open: false }">
                                            <a @click="open = true" class="hover:shadow-lg cursor-pointer w-full flex items-center justify-center px-8 py-1 border border-transparent text-base font-medium rounded-md text-' . THEME_PANEL_COLOUR . '-700 bg-' . THEME_PANEL_COLOUR . '-100 hover:bg-' . THEME_PANEL_COLOUR . '-200 transition-all duration-200 md:py-1 md:text-rg md:px-10 h-full">
                                                <i class="fas fa-eye-slash" aria-hidden="true"></i>&nbsp;Hide
                                            </a>
                                            ' . display_modal('red', 'Hide Article: ' . $article, 'Are you sure you want to hide this article from readers?<br><br>It will no longer be available on the website and it\'s link will no longer work.<br>You will need to publish the article again to regain access to these features.', '<div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse flex">
                                    <input type="submit" id="hide" name="hide" value="Hide Article" class="transition-all duration-200 hover:shadow-lg cursor-pointer w-full flex items-center justify-center px-8 py-1 border border-transparent text-base font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 md:py-1 md:text-rg md:px-10">
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <a @click="open=false" class="flex-grow transition-all duration-200 hover:shadow-lg cursor-pointer w-full flex items-center justify-center px-8 py-1 border border-transparent text-base font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 md:py-1 md:text-rg md:px-10">Cancel</a>
                                </div>') . '
                                        </form>';
                        }
                        else {
                            alert('ERROR', 'Unable to fetch approval status.');
                        }
                        $contents = 'Article Owner: '.display_user_list('SELECTME');
                        echo '          <form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '/?id=' . $i . '" method="post" x-data="{open: false}">
                                            <a @click="open = true" class="hover:shadow-lg cursor-pointer w-full flex items-center justify-center px-8 py-1 border border-transparent text-base font-medium rounded-md text-' . THEME_PANEL_COLOUR . '-700 bg-' . THEME_PANEL_COLOUR . '-100 hover:bg-' . THEME_PANEL_COLOUR . '-200 transition-all duration-200 md:py-1 md:text-rg md:px-10 h-full">
                                                <i class="fas fa-cogs" aria-hidden="true"></i>&nbsp;Settings
                                            </a>
                                        ' . display_modal_sidebar('Article Settings: ' . $article, $contents, '<span class="mx-6">WARNING: Check these settings are correct before you save, saving them may cause irreversable changes.</span><div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse flex">
                                    <input type="submit" id="savesettings" name="savesettings" value="Save" class="transition-all duration-200 hover:shadow-lg cursor-pointer w-full flex items-center justify-center px-8 py-1 border border-transparent text-base font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 md:py-1 md:text-rg md:px-10">
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <a @click="open=false" class="flex-grow transition-all duration-200 hover:shadow-lg cursor-pointer w-full flex items-center justify-center px-8 py-1 border border-transparent text-base font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 md:py-1 md:text-rg md:px-10">Cancel</a>
                                </div>') . '
                                        </form>
                                        <form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '/?id=' . $i . '" method="post" x-data="{ open: false }">
                                            <a @click="open = true" class="hover:shadow-lg cursor-pointer w-full flex items-center justify-center px-8 py-1 border border-transparent text-base font-medium rounded-md text-' . THEME_PANEL_COLOUR . '-700 bg-' . THEME_PANEL_COLOUR . '-100 hover:bg-' . THEME_PANEL_COLOUR . '-200 transition-all duration-200 md:py-1 md:text-rg md:px-10 h-full">
                                                <i class="fas fa-trash-alt" aria-hidden="true"></i>&nbsp;Delete
                                            </a>
                                            ' . display_modal('red', 'Delete Article: ' . $article, 'Are you sure you want to delete this article?', '<div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse flex">
                                    <input type="submit" id="delete" name="delete" value="Delete Article" class="transition-all duration-200 hover:shadow-lg cursor-pointer w-full flex items-center justify-center px-8 py-1 border border-transparent text-base font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 md:py-1 md:text-rg md:px-10">
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <a @click="open=false" class="flex-grow transition-all duration-200 hover:shadow-lg cursor-pointer w-full flex items-center justify-center px-8 py-1 border border-transparent text-base font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 md:py-1 md:text-rg md:px-10">Cancel</a>
                                </div>') . '
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <hr><br>';
                    }
                    unset($article);
                    $i++;
                    $article = get_article_title($i);
                }
                ?>
        </div>
    </body>
</html>