<?php
/**
 * ezcGraphAxisRotatedRendererTest 
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

use Ezc\Graph\Charts\LineChart;
use Ezc\Graph\Datasets\ArrayDataSet;
use Ezc\Graph\Renderer\Renderer3d;
use Ezc\Graph\Renderer\Renderer2d;
use Ezc\Graph\Renderer\AxisRotatedLabelRenderer;
use Ezc\Graph\Renderer\AxisNoLabelRenderer;
use Ezc\Graph\Palette\Black;
use Ezc\Graph\Structs\Coordinate;

/**
 * Tests for ezcGraph class.
 * 
 * @package Graph
 * @subpackage Tests
 */
class ezcGraphAxisRotatedRendererTest extends ezcGraphTestCase
{
    protected $basePath;

    protected $tempDir;

    protected $renderer;

    protected $driver;

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "ezcGraphAxisRotatedRendererTest" );
	}

    public function setUp()
    {
        parent::setUp();

        static $i = 0;

        if ( version_compare( phpversion(), '5.1.3', '<' ) )
        {
            $this->markTestSkipped( "This test requires PHP 5.1.3 or later." );
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

    protected function getRandomData( $count, $min = 500, $max = 2000, $randomize = 23 )
    {
        $data = parent::getRandomData( $count, $min, $max, $randomize );

        foreach ( $data as $k => $v )
        {
            $data[(string) ($k + 2000)] = $v;
            unset( $data[$k] );
        }

        return $data;
    }

    public function testRenderTextBoxes()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisRotatedLabelRenderer();
        $chart->xAxis->axisLabelRenderer->angle = 45;
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawText',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 146.3, 180., 160., 208.3 ), 1. ),
                $this->equalTo( 'sample 1' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::RIGHT ),
                $this->equalTo( new ezcGraphRotation( -45, new Coordinate( 160, 180 ) ) )
            );
        $mockedRenderer
            ->expects( $this->at( 1 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 221.3, 180., 235., 236.6 ), 1. ),
                $this->equalTo( 'sample 2' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::RIGHT ),
                $this->equalTo( new ezcGraphRotation( -45, new Coordinate( 235, 180 ) ) )
            );
        $mockedRenderer
            ->expects( $this->at( 4 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 446.3, 180., 460., 208.3 ), 1. ),
                $this->equalTo( 'sample 5' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::RIGHT ),
                $this->equalTo( new ezcGraphRotation( -45, new Coordinate( 460, 180 ) ) )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderTextBoxesNoOffset()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisRotatedLabelRenderer();
        $chart->xAxis->axisLabelRenderer->angle = 45;
        $chart->xAxis->axisLabelRenderer->labelOffset = false;
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawText',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 109.4, 180., 140., 208.3 ), 1. ),
                $this->equalTo( 'sample 1' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::RIGHT ),
                $this->equalTo( new ezcGraphRotation( -45, new Coordinate( 140, 180 ) ) )
            );
        $mockedRenderer
            ->expects( $this->at( 1 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 197.6, 180., 220., 236.6 ), 1. ),
                $this->equalTo( 'sample 2' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::RIGHT ),
                $this->equalTo( new ezcGraphRotation( -45, new Coordinate( 220, 180 ) ) )
            );
        $mockedRenderer
            ->expects( $this->at( 4 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 429.4, 180., 460., 208.3 ), 1. ),
                $this->equalTo( 'sample 5' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::RIGHT ),
                $this->equalTo( new ezcGraphRotation( -45, new Coordinate( 460, 180 ) ) )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderTextBoxes3D()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisRotatedLabelRenderer();
        $chart->xAxis->axisLabelRenderer->angle = 45;
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( 'Renderer3d', array(
            'drawText',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 146.3, 180., 160., 208.3 ), 1. ),
                $this->equalTo( 'sample 1' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::RIGHT ),
                $this->equalTo( new ezcGraphRotation( -45, new Coordinate( 160, 180 ) ) )
            );
        $mockedRenderer
            ->expects( $this->at( 1 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 221.3, 180., 235., 236.6 ), 1. ),
                $this->equalTo( 'sample 2' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::RIGHT ),
                $this->equalTo( new ezcGraphRotation( -45, new Coordinate( 235, 180 ) ) )
            );
        $mockedRenderer
            ->expects( $this->at( 4 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 446.3, 180., 460., 208.3 ), 1. ),
                $this->equalTo( 'sample 5' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::RIGHT ),
                $this->equalTo( new ezcGraphRotation( -45, new Coordinate( 460, 180 ) ) )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testAxisRotatedLabelRendererPropertyAngle()
    {
        $options = new AxisRotatedLabelRenderer();

        $this->assertSame(
            null,
            $options->angle,
            'Wrong default value for property angle in class AxisRotatedLabelRenderer'
        );

        $options->angle = 89.5;
        $this->assertSame(
            89.5,
            $options->angle,
            'Setting property value did not work for property angle in class AxisRotatedLabelRenderer'
        );

        $options->angle = 410.5;
        $this->assertSame(
            50.5,
            $options->angle,
            'Setting property value did not work for property angle in class AxisRotatedLabelRenderer'
        );

        try
        {
            $options->angle = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testAxisRotatedLabelRendererPropertyLabelOffset()
    {
        $options = new AxisRotatedLabelRenderer();

        $this->assertSame(
            true,
            $options->labelOffset,
            'Wrong default value for property labelOffset in class AxisRotatedLabelRenderer'
        );

        $options->labelOffset = false;
        $this->assertSame(
            false,
            $options->labelOffset,
            'Setting property value did not work for property labelOffset in class AxisRotatedLabelRenderer'
        );

        try
        {
            $options->labelOffset = 'true';
            $this->fail( 'Expected ezcBaseValueException.' );
        }
        catch ( ezcBaseValueException $e )
        { /* Expecetd */ }
    }

    public function testRenderCompleteLineChart()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new LineChart();

        $chart->data['Line 1'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['Line 2'] = new ArrayDataSet( array( 'sample 1' => 543, 'sample 2' => 234, 'sample 3' => 298, 'sample 4' => 5, 'sample 5' => 613) );

        $chart->xAxis->axisLabelRenderer = new AxisRotatedLabelRenderer();
        $chart->xAxis->axisSpace = .25;
        $chart->xAxis->axisLabelRenderer->angle = 45;
        $chart->yAxis->axisLabelRenderer = new AxisRotatedLabelRenderer();
        $chart->yAxis->axisLabelRenderer->angle = 45;

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderCompleteLineChartReverseRotated()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new LineChart();

        $chart->data['Line 1'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['Line 2'] = new ArrayDataSet( array( 'sample 1' => 543, 'sample 2' => 234, 'sample 3' => 298, 'sample 4' => 5, 'sample 5' => 613) );

        $chart->xAxis->axisLabelRenderer = new AxisRotatedLabelRenderer();
        $chart->xAxis->axisSpace = .25;
        $chart->xAxis->axisLabelRenderer->angle = -45;

        $chart->yAxis->axisLabelRenderer = new AxisRotatedLabelRenderer();
        $chart->yAxis->axisLabelRenderer->angle = -45;

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderRotatedAxisWithLotsOfLabels()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $labelCount = 30;
        $data = $this->getRandomData( $labelCount, 500, 2000, 23 );

        $chart = new LineChart();
        $chart->data['sample'] = new ArrayDataSet( $data );

        // Set manual label count
        $chart->xAxis->labelCount = 31;

        $chart->xAxis->axisLabelRenderer = new AxisRotatedLabelRenderer();
        $chart->xAxis->axisLabelRenderer->angle = 45;

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderRotatedAxisWithLotsOfLabelsVertical()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $labelCount = 20;
        $data = $this->getRandomData( $labelCount, 500, 2000, 23 );

        $chart = new LineChart();
        $chart->data['sample'] = new ArrayDataSet( $data );

        // Set manual label count
        $chart->xAxis->labelCount = 21;

        $chart->xAxis->axisLabelRenderer = new AxisRotatedLabelRenderer();
        $chart->xAxis->axisLabelRenderer->angle = 0;

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderRotatedAxisWithLotsOfLabelsLargeAngle()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $labelCount = 10;
        $data = $this->getRandomData( $labelCount, 500, 2000, 23 );

        $chart = new LineChart();
        $chart->data['sample'] = new ArrayDataSet( $data );

        // Set manual label count
        $chart->xAxis->labelCount = 11;

        $chart->xAxis->axisLabelRenderer = new AxisRotatedLabelRenderer();
        $chart->xAxis->axisLabelRenderer->angle = 75;

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRender3dRotatedAxisWithLotsOfLabels()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $labelCount = 30;
        $data = $this->getRandomData( $labelCount, 500, 2000, 23 );

        $chart = new LineChart();
        $chart->data['sample'] = new ArrayDataSet( $data );

        // Set manual label count
        $chart->xAxis->labelCount = 31;

        $chart->xAxis->axisLabelRenderer = new AxisRotatedLabelRenderer();
        $chart->xAxis->axisLabelRenderer->angle = 45;

        $chart->renderer = new Renderer3d();
        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testOptimalAngleCalculation()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new LineChart();

        $chart->data['Line 1'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['Line 2'] = new ArrayDataSet( array( 'sample 1' => 543, 'sample 2' => 234, 'sample 3' => 298, 'sample 4' => 5, 'sample 5' => 613) );

        $chart->xAxis->axisLabelRenderer = new AxisRotatedLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisRotatedLabelRenderer();

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );

        $this->assertEquals(
            $chart->xAxis->axisLabelRenderer->angle,
            76.,
            'Angle estimation wrong.',
            1.
        );

        $this->assertEquals(
            $chart->yAxis->axisLabelRenderer->angle,
            53.,
            'Angle estimation wrong.',
            1.
        );
    }

    public function testRenderWithModifiedAxisSpace()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $labelCount = 20;
        $data = $this->getRandomData( $labelCount, 500, 2000, 23 );

        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->data['sample'] = new ArrayDataSet( $data );

        // Set manual label count
        $chart->xAxis->labelCount = 21;

        $chart->xAxis->axisLabelRenderer = new AxisRotatedLabelRenderer();
        $chart->xAxis->axisLabelRenderer->angle = 45;
        $chart->xAxis->axisSpace = 0.1;

        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->yAxis->axisSpace = 0.05;

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderWithZeroAxisSpace()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $labelCount = 20;
        $data = $this->getRandomData( $labelCount, 500, 2000, 23 );

        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->data['sample'] = new ArrayDataSet( $data );

        // Set manual label count
        $chart->xAxis->labelCount = 21;

        $chart->xAxis->axisLabelRenderer = new AxisRotatedLabelRenderer();
        $chart->xAxis->axisLabelRenderer->angle = 45;
        $chart->xAxis->axisSpace = 0.1;

        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->yAxis->axisSpace = 0;

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }
}

?>
