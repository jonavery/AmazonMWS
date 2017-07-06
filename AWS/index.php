<?php

/**
 * The demo page with bootstrap front end, also handles upload of competitor offers and queueing
 *
 * @author      Jon Avery
 * @license     http://opensource.org/licenses/MIT MIT License
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/autoload.php';

use jonavery\AmazonMWS\Message;
use jonavery\AmazonMWS\Queue;

// Array of messages to be displayed to the user.
$warnings = array();

if ( !empty($_FILES)) {
    // check number of files to upload
    $number_of_images = count($_FILES['images']['name']);

    // Only upload a max of 10 files
    if ($number_of_images > 10) {
        $warnings[] = array(
            'class' => 'alert-danger',
            'text' => 'Too many images, please upload a maximum of 10 images.'
        );
    } else {
        $successes = 0;

        // For each upload, check if an image and valid etc.
        for ($i = 0; $i < $number_of_images; $i++) {
            if ($_FILES['images']['error'][$i] > 0) {
                $warnings[] = array('class' => 'alert-danger', 'text' => 'Error uploading file.');
            } elseif ( !filesize($_FILES['images']['tmp_name'][$i])) {
                $warnings[] = array('class' => 'alert-danger', 'text' => 'Error uploading file.');
            } elseif ($_FILES['images']['type'][$i] != 'image/png' and $_FILES['images']['type'][$i] != 'image/jpeg') {
                $warnings[] = array('class' => 'alert-danger', 'text' => 'Invalid file type.');
            } elseif ($_FILES['images']['size'][$i] > 2000000) {
                $warnings[] = array('class' => 'alert-danger', 'text' => 'File too big.');
            } else {
                // Create a new filename for the uploaded image and move it there
                $extension = $_FILES['images']['type'][$i] == 'image/png' ? '.png' : '.jpg';
                $new_name = uniqid() . $extension;
                if ( !move_uploaded_file($_FILES['images']['tmp_name'][$i], __DIR__ . '/images/queued/' . $new_name)) {
                    $warnings[] = array('class' => 'alert-danger', 'text' => 'Error uploading file.');
                } else {
                    // Create a new message with processing instructions and push to SQS queue
                    $message = new Message(array(
                        'input_file_path' => __DIR__ . '/images/queued/' . $new_name,
                        'output_file_path' => __DIR__ . '/images/watermarked/' . $new_name
                    ));
                    $queue = new Queue(QUEUE_NAME, unserialize(AWS_CREDENTIALS));
                    if ($queue->send($message)) {
                        $successes++;
                    } else {
                        $warnings[] = array('class' => 'alert-danger', 'text' => 'Error adding file to queue.');
                    }
                }
            }
        }

        if ($successes > 0) {
            $warnings[] = array('class' => 'alert-success', 'text' => "$successes images uploaded successfully.");
            $warnings[] = array('class' => 'alert-info', 'text' => "Uploaded images added to queue...");
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Klasrun Repricer</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>
                Amazon Repricer
                <small>Created by <a href="https://github.com/jonavery" target="_blank">Jon Avery</a></small>
            </h1>
        </div>

        <div class="alert alert-info">
            This is a repricer for matching competitor pricing on Amazon. Source code can be found at 
            <a href="https://github.com/jonavery/AmazonMWS/tree/master/AWS" class="alert-link" target="_blank">
                https://github.com/jonavery/AmazonMWS/tree/master/AWS
            </a>
        </div>

        <?php foreach ($warnings as $warning) : ?>
            <?php if ( !empty($warning)) : ?>
                <div class="alert <?php echo $warning['class']; ?>"><?php echo $warning['text']; ?></div>
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="row">
            <div class="col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">View queue of Amazon items waiting to be repriced.</h3>
                    </div>

                    <div class="panel-body">
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="imageUpload">File input</label>
                                <input type="file" multiple="multiple" id="imageUpload" name="images[]">
                                <p class="help-block">The text is smaller and greyed out.</p>
                            </div>
                            <button type="submit" class="btn btn-default">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Listings</h3>
                    </div>

                    <div class="panel-body">
                        <div class="alert alert-warning">
                            <strong>Alert!</strong> These items are being undercut by 10% or more:
                        </div>

                        <div class="watermarked-images">
                            Loading ...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script>
    //@TODO: Change this from polling for images to polling for new offers.
    //@TODO: Display offers waiting to be matched.
        $(document).ready(function() {
            // Poll the server every 2 seconds to look for newly uploaded and processed files.
            setInterval(function() {
                $.ajax({
                    url: 'images.php',
                    method: 'get',
                    dataType: 'json'
                })
                    .done(function(data) {
                        // Display image files on page, show placeholder for unprocessed files.

                        var total = 0;
                        var output = '<div class="row">';

                        for (var i = 0; i < data.waiting.length; i++) {
                            if (data.waiting[i].indexOf(".jpg") > -1) {
                                output += '<div class="col-xs-6 col-xs-3">' +
                                '<img class="img-responsive" src="images/placeholder.png">' +
                                '</div>';
                                total++;
                            }
                        }

                        for (var i = 0; i < data.watermarked.length; i++) {
                            if (data.watermarked[i].indexOf(".jpg") > -1) {
                                output += '<div class="col-xs-6 col-xs-3">' +
                                '<img class="img-responsive" src="images/watermarked/' + data.watermarked[i] + '">' +
                                '</div>';
                                total++;
                            }
                        }

                        output += '</div>';

                        if (total <= 0) {
                            output = 'No images yet...';
                        }

                        $('.watermarked-images').html(output);
                    });
            }, 2000);
        });
    </script>
</body>
</html>
