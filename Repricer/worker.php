<?php

/**
 * The queue worker script
 *
 *
 * @author      Jon Avery
 * @license     http://opensource.org/licenses/MIT MIT License
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/autoload.php';

use jonavery\AmazonMWS\Repricer\Queue;

// Instantiate queue with aws credentials from config.
$queue = new Queue(QUEUE_NAME, unserialize(AWS_CREDENTIALS));

// Continuously poll queue for new messages and process them.
while (true) {
    $message = $queue->receive();
    if ($message) {
        try {
            $message->process();
            $queue->delete($message);
        } catch (Exception $e) {
            $queue->release($message);
            echo $e->getMessage();
        }
    } else {
        // Wait 20 seconds if no jobs in queue to minimise requests to AWS API
        sleep(20);
    }
}
