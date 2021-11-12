<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/common/global_public.php';

const THEME_DIRECTORY = '/themes/';

function get_page_id_from_url($uri)
{
    $uri = checkInput('HTML', $uri);

    global $conn;

    $query = 'SELECT `id` FROM `'.DATABASE_PREFIX."pages` WHERE `url` = '".$uri."';";
    $rs = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($rs);

    return $row['id'];
}

function getdata($pageID): array
{
    $pageData['title'] = get_page_title($pageID);
    $pageData['content'] = get_page_content($pageID);
    $pageData['author']['id'] = get_page_last_edit_user_id($pageID);
    $pageData['section']['navigation'] = file_get_contents($_SERVER['DOCUMENT_ROOT'].THEME_DIRECTORY.THEME_SLUG.'/navigation.tt');
    $pageData['section']['footer'] = file_get_contents($_SERVER['DOCUMENT_ROOT'].THEME_DIRECTORY.THEME_SLUG.'/footer.tt');

    return $pageData;
}

function replacedata($pageOutput, $pageData, $themeData): string
{
    if (CONFIG_DEBUG) {
        $starttime = microtime(true);
    }
    // Sections
    $pageOutput = str_replace('{{section:navigation}}', $pageData['section']['navigation'], $pageOutput);
    $pageOutput = str_replace('{{section:footer}}', $pageData['section']['footer'], $pageOutput);
    // Page Data
    $pageOutput = str_replace('{{page:title}}', $pageData['title'], $pageOutput);
    $pageOutput = str_replace('{{page:content}}', $pageData['content'], $pageOutput);
    $pageOutput = str_replace('{{page:author:name}}', get_user_fullname($pageData['author']['id']), $pageOutput);
    $pageOutput = str_replace('{{article:title}}', $pageData['title'], $pageOutput);
    $pageOutput = str_replace('{{article:content}}', $pageData['content'], $pageOutput);
    $pageOutput = str_replace('{{article:author:name}}', get_user_fullname($pageData['author']['id']), $pageOutput);
    // Config values
    $pageOutput = str_replace('{{config:basedir}}', CONFIG_INSTALL_URL, $pageOutput);
    $pageOutput = str_replace('{{config:timezone}}', CONFIG_SITE_TIMEZONE, $pageOutput);
    $pageOutput = str_replace('{{config:sitename}}', CONFIG_SITE_NAME, $pageOutput);
    $pageOutput = str_replace('{{config:description}}', CONFIG_SITE_DESCRIPTION, $pageOutput);
    $pageOutput = str_replace('{{config:keywords}}', CONFIG_SITE_KEYWORDS, $pageOutput);
    $pageOutput = str_replace('{{config:charset}}', CONFIG_SITE_CHARSET, $pageOutput);
    // Images
    $pageOutput = str_replace('{{image:logo}}', '/assets/storage/images/logo.png', $pageOutput);
    $pageOutput = str_replace('{{image:icon}}', '/assets/storage/images/icon.png', $pageOutput);
    // Colours
    $cd = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/themes/'.THEME_SLUG.'/'.THEME_COLOUR_SCHEME.'.tc');
    $cd = json_decode($cd);
    $pageOutput = str_replace('{{colour:text}}', $cd->colours->text, $pageOutput);
    $pageOutput = str_replace('{{colour:bg}}', $cd->colours->bg, $pageOutput);
    $pageOutput = str_replace('{{colour:link}}', $cd->colours->link->default, $pageOutput);
    $pageOutput = str_replace('{{colour:link:hover}}', $cd->colours->link->hover, $pageOutput);
    $pageOutput = str_replace('{{colour:link:focus}}', $cd->colours->link->focus, $pageOutput);
    $pageOutput = str_replace('{{colour:navbar:text}}', $cd->colours->navbar->text->default, $pageOutput);
    $pageOutput = str_replace('{{colour:navbar:text:hover}}', $cd->colours->navbar->text->hover, $pageOutput);
    $pageOutput = str_replace('{{colour:navbar:text:focus}}', $cd->colours->navbar->text->focus, $pageOutput);
    $pageOutput = str_replace('{{colour:navbar:bg}}', $cd->colours->navbar->bg->default, $pageOutput);
    $pageOutput = str_replace('{{colour:navbar:bg:hover}}', $cd->colours->navbar->bg->hover, $pageOutput);
    $pageOutput = str_replace('{{colour:navbar:bg:focus}}', $cd->colours->navbar->bg->focus, $pageOutput);
    $pageOutput = str_replace('{{colour:navbar:button:text}}', $cd->colours->navbar->button->text->default, $pageOutput);
    $pageOutput = str_replace('{{colour:navbar:button:text:hover}}', $cd->colours->navbar->button->text->hover, $pageOutput);
    $pageOutput = str_replace('{{colour:navbar:button:text:focus}}', $cd->colours->navbar->button->text->focus, $pageOutput);
    $pageOutput = str_replace('{{colour:navbar:button:bg}}', $cd->colours->navbar->button->bg->default, $pageOutput);
    $pageOutput = str_replace('{{colour:navbar:button:bg:hover}}', $cd->colours->navbar->button->bg->hover, $pageOutput);
    $pageOutput = str_replace('{{colour:navbar:button:bg:focus}}', $cd->colours->navbar->button->bg->focus, $pageOutput);
    $pageOutput = str_replace('{{colour:footer:text}}', $cd->colours->footer->text->default, $pageOutput);
    $pageOutput = str_replace('{{colour:footer:text:hover}}', $cd->colours->footer->text->hover, $pageOutput);
    $pageOutput = str_replace('{{colour:footer:text:focus}}', $cd->colours->footer->text->focus, $pageOutput);
    $pageOutput = str_replace('{{colour:footer:button:text}}', $cd->colours->footer->button->text->default, $pageOutput);
    $pageOutput = str_replace('{{colour:footer:button:text:hover}}', $cd->colours->footer->button->text->hover, $pageOutput);
    $pageOutput = str_replace('{{colour:footer:button:text:focus}}', $cd->colours->footer->button->text->focus, $pageOutput);
    $pageOutput = str_replace('{{colour:footer:button:bg}}', $cd->colours->footer->button->bg->default, $pageOutput);
    $pageOutput = str_replace('{{colour:footer:button:bg:hover}}', $cd->colours->footer->button->bg->hover, $pageOutput);
    $pageOutput = str_replace('{{colour:footer:button:bg:focus}}', $cd->colours->footer->button->bg->focus, $pageOutput);
    $pageOutput = str_replace('{{colour:footer:bg}}', $cd->colours->footer->bg->default, $pageOutput);
    $pageOutput = str_replace('{{colour:footer:bg:hover}}', $cd->colours->footer->bg->hover, $pageOutput);
    $pageOutput = str_replace('{{colour:footer:bg:focus}}', $cd->colours->footer->bg->focus, $pageOutput);
    $pageOutput = str_replace('{{colour:button:text}}', $cd->colours->button->text->default, $pageOutput);
    $pageOutput = str_replace('{{colour:button:text:hover}}', $cd->colours->button->text->hover, $pageOutput);
    $pageOutput = str_replace('{{colour:button:text:focus}}', $cd->colours->button->text->focus, $pageOutput);
    $pageOutput = str_replace('{{colour:button:bg}}', $cd->colours->button->bg->default, $pageOutput);
    $pageOutput = str_replace('{{colour:button:bg:hover}}', $cd->colours->button->bg->hover, $pageOutput);
    $pageOutput = str_replace('{{colour:button:bg:focus}}', $cd->colours->button->bg->focus, $pageOutput);
    // CDN
    if ($themeData->{'theme'}->{'framework'} == 'tailwind') {
        $cdn_css = 'https://unpkg.com/tailwindcss@2.2.16/dist/tailwind.min.css';
        $cdn_js = 'https://unpkg.com/alpinejs@2.8.2/dist/alpine.js';
    } elseif ($themeData->{'theme'}->{'framework'} == 'bootstrap') {
        $cdn_css = 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css';
        $cdn_js = 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js';
    } elseif ($themeData->{'theme'}->{'framework'} == 'materialize') {
        $cdn_css = 'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css';
        $cdn_js = 'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js';
    } else {
        if (CONFIG_DEBUG) {
            $cdn_css = '';
            $cdn_js = '';
            log_console('Saturn][Resource Loader][G-Tags', 'Unable to load framework or a framework may not be assigned.');
        }
    }
    $pageOutput = str_replace('{{cdn:css}}', $cdn_css, $pageOutput);
    $pageOutput = str_replace('{{cdn:js}}', $cdn_js, $pageOutput);
    // Config
    $pageOutput = str_replace('{{config:slug}}', THEME_SLUG, $pageOutput);
    $pageOutput = str_replace('{{config:name}}', $themeData->{'theme'}->{'name'}, $pageOutput);
    $pageOutput = str_replace('{{config:colourscheme}}', THEME_COLOUR_SCHEME, $pageOutput);
    $pageOutput = str_replace('{{config:font}}', THEME_FONT, $pageOutput);
    $pageOutput = str_replace('{{config:panelfont}}', THEME_PANEL_FONT, $pageOutput);
    $pageOutput = str_replace('{{config:panelcolour}}', THEME_PANEL_COLOUR, $pageOutput);
    $pageOutput = str_replace('{{config:socialimage}}', THEME_SOCIAL_IMAGE, $pageOutput);

    if (CONFIG_DEBUG) {
        log_console('Saturn][Resource Loader][G-Tags', 'Converted 60 Global Tags in '.(number_format(microtime(true) - $starttime, 5)).' seconds.');
    }

    return $pageOutput;
}

$pageID = get_page_id_from_url($pageuri);

$data = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].THEME_DIRECTORY.THEME_SLUG.'/theme.json'));

$file = strtolower(get_page_template($pageID));
$pageOutput = file_get_contents($_SERVER['DOCUMENT_ROOT'].THEME_DIRECTORY.THEME_SLUG.'/'.$file.'.tt');

$pageData = getdata($pageID);
echo replacedata($pageOutput, $pageData, $data);