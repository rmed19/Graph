<?php

require_once 'tutorial_autoload.php';
$wikidata = include 'tutorial_wikipedia_data.php';

$graph = new \Ezc\Graph\Charts\LineChart();
$graph->title = 'Wikipedia articles';

$graph->xAxis->axisLabelRenderer = new \Ezc\Graph\Renderer\AxisRotatedLabelRenderer();
$graph->xAxis->axisLabelRenderer->angle = 45;
$graph->xAxis->axisSpace = .2;

// Add data
foreach ( $wikidata as $language => $data )
{
    $graph->data[$language] = new \Ezc\Graph\Datasets\ArrayDataSet( $data );
}
$graph->data['German']->displayType = ezcGraph::LINE;

$graph->options->fillLines = 210;

$graph->render( 400, 150, 'tutorial_rotated_labels.svg' );

?>
