<?php
/**
 * Plugin Name: YCN s2member to ConvertKit (Form List ID:<FormId> Tag ID:<TagId>)
 * Plugin URI:  https://reedportal.com
 * Description: YCN s2member to ConvertKit plugin that will register users to a specific form list in your ConvertKit account based on the parameters set in the plugin file.
 * Version:     1.0.0
 * Author:      Bill Reed
 * Author URI:  http://reedportal.com
 * License:     MIT
 */
add_action('init', 'ycn_s2convert');
function ycn_s2convert()
    {
        if(!empty($_GET['ycn_s2convert']) && $_GET['ycn_s2convert'] === 'yes') 
            {
                if(!empty($_GET['user_email']) && !empty($_GET['user_first_name']))
                    {
                    $ckapikey = "<insert your ConvertKit API key here>";
                    $ckfname = $_GET['user_first_name'];
                    $ckemail = $_GET['user_email'];
                    $cktags = "<comma separated list of tag ids to use from your ConvertKit acct>";
                    $service_url = "https://api.convertkit.com/v3/forms/<ConvertKit FormID>/subscribe";
                    $curl = curl_init($service_url);
                    $curl_post_data = array(
                            "api_key" => $ckapikey,
                            "email" => $ckemail,
                            "tags" => $cktags,
                            "name" => $ckfname
                    );
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
                    $curl_response = curl_exec($curl);
                    if ($curl_response === false) {
                        $info = curl_getinfo($curl);
                        curl_close($curl);
                        die('error occured during curl exec. Additioanl info: ' . var_export($info));
                    }
                    curl_close($curl);
                    $decoded = json_decode($curl_response);
                    if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
                        die('error occured: ' . $decoded->response->errormessage);
                    }
                    echo 'Response OK';
                    var_export($decoded->response);
                    }
                exit;
            }
    }
