<?php
// Include the SDK using the Composer autoloader
require_once __DIR__ . '/autoload.php';

use Aws\Sqs\SqsClient;

$config = array(
    'version' => 'latest',
    'region' => 'us-west-1',
    'key' => 'AKIAJJWRPHCT6ZPPFIEQ',
    'secret' => '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'
);

$sqs = SqsClient::factory($config);

$queueUrl = 'https://sqs.us-west-1.amazonaws.com/104285975220/Subscriptions';

$result = $sqs->receiveMessage(array(
    'QueueUrl' => $queueUrl,
    'MaxNumberOfMessages' => 3
));

foreach( $result->get('Messages') as $message ){

    echo "<pre>";
    echo htmlentities( print_r( $message['Body'], true ) );
    echo "</pre>";

    echo "<hr />";
}

?>
