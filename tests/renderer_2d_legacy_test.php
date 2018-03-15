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

use Ezc\Graph\Charts\LineChart;
use Ezc\Graph\Colors\Color;
use Ezc\Graph\Renderer\Renderer2d;
use Ezc\Graph\Structs\Coordinate;

/**
 * Tests for ezcGraph class.
 * 
 * @package Graph
 * @subpackage Tests
 */
class Renderer2dLegacyTest extends ezcGraphTestCase
{
    protected $basePath;

    protected $tempDir;

    protected $renderer;

    protected $driver;

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

        $this->renderer = new Renderer2d();

        $this->driver = $this->getMock( 'ezcGraphSvgDriver', array(
            'drawPolygon',
            'drawLine',
            'drawTextBox',
            'drawCircleSector',
            'drawCircularArc',
            'drawCircle',
            'drawImage',
        ) );
        $this->renderer->setDriver( $this->driver );

        $this->driver->options->width = 400;
        $this->driver->options->height = 200;
    }

    public function tearDown()
    {
        $this->renderer = null;
        $this->driver = null;

        if ( !$this->hasFailed() )
        {
            $this->removeTempDir();
        }
    }

    public function testRenderVerticalAxis()
    {
        $chart = new LineChart();

        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawLine' )
            ->with(
                $this->equalTo( new Coordinate( 120., 220. ), 1. ),
                $this->equalTo( new Coordinate( 120., 20. ), 1. ),
                $this->equalTo( Color::fromHex( '#2E3436' ) ),
                $this->equalTo( 1 )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 120., 20. ),
                    new Coordinate( 122.5, 25. ),
                    new Coordinate( 117.5, 25. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#2E3436' ) ),
                $this->equalTo( true )
            );

        $this->renderer->drawAxis(
            new ezcGraphBoundings( 100, 20, 500, 220 ),
            new Coordinate( 20, 200 ),
            new Coordinate( 20, 0 ),
            $chart->yAxis
        );
    }
    
    public function testRenderVerticalAxisReverse()
    {
        $chart = new LineChart();

        $this->driver
            ->expects( $this->at( 0 ) )
            ->method( 'drawLine' )
            ->with(
                $this->equalTo( new Coordinate( 120., 20. ), 1. ),
                $this->equalTo( new Coordinate( 120., 220. ), 1. ),
                $this->equalTo( Color::fromHex( '#2E3436' ) ),
                $this->equalTo( 1 )
            );
        $this->driver
            ->expects( $this->at( 1 ) )
            ->method( 'drawPolygon' )
            ->with(
                $this->equalTo( array(
                    new Coordinate( 120., 220. ),
                    new Coordinate( 117.5, 215. ),
                    new Coordinate( 122.5, 215. ),
                ), 1. ),
                $this->equalTo( Color::fromHex( '#2E3436' ) ),
                $this->equalTo( true )
            );

        $this->renderer->drawAxis(
            new ezcGraphBoundings( 100, 20, 500, 220 ),
            new Coordinate( 20, 0 ),
            new Coordinate( 20, 200 ),
            $chart->yAxis
        );
    }
    
    public function testRenderHorizontalAxis()
    {
        $chart = new LineChart();

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
            $chart->yAxis
        );
    }
    
    public function testRenderHorizontalAxisReverse()
    {
        $chart = new LineChart();

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
            $chart->yAxis
        );
    }
}
?>
