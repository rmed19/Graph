<?php

require_once 'tutorial_autoload.php';
$wikidata = include 'tutorial_wikipedia_data.php';

$graph = new \Ezc\Graph\Charts\RadarChart();
$graph->title = 'Wikipedia articles';
$graph->options->fillLines = 220;

// Add data
foreach ( $wikidata as $language => $data )
{
    $graph->data[$language] = new \Ezc\Graph\Datasets\ArrayDataSet( $data );
    $graph->data[$language][] = reset( $data );
}

$graph->render( 400, 150, 'tutorial_radar_chart.svg' );

?>
