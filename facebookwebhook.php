<?php

// Get the request data from Facebook
$verify_token = $_GET['hub_verify_token'];
$challenge = $_GET['hub_challenge'];
$event_data = file_get_contents('php://input');
$events = json_decode($event_data, true);

// Check if the request is for verifying the webhook subscription
if ($verify_token === 'your_verification_token') {
    echo $challenge;
    die();
}

// Process the incoming events
foreach ($events['entry'] as $entry) {
    foreach ($entry['messaging'] as $event) {
        // Handle different types of events (e.g. message, postback, etc.)
        if ($event['message']) {
            // Handle incoming message event
            $sender_id = $event['sender']['id'];
            $message_text = $event['message']['text'];
            // Send a response message
            send_message($sender_id, "Received: $message_text");
        }
    }
}

// Function to send a message to the user
function send_message($recipient_id, $message_text) {
    $access_token = 'your_facebook_page_access_token';
    $request_url = "https://graph.facebook.com/v13.0/me/messages?access_token=$access_token";
    $request_body = [
        'recipient' => ['id' => $recipient_id],
        'message' => ['text' => $message_text]
    ];
    $ch = curl_init($request_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_body));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_exec($ch);
    curl_close($ch);
}
