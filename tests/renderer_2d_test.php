<?php
/**
 * Renderer2dTest 
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

use Ezc\Graph\Charts\BarChart;
use Ezc\Graph\Options\ChartOptions;
use Ezc\Graph\Options\RendererOptions;
use Ezc\Graph\Charts\LineChart;
use Ezc\Graph\Charts\PieChart;
use Ezc\Graph\Options\Renderer2dOptions;
use Ezc\Graph\Axis\ChartElementNumericAxis;
use Ezc\Graph\Colors\Color;
use Ezc\Graph\Datasets\ArrayDataSet;
use Ezc\Graph\Renderer\Renderer2d;
use Ezc\Graph\Renderer\AxisNoLabelRenderer;
use Ezc\Graph\Renderer\AxisCenteredLabelRenderer;
use Ezc\Graph\Renderer\AxisBoxedLabelRenderer;
use Ezc\Graph\Palette\Black;
use Ezc\Graph\Structs\Context;
use Ezc\Graph\Structs\Coordinate;

/**
 * Tests for ezcGraph class.
 * 
 * @package Graph
 * @subpackage Tests
 */
class Renderer2dTest extends ezcGraphTestCase
{
    protected $basePath;

    protected $tempDir;

    protected $renderer;

    protected $driver;

	public static function suite()
	{
	    return new PHPUnit_Framework_TestSuite( "Renderer2dTest" );
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

        $this->renderer = new Renderer2d();

        $this->driver = $this->getMockBuilder( 'ezcGraphSvgDriver' )
            ->enableArgumentCloning()
            ->setMethods( array(
                'drawPolygon',
                'drawLine',
                'drawTextBox',
                'drawCircleSector',
                'drawCircularArc',
                'drawCircle',
                'drawImage',
            ) )->getMock();
        $this->renderer->setDriver( $this->driver );

        $this->driver->options->width= 400;
        $this->driver->options->height= 200;
    }

    public function tearDown()
    {
        $this->driver = null;
        $this->renderer = null;

        if ( !$this->hasFailed() )
        {
            $this->removeTempDir();
        }
    }
// /*
    public function testRenderLabeledPieSegment()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawCircleSector' )
            ->with(
                $this->equalTo( new Coordinate( 200, 100 ), 1. ),
                $this->equalTo( 180, 1. ),
                $this->equalTo( 180, 1. ),
                $this->equalTo( 15, 1. ),
                $this->equalTo( 156, 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawCircleSector' )
            ->with(
                $this->equalTo( new Coordinate( 200, 100 ), 1. ),
                $this->equalTo( 180, 1. ),
                $this->equalTo( 180, 1. ),
                $this->equalTo( 15, 1. ),
                $this->equalTo( 156, 1. ),
                $this->equalTo( Color::fromHex( '#800000' ) ),
                $this->equalTo( false )
            );

        $this->driver
            ->expects( $this->at( 2 ) )
            ->method( 'drawLine' )
            ->with(
                $this->equalTo( new Coordinate( 205., 166. ), 1. ),
                $this->equalTo( new Coordinate( 250., 190. ), 1. ),
                $this->equalTo( Color::fromHex( '#000000' ) ),
                $this->equalTo( 1 )
            );

        $this->driver
            ->expects( $this->at( 3 ) )
            ->method( 'drawCircle' )
            ->with(
                $this->equalTo( new Coordinate( 205., 166. ), 1. ),
                $this->equalTo( 6 ),
                $this->equalTo( 6 ),
                $this->equalTo( Color::fromHex( '#000000' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 4 ) )
            ->method( 'drawCircle' )
            ->with(
                $this->equalTo( new Coordinate( 250., 190. ), 1. ),
                $this->equalTo( 6 ),
                $this->equalTo( 6 ),
                $this->equalTo( Color::fromHex( '#000000' ) ),
                $this->equalTo( true )
            );

        $this->driver
            ->expects( $this->at( 5 ) )
            ->method( 'drawTextBox' )
            ->with(
                $this->equalTo( 'Testlabel' ),
                $this->equalTo( new Coordinate( 256., 180. ), 1. ),
                $this->equalTo( 144.5, 1. ),
                $this->equalTo( 20., 1. ),
                $this->equalTo( 36 )
            );


        // Render
        $this->renderer->drawPieSegment(
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            new Context(),
            Color::fromHex( '#FF0000' ),
            15,
            156,
            'Testlabel',
            0
        );

        $this->renderer->render( $this->tempDir . '/' . __METHOD__ . '.svg' );
    }

    public function testRenderNonLabeledPieSegment()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawCircleSector' )
            ->with(
                $this->equalTo( new Coordinate( 200., 100. ), 1. ),
                $this->equalTo( 180., 1. ),
                $this->equalTo( 180., 1. ),
                $this->equalTo( 15., 1. ),
                $this->equalTo( 156., 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawCircleSector' )
            ->with(
                $this->equalTo( new Coordinate( 200., 100. ), 1. ),
                $this->equalTo( 180., 1. ),
                $this->equalTo( 180., 1. ),
                $this->equalTo( 15., 1. ),
                $this->equalTo( 156., 1. ),
                $this->equalTo( Color::fromHex( '#800000' ) ),
                $this->equalTo( false )
            );

        // Render
        $this->renderer->drawPieSegment(
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            new Context(),
            Color::fromHex( '#FF0000' ),
            15,
            156,
            false,
            0
        );

        $this->renderer->render( $this->tempDir . '/' . __METHOD__ . '.svg' );
    }

    public function testRenderNonLabeledPieSegmentMoveOut()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawCircleSector' )
            ->with(
                $this->equalTo( new Coordinate( 201., 109. ), 1. ),
                $this->equalTo( 180., 1. ),
                $this->equalTo( 180., 1. ),
                $this->equalTo( 15., 1. ),
                $this->equalTo( 156., 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawCircleSector' )
            ->with(
                $this->equalTo( new Coordinate( 201., 109. ), 1. ),
                $this->equalTo( 180., 1. ),
                $this->equalTo( 180., 1. ),
                $this->equalTo( 15., 1. ),
                $this->equalTo( 156., 1. ),
                $this->equalTo( Color::fromHex( '#800000' ) ),
                $this->equalTo( false )
            );

        // Render
        $this->renderer->drawPieSegment(
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            new Context(),
            Color::fromHex( '#FF0000' ),
            15,
            156,
            false,
            true
        );

        $this->renderer->render( $this->tempDir . '/' . __METHOD__ . '.svg' );
    }

    public function testRenderLotsOfLabeledPieSegments()
    {
        $this->driver
            ->expects( $this->at( 13 ) )
            ->method( 'drawTextBox' )
            ->with(
                $this->equalTo( 'Label 5' ),
                $this->equalTo( new Coordinate( 0, 180. ), 1. ),
                $this->equalTo( 144.5, 1. ),
                $this->equalTo( 20., 1. ),
                $this->equalTo( 40 )
            );
        $this->driver
            ->expects( $this->at( 17 ) )
            ->method( 'drawTextBox' )
            ->with(
                $this->equalTo( 'Label 1' ),
                $this->equalTo( new Coordinate( 307., 120. ), 1. ),
                $this->equalTo( 92.5, 1. ),
                $this->equalTo( 20., 1. ),
                $this->equalTo( 36 )
            );
        $this->driver
            ->expects( $this->at( 21 ) )
            ->method( 'drawTextBox' )
            ->with(
                $this->equalTo( 'Label 2' ),
                $this->equalTo( new Coordinate( 298.5, 140. ), 1. ),
                $this->equalTo( 101.5, 1. ),
                $this->equalTo( 20., 1. ),
                $this->equalTo( 36 )
            );
        $this->driver
            ->expects( $this->at( 25 ) )
            ->method( 'drawTextBox' )
            ->with(
                $this->equalTo( 'Label 3' ),
                $this->equalTo( new Coordinate( 283.5, 160. ), 1. ),
                $this->equalTo( 116.5, 1. ),
                $this->equalTo( 20., 1. ),
                $this->equalTo( 36 )
            );
        $this->driver
            ->expects( $this->at( 29 ) )
            ->method( 'drawTextBox' )
            ->with(
                $this->equalTo( 'Label 4' ),
                $this->equalTo( new Coordinate( 255.5, 180. ), 1. ),
                $this->equalTo( 144.5, 1. ),
                $this->equalTo( 20., 1. ),
                $this->equalTo( 36 )
            );

        // Render
        $this->renderer->drawPieSegment( new ezcGraphBoundings( 0, 0, 400, 200 ), new Context(), Color::fromHex( '#FF0000' ), 15, 27, 'Label 1', true );
        $this->renderer->drawPieSegment( new ezcGraphBoundings( 0, 0, 400, 200 ), new Context(), Color::fromHex( '#FF0000' ), 27, 38, 'Label 2', true );
        $this->renderer->drawPieSegment( new ezcGraphBoundings( 0, 0, 400, 200 ), new Context(), Color::fromHex( '#FF0000' ), 38, 45, 'Label 3', true );
        $this->renderer->drawPieSegment( new ezcGraphBoundings( 0, 0, 400, 200 ), new Context(), Color::fromHex( '#FF0000' ), 45, 70, 'Label 4', true );
        $this->renderer->drawPieSegment( new ezcGraphBoundings( 0, 0, 400, 200 ), new Context(), Color::fromHex( '#FF0000' ), 70, 119, 'Label 5', true );

        $this->renderer->render( $this->tempDir . '/' . __METHOD__ . '.svg' );
    }

    public function testRenderBar()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array( 
                    new Coordinate( 157.5, 0. ),
                    new Coordinate( 157.5, 40. ),
                    new Coordinate( 242.5, 40. ),
                    new Coordinate( 242.5, 0. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array( 
                    new Coordinate( 157.5, 0. ),
                    new Coordinate( 157.5, 40. ),
                    new Coordinate( 242.5, 40. ),
                    new Coordinate( 242.5, 0. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#800000' ) ),
                $this->equalTo( false )
            );

        $this->renderer->drawBar( 
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            new Context(),
            Color::fromHex( '#FF0000' ), 
            new Coordinate( .5, .2 ),
            100,
            0,
            1,
            0
        );
    }

    public function testRenderSecondBar()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array( 
                    new Coordinate( 157.5, 0. ),
                    new Coordinate( 157.5, 40. ),
                    new Coordinate( 197.5, 40. ),
                    new Coordinate( 197.5, 0. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( true )
            );

        $this->renderer->drawBar( 
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            new Context(),
            Color::fromHex( '#FF0000' ), 
            new Coordinate( .5, .2 ),
            100,
            1,
            2,
            0
        );
    }

    public function testRenderStackedBar()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array( 
                    new Coordinate( 155, 40. ),
                    new Coordinate( 155, 120. ),
                    new Coordinate( 245, 120. ),
                    new Coordinate( 245, 40. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array( 
                    new Coordinate( 155, 40. ),
                    new Coordinate( 155, 120. ),
                    new Coordinate( 245, 120. ),
                    new Coordinate( 245, 40. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#800000' ) ),
                $this->equalTo( false )
            );

        $this->renderer->drawStackedBar( 
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            new Context(),
            Color::fromHex( '#FF0000' ), 
            new Coordinate( .5, .2 ),
            new Coordinate( .5, .6 ),
            100,
            0
        );
    }

    public function testRenderDataLine()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawLine' )
            ->with(
                $this->equalTo( new Coordinate( 40., 40. ), 1. ),
                $this->equalTo( new Coordinate( 280., 60. ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( 1 )
            );

        $this->renderer->drawDataLine( 
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            new Context(),
            Color::fromHex( '#FF0000' ), 
            new Coordinate( .1, .2 ),
            new Coordinate( .7, .3 )
        );
    }

    public function testRenderFilledDataLine()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 40., 40. ),
                    new Coordinate( 280., 60. ),
                    new Coordinate( 280., 0. ),
                    new Coordinate( 40., 0. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000DD' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawLine' )
            ->with(
                $this->equalTo( new Coordinate( 40., 40. ), 1. ),
                $this->equalTo( new Coordinate( 280., 60. ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( 1 )
            );

        $this->renderer->drawDataLine( 
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            new Context(),
            Color::fromHex( '#FF0000' ), 
            new Coordinate( .1, .2 ),
            new Coordinate( .7, .3 ),
            0,
            1,
            ezcGraph::NO_SYMBOL,
            null, 
            Color::fromHex( '#FF0000DD' ), 
            .0
        );
    }

    public function testRenderFilledDataLineWithIntersection()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 40., 100. ),
                    new Coordinate( 40., 40. ),
                    new Coordinate( 184., 100. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000DD' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 280., 100. ),
                    new Coordinate( 280., 140. ),
                    new Coordinate( 184., 100. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000DD' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 2 ) )
            ->method( 'drawLine' )
            ->with(
                $this->equalTo( new Coordinate( 40., 40. ), 1. ),
                $this->equalTo( new Coordinate( 280., 140. ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( 1 )
            );

        $this->renderer->drawDataLine( 
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            new Context(),
            Color::fromHex( '#FF0000' ), 
            new Coordinate( .1, .2 ),
            new Coordinate( .7, .7 ),
            0,
            1,
            ezcGraph::NO_SYMBOL,
            null, 
            Color::fromHex( '#FF0000DD' ), 
            .5
        );
    }

    public function testRenderRadarDataLine()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawLine' )
            ->with(
                $this->equalTo( new Coordinate( 200., 50. ), 1. ),
                $this->equalTo( new Coordinate( 300., 100. ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( 1 )
            );

        $this->renderer->drawRadarDataLine( 
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            new Context(),
            Color::fromHex( '#FF0000' ), 
            new Coordinate( 200., 100. ),
            new Coordinate( 0., .5 ),
            new Coordinate( .25, .5 )
        );
    }

    public function testRenderFilledRadarDataLine()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 200., 50. ),
                    new Coordinate( 300., 100. ),
                    new Coordinate( 200., 100. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000DD' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawLine' )
            ->with(
                $this->equalTo( new Coordinate( 200., 50. ), 1. ),
                $this->equalTo( new Coordinate( 300., 100. ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( 1 )
            );

        $this->renderer->drawRadarDataLine( 
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            new Context(),
            Color::fromHex( '#FF0000' ), 
            new Coordinate( 200., 100. ),
            new Coordinate( 0., .5 ),
            new Coordinate( .25, .5 ),
            0,
            1,
            ezcGraph::NO_SYMBOL,
            null, 
            Color::fromHex( '#FF0000DD' )
        );
    }

    public function testRenderFilledRadarDataLineWithSymbol()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 200., 50. ),
                    new Coordinate( 300., 100. ),
                    new Coordinate( 200., 100. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000DD' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawLine' )
            ->with(
                $this->equalTo( new Coordinate( 200., 50. ), 1. ),
                $this->equalTo( new Coordinate( 300., 100. ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( 1 )
            );
        $this->driver
            ->expects( $this->at( 2 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 300., 97. ),
                    new Coordinate( 303., 100. ),
                    new Coordinate( 300., 103. ),
                    new Coordinate( 297., 100. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( true )
            );

        $this->renderer->drawRadarDataLine( 
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            new Context(),
            Color::fromHex( '#FF0000' ), 
            new Coordinate( 200., 100. ),
            new Coordinate( 0., .5 ),
            new Coordinate( .25, .5 ),
            0,
            1,
            ezcGraph::DIAMOND,
            Color::fromHex( '#FF0000' ), 
            Color::fromHex( '#FF0000DD' )
        );
    }

    public function testRenderSymbolNone()
    {
        $this->driver
            ->expects( $this->once() )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 100, 100 ),
                    new Coordinate( 120, 100 ),
                    new Coordinate( 120, 120 ),
                    new Coordinate( 100, 120 ),
                ) ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                true
            );

        $this->renderer->drawSymbol(
            new ezcGraphBoundings( 100, 100, 120, 120 ),
            Color::fromHex( '#FF0000' )
        );
    }

    public function testRenderSymbolDiamond()
    {
        $this->driver
            ->expects( $this->once() )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 110, 100 ),
                    new Coordinate( 120, 110 ),
                    new Coordinate( 110, 120 ),
                    new Coordinate( 100, 110 ),
                ) ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                true
            );

        $this->renderer->drawSymbol(
            new ezcGraphBoundings( 100, 100, 120, 120 ),
            Color::fromHex( '#FF0000' ),
            ezcGraph::DIAMOND
        );
    }

    public function testRenderSymbolBullet()
    {
        $this->driver
            ->expects( $this->once() )
            ->method( 'drawCircle' )
            ->with(
                $this->equalTo( new Coordinate( 110, 110 ) ),
                $this->equalTo( 20 ),
                $this->equalTo( 20 ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( true )
            );

        $this->renderer->drawSymbol(
            new ezcGraphBoundings( 100, 100, 120, 120 ),
            Color::fromHex( '#FF0000' ),
            ezcGraph::BULLET
        );
    }

    public function testRenderSymbolCircle()
    {
        $this->driver
            ->expects( $this->once() )
            ->method( 'drawCircle' )
            ->with(
                $this->equalTo( new Coordinate( 110, 110 ) ),
                $this->equalTo( 20 ),
                $this->equalTo( 20 ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( false )
            );

        $this->renderer->drawSymbol(
            new ezcGraphBoundings( 100, 100, 120, 120 ),
            Color::fromHex( '#FF0000' ),
            ezcGraph::CIRCLE
        );
    }

    public function testRenderSymbolSquare()
    {
        $this->driver
            ->expects( $this->once() )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 100, 100 ),
                    new Coordinate( 120, 100 ),
                    new Coordinate( 120, 120 ),
                    new Coordinate( 100, 120 ),
                ) ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( true )
            );

        $this->renderer->drawSymbol(
            new ezcGraphBoundings( 100, 100, 120, 120 ),
            Color::fromHex( '#FF0000' ),
            ezcGraph::SQUARE
        );
    }

    public function testRenderSymbolBox()
    {
        $this->driver
            ->expects( $this->once() )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 100, 100 ),
                    new Coordinate( 120, 100 ),
                    new Coordinate( 120, 120 ),
                    new Coordinate( 100, 120 ),
                ) ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( false )
            );

        $this->renderer->drawSymbol(
            new ezcGraphBoundings( 100, 100, 120, 120 ),
            Color::fromHex( '#FF0000' ),
            ezcGraph::BOX
        );
    }

    public function testRenderFilledDataLineWithSymbolSameColor()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 40., 100. ),
                    new Coordinate( 40., 40. ),
                    new Coordinate( 184., 100. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000DD' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 280., 100. ),
                    new Coordinate( 280., 140. ),
                    new Coordinate( 184., 100. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000DD' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 2 ) )
            ->method( 'drawLine' )
            ->with(
                $this->equalTo( new Coordinate( 40., 40. ), 1. ),
                $this->equalTo( new Coordinate( 280., 140. ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( 1 )
            );
        $this->driver
            ->expects( $this->at( 3 ) )
            ->method( 'drawCircle' )
            ->with(
                $this->equalTo( new Coordinate( 280, 140 ) ),
                $this->equalTo( 6 ),
                $this->equalTo( 6 ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( false )
            );

        $this->renderer->drawDataLine( 
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            new Context(),
            Color::fromHex( '#FF0000' ), 
            new Coordinate( .1, .2 ),
            new Coordinate( .7, .7 ),
            0,
            1,
            ezcGraph::CIRCLE,
            null, 
            Color::fromHex( '#FF0000DD' ), 
            .5
        );
        $this->renderer->render( $this->tempDir . __METHOD__ . 'svg' );
    }

    public function testRenderFilledDataLineWithSymbolInDifferentColorAndCustomSize()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 40., 100. ),
                    new Coordinate( 40., 40. ),
                    new Coordinate( 184., 100. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000DD' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 280., 100. ),
                    new Coordinate( 280., 140. ),
                    new Coordinate( 184., 100. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000DD' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 2 ) )
            ->method( 'drawLine' )
            ->with(
                $this->equalTo( new Coordinate( 40., 40. ), 1. ),
                $this->equalTo( new Coordinate( 280., 140. ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( 1 )
            );
        $this->driver
            ->expects( $this->at( 3 ) )
            ->method( 'drawCircle' )
            ->with(
                $this->equalTo( new Coordinate( 280, 140 ) ),
                $this->equalTo( 10 ),
                $this->equalTo( 10 ),
                $this->equalTo( Color::fromHex( '#00FF00' ) ),
                $this->equalTo( true )
            );

        $this->renderer->options->symbolSize = 10;

        $this->renderer->drawDataLine( 
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            new Context(),
            Color::fromHex( '#FF0000' ), 
            new Coordinate( .1, .2 ),
            new Coordinate( .7, .7 ),
            0,
            1,
            ezcGraph::BULLET,
            Color::fromHex( '#00FF00' ), 
            Color::fromHex( '#FF0000DD' ), 
            .5
        );
        $this->renderer->render( $this->tempDir . __METHOD__ . 'svg' );
    }

    public function testRenderBox()
    {
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 1., 1. ),
                    new Coordinate( 399., 1. ),
                    new Coordinate( 399., 199. ),
                    new Coordinate( 1., 199. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( false )
            );
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 2., 2. ),
                    new Coordinate( 398., 2. ),
                    new Coordinate( 398., 198. ),
                    new Coordinate( 2., 198. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#BB0000' ) ),
                $this->equalTo( true )
            );

        $boundings = $this->renderer->drawBox( 
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            Color::fromHex( '#BB0000' ), 
            Color::fromHex( '#FF0000' ), 
            1,
            1,
            1
        );

        $this->assertEquals(
            $boundings,
            new ezcGraphBoundings( 3., 3., 397., 197. ),
            'Returned boundings are not as expected.',
            1.
        );
    }

    public function testRenderBoxDifferentPadding()
    {
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 3., 3. ),
                    new Coordinate( 397., 3. ),
                    new Coordinate( 397., 197. ),
                    new Coordinate( 3., 197. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( false )
            );
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 3., 3. ),
                    new Coordinate( 397., 3. ),
                    new Coordinate( 397., 197. ),
                    new Coordinate( 3., 197. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#BB0000' ) ),
                $this->equalTo( true )
            );

        $boundings = $this->renderer->drawBox( 
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            Color::fromHex( '#BB0000' ), 
            Color::fromHex( '#FF0000' ), 
            2,
            3,
            4
        );

        $this->assertEquals(
            $boundings,
            new ezcGraphBoundings( 9., 9., 391., 191. ),
            'Returned boundings are not as expected.',
            1.
        );
    }

    public function testRenderBoxWithoutBorder()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 1., 1. ),
                    new Coordinate( 399., 1. ),
                    new Coordinate( 399., 199. ),
                    new Coordinate( 1., 199. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#BB0000' ) ),
                $this->equalTo( true )
            );

        $boundings = $this->renderer->drawBox( 
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            Color::fromHex( '#BB0000' ), 
            null, 
            0,
            1,
            1
        );

        $this->assertEquals(
            $boundings,
            new ezcGraphBoundings( 2., 2., 398., 198. ),
            'Returned boundings are not as expected.',
            1.
        );
    }

    public function testRenderBoxWithoutBackground()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 1., 1. ),
                    new Coordinate( 399., 1. ),
                    new Coordinate( 399., 199. ),
                    new Coordinate( 1., 199. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( false )
            );

        $boundings = $this->renderer->drawBox( 
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            null, 
            Color::fromHex( '#FF0000' ), 
            1,
            1,
            1
        );

        $this->assertEquals(
            $boundings,
            new ezcGraphBoundings( 3., 3., 397., 197. ),
            'Returned boundings are not as expected.',
            1.
        );
    }

    public function testRenderBoxWithTitle()
    {
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 1., 1. ),
                    new Coordinate( 399., 1. ),
                    new Coordinate( 399., 199. ),
                    new Coordinate( 1., 199. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( false )
            );
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 2., 2. ),
                    new Coordinate( 398., 2. ),
                    new Coordinate( 398., 198. ),
                    new Coordinate( 2., 198. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#BB0000' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 2 ) )
            ->method( 'drawTextBox' )
            ->with(
                $this->equalTo( 'Boxtitle' ),
                $this->equalTo( new Coordinate( 3., 3. ), 1. ),
                $this->equalTo( 394., 1. ),
                $this->equalTo( 20., 1. ),
                $this->equalTo( 48 )
            );

        $boundings = $this->renderer->drawBox( 
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            Color::fromHex( '#BB0000' ), 
            Color::fromHex( '#FF0000' ), 
            1,
            1,
            1,
            'Boxtitle',
            20
        );

        $this->assertEquals(
            $boundings,
            new ezcGraphBoundings( 3., 24., 397., 176. ),
            'Returned boundings are not as expected.',
            1.
        );
    }

    public function testRenderBoxWithBottomTitleAndLeftAlignement()
    {
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 1., 1. ),
                    new Coordinate( 399., 1. ),
                    new Coordinate( 399., 199. ),
                    new Coordinate( 1., 199. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( false )
            );
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 2., 2. ),
                    new Coordinate( 398., 2. ),
                    new Coordinate( 398., 198. ),
                    new Coordinate( 2., 198. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#BB0000' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 2 ) )
            ->method( 'drawTextBox' )
            ->with(
                $this->equalTo( 'Boxtitle' ),
                $this->equalTo( new Coordinate( 3., 177. ), 1. ),
                $this->equalTo( 394., 1. ),
                $this->equalTo( 20., 1. ),
                $this->equalTo( 4 )
            );

        $this->renderer->options->titleAlignement = ezcGraph::LEFT;
        $this->renderer->options->titlePosition = ezcGraph::BOTTOM;

        $boundings = $this->renderer->drawBox( 
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            Color::fromHex( '#BB0000' ), 
            Color::fromHex( '#FF0000' ), 
            1,
            1,
            1,
            'Boxtitle',
            20
        );

        $this->assertEquals(
            $boundings,
            new ezcGraphBoundings( 3., 3., 397., 176. ),
            'Returned boundings are not as expected.',
            1.
        );
    }

    public function testRenderText()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawTextBox' )
            ->with(
                $this->equalTo( 'A common test string is "foobar"' ),
                $this->equalTo( new Coordinate( 0., 0. ), 1. ),
                $this->equalTo( 400., 1. ),
                $this->equalTo( 200., 1. ),
                $this->equalTo( 20 )
            );

        $this->renderer->drawText( 
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            'A common test string is "foobar"',
            20
        );
    }

    public function testRenderBackgroundImage()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawImage' )
            ->with(
                $this->equalTo( dirname( __FILE__ ) . '/data/jpeg.jpg' ),
                $this->equalTo( new Coordinate( 125., 43.5 ), 1. ),
                $this->equalTo( 150., 1. ),
                $this->equalTo( 113., 1. )
            );

        $this->renderer->drawBackgroundImage(
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            dirname( __FILE__ ) . '/data/jpeg.jpg'
        );
    }

    public function testRenderTopLeftBackgroundImage()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawImage' )
            ->with(
                $this->equalTo( dirname( __FILE__ ) . '/data/jpeg.jpg' ),
                $this->equalTo( new Coordinate( 0., 0. ), 1. ),
                $this->equalTo( 150., 1. ),
                $this->equalTo( 113., 1. )
            );

        $this->renderer->drawBackgroundImage(
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            dirname( __FILE__ ) . '/data/jpeg.jpg',
            ezcGraph::TOP | ezcGraph::LEFT
        );
    }

    public function testRenderBottomRightBackgroundImage()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawImage' )
            ->with(
                $this->equalTo( dirname( __FILE__ ) . '/data/jpeg.jpg' ),
                $this->equalTo( new Coordinate( 250., 87. ), 1. ),
                $this->equalTo( 150., 1. ),
                $this->equalTo( 113., 1. )
            );

        $this->renderer->drawBackgroundImage(
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            dirname( __FILE__ ) . '/data/jpeg.jpg',
            ezcGraph::BOTTOM | ezcGraph::RIGHT
        );
    }

    public function testRenderToBigBackgroundImage()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawImage' )
            ->with(
                $this->equalTo( dirname( __FILE__ ) . '/data/jpeg.jpg' ),
                $this->equalTo( new Coordinate( 0., 0. ), 1. ),
                $this->equalTo( 100., 1. ),
                $this->equalTo( 100., 1. )
            );

        $this->renderer->drawBackgroundImage(
            new ezcGraphBoundings( 0, 0, 100, 100 ),
            dirname( __FILE__ ) . '/data/jpeg.jpg',
            ezcGraph::BOTTOM | ezcGraph::RIGHT
        );
    }

    public function testRenderBackgroundImageRepeatX()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawImage' )
            ->with(
                $this->equalTo( dirname( __FILE__ ) . '/data/jpeg.jpg' ),
                $this->equalTo( new Coordinate( 0., 87. ), 1. ),
                $this->equalTo( 150., 1. ),
                $this->equalTo( 113., 1. )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawImage' )
            ->with(
                $this->equalTo( dirname( __FILE__ ) . '/data/jpeg.jpg' ),
                $this->equalTo( new Coordinate( 150., 87. ), 1. ),
                $this->equalTo( 150., 1. ),
                $this->equalTo( 113., 1. )
            );
        $this->driver
            ->expects( $this->at( 2 ) )
            ->method( 'drawImage' )
            ->with(
                $this->equalTo( dirname( __FILE__ ) . '/data/jpeg.jpg' ),
                $this->equalTo( new Coordinate( 300., 87. ), 1. ),
                $this->equalTo( 150., 1. ),
                $this->equalTo( 113., 1. )
            );

        $this->renderer->drawBackgroundImage(
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            dirname( __FILE__ ) . '/data/jpeg.jpg',
            ezcGraph::BOTTOM | ezcGraph::RIGHT,
            ezcGraph::HORIZONTAL
        );
    }

    public function testRenderBackgroundImageRepeatY()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawImage' )
            ->with(
                $this->equalTo( dirname( __FILE__ ) . '/data/jpeg.jpg' ),
                $this->equalTo( new Coordinate( 250., 0. ), 1. ),
                $this->equalTo( 150., 1. ),
                $this->equalTo( 113., 1. )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawImage' )
            ->with(
                $this->equalTo( dirname( __FILE__ ) . '/data/jpeg.jpg' ),
                $this->equalTo( new Coordinate( 250., 113. ), 1. ),
                $this->equalTo( 150., 1. ),
                $this->equalTo( 113., 1. )
            );

        $this->renderer->drawBackgroundImage(
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            dirname( __FILE__ ) . '/data/jpeg.jpg',
            ezcGraph::BOTTOM | ezcGraph::RIGHT,
            ezcGraph::VERTICAL
        );
    }

    public function testRenderBackgroundImageRepeatBoth()
    {
        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawImage' )
            ->with(
                $this->equalTo( dirname( __FILE__ ) . '/data/jpeg.jpg' ),
                $this->equalTo( new Coordinate( 0., 0. ), 1. ),
                $this->equalTo( 150., 1. ),
                $this->equalTo( 113., 1. )
            );
        $this->driver
            ->expects( $this->at( 3 ) )
            ->method( 'drawImage' )
            ->with(
                $this->equalTo( dirname( __FILE__ ) . '/data/jpeg.jpg' ),
                $this->equalTo( new Coordinate( 150., 113. ), 1. ),
                $this->equalTo( 150., 1. ),
                $this->equalTo( 113., 1. )
            );
        $this->driver
            ->expects( $this->at( 5 ) )
            ->method( 'drawImage' )
            ->with(
                $this->equalTo( dirname( __FILE__ ) . '/data/jpeg.jpg' ),
                $this->equalTo( new Coordinate( 300., 113. ), 1. ),
                $this->equalTo( 150., 1. ),
                $this->equalTo( 113., 1. )
            );

        $this->renderer->drawBackgroundImage(
            new ezcGraphBoundings( 0, 0, 400, 200 ),
            dirname( __FILE__ ) . '/data/jpeg.jpg',
            ezcGraph::BOTTOM | ezcGraph::RIGHT,
            ezcGraph::VERTICAL | ezcGraph::HORIZONTAL
        );
    }

    public function testRenderVerticalLegendSymbols()
    {
        $chart = new LineChart();

        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['sampleData']->color = '#0000FF';
        $chart->data['sampleData']->symbol = ezcGraph::DIAMOND;
        $chart->data['moreData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['moreData']->color = '#FF0000';
        $chart->data['evenMoreData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['evenMoreData']->color = '#00FF00';
        $chart->data['evenMoreData']->label = 'Even more data';

        $chart->legend->generateFromDataSets( $chart->data );

        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 8., 1. ),
                    new Coordinate( 15., 8. ),
                    new Coordinate( 8., 15. ),
                    new Coordinate( 1., 8. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#0000FF' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 2 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 1., 19. ),
                    new Coordinate( 15., 19. ),
                    new Coordinate( 15., 33. ),
                    new Coordinate( 1., 33. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 4 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 1., 37. ),
                    new Coordinate( 15., 37. ),
                    new Coordinate( 15., 51. ),
                    new Coordinate( 1., 51. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#00FF00' ) ),
                $this->equalTo( true )
            );

        $this->renderer->drawLegend(
            new ezcGraphBoundings( 0, 0, 100, 200 ),
            $chart->legend
        );
    }

    public function testRenderVerticalLegendText()
    {
        $chart = new LineChart();

        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['sampleData']->color = '#0000FF';
        $chart->data['sampleData']->symbol = ezcGraph::DIAMOND;
        $chart->data['moreData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['moreData']->color = '#FF0000';
        $chart->data['evenMoreData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['evenMoreData']->color = '#00FF00';
        $chart->data['evenMoreData']->label = 'Even more data';

        $chart->legend->generateFromDataSets( $chart->data );

        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawTextBox' )
            ->with(
                $this->equalTo( 'sampleData' ),
                $this->equalTo( new Coordinate( 16., 1. ), 1. ),
                $this->equalTo( 83., 1. ),
                $this->equalTo( 14., 1. ),
                $this->equalTo( 36 )
            );
        $this->driver
            ->expects( $this->at( 3 ) )
            ->method( 'drawTextBox' )
            ->with(
                $this->equalTo( 'moreData' ),
                $this->equalTo( new Coordinate( 16., 19. ), 1. ),
                $this->equalTo( 83., 1. ),
                $this->equalTo( 14., 1. ),
                $this->equalTo( 36 )
            );
        $this->driver
            ->expects( $this->at( 5 ) )
            ->method( 'drawTextBox' )
            ->with(
                $this->equalTo( 'Even more data' ),
                $this->equalTo( new Coordinate( 16., 37. ), 1. ),
                $this->equalTo( 83., 1. ),
                $this->equalTo( 14., 1. ),
                $this->equalTo( 36 )
            );

        $this->renderer->drawLegend(
            new ezcGraphBoundings( 0, 0, 100, 200 ),
            $chart->legend
        );
    }

    public function testRenderHorizontalLegendSymbols()
    {
        $chart = new LineChart();

        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['sampleData']->color = '#0000FF';
        $chart->data['sampleData']->symbol = ezcGraph::DIAMOND;
        $chart->data['moreData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['moreData']->color = '#FF0000';
        $chart->data['evenMoreData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['evenMoreData']->color = '#00FF00';
        $chart->data['evenMoreData']->label = 'Even more data';

        $chart->legend->generateFromDataSets( $chart->data );

        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 8., 1. ),
                    new Coordinate( 15., 8. ),
                    new Coordinate( 8., 15. ),
                    new Coordinate( 1., 8. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#0000FF' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 2 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 101., 1. ),
                    new Coordinate( 115., 1. ),
                    new Coordinate( 115., 15. ),
                    new Coordinate( 101., 15. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#FF0000' ) ),
                $this->equalTo( true )
            );
        $this->driver
            ->expects( $this->at( 4 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 201., 1. ),
                    new Coordinate( 215., 1. ),
                    new Coordinate( 215., 15. ),
                    new Coordinate( 201., 15. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#00FF00' ) ),
                $this->equalTo( true )
            );

        $this->renderer->drawLegend(
            new ezcGraphBoundings( 0, 0, 300, 50 ),
            $chart->legend,
            ezcGraph::HORIZONTAL
        );
    }

    public function testRenderHorizontalLegendText()
    {
        $chart = new LineChart();

        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['sampleData']->color = '#0000FF';
        $chart->data['sampleData']->symbol = ezcGraph::DIAMOND;
        $chart->data['moreData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['moreData']->color = '#FF0000';
        $chart->data['evenMoreData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['evenMoreData']->color = '#00FF00';
        $chart->data['evenMoreData']->label = 'Even more data';

        $chart->legend->generateFromDataSets( $chart->data );

        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawTextBox' )
            ->with(
                $this->equalTo( 'sampleData' ),
                $this->equalTo( new Coordinate( 16., 1. ), 1. ),
                $this->equalTo( 81., 1. ),
                $this->equalTo( 14., 1. ),
                $this->equalTo( 36 )
            );
        $this->driver
            ->expects( $this->at( 3 ) )
            ->method( 'drawTextBox' )
            ->with(
                $this->equalTo( 'moreData' ),
                $this->equalTo( new Coordinate( 116., 1. ), 1. ),
                $this->equalTo( 81., 1. ),
                $this->equalTo( 14., 1. ),
                $this->equalTo( 36 )
            );
        $this->driver
            ->expects( $this->at( 5 ) )
            ->method( 'drawTextBox' )
            ->with(
                $this->equalTo( 'Even more data' ),
                $this->equalTo( new Coordinate( 216., 1. ), 1. ),
                $this->equalTo( 81., 1. ),
                $this->equalTo( 14., 1. ),
                $this->equalTo( 36 )
            );

        $this->renderer->drawLegend(
            new ezcGraphBoundings( 0, 0, 300, 50 ),
            $chart->legend,
            ezcGraph::HORIZONTAL
        );
    }
    
    public function testRenderVerticalAxis()
    {
        $chart = new LineChart();
        $chart->yAxis->addData( array( 1, 2, 3, 4, 5 ) );
        $chart->yAxis->calculateAxisBoundings();

        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawLine' )
            ->with(
                $this->equalTo( new Coordinate( 140., 220. ), 1. ),
                $this->equalTo( new Coordinate( 140., 20. ), 1. ),
                $this->equalTo( Color::fromHex( '#2E3436' ) ),
                $this->equalTo( 1 )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 140., 20. ),
                    new Coordinate( 142.5, 25. ),
                    new Coordinate( 137.5, 25. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#2E3436' ) ),
                $this->equalTo( true )
            );

        $this->renderer->drawAxis(
            new ezcGraphBoundings( 100, 20, 500, 220 ),
            new Coordinate( 40, 200 ),
            new Coordinate( 40, 0 ),
            $chart->yAxis,
            new AxisCenteredLabelRenderer(),
            new ezcGraphBoundings( 140, 40, 460, 200 )
        );
    }
    
    public function testRenderVerticalShortAxis()
    {
        $chart = new LineChart();
        $chart->yAxis->addData( array( 1, 2, 3, 4, 5 ) );
        $chart->yAxis->calculateAxisBoundings();

        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawLine' )
            ->with(
                $this->equalTo( new Coordinate( 140., 200. ), 1. ),
                $this->equalTo( new Coordinate( 140., 40. ), 1. ),
                $this->equalTo( Color::fromHex( '#2E3436' ) ),
                $this->equalTo( 1 )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 140., 40. ),
                    new Coordinate( 142, 45. ),
                    new Coordinate( 138, 45. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#2E3436' ) ),
                $this->equalTo( true )
            );

        $this->renderer->options->shortAxis = true;
        $this->renderer->drawAxis(
            new ezcGraphBoundings( 100, 20, 500, 220 ),
            new Coordinate( 40, 200 ),
            new Coordinate( 40, 0 ),
            $chart->yAxis,
            new AxisCenteredLabelRenderer(),
            new ezcGraphBoundings( 140, 40, 460, 200 )
        );
    }
    
    public function testRenderVerticalAxisReverse()
    {
        $chart = new LineChart();
        $chart->yAxis->addData( array( 1, 2, 3, 4, 5 ) );
        $chart->yAxis->calculateAxisBoundings();

        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawLine' )
            ->with(
                $this->equalTo( new Coordinate( 140., 20. ), 1. ),
                $this->equalTo( new Coordinate( 140., 220. ), 1. ),
                $this->equalTo( Color::fromHex( '#2E3436' ) ),
                $this->equalTo( 1 )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 140., 220. ),
                    new Coordinate( 137.5, 215. ),
                    new Coordinate( 142.5, 215. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#2E3436' ) ),
                $this->equalTo( true )
            );

        $this->renderer->drawAxis(
            new ezcGraphBoundings( 100, 20, 500, 220 ),
            new Coordinate( 40, 0 ),
            new Coordinate( 40, 200 ),
            $chart->yAxis,
            new AxisCenteredLabelRenderer(),
            new ezcGraphBoundings( 140, 40, 460, 200 )
        );
    }
    
    public function testRenderHorizontalAxis()
    {
        $chart = new LineChart();
        $chart->yAxis->addData( array( 1, 2, 3, 4, 5 ) );
        $chart->yAxis->calculateAxisBoundings();

        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawLine' )
            ->with(
                $this->equalTo( new Coordinate( 150., 120. ), 1. ),
                $this->equalTo( new Coordinate( 450., 120. ), 1. ),
                $this->equalTo( Color::fromHex( '#2E3436' ) ),
                $this->equalTo( 1 )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 450., 120. ),
                    new Coordinate( 442., 124. ),
                    new Coordinate( 442., 116. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#2E3436' ) ),
                $this->equalTo( true )
            );

        $this->renderer->drawAxis(
            new ezcGraphBoundings( 100, 20, 500, 220 ),
            new Coordinate( 50, 100 ),
            new Coordinate( 350, 100 ),
            $chart->yAxis,
            new AxisCenteredLabelRenderer(),
            new ezcGraphBoundings( 140, 40, 460, 200 )
        );
    }
    
    public function testRenderHorizontalShortAxis()
    {
        $chart = new LineChart();
        $chart->xAxis->addData( array( 1, 2, 3, 4, 5 ) );
        $chart->xAxis->calculateAxisBoundings();

        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawLine' )
            ->with(
                $this->equalTo( new Coordinate( 140., 120. ), 1. ),
                $this->equalTo( new Coordinate( 460., 120. ), 1. ),
                $this->equalTo( Color::fromHex( '#2E3436' ) ),
                $this->equalTo( 1 )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 460., 120. ),
                    new Coordinate( 452, 124. ),
                    new Coordinate( 452, 116. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#2E3436' ) ),
                $this->equalTo( true )
            );

        $this->renderer->options->shortAxis = true;
        $this->renderer->drawAxis(
            new ezcGraphBoundings( 100, 20, 500, 220 ),
            new Coordinate( 0, 100 ),
            new Coordinate( 400, 100 ),
            $chart->xAxis,
            new AxisCenteredLabelRenderer(),
            new ezcGraphBoundings( 140, 40, 460, 200 )
        );
    }
    
    public function testRenderHorizontalAxisReverse()
    {
        $chart = new LineChart();
        $chart->yAxis->addData( array( 1, 2, 3, 4, 5 ) );
        $chart->yAxis->calculateAxisBoundings();

        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawLine' )
            ->with(
                $this->equalTo( new Coordinate( 450., 120. ), 1. ),
                $this->equalTo( new Coordinate( 150., 120. ), 1. ),
                $this->equalTo( Color::fromHex( '#2E3436' ) ),
                $this->equalTo( 1 )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 150., 120. ),
                    new Coordinate( 157., 116.5 ),
                    new Coordinate( 157., 123.5 ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#2E3436' ) ),
                $this->equalTo( true )
            );

        $this->renderer->drawAxis(
            new ezcGraphBoundings( 100, 20, 500, 220 ),
            new Coordinate( 350, 100 ),
            new Coordinate( 50, 100 ),
            $chart->yAxis,
            new AxisCenteredLabelRenderer(),
            new ezcGraphBoundings( 140, 40, 460, 200 )
        );
    }

    public function testRenderLineChartToOutput()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new LineChart();
        $chart->palette = new Black();

        $chart->data['Line 1'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );

        ob_start();
        // Suppress header already sent warning
        @$chart->renderToOutput( 500, 200 );
        file_put_contents( $filename, ob_get_clean() );

        $this->compare(
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg',
            $filename
        );
    }

    public function testRenderLineChart()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new LineChart();
        $chart->palette = new Black();

        $chart->data['Line 1'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['Line 2'] = new ArrayDataSet( array( 'sample 1' => 543, 'sample 2' => 234, 'sample 3' => 298, 'sample 4' => 5, 'sample 5' => 613) );

        $chart->driver = new ezcGraphSvgDriver();
        $chart->render( 500, 200, $filename );

        $this->compare(
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg',
            $filename
        );
    }

    public function testRenderLineChartZeroAxisSpace()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new LineChart();
        $chart->palette = new Black();

        $chart->xAxis->axisSpace = .0;
        $chart->xAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->yAxis->axisSpace = .0;
        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();

        $chart->data['Line 1'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['Line 2'] = new ArrayDataSet( array( 'sample 1' => 543, 'sample 2' => 234, 'sample 3' => 298, 'sample 4' => 5, 'sample 5' => 613) );

        $chart->driver = new ezcGraphSvgDriver();
        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderFilledLineChart()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->options->fillLines = 200;

        $chart->data['Line 1'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['Line 2'] = new ArrayDataSet( array( 'sample 1' => 543, 'sample 2' => 234, 'sample 3' => 298, 'sample 4' => 5, 'sample 5' => 613) );

        $chart->driver = new ezcGraphSvgDriver();
        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderBarChartWithMoreBarsThenMajorSteps()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new BarChart();
        $chart->legend = false;

        $chart->xAxis = new ChartElementNumericAxis();
        $chart->xAxis->axisLabelRenderer = new AxisBoxedLabelRenderer();

        $chart->data['dataset'] = new ArrayDataSet( array( 12, 43, 324, 12, 43, 125, 120, 123 , 543,  12, 45, 76, 87 , 99, 834, 34, 453 ) );
        $chart->data['dataset']->color = '#3465A47F';

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderFilledLineBarChart()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->options->fillLines = 200;

        $chart->data['Line 0'] = new ArrayDataSet( array( 'sample 1' => 432, 'sample 2' => 43, 'sample 3' => 65, 'sample 4' => 97, 'sample 5' => 154) );
        $chart->data['Line 1'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['Line 2'] = new ArrayDataSet( array( 'sample 1' => 543, 'sample 2' => 234, 'sample 3' => 298, 'sample 4' => 5, 'sample 5' => 613) );

        $chart->data['Line 0']->displayType = ezcGraph::BAR;
        $chart->data['Line 1']->displayType = ezcGraph::BAR;

        $chart->xAxis->axisLabelRenderer = new AxisBoxedLabelRenderer();

        $chart->driver = new ezcGraphSvgDriver();
        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderFilledLineChartWithAxisIntersection()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new LineChart();
        $chart->palette = new Black();
        $chart->options->fillLines = 200;

        $chart->data['Line 1'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => -151, 'sample 3' => 324, 'sample 4' => -120, 'sample 5' => 1) );
        $chart->data['Line 2'] = new ArrayDataSet( array( 'sample 1' => 543, 'sample 2' => 234, 'sample 3' => 298, 'sample 4' => -5, 'sample 5' => -124) );

        $chart->driver = new ezcGraphSvgDriver();
        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderLineChartWithDifferentAxisSpace()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new LineChart();
        $chart->palette = new Black();

        $chart->data['Line 1'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['Line 2'] = new ArrayDataSet( array( 'sample 1' => 543, 'sample 2' => 234, 'sample 3' => 298, 'sample 4' => 5, 'sample 5' => 613) );
        
        $chart->xAxis->axisSpace = .2;
        $chart->yAxis->axisSpace = .05;
        
        $chart->driver = new ezcGraphSvgDriver();
        
        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderPieChart()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new PieChart();
        $chart->data['sample'] = new ArrayDataSet( array(
            'Mozilla' => 4375,
            'IE' => 345,
            'Opera' => 1204,
            'wget' => 231,
            'Safari' => 987,
        ) );

        $chart->data['sample']->highlight['Safari'] = true;

        $chart->driver = new ezcGraphSvgDriver();
        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderPieChartDifferentDataBorder()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new PieChart();
        $chart->data['sample'] = new ArrayDataSet( array(
            'Mozilla' => 4375,
            'IE' => 345,
            'Opera' => 1204,
            'wget' => 231,
            'Safari' => 987,
        ) );

        $chart->data['sample']->highlight['Safari'] = true;

        $chart->renderer->options->dataBorder = .1;

        $chart->driver = new ezcGraphSvgDriver();
        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderPieChartWithHighlightAndOffset()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new PieChart();
        $chart->data['sample'] = new ArrayDataSet( array(
            'Mozilla' => 4375,
            'IE' => 345,
            'Opera' => 1204,
            'wget' => 231,
            'Safari' => 987,
        ) );

        $chart->data['sample']->highlight['Safari'] = true;

        $chart->renderer->options->pieChartOffset = 76;

        $chart->driver = new ezcGraphSvgDriver();
        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderPieChartWithOffset()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new PieChart();
        $chart->data['sample'] = new ArrayDataSet( array(
            'Mozilla' => 4375,
            'IE' => 345,
            'Opera' => 1204,
            'wget' => 231,
            'Safari' => 987,
        ) );

        $chart->renderer->options->pieChartOffset = 156;

        $chart->driver = new ezcGraphSvgDriver();
        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderPieChartWithLegendTitle()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new PieChart();
        $chart->data['sample'] = new ArrayDataSet( array(
            'Mozilla' => 4375,
            'IE' => 345,
            'Opera' => 1204,
            'wget' => 231,
            'Safari' => 987,
        ) );

        $chart->legend->title = 'Legenda';

        $chart->driver = new ezcGraphSvgDriver();
        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderLabeledPieSegmentWithModifiedSymbolColor()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new PieChart();
        $chart->data['sample'] = new ArrayDataSet( array(
            'Mozilla' => 4375,
            'IE' => 345,
            'Opera' => 1204,
            'wget' => 231,
            'Safari' => 987,
        ) );

        $chart->data['sample']->highlight['Safari'] = true;

        $chart->renderer->options->pieChartSymbolColor = '#000000BB';

        $chart->driver = new ezcGraphSvgDriver();
        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderPieChartWithShadow()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new PieChart();
        $chart->data['sample'] = new ArrayDataSet( array(
            'Mozilla' => 4375,
            'IE' => 345,
            'Opera' => 1204,
            'wget' => 231,
            'Safari' => 987,
        ) );

        $chart->data['sample']->highlight['Opera'] = true;
        $chart->renderer->options->pieChartShadowSize = 5;

        $chart->driver = new ezcGraphSvgDriver();
        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderPieChartWithGleamAndShadow()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new PieChart();
        $chart->data['sample'] = new ArrayDataSet( array(
            'Mozilla' => 4375,
            'IE' => 345,
            'Opera' => 1204,
            'wget' => 231,
            'Safari' => 987,
        ) );

        $chart->data['sample']->highlight['Opera'] = true;
        $chart->renderer->options->legendSymbolGleam = .5;
        $chart->renderer->options->pieChartShadowSize = 5;
        $chart->renderer->options->pieChartGleamBorder = 3;
        $chart->renderer->options->pieChartGleam = .5;

        $chart->driver = new ezcGraphSvgDriver();
        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderLineChartWithAxisLabels()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new LineChart();
        $chart->palette = new Black();

        $chart->data['Line 1'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['Line 2'] = new ArrayDataSet( array( 'sample 1' => 543, 'sample 2' => 234, 'sample 3' => 298, 'sample 4' => 5, 'sample 5' => 613) );

        $chart->xAxis->label = 'Samples';
        $chart->yAxis->label = 'Numbers';

        $chart->driver = new ezcGraphSvgDriver();

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderLineChartWithAxisLabelsReversedAxis()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new LineChart();
        $chart->palette = new Black();

        $chart->data['Line 1'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['Line 2'] = new ArrayDataSet( array( 'sample 1' => 543, 'sample 2' => 234, 'sample 3' => 298, 'sample 4' => 5, 'sample 5' => 613) );

        $chart->xAxis->label = 'Samples';
        $chart->xAxis->position = ezcGraph::RIGHT;
        $chart->xAxis->axisLabelRenderer = new AxisCenteredLabelRenderer();
        $chart->yAxis->label = 'Numbers';
        $chart->yAxis->position = ezcGraph::TOP;
        $chart->yAxis->axisLabelRenderer = new AxisCenteredLabelRenderer();

        $chart->driver = new ezcGraphSvgDriver();

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderLineChartWithHighlightedData()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new LineChart();
        $chart->palette = new Black();

        $chart->data['Line 1'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => -21, 'sample 3' => 324, 'sample 4' => -120, 'sample 5' => 1) );
        $chart->data['Line 2'] = new ArrayDataSet( array( 'sample 1' => 543, 'sample 2' => 234, 'sample 3' => 298, 'sample 4' => 5, 'sample 5' => 613) );

        $chart->data['Line 1']->highlight = true;
        $chart->data['Line 2']->highlight['sample 5'] = true;

        $chart->options->highlightSize = 12;
        $chart->options->highlightFont->color = Color::fromHex( '#3465A4' );
        $chart->options->highlightFont->background = Color::fromHex( '#D3D7CF' );
        $chart->options->highlightFont->border = Color::fromHex( '#888A85' );
        
        $chart->xAxis->axisLabelRenderer = new AxisBoxedLabelRenderer();

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderBarChartWithHighlightedData3Bars()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new BarChart();
        $chart->palette = new Black();

        $chart->data['Line 1'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => -21, 'sample 3' => 324, 'sample 4' => -120, 'sample 5' => 1) );
        $chart->data['Line 2'] = new ArrayDataSet( array( 'sample 1' => 543, 'sample 2' => 234, 'sample 3' => 298, 'sample 4' => 5, 'sample 5' => 613) );
        $chart->data['Line 3'] = new ArrayDataSet( array( 'sample 2' => 42, 'sample 3' => 398, 'sample 4' => -15, 'sample 5' => 244) );

        $chart->data['Line 1']->highlight = true;
        $chart->data['Line 2']->highlight['sample 5'] = true;
        $chart->data['Line 3']->highlight = true;

        $chart->options->highlightSize = 12;
        $chart->options->highlightFont->color = Color::fromHex( '#3465A4' );
        $chart->options->highlightFont->background = Color::fromHex( '#D3D7CF' );
        $chart->options->highlightFont->border = Color::fromHex( '#888A85' );
        
        $chart->xAxis->axisLabelRenderer = new AxisBoxedLabelRenderer();

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderBarChartWithHighlightedData()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new BarChart();
        $chart->palette = new Black();

        $chart->data['Line 1'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => -21, 'sample 3' => 324, 'sample 4' => -120, 'sample 5' => 1) );
        $chart->data['Line 2'] = new ArrayDataSet( array( 'sample 1' => 543, 'sample 2' => 234, 'sample 3' => 298, 'sample 4' => 5, 'sample 5' => 613) );

        $chart->data['Line 1']->highlight = true;
        $chart->data['Line 2']->highlight['sample 5'] = true;
        
        $chart->data['Line 1']->displayType = ezcGraph::BAR;

        $chart->options->highlightSize = 12;
        $chart->options->highlightFont->color = Color::fromHex( '#3465A4' );
        $chart->options->highlightFont->background = Color::fromHex( '#D3D7CF' );
        $chart->options->highlightFont->border = Color::fromHex( '#888A85' );
        
        $chart->xAxis->axisLabelRenderer = new AxisBoxedLabelRenderer();

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testBug11107_MissingGridWithBottomLegend()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';
        
        $graph = new LineChart();
        $graph->palette = new Black();
        $graph->legend->position = ezcGraph::BOTTOM;

        $graph->data['sample'] = new ArrayDataSet(
            array( 1, 4, 6, 8, 2 )
        );

        $graph->render( 560, 250, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testNoArrowHead()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';
        
        $graph = new LineChart();
        $graph->palette = new Black();
        $graph->legend->position = ezcGraph::BOTTOM;

        $graph->data['sample'] = new ArrayDataSet(
            array( 1, 4, 6, 8, 2 )
        );

        $graph->renderer->options->axisEndStyle = ezcGraph::NO_SYMBOL;

        $graph->render( 560, 250, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testCircleArrowHead()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';
        
        $graph = new LineChart();
        $graph->palette = new Black();
        $graph->legend->position = ezcGraph::BOTTOM;

        $graph->data['sample'] = new ArrayDataSet(
            array( 1, 4, 6, 8, 2 )
        );

        $graph->renderer->options->axisEndStyle = ezcGraph::CIRCLE;

        $graph->render( 560, 250, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testShortAxis()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';
        
        $graph = new LineChart();
        $graph->palette = new Black();
        $graph->legend->position = ezcGraph::BOTTOM;

        $graph->data['sample'] = new ArrayDataSet(
            array( 1, 4, 6, 8, 2 )
        );

        $graph->renderer->options->axisEndStyle = ezcGraph::NO_SYMBOL;
        $graph->renderer->options->shortAxis    = true;

        $graph->render( 560, 250, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testSquareAndBoxSymbolsInChart()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';
        
        $graph = new LineChart();
        $graph->palette = new Black();

        $graph->data['sample1'] = new ArrayDataSet( array( 1, 4, 6, 8, 2 ) );
        $graph->data['sample1']->symbol = ezcGraph::SQUARE;
        $graph->data['sample2'] = new ArrayDataSet( array( 4, 6, 8, 2, 1 ) );
        $graph->data['sample2']->symbol = ezcGraph::BOX;

        $graph->render( 560, 250, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRotatedAxisLabel()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';
        
        $graph = new LineChart();
        $graph->palette = new Black();

        $graph->data['sample1'] = new ArrayDataSet( array( 1, 4, 6, 8, 2 ) );
        $graph->data['sample1']->symbol = ezcGraph::SQUARE;
        $graph->data['sample2'] = new ArrayDataSet( array( 4, 6, 8, 2, 1 ) );
        $graph->data['sample2']->symbol = ezcGraph::BOX;

        $graph->xAxis->label = "Some axis label";
        $graph->xAxis->labelRotation = 90;

        $graph->render( 560, 250, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRendererOptionsPropertyMaxLabelHeight()
    {
        $options = new RendererOptions();

        $this->assertSame(
            .1,
            $options->maxLabelHeight,
            'Wrong default value for property maxLabelHeight in class RendererOptions'
        );

        $options->maxLabelHeight = .2;
        $this->assertSame(
            .2,
            $options->maxLabelHeight,
            'Setting property value did not work for property maxLabelHeight in class RendererOptions'
        );

        try
        {
            $options->maxLabelHeight = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRendererOptionsPropertyShowSymbol()
    {
        $options = new RendererOptions();

        $this->assertSame(
            true,
            $options->showSymbol,
            'Wrong default value for property showSymbol in class RendererOptions'
        );

        $options->showSymbol = false;
        $this->assertSame(
            false,
            $options->showSymbol,
            'Setting property value did not work for property showSymbol in class RendererOptions'
        );

        try
        {
            $options->showSymbol = 42;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRendererOptionsPropertySyncAxisFonts()
    {
        $options = new RendererOptions();

        $this->assertSame(
            true,
            $options->syncAxisFonts,
            'Wrong default value for property syncAxisFonts in class RendererOptions'
        );

        $options->syncAxisFonts = false;
        $this->assertSame(
            false,
            $options->syncAxisFonts,
            'Setting property value did not work for property syncAxisFonts in class RendererOptions'
        );

        try
        {
            $options->syncAxisFonts = 42;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRendererOptionsPropertySymbolSize()
    {
        $options = new RendererOptions();

        $this->assertSame(
            6,
            $options->symbolSize,
            'Wrong default value for property symbolSize in class RendererOptions'
        );

        $options->symbolSize = 8;
        $this->assertSame(
            8,
            $options->symbolSize,
            'Setting property value did not work for property symbolSize in class RendererOptions'
        );

        try
        {
            $options->symbolSize = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRendererOptionsPropertyMoveOut()
    {
        $options = new RendererOptions();

        $this->assertSame(
            .1,
            $options->moveOut,
            'Wrong default value for property moveOut in class RendererOptions'
        );

        $options->moveOut = .2;
        $this->assertSame(
            .2,
            $options->moveOut,
            'Setting property value did not work for property moveOut in class RendererOptions'
        );

        try
        {
            $options->moveOut = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRendererOptionsPropertyTitlePosition()
    {
        $options = new RendererOptions();

        $this->assertSame(
            ezcGraph::TOP,
            $options->titlePosition,
            'Wrong default value for property titlePosition in class RendererOptions'
        );

        $options->titlePosition = ezcGraph::BOTTOM;
        $this->assertSame(
            ezcGraph::BOTTOM,
            $options->titlePosition,
            'Setting property value did not work for property titlePosition in class RendererOptions'
        );

        try
        {
            $options->titlePosition = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRendererOptionsPropertyTitleAlignement()
    {
        $options = new RendererOptions();

        $this->assertSame(
            ezcGraph::MIDDLE | ezcGraph::CENTER,
            $options->titleAlignement,
            'Wrong default value for property titleAlignement in class RendererOptions'
        );

        $options->titleAlignement = ezcGraph::BOTTOM;
        $this->assertSame(
            ezcGraph::BOTTOM,
            $options->titleAlignement,
            'Setting property value did not work for property titleAlignement in class RendererOptions'
        );

        try
        {
            $options->titleAlignement = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRendererOptionsPropertyDataBorder()
    {
        $options = new RendererOptions();

        $this->assertSame(
            .5,
            $options->dataBorder,
            'Wrong default value for property dataBorder in class RendererOptions'
        );

        $options->dataBorder = 1.;
        $this->assertSame(
            1.,
            $options->dataBorder,
            'Setting property value did not work for property dataBorder in class RendererOptions'
        );

        $options->dataBorder = false;
        $this->assertSame(
            false,
            $options->dataBorder,
            'Setting property value did not work for property dataBorder in class RendererOptions'
        );

        try
        {
            $options->dataBorder = true;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRendererOptionsPropertyBarMargin()
    {
        $options = new RendererOptions();

        $this->assertSame(
            .1,
            $options->barMargin,
            'Wrong default value for property barMargin in class RendererOptions'
        );

        $options->barMargin = .2;
        $this->assertSame(
            .2,
            $options->barMargin,
            'Setting property value did not work for property barMargin in class RendererOptions'
        );

        try
        {
            $options->barMargin = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRendererOptionsPropertyBarPadding()
    {
        $options = new RendererOptions();

        $this->assertSame(
            .05,
            $options->barPadding,
            'Wrong default value for property barPadding in class RendererOptions'
        );

        $options->barPadding = .1;
        $this->assertSame(
            .1,
            $options->barPadding,
            'Setting property value did not work for property barPadding in class RendererOptions'
        );

        try
        {
            $options->barPadding = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRendererOptionsPropertyPieChartOffset()
    {
        $options = new RendererOptions();

        $this->assertSame(
            0,
            $options->pieChartOffset,
            'Wrong default value for property pieChartOffset in class RendererOptions'
        );

        $options->pieChartOffset = 1;
        $this->assertSame(
            1.,
            $options->pieChartOffset,
            'Setting property value did not work for property pieChartOffset in class RendererOptions'
        );

        try
        {
            $options->pieChartOffset = 450;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRendererOptionsPropertyLegendSymbolGleam()
    {
        $options = new RendererOptions();

        $this->assertSame(
            false,
            $options->legendSymbolGleam,
            'Wrong default value for property legendSymbolGleam in class RendererOptions'
        );

        $options->legendSymbolGleam = .1;
        $this->assertSame(
            .1,
            $options->legendSymbolGleam,
            'Setting property value did not work for property legendSymbolGleam in class RendererOptions'
        );

        $options->legendSymbolGleam = false;
        $this->assertSame(
            false,
            $options->legendSymbolGleam,
            'Setting property value did not work for property legendSymbolGleam in class RendererOptions'
        );

        try
        {
            $options->legendSymbolGleam = true;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRendererOptionsPropertyLegendSymbolGleamSize()
    {
        $options = new RendererOptions();

        $this->assertSame(
            .9,
            $options->legendSymbolGleamSize,
            'Wrong default value for property legendSymbolGleamSize in class RendererOptions'
        );

        $options->legendSymbolGleamSize = .8;
        $this->assertSame(
            .8,
            $options->legendSymbolGleamSize,
            'Setting property value did not work for property legendSymbolGleamSize in class RendererOptions'
        );

        try
        {
            $options->legendSymbolGleamSize = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRendererOptionsPropertyLegendSymbolGleamColor()
    {
        $options = new RendererOptions();

        $this->assertEquals(
            Color::fromHex( '#FFFFFF' ),
            $options->legendSymbolGleamColor,
            'Wrong default value for property pieChartSymbolColor in class RendererOptions'
        );

        $options->legendSymbolGleamColor = $color = Color::fromHex( '#000000' );
        $this->assertSame(
            $color,
            $options->legendSymbolGleamColor,
            'Setting property value did not work for property pieChartSymbolColor in class RendererOptions'
        );

        try
        {
            $options->legendSymbolGleamColor = false;
        }
        catch ( ezcGraphUnknownColorDefinitionException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphUnknownColorDefinitionException.' );
    }


    public function testRendererOptionsPropertyPieVerticalSize()
    {
        $options = new RendererOptions();

        $this->assertSame(
            .5,
            $options->pieVerticalSize,
            'Wrong default value for property pieVerticalSize in class RendererOptions'
        );

        $options->pieVerticalSize = .6;
        $this->assertSame(
            .6,
            $options->pieVerticalSize,
            'Setting property value did not work for property pieVerticalSize in class RendererOptions'
        );

        try
        {
            $options->pieVerticalSize = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRendererOptionsPropertyPieHorizontalSize()
    {
        $options = new RendererOptions();

        $this->assertSame(
            .25,
            $options->pieHorizontalSize,
            'Wrong default value for property pieHorizontalSize in class RendererOptions'
        );

        $options->pieHorizontalSize = .5;
        $this->assertSame(
            .5,
            $options->pieHorizontalSize,
            'Setting property value did not work for property pieHorizontalSize in class RendererOptions'
        );

        try
        {
            $options->pieHorizontalSize = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRendererOptionsPropertyPieChartSymbolColor()
    {
        $options = new RendererOptions();

        $this->assertEquals(
            Color::fromHex( '#000000' ),
            $options->pieChartSymbolColor,
            'Wrong default value for property pieChartSymbolColor in class RendererOptions'
        );

        $options->pieChartSymbolColor = $color = Color::fromHex( '#FFFFFF' );
        $this->assertSame(
            $color,
            $options->pieChartSymbolColor,
            'Setting property value did not work for property pieChartSymbolColor in class RendererOptions'
        );

        try
        {
            $options->pieChartSymbolColor = false;
        }
        catch ( ezcGraphUnknownColorDefinitionException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphUnknownColorDefinitionException.' );
    }

    public function testRendererOptionsPropertyPieChartGleam()
    {
        $options = new RendererOptions();

        $this->assertSame(
            false,
            $options->pieChartGleam,
            'Wrong default value for property pieChartGleam in class RendererOptions'
        );

        $options->pieChartGleam = .2;
        $this->assertSame(
            .2,
            $options->pieChartGleam,
            'Setting property value did not work for property pieChartGleam in class RendererOptions'
        );

        $options->pieChartGleam = false;
        $this->assertSame(
            false,
            $options->pieChartGleam,
            'Setting property value did not work for property pieChartGleam in class RendererOptions'
        );

        try
        {
            $options->pieChartGleam = true;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRendererOptionsPropertyPieChartGleamColor()
    {
        $options = new RendererOptions();

        $this->assertEquals(
            Color::fromHex( '#FFFFFF' ),
            $options->pieChartGleamColor,
            'Wrong default value for property pieChartGleamColor in class RendererOptions'
        );

        $options->pieChartGleamColor = $color = Color::fromHex( '#000000' );
        $this->assertSame(
            $color,
            $options->pieChartGleamColor,
            'Setting property value did not work for property pieChartGleamColor in class RendererOptions'
        );

        try
        {
            $options->pieChartGleamColor = false;
        }
        catch ( ezcGraphUnknownColorDefinitionException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphUnknownColorDefinitionException.' );
    }

    public function testRendererOptionsPropertyPieChartGleamBorder()
    {
        $options = new RendererOptions();

        $this->assertSame(
            0,
            $options->pieChartGleamBorder,
            'Wrong default value for property pieChartGleamBorder in class RendererOptions'
        );

        $options->pieChartGleamBorder = 1;
        $this->assertSame(
            1,
            $options->pieChartGleamBorder,
            'Setting property value did not work for property pieChartGleamBorder in class RendererOptions'
        );

        try
        {
            $options->pieChartGleamBorder = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRenderer2dOptionsPropertyPieChartShadowSize()
    {
        $options = new Renderer2dOptions();

        $this->assertSame(
            0,
            $options->pieChartShadowSize,
            'Wrong default value for property pieChartShadowSize in class Renderer2dOptions'
        );

        $options->pieChartShadowSize = 5;
        $this->assertSame(
            5,
            $options->pieChartShadowSize,
            'Setting property value did not work for property pieChartShadowSize in class Renderer2dOptions'
        );

        try
        {
            $options->pieChartShadowSize = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRenderer2dOptionsPropertyPieChartShadowTransparency()
    {
        $options = new Renderer2dOptions();

        $this->assertSame(
            .3,
            $options->pieChartShadowTransparency,
            'Wrong default value for property pieChartShadowTransparency in class Renderer2dOptions'
        );

        $options->pieChartShadowTransparency = .5;
        $this->assertSame(
            .5,
            $options->pieChartShadowTransparency,
            'Setting property value did not work for property pieChartShadowTransparency in class Renderer2dOptions'
        );

        try
        {
            $options->pieChartShadowTransparency = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRenderer2dOptionsPropertyPieChartShadowColor()
    {
        $options = new Renderer2dOptions();

        $this->assertEquals(
            Color::fromHex( '#000000' ),
            $options->pieChartShadowColor,
            'Wrong default value for property pieChartShadowColor in class Renderer2dOptions'
        );

        $options->pieChartShadowColor = $color = Color::fromHex( '#FFFFFF' );
        $this->assertSame(
            $color,
            $options->pieChartShadowColor,
            'Setting property value did not work for property pieChartShadowColor in class Renderer2dOptions'
        );

        try
        {
            $options->pieChartShadowColor = false;
        }
        catch ( ezcGraphUnknownColorDefinitionException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphUnknownColorDefinitionException.' );
    }

    public function testRendererOptionsPropertyAxisEndStyle()
    {
        $options = new Renderer2dOptions();

        $this->assertSame(
            ezcGraph::ARROW,
            $options->axisEndStyle,
            'Wrong default value for property axisEndStyle in class Renderer2dOptions'
        );

        $options->axisEndStyle = ezcGraph::NO_SYMBOL;
        $this->assertSame(
            ezcGraph::NO_SYMBOL,
            $options->axisEndStyle,
            'Setting property value did not work for property axisEndStyle in class Renderer2dOptions'
        );

        try
        {
            $options->axisEndStyle = false;
            $this->fail( 'Expected ezcBaseValueException.' );
        }
        catch ( ezcBaseValueException $e )
        { /* Expected */ }
    }

    public function testRendererOptionsPropertyShortAxis()
    {
        $options = new Renderer2dOptions();

        $this->assertSame(
            false,
            $options->shortAxis,
            'Wrong default value for property shortAxis in class Renderer2dOptions'
        );

        $options->shortAxis = true;
        $this->assertSame(
            true,
            $options->shortAxis,
            'Setting property value did not work for property shortAxis in class Renderer2dOptions'
        );

        try
        {
            $options->shortAxis = 'true';
            $this->fail( 'Expected ezcBaseValueException.' );
        }
        catch ( ezcBaseValueException $e )
        { /* Expected */ }
    }

    public function testChartOptionsPropertyWidth()
    {
        $options = new Renderer2dOptions();

        $this->assertSame(
            null,
            $options->width,
            'Wrong default value for property width in class ChartOptions'
        );

        $options->width = 100;
        $this->assertSame(
            100,
            $options->width,
            'Setting property value did not work for property width in class ChartOptions'
        );

        try
        {
            $options->width = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartOptionsPropertyHeigh()
    {
        $options = new ChartOptions();

        $this->assertSame(
            null,
            $options->height,
            'Wrong default value for property heigh in class ChartOptions'
        );

        $options->height = 100;
        $this->assertSame(
            100,
            $options->height,
            'Setting property value did not work for property heigh in class ChartOptions'
        );

        try
        {
            $options->height = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartOptionsPropertyFont()
    {
        $options = new ChartOptions();

        $this->assertSame(
            'ezcGraphFontOptions',
            get_class( $options->font ),
            'Wrong default value for property font in class ChartOptions'
        );

        $options->font = $file = dirname( __FILE__ ) . '/data/font2.ttf';
        $this->assertSame(
            $file,
            $options->font->path,
            'Setting property value did not work for property font in class ChartOptions'
        );

        try
        {
            $options->font = false;
        }
        catch ( ezcBaseFileNotFoundException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseFileNotFoundException.' );
    }
}
?>
