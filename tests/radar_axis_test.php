<?php

namespace Ezc\Graph\Tests;

require_once dirname( __FILE__ ) . '/test_case.php';

use Ezc\Graph\Axis\ChartElementDateAxis;
use Ezc\Graph\Axis\ChartElementLabeledAxis;
use Ezc\Graph\Axis\ChartElementLogarithmicalAxis;
use Ezc\Graph\Axis\ChartElementNumericAxis;
use Ezc\Graph\Charts\RadarChart;
use Ezc\Graph\Datasets\ArrayDataSet;
use Ezc\Graph\Palette\EzBlue;
use Ezc\Graph\Renderer\AxisExactLabelRenderer;
use Ezc\Graph\Renderer\AxisCenteredLabelRenderer;
use Ezc\Graph\Renderer\AxisBoxedLabelRenderer;


/**
 * Tests for ezcGraph class.
 * 
 * @package Graph
 * @subpackage Tests
 */
class RadarChartAxisTest extends ezcGraphTestCase
{
    protected $basePath;

    protected $tempDir;

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "RadarChartAxisTest" );
	}

    public function setUp()
    {
        parent::setUp();

        static $i = 0;
        $this->tempDir = $this->createTempDir( __CLASS__ . sprintf( '_%03d_', ++$i ) ) . '/';
        $this->basePath = dirname( __FILE__ ) . '/data/';
    }

    public function tearDown()
    {
        if ( !$this->hasFailed() )
        {
            $this->removeTempDir();
        }
    }

    public function testCenteredMultipleDirections()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new RadarChart();
        $chart->palette = new EzBlue();
        $chart->legend = false;
        $chart->data['sample'] = new ArrayDataSet( $this->getRandomData( 13 ) );

        $chart->axis->axisLabelRenderer = new AxisCenteredLabelRenderer();

        $chart->render( 500, 500, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testBoxedMultipleDirections()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new RadarChart();
        $chart->palette = new EzBlue();
        $chart->legend = false;
        $chart->data['sample'] = new ArrayDataSet( $this->getRandomData( 13 ) );

        $chart->axis->axisLabelRenderer = new AxisBoxedLabelRenderer();

        $chart->render( 500, 500, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testExactMultipleDirections()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new RadarChart();
        $chart->palette = new EzBlue();
        $chart->legend = false;
        $chart->data['sample'] = new ArrayDataSet( $this->getRandomData( 13 ) );

        $chart->axis->axisLabelRenderer = new AxisExactLabelRenderer();
        $chart->axis->axisLabelRenderer->showLastValue = false;

        $chart->render( 500, 500, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testDateRotationAxis()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new RadarChart();
        $chart->palette = new EzBlue();
        $chart->legend = false;
        $chart->data['sample'] = new ArrayDataSet( array( 
            strtotime( '2006-10-16' ) => 7.78507871321,
            strtotime( '2006-10-30' ) => 7.52224503765,
            strtotime( '2006-11-20' ) => 7.29226557153,
            strtotime( '2006-11-28' ) => 7.06228610541,
            strtotime( '2006-12-05' ) => 6.66803559206,
            strtotime( '2006-12-11' ) => 6.37234770705,
            strtotime( '2006-12-28' ) => 6.04517453799,
        ) );

        $chart->rotationAxis = new ChartElementDateAxis();

        $chart->render( 400, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testNumericRotationAxis()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new RadarChart();
        $chart->palette = new EzBlue();
        $chart->legend = false;
        $chart->data['sample'] = new ArrayDataSet( $this->getRandomData( 9 ) );

        $chart->rotationAxis = new ChartElementNumericAxis();

        $chart->render( 400, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testLabeledRotationAxis()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new RadarChart();
        $chart->palette = new EzBlue();
        $chart->legend = false;
        $chart->data['sample'] = new ArrayDataSet( $this->getRandomData( 9 ) );

        $chart->rotationAxis = new ChartElementLabeledAxis();

        $chart->render( 400, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testLogarithmicalRotationAxis()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new RadarChart();
        $chart->palette = new EzBlue();
        $chart->legend = false;
        $chart->data['sample'] = new ArrayDataSet( array(
            1 => 12,
            5 => 7,
            10 => 234,
            132 => 34,
            1125 => 12,
            12346 => 6,
            140596 => 1,
        ) );

        $chart->rotationAxis = new ChartElementLogarithmicalAxis();

        $chart->render( 400, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }
}
?>
