<?php

require_once 'tutorial_autoload.php';
$wikidata = include 'tutorial_wikipedia_data.php';

$graph = new \Ezc\Graph\Charts\BarChart();
$graph->title = 'Wikipedia articles';

// Add data
foreach ( $wikidata as $language => $data )
{
    $graph->data[$language] = new \Ezc\Graph\Datasets\ArrayDataSet( $data );
}
$graph->data['German']->displayType = ezcGraph::LINE;
$graph->data['German']->highlight = true;
$graph->data['German']->highlight['Mar 2006'] = false;

$graph->options->fillLines = 210;

$graph->options->highlightSize = 12;

$graph->options->highlightFont->background = '#EEEEEC88';
$graph->options->highlightFont->border = '#000000';
$graph->options->highlightFont->borderWidth = 1;

$graph->render( 400, 150, 'tutorial_bar_options.svg' );

?>
