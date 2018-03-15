<?php

require_once 'tutorial_autoload.php';

$graph = new \Ezc\Graph\Charts\PieChart();
$graph->palette = new \Ezc\Graph\Palette\EzGreen();
$graph->title = 'Access statistics';
$graph->legend = false;

$graph->driver = new ezcGraphGdDriver();
$graph->options->font = 'tutorial_font.ttf';

// Generate a Jpeg with lower quality. The default settings result in a image
// with better quality.
// 
// The reduction of the supersampling to 1 will result in no anti aliasing of
// the image. JPEG is not the optimal format for grapics, PNG is far better for
// this kind of images.
$graph->driver->options->supersampling = 1;
$graph->driver->options->jpegQuality = 100;
$graph->driver->options->imageFormat = IMG_JPEG;

$graph->data['Access statistics'] = new \Ezc\Graph\Datasets\ArrayDataSet( array(
    'Mozilla' => 19113,
    'Explorer' => 10917,
    'Opera' => 1464,
    'Safari' => 652,
    'Konqueror' => 474,
) );

$graph->render( 400, 200, 'tutorial_driver_gd.jpg' );

?>
