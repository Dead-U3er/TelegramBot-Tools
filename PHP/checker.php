<?php
/**
  Coded by U3er
  V1.0
 * 
 * https://github.com/Dead-U3er
 * https://T.me/Dead_U3er
 * https://T.me/Good-U3er
 * 
   ⌜ Coded with love ⌟
*/
$valid_tokens = [];

function main_check($address) {
    global $valid_tokens;
    $correct_count = 0;
    $incorrect_count = 0;
    $response = [];

    if (($file = fopen($address, 'r')) !== false) {
        while (($line = fgets($file)) !== false) {
            $line = trim($line);
            $url = "https://api.telegram.org/bot{$line}/getme";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $api_response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($api_response, true);

            if (isset($data['ok']) && $data['ok'] === true) {
                $response['tokens'][] = [
                    'status' => 'valid',
                    'token' => $line
                ];
                $correct_count++;
                $valid_tokens[] = $line;
            } else {
                $response['tokens'][] = [
                    'status' => 'invalid',
                    'token' => $line
                ];
                $incorrect_count++;
            }
        }
        fclose($file);

        $response['summary'] = [
            'correct_tokens' => $correct_count,
            'incorrect_tokens' => $incorrect_count
        ];
    } else {
        $response['error'] = "Unable to open the file.";
    }

    echo json_encode($response);
}

function type_getme($name) {
    global $valid_tokens;
    $response = [];

    if (($valid_file = fopen($name, 'w')) !== false) {
        foreach ($valid_tokens as $token) {
            $token = trim($token);
            $url = "https://api.telegram.org/bot{$token}/getme";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $api_response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($api_response, true);

            if (isset($data['ok']) && $data['ok'] === true) {
                $bot_info = [
                    'ID Bot' => $data['result']['id'],
                    'First name' => $data['result']['first_name'],
                    'Username' => '@' . $data['result']['username'],
                    'Can join groups' => $data['result']['can_join_groups'] ? 'true' : 'false',
                    'Can read all group messages' => $data['result']['can_read_all_group_messages'] ? 'true' : 'false',
                    'Supports inline queries' => $data['result']['supports_inline_queries'] ? 'true' : 'false',
                    'Can connect to business' => isset($data['result']['can_connect_to_business']) ? ($data['result']['can_connect_to_business'] ? 'true' : 'false') : 'N/A'
                ];

                fwrite($valid_file, "# ------------------------------ #\n");
                fwrite($valid_file, "{$token}\n");
                foreach ($bot_info as $key => $value) {
                    fwrite($valid_file, "{$key} : {$value}\n");
                }
                fwrite($valid_file, "# ------------------------------ #\n\n");

                $response['tokens'][] = [
                    'token' => $token,
                    'info' => $bot_info
                ];
            }
        }
        fclose($valid_file);
    } else {
        $response['error'] = "Unable to open the file for writing.";
    }

    echo json_encode($response);
}

function type_webhook($name) {
    global $valid_tokens;
    $response = [];

    if (($valid_file = fopen($name, 'w')) !== false) {
        foreach ($valid_tokens as $token) {
            $token = trim($token);
            $url = "https://api.telegram.org/bot{$token}/getwebhookinfo";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $api_response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($api_response, true);

            if (isset($data['ok']) && $data['ok'] === true) {
                $webhook_info = [
                    'URL' => isset($data['result']['url']) ? $data['result']['url'] : "Empty",
                    'Has custom certificate' => isset($data['result']['has_custom_certificate']) ? ($data['result']['has_custom_certificate'] ? 'true' : 'false') : 'N/A',
                    'Pending update count' => isset($data['result']['pending_update_count']) ? $data['result']['pending_update_count'] : 'N/A'
                ];

                fwrite($valid_file, "# ------------------------------ #\n");
                fwrite($valid_file, "{$token}\n");
                foreach ($webhook_info as $key => $value) {
                    fwrite($valid_file, "{$key} : {$value}\n");
                }
                fwrite($valid_file, "# ------------------------------ #\n\n");

                $response['tokens'][] = [
                    'token' => $token,
                    'webhook_info' => $webhook_info
                ];
            }
        }
        fclose($valid_file);
    } else {
        $response['error'] = "Unable to open the file for writing.";
    }

    echo json_encode($response);
}

function deletewebhook($token_bot) {
    $url = "https://api.telegram.org/bot{$token_bot}/deletewebhook";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    $result = [];

    if (isset($data['ok']) && $data['ok'] === true) {
        $result['description'] = $data['description'];
    } else {
        $result['error'] = isset($data['description']) ? $data['description'] : "Failed to delete webhook.";
    }

    echo json_encode($result);
}

function setwebhook($token, $url) {
    $api_url = "https://api.telegram.org/bot{$token}/setwebhook?url={$url}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    $result = [];

    if (isset($data['ok']) && $data['ok'] === true) {
        $result['description'] = $data['description'];
        $result['url'] = $url;
    } else {
        $result['error'] = isset($data['description']) ? $data['description'] : "Failed to set webhook.";
    }

    echo json_encode($result);
}

function output($name) {
    global $valid_tokens;
    $response = [];

    if (($valid_file = fopen($name, 'w')) !== false) {
        foreach ($valid_tokens as $token) {
            fwrite($valid_file, $token . "\n");
        }
        fclose($valid_file);
        $response['message'] = "Tokens successfully written to the file.";
    } else {
        $response['error'] = "Unable to open the file for writing.";
    }

    echo json_encode($response);
}

if (isset($_GET['type']) && strtolower($_GET['type']) === 'checker') {
    if (isset($_GET['address'])) {
        main_check($_GET['address']);
        if (isset($_GET['type_out']) && isset($_GET['output'])) {
            if (strtolower($_GET['type_out']) === 'getme') {
                type_getme($_GET['output']);
            } elseif (strtolower($_GET['type_out']) === 'webhook') {
                type_webhook($_GET['output']);
            } else {
                output($_GET['output']);
            }
        }
    }
} elseif (isset($_GET['type']) && strtolower($_GET['type']) === 'webhook') {
    if (isset($_GET['type_webhook']) && isset($_GET['token']) && isset($_GET['address'])) {
        if (strtolower($_GET['type_webhook']) === 'set') {
            setwebhook($_GET['token'], $_GET['address']);
        } elseif (strtolower($_GET['type_webhook']) === 'delete') {
            deletewebhook($_GET['token']);
        }
    }
}

/**
  Coded by U3er
  V1.0
 * 
 * https://github.com/Dead-U3er
 * https://T.me/Dead_U3er
 * https://T.me/Good-U3er
 * https://u3er.xyz
 * 
   ⌜ Coded with love ⌟
*/
?>
