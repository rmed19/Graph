<?php

require_once 'tutorial_autoload.php';
require_once 'tutorial_custom_palette_palette.php';
$wikidata = include 'tutorial_wikipedia_data.php';

$graph = new \Ezc\Graph\Charts\BarChart();
$graph->palette = new tutorialCustomPalette();
$graph->title = 'Wikipedia articles';

// Add data
foreach ( $wikidata as $language => $data )
{
    $graph->data[$language] = new \Ezc\Graph\Datasets\ArrayDataSet( $data );
}
$graph->data['German']->displayType = ezcGraph::LINE;

$graph->options->fillLines = 210;

$graph->render( 400, 150, 'tutorial_custom_palette.svg' );

?>
