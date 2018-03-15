<?php

require_once 'tutorial_autoload.php';

$graph = new \Ezc\Graph\Charts\PieChart();
$graph->background->color = '#FFFFFFFF';
$graph->title = 'Access statistics';
$graph->legend = false;

$graph->data['Access statistics'] = new \Ezc\Graph\Datasets\ArrayDataSet( array(
    'Mozilla' => 19113,
    'Explorer' => 10917,
    'Opera' => 1464,
    'Safari' => 652,
    'Konqueror' => 474,
) );

$graph->renderer = new \Ezc\Graph\Renderer\Renderer3d();
$graph->renderer->options->pieChartShadowSize = 10;
$graph->renderer->options->pieChartGleam = .5;
$graph->renderer->options->dataBorder = false;
$graph->renderer->options->pieChartHeight = 16;
$graph->renderer->options->legendSymbolGleam = .5;

$graph->driver->options->templateDocument = dirname( __FILE__ ) . '/template.svg';
$graph->driver->options->graphOffset = new \Ezc\Graph\Structs\Coordinate( 25, 40 );
$graph->driver->options->insertIntoGroup = 'ezcGraph';

$graph->render( 400, 200, 'tutorial_driver_svg.svg' );

?>
