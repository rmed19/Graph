<?php
/**
 * OdometerChartTest 
 * 
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 * 
 *   http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 *
 * @package Graph
 * @version //autogen//
 * @subpackage Tests
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

namespace Ezc\Graph\Tests;


require_once dirname( __FILE__ ) . '/test_case.php';
use Ezc\Graph\Axis\ChartElementLabeledAxis;
use Ezc\Graph\Options\OdometerChartOptions;
use Ezc\Graph\Charts\OdometerChart;
use Ezc\Graph\Axis\ChartElementNumericAxis;
use Ezc\Graph\Colors\Color;
use Ezc\Graph\Datasets\ArrayDataSet;
use Ezc\Graph\Renderer\Renderer3d;
use Ezc\Graph\Renderer\Renderer2d;
use Ezc\Graph\Structs\Coordinate;

/**
 * Tests for ezcGraph class.
 * 
 * @package Graph
 * @subpackage Tests
 */
class OdometerChartTest extends ezcGraphTestCase
{
    protected $basePath;

    protected $tempDir;

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "OdometerChartTest" );
	}

    public function setUp()
    {
        parent::setUp();

        static $i = 0;
        if ( version_compare( phpversion(), '5.1.3', '<' ) )
        {
            $this->markTestSkipped( "These tests required atleast PHP 5.1.3" );
        }
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

    public function testOdometerChartOptionsPropertyBorderColor()
    {
        $options = new OdometerChartOptions();

        $this->assertEquals(
            Color::create( '#000000' ),
            $options->borderColor,
            'Wrong default value for property borderColor in class OdometerChartOptions'
        );

        $options->borderColor = '#FF0000';
        $this->assertEquals(
            Color::create( '#FF0000' ),
            $options->borderColor,
            'Setting property value did not work for property borderColor in class OdometerChartOptions'
        );

        try
        {
            $options->borderColor = false;
        }
        catch ( ezcGraphUnknownColorDefinitionException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphUnknownColorDefinitionException.' );
    }

    public function testOdometerChartOptionsPropertyBorderWidth()
    {
        $options = new OdometerChartOptions();

        $this->assertEquals(
            0,
            $options->borderWidth,
            'Wrong default value for property borderWidth in class OdometerChartOptions'
        );

        $options->borderWidth = 4;
        $this->assertEquals(
            4,
            $options->borderWidth,
            'Setting property value did not work for property borderWidth in class OdometerChartOptions'
        );

        try
        {
            $options->borderWidth = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testOdometerChartOptionsPropertyStartColor()
    {
        $options = new OdometerChartOptions();

        $this->assertEquals(
            Color::create( '#4e9a06A0' ),
            $options->startColor,
            'Wrong default value for property startColor in class OdometerChartOptions'
        );

        $options->startColor = '#00FF00';
        $this->assertEquals(
            Color::create( '#00FF00' ),
            $options->startColor,
            'Setting property value did not work for property startColor in class OdometerChartOptions'
        );

        try
        {
            $options->startColor = false;
        }
        catch ( ezcGraphUnknownColorDefinitionException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphUnknownColorDefinitionException.' );
    }

    public function testOdometerChartOptionsPropertyEndColor()
    {
        $options = new OdometerChartOptions();

        $this->assertEquals(
            Color::create( '#A40000A0' ),
            $options->endColor,
            'Wrong default value for property endColor in class OdometerChartOptions'
        );

        $options->endColor = '#FF0000';
        $this->assertEquals(
            Color::create( '#FF0000' ),
            $options->endColor,
            'Setting property value did not work for property endColor in class OdometerChartOptions'
        );

        try
        {
            $options->endColor = false;
        }
        catch ( ezcGraphUnknownColorDefinitionException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphUnknownColorDefinitionException.' );
    }

    public function testOdometerChartOptionsPropertyMarkerWidth()
    {
        $options = new OdometerChartOptions();

        $this->assertEquals(
            2,
            $options->markerWidth,
            'Wrong default value for property markerWidth in class OdometerChartOptions'
        );

        $options->markerWidth = 4;
        $this->assertEquals(
            4,
            $options->markerWidth,
            'Setting property value did not work for property markerWidth in class OdometerChartOptions'
        );

        try
        {
            $options->markerWidth = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testOdometerChartOptionsPropertyOdometerHeight()
    {
        $options = new OdometerChartOptions();

        $this->assertEquals(
            .5,
            $options->odometerHeight,
            'Wrong default value for property odometerHeight in class OdometerChartOptions'
        );

        $options->odometerHeight = .3;
        $this->assertEquals(
            .3,
            $options->odometerHeight,
            'Setting property value did not work for property odometerHeight in class OdometerChartOptions'
        );

        try
        {
            $options->odometerHeight = 1.2;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testOdometerChartPropertyAxis()
    {
        $chart = new OdometerChart();

        $this->assertTrue(
            $chart->axis instanceof ChartElementNumericAxis,
            'Wrong default value for property axis in class OdometerChart'
        );

        $chart->axis = new ChartElementLabeledAxis();
        $this->assertTrue(
            $chart->axis instanceof ChartElementLabeledAxis,
            'Setting property value did not work for property axis in class OdometerChart'
        );

        try
        {
            $chart->axis = true;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRenderOdometer()
    {
        $chart = new OdometerChart();
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1 ) );

        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawOdometer',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawOdometer' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 0., 0., 500., 200. ), 1. ),
                $this->equalTo( $chart->axis ),
                $this->equalTo( $chart->options )
            )
            ->will(
                $this->returnValue( new ezcGraphBoundings(  0., 0., 500., 200. ) )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderOdometerMarker()
    {
        $chart = new OdometerChart();
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 1, 'sample 5' => 120 ) );

        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawOdometerMarker',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawOdometerMarker' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 25., 50., 475., 150. ), 1. ),
                $this->equalTo( new Coordinate( .585, .0 ), .001 ),
                $this->equalTo( ezcGraph::NO_SYMBOL ),
                $this->equalTo( Color::create( '#3465A4' ) ),
                $this->equalTo( 2 )
            );

        $mockedRenderer
            ->expects( $this->at( 1 ) )
            ->method( 'drawOdometerMarker' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 25., 50., 475., 150. ), 1. ),
                $this->equalTo( new Coordinate( .0525, .0 ), .001 ),
                $this->equalTo( ezcGraph::NO_SYMBOL ),
                $this->equalTo( Color::create( '#4E9A06' ) ),
                $this->equalTo( 2 )
            );

        $mockedRenderer
            ->expects( $this->at( 2 ) )
            ->method( 'drawOdometerMarker' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 25., 50., 475., 150. ), 1. ),
                $this->equalTo( new Coordinate( .81, .0 ), .001 ),
                $this->equalTo( ezcGraph::NO_SYMBOL ),
                $this->equalTo( Color::create( '#CC0000' ) ),
                $this->equalTo( 2 )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testAddMultipleDatasets()
    {
        $chart = new OdometerChart();

        try
        {
            $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 1, 'sample 5' => 120 ) );
            $chart->data['moreData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 1, 'sample 5' => 120 ) );

            $chart->render( 500, 200 );
        }
        catch ( ezcGraphTooManyDataSetsExceptions $e )
        {
            return;
        }

        $this->fail( 'Expected ezcGraphTooManyDataSetsExceptions.' );
    }

    public function testNoDatasets()
    {
        $chart = new OdometerChart();

        try
        {
            $chart->render( 500, 200 );
        }
        catch ( ezcGraphNoDataException $e )
        {
            return;
        }

        $this->fail( 'Expected ezcGraphNoDataException.' );
    }

    public function testIncompatibleRenderer()
    {
        $chart = new OdometerChart();
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 1, 'sample 5' => 120 ) );

        try
        {
            $chart->renderer = new Renderer3d();
            $chart->render( 500, 200 );
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRenderCompleteOdometer()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new OdometerChart();

        $chart->data['data'] = new ArrayDataSet(
            array( 1, 7, 18 )
        );

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderCompleteOdometerWithDifferentOptions()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new OdometerChart();

        $chart->data['data'] = new ArrayDataSet(
            array( 1, 7, 18 )
        );

        $chart->options->borderWidth = 2;
        $chart->options->borderColor = '#2e3436';

        $chart->options->startColor = '#EEEEEC';
        $chart->options->endColor = '#A00000';

        $chart->options->markerWidth = 5;
        $chart->options->odometerHeight = .7;

        $chart->render( 300, 100, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderCompleteOdometerToOutput()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new OdometerChart();

        $chart->data['data'] = new ArrayDataSet(
            array( 1, 7, 18 )
        );

        ob_start();
        // Suppress header already sent warning
        @$chart->renderToOutput( 500, 200 );
        file_put_contents( $filename, ob_get_clean() );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }
}
?>
