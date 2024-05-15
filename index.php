<?php

$page = $_GET['page'];

$dictionary = array( // we will use this dictionary to record the user's activity
    "date" => "",
    "user_id" => "", // the user ID is a way to determine if the user making the request is unique and distinguishable, we hash the user agent and the user's IP.
    "timestamp" => "",
    "user_agent" => "",
    "ip" => "",
    "request" => "",
    "response" => ""
);

function logger($stringToAdd) {
    $filePath = 'logs/log_'. date("Y-m-d") .'.json';
    // this function helps me format and save the search activity in the logs
    if (!file_exists($filePath)) {
        file_put_contents($filePath, "[\n", FILE_APPEND);
        file_put_contents($filePath, "]\n", FILE_APPEND);
    }

    // Read the current content of the file
    $lines = file($filePath);

    // If the file has more than one line, and the previous line is not the first line
    if (count($lines) > 1 && trim($lines[count($lines) - 2]) !== "[") {
        $beforeLastLineIndex = count($lines) - 2;
        $lines[$beforeLastLineIndex] = rtrim($lines[$beforeLastLineIndex], "\n") . ",\n";
    }

    // Insert the new string before the last line
    array_splice($lines, -1, 0, $stringToAdd . PHP_EOL);

    // Rewrite the file with the modified content
    file_put_contents($filePath, implode('', $lines));
}

// $jsonData = json_encode($dictionary);
// php -S 127.0.0.1:80 -t . 
// Function to display the content of a file
// this function is vulnerable to RFI
function display_file_content($page) {
    
    // Check if the file exists
    if (file_exists("pages/" . $page)) { // this part of the function will use a docker to fetch the files in it, the idea is to have a structure similar to a normal Linux OS without compromising the data on the real server.
        // Load the content of the file
        $content = file_get_contents("pages/" . $page);
		if ($content == "") {
			echo file_get_contents("pages/index.html");
		}
        echo $content;
    } else {
        $content = file_get_contents("pages/index.html");
        // Display the content
        echo $content;
    }

    global $dictionary;
    // Get the visitor's IP address
    $ip = $_SERVER['REMOTE_ADDR'];
    // Get the visitor's user agent
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    // Get the HTTP request
    $request = $_SERVER['REQUEST_URI'];
    // Get the current date and time
    $date = date("Y-m-d H:i:s");
    // Get the current timestamp
    $timestamp = time();
    $dictionary["date"] = $date;
    $dictionary["user_id"] = hash("sha256", $user_agent . $ip);
    $dictionary["timestamp"] = $timestamp;
    $dictionary["user_agent"] = $user_agent;
    $dictionary["ip"] = $ip;
    $dictionary["request"] = $request;
    $dictionary["response"] = $content;

    logger(json_encode($dictionary));
}

// Call the function to display the content of the file specified in ?page=
display_file_content($page);

?>
