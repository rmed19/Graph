<?php
/**
 * ezcGraphAxisExactRendererTest 
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
use Ezc\Graph\Colors\Color;
use Ezc\Graph\Datasets\ArrayDataSet;
use Ezc\Graph\Renderer\Renderer2d;
use Ezc\Graph\Renderer\AxisNoLabelRenderer;
use Ezc\Graph\Renderer\AxisExactLabelRenderer;
use Ezc\Graph\Palette\Black;
use Ezc\Graph\Structs\Coordinate;

/**
 * Tests for ezcGraph class.
 * 
 * @package Graph
 * @subpackage Tests
 */
class ezcGraphAxisExactRendererTest extends ezcGraphTestCase
{
    protected $basePath;

    protected $renderer;

    protected $driver;

    protected $tempDir;

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( __CLASS__ );
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

    public function testDetermineCuttingPoint()
    {
        $aStart = new Coordinate( -1, -5 );
        $aDir = new Coordinate( 4, 3 );

        $bStart = new Coordinate( 1, 2 );
        $bDir = new Coordinate( 1, -2 );

        $axisLabelRenderer = new AxisExactLabelRenderer();
        $cuttingPosition = $axisLabelRenderer->determineLineCuttingPoint( $aStart, $aDir, $bStart, $bDir );

        $this->assertEquals(
            $cuttingPosition,
            2.,
            'Cutting position should be <2>',
            .1
        );

        $cuttingPoint = new Coordinate(
            $bStart->x + $cuttingPosition * $bDir->x,
            $bStart->y + $cuttingPosition * $bDir->y
        );

        $this->assertEquals(
            $cuttingPoint,
            new Coordinate( 3., -2. ),
            'Wrong cutting point.',
            .1
        );
    }

    public function testDetermineCuttingPoint2()
    {
        $aStart = new Coordinate( 0, 2 );
        $aDir = new Coordinate( 3, 1 );

        $bStart = new Coordinate( 2, -1 );
        $bDir = new Coordinate( 1, 2 );

        $axisLabelRenderer = new AxisExactLabelRenderer();
        $cuttingPosition = $axisLabelRenderer->determineLineCuttingPoint( $aStart, $aDir, $bStart, $bDir );

        $this->assertEquals(
            $cuttingPosition,
            2.2,
            'Cutting position should be <2.2>',
            .1
        );

        $cuttingPoint = new Coordinate(
            $bStart->x + $cuttingPosition * $bDir->x,
            $bStart->y + $cuttingPosition * $bDir->y
        );

        $this->assertEquals(
            $cuttingPoint,
            new Coordinate( 4.2, 3.4 ),
            'Wrong cutting point.',
            .1
        );
    }

    public function testNoCuttingPoint()
    {
        $aStart = new Coordinate( 0, 0 );
        $aDir = new Coordinate( 1, 0 );

        $bStart = new Coordinate( 0, 1 );
        $bDir = new Coordinate( 3, 0 );

        $axisLabelRenderer = new AxisExactLabelRenderer();
        $cuttingPosition = $axisLabelRenderer->determineLineCuttingPoint( $aStart, $aDir, $bStart, $bDir );

        $this->assertSame(
            $cuttingPosition,
            false,
            'There should not be a cutting point.'
        );
    }

    public function testRenderAxisGrid()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisExactLabelRenderer();
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

    public function testRenderAxisOuterGrid()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisExactLabelRenderer();
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
        $chart->xAxis->axisLabelRenderer = new AxisExactLabelRenderer();
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
        $chart->xAxis->axisLabelRenderer = new AxisExactLabelRenderer();
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
        $chart->xAxis->axisLabelRenderer = new AxisExactLabelRenderer();
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
        $chart->xAxis->axisLabelRenderer = new AxisExactLabelRenderer();
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
        $chart->xAxis->axisLabelRenderer = new AxisExactLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawText',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 142., 182., 178., 198. ), 1. ),
                $this->equalTo( 'sample 1' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::LEFT )
            );
        $mockedRenderer
            ->expects( $this->at( 1 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 222., 182., 258., 198. ), 1. ),
                $this->equalTo( 'sample 2' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::LEFT )
            );
        $mockedRenderer
            ->expects( $this->at( 4 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 422., 182., 458., 198. ), 1. ),
                $this->equalTo( 'sample 5' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::RIGHT )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderTextBoxesWithoutLastLabel()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisExactLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->xAxis->axisLabelRenderer->showLastValue = false;
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawText',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 142., 182., 218., 198. ), 1. ),
                $this->equalTo( 'sample 1' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::LEFT )
            );
        $mockedRenderer
            ->expects( $this->at( 1 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 222., 182., 298., 198. ), 1. ),
                $this->equalTo( 'sample 2' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::LEFT )
            );
        $mockedRenderer
            ->expects( $this->at( 3 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 382., 182., 458., 198. ), 1. ),
                $this->equalTo( 'sample 4' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::LEFT )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderAxisGridFromRight()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisExactLabelRenderer();
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
        $chart->yAxis->axisLabelRenderer = new AxisExactLabelRenderer();
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
        $chart->yAxis->axisLabelRenderer = new AxisExactLabelRenderer();
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
        $chart->xAxis->axisLabelRenderer = new AxisExactLabelRenderer();
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
                $this->equalTo( new ezcGraphBoundings( 422., 182., 458., 198. ), 1. ),
                $this->equalTo( 'sample 1' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::RIGHT )
            );
        $mockedRenderer
            ->expects( $this->at( 1 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 342., 182., 378., 198. ), 1. ),
                $this->equalTo( 'sample 2' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::RIGHT )
            );
        $mockedRenderer
            ->expects( $this->at( 4 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 142., 182., 178., 198. ), 1. ),
                $this->equalTo( 'sample 5' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::LEFT )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderTextBoxesFromTop()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisExactLabelRenderer();
        $chart->yAxis->position = ezcGraph::TOP;
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawText',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 102., 22., 138., 38. ), 1. ),
                $this->equalTo( '0' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::RIGHT )
            );
        $mockedRenderer
            ->expects( $this->at( 1 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 102., 62., 138., 78. ), 1. ),
                $this->equalTo( '100' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::RIGHT )
            );
        $mockedRenderer
            ->expects( $this->at( 4 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 102., 162., 138., 178. ), 1. ),
                $this->equalTo( '400' ),
                $this->equalTo( ezcGraph::BOTTOM | ezcGraph::RIGHT )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRenderTextBoxesFromBottom()
    {
        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->xAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->yAxis->axisLabelRenderer = new AxisExactLabelRenderer();
        $chart->yAxis->position = ezcGraph::BOTTOM;
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        
        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawText',
        ) );

        $mockedRenderer
            ->expects( $this->at( 0 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 102., 162., 138., 178. ), 1. ),
                $this->equalTo( '0' ),
                $this->equalTo( ezcGraph::BOTTOM | ezcGraph::RIGHT )
            );
        $mockedRenderer
            ->expects( $this->at( 1 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 102., 122., 138., 138. ), 1. ),
                $this->equalTo( '100' ),
                $this->equalTo( ezcGraph::BOTTOM | ezcGraph::RIGHT )
            );
        $mockedRenderer
            ->expects( $this->at( 4 ) )
            ->method( 'drawText' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 102., 22., 138., 38. ), 1. ),
                $this->equalTo( '400' ),
                $this->equalTo( ezcGraph::TOP | ezcGraph::RIGHT )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testAxisLabelRendererPropertyMajorStepCount()
    {
        $axisLabelRenderer = new AxisExactLabelRenderer();

        $this->assertSame(
            false,
            $axisLabelRenderer->majorStepCount,
            'Wrong default value for property majorStepCount in class AxisExactLabelRenderer'
        );

        $axisLabelRenderer->majorStepCount = 1;
        $this->assertSame(
            1,
            $axisLabelRenderer->majorStepCount,
            'Setting property value did not work for property majorStepCount in class AxisExactLabelRenderer'
        );

        try
        {
            $axisLabelRenderer->majorStepCount = true;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testAxisLabelRendererPropertyMinorStepCount()
    {
        $axisLabelRenderer = new AxisExactLabelRenderer();

        $this->assertSame(
            false,
            $axisLabelRenderer->minorStepCount,
            'Wrong default value for property minorStepCount in class AxisExactLabelRenderer'
        );

        $axisLabelRenderer->minorStepCount = 1;
        $this->assertSame(
            1,
            $axisLabelRenderer->minorStepCount,
            'Setting property value did not work for property minorStepCount in class AxisExactLabelRenderer'
        );

        try
        {
            $axisLabelRenderer->minorStepCount = true;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testAxisLabelRendererPropertyMajorStepSize()
    {
        $axisLabelRenderer = new AxisExactLabelRenderer();

        $this->assertSame(
            3,
            $axisLabelRenderer->majorStepSize,
            'Wrong default value for property majorStepSize in class AxisExactLabelRenderer'
        );

        $axisLabelRenderer->majorStepSize = 1;
        $this->assertSame(
            1,
            $axisLabelRenderer->majorStepSize,
            'Setting property value did not work for property majorStepSize in class AxisExactLabelRenderer'
        );

        try
        {
            $axisLabelRenderer->majorStepSize = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testAxisLabelRendererPropertyMinorStepSize()
    {
        $axisLabelRenderer = new AxisExactLabelRenderer();

        $this->assertSame(
            1,
            $axisLabelRenderer->minorStepSize,
            'Wrong default value for property minorStepSize in class AxisExactLabelRenderer'
        );

        $axisLabelRenderer->minorStepSize = 2;
        $this->assertSame(
            2,
            $axisLabelRenderer->minorStepSize,
            'Setting property value did not work for property minorStepSize in class AxisExactLabelRenderer'
        );

        try
        {
            $axisLabelRenderer->minorStepSize = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testAxisLabelRendererPropertyInnerStep()
    {
        $axisLabelRenderer = new AxisExactLabelRenderer();

        $this->assertSame(
            true,
            $axisLabelRenderer->innerStep,
            'Wrong default value for property innerStep in class AxisExactLabelRenderer'
        );

        $axisLabelRenderer->innerStep = false;
        $this->assertSame(
            false,
            $axisLabelRenderer->innerStep,
            'Setting property value did not work for property innerStep in class AxisExactLabelRenderer'
        );

        try
        {
            $axisLabelRenderer->innerStep = 42;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testAxisLabelRendererPropertyOuterStep()
    {
        $axisLabelRenderer = new AxisExactLabelRenderer();

        $this->assertSame(
            false,
            $axisLabelRenderer->outerStep,
            'Wrong default value for property outerStep in class AxisExactLabelRenderer'
        );

        $axisLabelRenderer->outerStep = true;
        $this->assertSame(
            true,
            $axisLabelRenderer->outerStep,
            'Setting property value did not work for property outerStep in class AxisExactLabelRenderer'
        );

        try
        {
            $axisLabelRenderer->outerStep = 42;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testAxisLabelRendererPropertyOuterGrid()
    {
        $axisLabelRenderer = new AxisExactLabelRenderer();

        $this->assertSame(
            false,
            $axisLabelRenderer->outerGrid,
            'Wrong default value for property outerGrid in class AxisExactLabelRenderer'
        );

        $axisLabelRenderer->outerGrid = true;
        $this->assertSame(
            true,
            $axisLabelRenderer->outerGrid,
            'Setting property value did not work for property outerGrid in class AxisExactLabelRenderer'
        );

        try
        {
            $axisLabelRenderer->outerGrid = 42;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testAxisLabelRendererPropertyLabelPadding()
    {
        $axisLabelRenderer = new AxisExactLabelRenderer();

        $this->assertSame(
            2,
            $axisLabelRenderer->labelPadding,
            'Wrong default value for property labelPadding in class AxisExactLabelRenderer'
        );

        $axisLabelRenderer->labelPadding = 1;
        $this->assertSame(
            1,
            $axisLabelRenderer->labelPadding,
            'Setting property value did not work for property labelPadding in class AxisExactLabelRenderer'
        );

        try
        {
            $axisLabelRenderer->labelPadding = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testAxisExactLabelRendererPropertyShowLastValue()
    {
        $options = new AxisExactLabelRenderer();

        $this->assertSame(
            true,
            $options->showLastValue,
            'Wrong default value for property showLastValue in class AxisExactLabelRenderer'
        );

        $options->showLastValue = false;
        $this->assertSame(
            false,
            $options->showLastValue,
            'Setting property value did not work for property showLastValue in class AxisExactLabelRenderer'
        );

        try
        {
            $options->showLastValue = 42;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testAxisExactLabelRendererPropertyRenderLastOutside()
    {
        $options = new AxisExactLabelRenderer();

        $this->assertSame(
            false,
            $options->renderLastOutside,
            'Wrong default value for property renderLastOutside in class AxisExactLabelRenderer'
        );

        $options->renderLastOutside = true;
        $this->assertSame(
            true,
            $options->renderLastOutside,
            'Setting property value did not work for property renderLastOutside in class AxisExactLabelRenderer'
        );

        try
        {
            $options->renderLastOutside = 42;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testOutsideLabelsBottomLeft()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';
        
        $graph = new LineChart();
        $graph->legend = false;

        $graph->xAxis->axisLabelRenderer = new AxisExactLabelRenderer();
        $graph->xAxis->axisLabelRenderer->renderLastOutside = true;
        $graph->yAxis->axisLabelRenderer = new AxisExactLabelRenderer();
        $graph->yAxis->axisLabelRenderer->renderLastOutside = true;

        $graph->data['sample'] = new ArrayDataSet(
            array( 1, 4, 6, 8, 2 )
        );

        $graph->render( 560, 250, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testOutsideLabelsTopRight()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';
        
        $graph = new LineChart();
        $graph->legend = false;

        $graph->xAxis->axisLabelRenderer = new AxisExactLabelRenderer();
        $graph->xAxis->axisLabelRenderer->renderLastOutside = true;
        $graph->xAxis->position = ezcGraph::RIGHT;
        $graph->yAxis->axisLabelRenderer = new AxisExactLabelRenderer();
        $graph->yAxis->axisLabelRenderer->renderLastOutside = true;
        $graph->yAxis->position = ezcGraph::TOP;

        $graph->data['sample'] = new ArrayDataSet(
            array( 1, 4, 6, 8, 2 )
        );

        $graph->render( 560, 250, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }
}

?>
