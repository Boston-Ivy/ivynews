<?php

function json_api_init() {

    $dir = json_api_dir();
    @include_once "$dir/singletons/api.php";
    @include_once "$dir/singletons/query.php";
    @include_once "$dir/singletons/introspector.php";
    @include_once "$dir/singletons/response.php";
    @include_once "$dir/models/post.php";
    @include_once "$dir/models/comment.php";
    @include_once "$dir/models/category.php";
    @include_once "$dir/models/tag.php";
    @include_once "$dir/models/author.php";
    @include_once "$dir/models/attachment.php";
    require_once dirname( __FILE__ ) . '/menu-icons/menu-icons.php';
    Menu_Icons::_load();
    global $json_api;

    add_filter('json_api_controllers', 'pimJsonApiController');
    add_filter('json_api_user_controller_path', 'setUserControllerPath');
    add_filter('json_api_menus_controller_path', 'setMenusControllerPath');

    $json_api = new JSON_API();

    json_api_user_checkAuthCookie($json_api);

}

function json_api_php_version_warning() {
    echo "<div id=\"json-api-warning\" class=\"updated fade\"><p>Sorry, JSON API requires PHP version 5.0 or greater.</p></div>";
}

function json_api_class_warning() {
    echo "<div id=\"json-api-warning\" class=\"error fade\"><p>WARNING: The plugin has conflicts with other plugins installed plugins. Please check if you already have activated JSON API, JSON REST API or another rest api plugin and deactivate it</p></div>";
}


function json_api_dir() {
    if (defined('JSON_API_DIR') && file_exists(JSON_API_DIR)) {
        return JSON_API_DIR;
    } else {
        return dirname(__FILE__);
    }
}

function pimJsonApiController($aControllers) {
    $aControllers[] = 'user';
    $aControllers[] = 'menus';
    return $aControllers;
}


function setUserControllerPath() {
    return dirname(__FILE__) . '/addons/user.php';
}

function setMenusControllerPath() {
    return dirname(__FILE__) . '/addons/menus.php';
}

function json_api_user_checkAuthCookie($json_api) {

    if ($json_api->query->cookie) {
        $user_id = wp_validate_auth_cookie($json_api->query->cookie, 'logged_in');
        if ($user_id) {
            $user = get_userdata($user_id);
            wp_set_current_user($user->ID, $user->user_login);
        }
    }
}
?>