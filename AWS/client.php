<?php
// Include the SDK using the Composer autoloader
require_once 'vendor/autoload.php';

use Aws\Sqs\SqsClient;

$client = SqsClient::factory(array(
    'key' => 'AKIAJJWRPHCT6ZPPFIEQ',
    'secret' => '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',
    'region' => 'us-west-1'
));

$queueURL = 'https://sqs.us-west-1.amazonaws.com/104285975220/Subscriptions';

$result = $client->receiveMessage(array(
    'QueueURL' => $queueURL,
    'MaxNumberOfMessages' => 3
));

foreach( $result->get('Messages') as $message ){

    echo "<pre>";
    echo htmlentities( print_r( $message['Body'], true ) );
    echo "</pre>";

    echo "<hr />";
}

?>
