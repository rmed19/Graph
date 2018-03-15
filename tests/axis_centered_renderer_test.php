<?php
/**
 * ezcGraphAxisCenteredRendererTest 
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
use Ezc\Graph\Renderer\AxisCenteredLabelRenderer;
use Ezc\Graph\Palette\Black;
use Ezc\Graph\Structs\Coordinate;

/**
 * Tests for ezcGraph class.
 * 
 * @package Graph
 * @subpackage Tests
 */
class ezcGraphAxisCenteredRendererTest extends ezcTestCase
{
    protected $renderer;

    protected $driver;

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "ezcGraphAxisCenteredRendererTest" );
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
        $chart->xAxis->axisLabelRenderer = new AxisCenteredLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawGridLine',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawGridLine' )
            ->with(
                $this->equalTo( new Coordinate( 220., 20. ), 1. ),
                $this->equalTo( new Coordinate( 220., 180. ), 1. ),
                $this->equalTo( Color::fromHex( '#888A85' ) )
            );
        $mockedRenderer
            ->expects( $this->at( 3 ) )
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
        $chart->xAxis->axisLabelRenderer = new AxisCenteredLabelRenderer();
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
                $this->equalTo( new Coordinate( 200., 20. ), 1. ),
                $this->equalTo( new Coordinate( 200., 180. ), 1. ),
                $this->equalTo( Color::fromHex( '#888A85' ) )
            );
        $mockedRenderer
            ->expects( $this->at( 3 ) )
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
        $chart->xAxis->axisLabelRenderer = new AxisCenteredLabelRenderer();
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
                $this->equalTo( new Coordinate( 220., 0. ), 1. ),
                $this->equalTo( new Coordinate( 220., 200. ), 1. ),
                $this->equalTo( Color::fromHex( '#888A85' ) )
            );
        $mockedRenderer
            ->expects( $this->at( 3 ) )
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
        $chart->xAxis->axisLabelRenderer = new AxisCenteredLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawStepLine',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawStepLine' )
            ->with(
                $this->equalTo( new Coordinate( 220, 177. ), 1. ),
                $this->equalTo( new Coordinate( 220, 180. ), 1. ),
                $this->equalTo( Color::fromHex( '#EEEEEC' ) )
            );
        $mockedRenderer
            ->expects( $this->at( 3 ) )
            ->method( 'drawStepLine' )
            ->with(
                $this->equalTo( new Coordinate( 460., 177. ), 1. ),
                $this->equalTo( new Coordinate( 460., 180. ), 1. ),
                $this->equalTo( Color::fromHex( '#EEEEEC' ) )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderAxisOuterSteps()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisCenteredLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->xAxis->axisLabelRenderer->outerStep = true;
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawStepLine',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawStepLine' )
            ->with(
                $this->equalTo( new Coordinate( 220., 177. ), 1. ),
                $this->equalTo( new Coordinate( 220., 183. ), 1. ),
                $this->equalTo( Color::fromHex( '#EEEEEC' ) )
            );
        $mockedRenderer
            ->expects( $this->at( 3 ) )
            ->method( 'drawStepLine' )
            ->with(
                $this->equalTo( new Coordinate( 460., 177. ), 1. ),
                $this->equalTo( new Coordinate( 460., 183. ), 1. ),
                $this->equalTo( Color::fromHex( '#EEEEEC' ) )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderAxisNoInnerSteps()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisCenteredLabelRenderer();
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
                $this->equalTo( new Coordinate( 220., 180. ), 1. ),
                $this->equalTo( new Coordinate( 220., 183. ), 1. ),
                $this->equalTo( Color::fromHex( '#EEEEEC' ) )
            );
        $mockedRenderer
            ->expects( $this->at( 3 ) )
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
        $chart->xAxis->axisLabelRenderer = new AxisCenteredLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->xAxis->axisLabelRenderer->innerStep = false;
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
        $chart->xAxis->axisLabelRenderer = new AxisCenteredLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawText',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 182., 182., 258., 198. ), 1. ),
                $this->equalTo( 'sample 2' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::CENTER )
            );
        $mockedRenderer
            ->expects( $this->at( 3 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 422., 182., 498., 198. ), 1. ),
                $this->equalTo( 'sample 5' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::CENTER )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderTextBoxesWithZeroValue()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisCenteredLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->xAxis->axisLabelRenderer->showZeroValue = true;
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawText',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 102., 182., 178., 198. ), 1. ),
                $this->equalTo( 'sample 1' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::CENTER )
            );
        $mockedRenderer
            ->expects( $this->at( 1 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 182., 182., 258., 198. ), 1. ),
                $this->equalTo( 'sample 2' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::CENTER )
            );
        $mockedRenderer
            ->expects( $this->at( 4 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 422., 182., 498., 198. ), 1. ),
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
        $chart->xAxis->axisLabelRenderer = new AxisCenteredLabelRenderer();
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
                $this->equalTo( new Coordinate( 380., 20. ), 1. ),
                $this->equalTo( new Coordinate( 380., 180. ), 1. ),
                $this->equalTo( Color::fromHex( '#888A85' ) )
            );
        $mockedRenderer
            ->expects( $this->at( 3 ) )
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
        $chart->yAxis->axisLabelRenderer = new AxisCenteredLabelRenderer();
        $chart->yAxis->position = ezcGraph::TOP;
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawGridLine',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawGridLine' )
            ->with(
                $this->equalTo( new Coordinate( 140., 30. ), 1. ),
                $this->equalTo( new Coordinate( 460., 30. ), 1. ),
                $this->equalTo( Color::fromHex( '#888A8588' ) )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderAxisGridFromBottom()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisCenteredLabelRenderer();
        $chart->yAxis->position = ezcGraph::BOTTOM;
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawGridLine',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawGridLine' )
            ->with(
                $this->equalTo( new Coordinate( 140., 170. ), 1. ),
                $this->equalTo( new Coordinate( 460., 170. ), 1. ),
                $this->equalTo( Color::fromHex( '#888A8588' ) )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderTextBoxesFromRight()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisCenteredLabelRenderer();
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
                $this->equalTo( new ezcGraphBoundings( 342., 182., 418., 198. ), 1. ),
                $this->equalTo( 'sample 2' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::CENTER )
            );
        $mockedRenderer
            ->expects( $this->at( 3 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 102., 182., 178., 198. ), 1. ),
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
        $chart->yAxis->axisLabelRenderer = new AxisCenteredLabelRenderer();
        $chart->yAxis->position = ezcGraph::TOP;
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawText',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 102., 42., 138., 78. ), 1. ),
                $this->equalTo( '100' ),
                $this->equalTo( ezcGraph::MIDDLE | ezcGraph::RIGHT )
            );
        $mockedRenderer
            ->expects( $this->at( 3 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 102., 162., 138., 198. ), 1. ),
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
        $chart->yAxis->axisLabelRenderer = new AxisCenteredLabelRenderer();
        $chart->yAxis->position = ezcGraph::BOTTOM;
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawText',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 102., 122., 138., 158. ), 1. ),
                $this->equalTo( '100' ),
                $this->equalTo( ezcGraph::MIDDLE | ezcGraph::RIGHT )
            );
        $mockedRenderer
            ->expects( $this->at( 3 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 102., 2., 138., 38. ), 1. ),
                $this->equalTo( '400' ),
                $this->equalTo( ezcGraph::MIDDLE | ezcGraph::RIGHT )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testAxisCenteredLabelRendererPropertyShowZeroValue()
    {
        $options = new AxisCenteredLabelRenderer();

        $this->assertSame(
            false,
            $options->showZeroValue,
            'Wrong default value for property showZeroValue in class AxisCenteredLabelRenderer'
        );

        $options->showZeroValue = true;
        $this->assertSame(
            true,
            $options->showZeroValue,
            'Setting property value did not work for property showZeroValue in class AxisCenteredLabelRenderer'
        );

        try
        {
            $options->showZeroValue = 42;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }
}
?>
