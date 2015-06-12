<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TitanFrameworkOptionCustomTable extends TitanFrameworkOption {

    public $defaultSecondarySettings = array(
        'placeholder' => '', // show this when blank
        'is_password' => false,
        'sanitize_callbacks' => array(),
        'maxlength' => '',
        'unit' => ''
    );

    public function cleanValueForSaving( $value ){
        $value = sanitize_text_field( $value );
        if( !empty( $this->settings['sanitize_callbacks'] ) ){
            foreach( $this->settings['sanitize_callbacks'] as $callback ){
                $value = call_user_func_array( $callback, array( $value, $this ) );
            }
        }

        return $value;
    }

    /*
     * Display for theme customizer
     */
    public function registerCustomizerControl( $wp_customize, $section, $priority = 1 ) {
        $wp_customize->add_control( new TitanFrameworkCustomizeControl( $wp_customize, $this->getID(), array(
            'label' => $this->settings['name'],
            'section' => $section->settings['id'],
            'settings' => $this->getID(),
            'description' => $this->settings['desc'],
            'priority' => $priority,
        ) ) );
    }

    public function display() {
        global $json_api;

        $available_controllers = $json_api->get_controllers();
        $active_controllers = explode(',', get_option('json_api_controllers', 'core'));

        if (count($active_controllers) == 1 && empty($active_controllers[0])) {
            $active_controllers = array();
        }

        if (!empty($_REQUEST['_wpnonce']) && wp_verify_nonce($_REQUEST['_wpnonce'], "update-options")) {

            if ((!empty($_REQUEST['action']) || !empty($_REQUEST['action2'])) && (!empty($_REQUEST['controller']) || !empty($_REQUEST['controllers']))) {
                if (!empty($_REQUEST['action'])) {
                    $action = $_REQUEST['action'];
                } else {
                    $action = $_REQUEST['action2'];
                }

                if (!empty($_REQUEST['controllers'])) {
                    $controllers = $_REQUEST['controllers'];
                } else {
                    $controllers = array(
                        $_REQUEST['controller']
                    );
                }

                foreach($controllers as $controller) {
                    if (in_array($controller, $available_controllers)) {
                        if ($action == 'activate' && !in_array($controller, $active_controllers)) {
                            $active_controllers[] = $controller;
                        }
                        elseif ($action == 'deactivate') {
                            $index = array_search($controller, $active_controllers);
                            if ($index !== false) {
                                unset($active_controllers[$index]);
                            }
                        }
                    }
                }

                $json_api->save_option('json_api_controllers', implode(',', $active_controllers));
            }

            if (isset($_REQUEST['json-api_base'])) {
                $json_api->save_option('json-api_base', $_REQUEST['json-api_base']);
                global $wp_rewrite;
                $wp_rewrite->flush_rules();
            }
        }

        echo "<h3>Controllers</h3>";

        ?>

        <table id="all-plugins-table" class="widefat">
            <thead>
                <tr>

                  <th class="manage-column" scope="col" colspan="2" style="text-align:right">Controller</th>
                  <th class="manage-column" scope="col">Description</th>
                </tr>
            </thead>

            <tbody class="plugins">

            <?php

            foreach ($available_controllers as $controller):

                $error = false;
                $active = in_array($controller, $active_controllers);
                $info = $json_api->controller_info($controller);
                //var_dump($info);
                if (is_string($info)) {
                    $active = false;
                    $error = true;
                    $info = array(
                      'name' => $controller,
                      'description' => "<p><strong>Error</strong>: $info</p>",
                      'methods' => array(),
                      'url' => null
                    );
                }

                ?>
                <tr class="<?php echo ($active ? 'active' : 'inactive'); ?>">
                    <th class="check-column" scope="row">

                    </th>
                    <td class="plugin-title">
                        <strong><?php echo $info['name']; ?></strong>
                        <div class="row-actions-visible">
                        <?php

                        if ($active) {
                            echo '<a href="' . wp_nonce_url('admin.php?page=my-app&tab=advanced-options&amp;action=deactivate&amp;controller=' . $controller, 'update-options') . '" title="' . __('Deactivate this controller') . '" class="edit">' . __('Deactivate') . '</a>';
                        } else if (!$error) {
                            echo '<a href="' . wp_nonce_url('admin.php?page=my-app&tab=advanced-options&amp;action=activate&amp;controller=' . $controller, 'update-options') . '" title="' . __('Activate this controller') . '" class="edit">' . __('Activate') . '</a>';
                        }

                        if (!empty($info['url'])) {
                            echo ' | ';
                            echo '<a href="' . $info['url'] . '" target="_blank">Docs</a></div>';
                        }

                        ?>
                    </td>
                    <td class="desc">
                        <p><?php echo $info['description']; ?></p>
                        <p> <?php
                        foreach($info['methods'] as $method) {
                            $url = $json_api->get_method_url($controller, $method);

                            if ($active) {
                                echo "<code><a href=\"$url\">$method</a></code> ";
                            } else {
                                echo "<code>$method</code> ";
                            }
                        } ?>
                        </p>
                    </td>
                </tr>
            <?php
            endforeach;
            ?>
            </tbody>
        </table>

        <br />

        <table class="form-table">
        <?php


        $site_url = get_site_url();
        $this->settings['desc'] = sprintf($this->settings['desc'], $site_url);


        $this->echoOptionHeader();
        printf("<code>%s/%s</code>",
            $site_url,
            esc_attr( get_option('json-api_base') ));


        $this->echoOptionFooter();

        ?>
        </table>

        <?php
    }
}