<?php
/**
 * ezcGraphAxisBoxedRendererTest 
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

use Ezc\Graph\Charts\LineChart;
use Ezc\Graph\Colors\Color;
use Ezc\Graph\Datasets\ArrayDataSet;
use Ezc\Graph\Renderer\Renderer2d;
use Ezc\Graph\Renderer\AxisNoLabelRenderer;
use Ezc\Graph\Renderer\AxisBoxedLabelRenderer;
use Ezc\Graph\Palette\Black;
use Ezc\Graph\Structs\Coordinate;

/**
 * Tests for ezcGraph class.
 * 
 * @package Graph
 * @subpackage Tests
 */
class ezcGraphAxisBoxedRendererTest extends ezcTestCase
{
    protected $renderer;

    protected $driver;

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "ezcGraphAxisBoxedRendererTest" );
	}

    public function setUp()
    {
        parent::setUp();

        if ( version_compare( phpversion(), '5.1.3', '<' ) )
        {
            $this->markTestSkipped( "These tests required atleast PHP 5.1.3" );
        }
    }

    public function testRenderAxisGrid()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisBoxedLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawGridLine',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawGridLine' )
            ->with(
                $this->equalTo( new Coordinate( 204., 20. ), 1. ),
                $this->equalTo( new Coordinate( 204., 180. ), 1. ),
                $this->equalTo( Color::fromHex( '#888A85' ) )
            );
        $mockedRenderer
            ->expects( $this->at( 4 ) )
            ->method( 'drawGridLine' )
            ->with(
                $this->equalTo( new Coordinate( 460., 20. ), 1. ),
                $this->equalTo( new Coordinate( 460., 180. ), 1. ),
                $this->equalTo( Color::fromHex( '#888A85' ) )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderAxisGridZeroAxisSpace()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisBoxedLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->yAxis->axisSpace = 0;
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawGridLine',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawGridLine' )
            ->with(
                $this->equalTo( new Coordinate( 180., 20. ), 1. ),
                $this->equalTo( new Coordinate( 180., 180. ), 1. ),
                $this->equalTo( Color::fromHex( '#888A85' ) )
            );
        $mockedRenderer
            ->expects( $this->at( 4 ) )
            ->method( 'drawGridLine' )
            ->with(
                $this->equalTo( new Coordinate( 500., 20. ), 1. ),
                $this->equalTo( new Coordinate( 500., 180. ), 1. ),
                $this->equalTo( Color::fromHex( '#888A85' ) )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderAxisOuterGrid()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisBoxedLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->xAxis->axisLabelRenderer->outerGrid = true;
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawGridLine',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawGridLine' )
            ->with(
                $this->equalTo( new Coordinate( 204., 0. ), 1. ),
                $this->equalTo( new Coordinate( 204., 200. ), 1. ),
                $this->equalTo( Color::fromHex( '#888A85' ) )
            );
        $mockedRenderer
            ->expects( $this->at( 4 ) )
            ->method( 'drawGridLine' )
            ->with(
                $this->equalTo( new Coordinate( 460., 0. ), 1. ),
                $this->equalTo( new Coordinate( 460., 200. ), 1. ),
                $this->equalTo( Color::fromHex( '#888A85' ) )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderAxisSteps()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisBoxedLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawStepLine',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawStepLine' )
            ->with(
                $this->equalTo( new Coordinate( 204., 177. ), 1. ),
                $this->equalTo( new Coordinate( 204., 183. ), 1. ),
                $this->equalTo( Color::fromHex( '#EEEEEC' ) )
            );
        $mockedRenderer
            ->expects( $this->at( 4 ) )
            ->method( 'drawStepLine' )
            ->with(
                $this->equalTo( new Coordinate( 460., 177. ), 1. ),
                $this->equalTo( new Coordinate( 460., 183. ), 1. ),
                $this->equalTo( Color::fromHex( '#EEEEEC' ) )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderAxisNoOuterSteps()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisBoxedLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->xAxis->axisLabelRenderer->outerStep = false;
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawStepLine',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawStepLine' )
            ->with(
                $this->equalTo( new Coordinate( 204., 177. ), 1. ),
                $this->equalTo( new Coordinate( 204., 180. ), 1. ),
                $this->equalTo( Color::fromHex( '#EEEEEC' ) )
            );
        $mockedRenderer
            ->expects( $this->at( 4 ) )
            ->method( 'drawStepLine' )
            ->with(
                $this->equalTo( new Coordinate( 460., 177. ), 1. ),
                $this->equalTo( new Coordinate( 460., 180. ), 1. ),
                $this->equalTo( Color::fromHex( '#EEEEEC' ) )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderAxisNoInnerSteps()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisBoxedLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->xAxis->axisLabelRenderer->innerStep = false;
        $chart->xAxis->axisLabelRenderer->outerStep = true;
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawStepLine',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawStepLine' )
            ->with(
                $this->equalTo( new Coordinate( 204., 180. ), 1. ),
                $this->equalTo( new Coordinate( 204., 183. ), 1. ),
                $this->equalTo( Color::fromHex( '#EEEEEC' ) )
            );
        $mockedRenderer
            ->expects( $this->at( 4 ) )
            ->method( 'drawStepLine' )
            ->with(
                $this->equalTo( new Coordinate( 460., 180. ), 1. ),
                $this->equalTo( new Coordinate( 460., 183. ), 1. ),
                $this->equalTo( Color::fromHex( '#EEEEEC' ) )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderAxisNoSteps()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisBoxedLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->xAxis->axisLabelRenderer->innerStep = false;
        $chart->xAxis->axisLabelRenderer->outerStep = false;
        $chart->yAxis->axisLabelRenderer->innerStep = false;
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawStepLine',
        ) );

        $mockedRenderer
            ->expects( $this->exactly( 0 ) )
            ->method( 'drawStepLine' );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderTextBoxes()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisBoxedLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawText',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 142., 182., 202., 198. ), 1. ),
                $this->equalTo( 'sample 1' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::CENTER )
            );
        $mockedRenderer
            ->expects( $this->at( 4 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 398., 182., 458., 198. ), 1. ),
                $this->equalTo( 'sample 5' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::CENTER )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderAxisGridFromRight()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisBoxedLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->xAxis->position = ezcGraph::RIGHT;
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawGridLine',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawGridLine' )
            ->with(
                $this->equalTo( new Coordinate( 396., 20. ), 1. ),
                $this->equalTo( new Coordinate( 396., 180. ), 1. ),
                $this->equalTo( Color::fromHex( '#888A85' ) )
            );
        $mockedRenderer
            ->expects( $this->at( 4 ) )
            ->method( 'drawGridLine' )
            ->with(
                $this->equalTo( new Coordinate( 140., 20. ), 1. ),
                $this->equalTo( new Coordinate( 140., 180. ), 1. ),
                $this->equalTo( Color::fromHex( '#888A85' ) )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderAxisGridFromTop()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisBoxedLabelRenderer();
        $chart->yAxis->position = ezcGraph::TOP;
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawGridLine',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawGridLine' )
            ->with(
                $this->equalTo( new Coordinate( 140., 52. ), 1. ),
                $this->equalTo( new Coordinate( 460., 52. ), 1. ),
                $this->equalTo( Color::fromHex( '#888A85' ) )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderAxisGridFromBottom()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisBoxedLabelRenderer();
        $chart->yAxis->position = ezcGraph::BOTTOM;
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawGridLine',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawGridLine' )
            ->with(
                $this->equalTo( new Coordinate( 140., 148. ), 1. ),
                $this->equalTo( new Coordinate( 460., 148. ), 1. ),
                $this->equalTo( Color::fromHex( '#888A85' ) )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderTextBoxesFromRight()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisBoxedLabelRenderer();
        $chart->xAxis->position = ezcGraph::RIGHT;
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawText',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 398., 182., 458., 198. ), 1. ),
                $this->equalTo( 'sample 1' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::CENTER )
            );
        $mockedRenderer
            ->expects( $this->at( 4 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 142., 182., 202., 198. ), 1. ),
                $this->equalTo( 'sample 5' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::CENTER )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderTextBoxesFromTop()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisBoxedLabelRenderer();
        $chart->yAxis->position = ezcGraph::TOP;
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawText',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 102., 22., 138., 50. ), 1. ),
                $this->equalTo( '0' ),
                $this->equalTo( ezcGraph::MIDDLE | ezcGraph::RIGHT )
            );
        $mockedRenderer
            ->expects( $this->at( 4 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 102., 150., 138., 178. ), 1. ),
                $this->equalTo( '400' ),
                $this->equalTo( ezcGraph::MIDDLE | ezcGraph::RIGHT )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderTextBoxesFromBottom()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisBoxedLabelRenderer();
        $chart->yAxis->position = ezcGraph::BOTTOM;
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawText',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 102., 150., 138., 178. ), 1. ),
                $this->equalTo( '0' ),
                $this->equalTo( ezcGraph::MIDDLE | ezcGraph::RIGHT )
            );
        $mockedRenderer
            ->expects( $this->at( 4 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 102., 22., 138., 50. ), 1. ),
                $this->equalTo( '400' ),
                $this->equalTo( ezcGraph::MIDDLE | ezcGraph::RIGHT )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }
}
?>
