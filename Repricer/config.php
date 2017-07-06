<?php
/**
 * Contains configuration options
 *
 * @author      Jon Avery
 * @license     http://opensource.org/licenses/MIT MIT License
 */

//@TODO: Figure out what uses this and change all of them.
/**
 * The name of the SQS queue
 */

define('QUEUE_NAME', 'Subscriptions');

/**
 * AWS Credentials array for accessing the API
 *
 * It is a serialised array, which is then unserialised when used.
 */

define('AWS_CREDENTIALS', serialize(array(
    'region' => "us-west-1",
    'version' => "latest",
    'credentials' => array(
        'key'    => "AKIAJGYHSJYQ53ED3OKA",
        'secret' => "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"))));
