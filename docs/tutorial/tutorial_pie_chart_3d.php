<?php

require_once 'tutorial_autoload.php';

$graph = new \Ezc\Graph\Charts\PieChart();
$graph->palette = new \Ezc\Graph\Palette\EzRed();
$graph->title = 'Access statistics';
$graph->options->label = '%2$d (%3$.1f%%)';

$graph->data['Access statistics'] = new \Ezc\Graph\Datasets\ArrayDataSet( array(
    'Mozilla' => 19113,
    'Explorer' => 10917,
    'Opera' => 1464,
    'Safari' => 652,
    'Konqueror' => 474,
) );
$graph->data['Access statistics']->highlight['Explorer'] = true;

$graph->renderer = new \Ezc\Graph\Renderer\Renderer3d();

$graph->renderer->options->moveOut = .2;

$graph->renderer->options->pieChartOffset = 63;

$graph->renderer->options->pieChartGleam = .3;
$graph->renderer->options->pieChartGleamColor = '#FFFFFF';

$graph->renderer->options->pieChartShadowSize = 5;
$graph->renderer->options->pieChartShadowColor = '#000000';

$graph->renderer->options->legendSymbolGleam = .5;
$graph->renderer->options->legendSymbolGleamSize = .9;
$graph->renderer->options->legendSymbolGleamColor = '#FFFFFF';

$graph->renderer->options->pieChartSymbolColor = '#55575388';

$graph->renderer->options->pieChartHeight = 5;
$graph->renderer->options->pieChartRotation = .8;

$graph->render( 400, 150, 'tutorial_pie_chart_3d.svg' );

?>
