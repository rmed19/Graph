<?php
/**
 * RadarChartTest 
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
use Ezc\Graph\Axis\ChartElementLogarithmicalAxis;
use Ezc\Graph\Options\RadarChartOptions;
use Ezc\Graph\Options\FontOptions;
use Ezc\Graph\Axis\ChartElementNumericAxis;
use Ezc\Graph\Charts\RadarChart;
use Ezc\Graph\Colors\Color;
use Ezc\Graph\Datasets\ArrayDataSet;
use Ezc\Graph\Palette\Tango;
use Ezc\Graph\Renderer\Renderer3d;
use Ezc\Graph\Renderer\Renderer2d;
use Ezc\Graph\Palette\Black;
use Ezc\Graph\Structs\Context;
use Ezc\Graph\Structs\Coordinate;

/**
 * Tests for ezcGraph class.
 * 
 * @package Graph
 * @subpackage Tests
 */
class RadarChartTest extends ezcGraphTestCase
{
    protected $basePath;

    protected $tempDir;

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "RadarChartTest" );
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

    public function testDrawMultipleAxis()
    {
        $chart = new RadarChart();
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1 ) );

        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawAxis',
        ) );

        $mockedRenderer
           ->expects( $this->at( 0 ) )
            ->method( 'drawAxis' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 100., 0., 500., 200. ), 1. ),
                $this->equalTo( new Coordinate( 200., 100. ), 1. ),
                $this->equalTo( new Coordinate( 200., 0. ), 1. )
            );
        $mockedRenderer
           ->expects( $this->at( 1 ) )
            ->method( 'drawAxis' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 100., 0., 500., 200. ), 1. ),
                $this->equalTo( new Coordinate( 200., 100. ), 1. ),
                $this->equalTo( new Coordinate( 400., 100. ), 1. )
            );
        $mockedRenderer
           ->expects( $this->at( 3 ) )
            ->method( 'drawAxis' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 100., 0., 500., 200. ), 1. ),
                $this->equalTo( new Coordinate( 200., 100. ), 1. ),
                $this->equalTo( new Coordinate( 0., 100. ), 1. )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testDrawDataLines()
    {
        $chart = new RadarChart();
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1 ) );
        $chart->data['sampleData']->color = '#CC0000';

        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawRadarDataLine',
        ) );

        $mockedRenderer
           ->expects( $this->at( 0 ) )
            ->method( 'drawRadarDataLine' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 100., 0., 500., 200. ), 1. ),
                $this->equalTo( new Context( 'sampleData', 'sample 1' ) ),
                $this->equalTo( Color::fromHex( '#CC0000' ) ),
                $this->equalTo( new Coordinate( 200., 100. ), 1. ),
                $this->equalTo( new Coordinate( .0, .585 ), .05 ),
                $this->equalTo( new Coordinate( .0, .585 ), .05 )
            );
        $mockedRenderer
           ->expects( $this->at( 1 ) )
            ->method( 'drawRadarDataLine' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 100., 0., 500., 200. ), 1. ),
                $this->equalTo( new Context( 'sampleData', 'sample 2' ) ),
                $this->equalTo( Color::fromHex( '#CC0000' ) ),
                $this->equalTo( new Coordinate( 200., 100. ), 1. ),
                $this->equalTo( new Coordinate( .0, .585 ), .05 ),
                $this->equalTo( new Coordinate( .25, .0525 ), .05 )
            );
        $mockedRenderer
           ->expects( $this->at( 4 ) )
            ->method( 'drawRadarDataLine' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 100., 0., 500., 200. ), 1. ),
                $this->equalTo( new Context( 'sampleData', 'sample 5' ) ),
                $this->equalTo( Color::fromHex( '#CC0000' ) ),
                $this->equalTo( new Coordinate( 200., 100. ), 1. ),
                $this->equalTo( new Coordinate( .75, .3 ), .05 ),
                $this->equalTo( new Coordinate( 1., .0025 ), .05 )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testDrawDataLinesWithSymbols()
    {
        $chart = new RadarChart();
        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => 21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1 ) );
        $chart->data['sampleData']->color = '#CC0000';
        $chart->data['sampleData']->symbol = ezcGraph::DIAMOND;

        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawRadarDataLine',
        ) );

        $mockedRenderer
           ->expects( $this->at( 0 ) )
            ->method( 'drawRadarDataLine' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 100., 0., 500., 200. ), 1. ),
                $this->equalTo( new Context( 'sampleData', 'sample 1' ) ),
                $this->equalTo( Color::fromHex( '#CC0000' ) ),
                $this->equalTo( new Coordinate( 200., 100. ), 1. ),
                $this->equalTo( new Coordinate( .0, .585 ), .05 ),
                $this->equalTo( new Coordinate( .0, .585 ), .05 ),
                $this->equalTo( 0 ),
                $this->equalTo( 1 ),
                $this->equalTo( ezcGraph::DIAMOND ),
                $this->equalTo( Color::fromHex( '#CC0000' ) )
            );
        $mockedRenderer
           ->expects( $this->at( 1 ) )
            ->method( 'drawRadarDataLine' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 100., 0., 500., 200. ), 1. ),
                $this->equalTo( new Context( 'sampleData', 'sample 2' ) ),
                $this->equalTo( Color::fromHex( '#CC0000' ) ),
                $this->equalTo( new Coordinate( 200., 100. ), 1. ),
                $this->equalTo( new Coordinate( .0, .585 ), .05 ),
                $this->equalTo( new Coordinate( .25, .0525 ), .05 ),
                $this->equalTo( 0 ),
                $this->equalTo( 1 ),
                $this->equalTo( ezcGraph::DIAMOND ),
                $this->equalTo( Color::fromHex( '#CC0000' ) )
            );
        $mockedRenderer
           ->expects( $this->at( 4 ) )
            ->method( 'drawRadarDataLine' )
            ->with(
                $this->equalTo( new ezcGraphBoundings( 100., 0., 500., 200. ), 1. ),
                $this->equalTo( new Context( 'sampleData', 'sample 5' ) ),
                $this->equalTo( Color::fromHex( '#CC0000' ) ),
                $this->equalTo( new Coordinate( 200., 100. ), 1. ),
                $this->equalTo( new Coordinate( .75, .3 ), .05 ),
                $this->equalTo( new Coordinate( 1., .0025 ), .05 ),
                $this->equalTo( 0 ),
                $this->equalTo( 1 ),
                $this->equalTo( ezcGraph::DIAMOND ),
                $this->equalTo( Color::fromHex( '#CC0000' ) )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testDrawGridLines()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new RadarChart();
        $chart->palette = new Black();
        $chart->data['sample'] = new ArrayDataSet( $this->getRandomData( 6 ) );

        $mockedRenderer = $this->getMock( Renderer2d::class, array(
            'drawGridLine',
        ) );

        $mockedRenderer
           ->expects( $this->at( 0 ) )
            ->method( 'drawGridLine' )
            ->with(
                $this->equalTo( new Coordinate( 338., 93.8 ), .1 ),
                $this->equalTo( new Coordinate( 300., 80. ), .1 ),
                $this->equalTo( Color::fromHex( '#888A85' ) )
            );
        $mockedRenderer
           ->expects( $this->at( 1 ) )
            ->method( 'drawGridLine' )
            ->with(
                $this->equalTo( new Coordinate( 343.75, 92.9 ), .1 ),
                $this->equalTo( new Coordinate( 300., 77. ), .1 ),
                $this->equalTo( Color::fromHex( '#888A8588' ) )
            );

        // Next axis
        $mockedRenderer
           ->expects( $this->at( 21 ) )
            ->method( 'drawGridLine' )
            ->with(
                $this->equalTo( new Coordinate( 323.5, 116.2 ), .1 ),
                $this->equalTo( new Coordinate( 338., 93.8 ), .1 ),
                $this->equalTo( Color::fromHex( '#888A85' ) )
            );

        $chart->renderer = $mockedRenderer;

        $chart->render( 500, 200 );
    }

    public function testRadarChartOptionsPropertyFillRadars()
    {
        $options = new RadarChartOptions();

        $this->assertSame(
            false,
            $options->fillLines,
            'Wrong default value for property fillLines in class RadarChartOptions'
        );

        $options->fillLines = 230;
        $this->assertSame(
            230,
            $options->fillLines,
            'Setting property value did not work for property fillLines in class RadarChartOptions'
        );

        $options->fillLines = false;
        $this->assertSame(
            false,
            $options->fillLines,
            'Setting property value did not work for property fillLines in class RadarChartOptions'
        );

        try
        {
            $options->fillLines = true;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRadarChartOptionsPropertySymbolSize()
    {
        $options = new RadarChartOptions();

        $this->assertSame(
            8,
            $options->symbolSize,
            'Wrong default value for property symbolSize in class RadarChartOptions'
        );

        $options->symbolSize = 10;
        $this->assertSame(
            10,
            $options->symbolSize,
            'Setting property value did not work for property symbolSize in class RadarChartOptions'
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

    public function testRadarChartOptionsPropertyHighlightFont()
    {
        $options = new RadarChartOptions();

        $options->highlightFont = $file = $this->basePath . 'font.ttf';
        $this->assertSame(
            $file,
            $options->highlightFont->path,
            'Setting property value did not work for property highlightFont in class RadarChartOptions'
        );

        $this->assertSame(
            true,
            $options->highlightFontCloned,
            'Font should be cloned now.'
        );

        $fontOptions = new FontOptions();
        $fontOptions->path = $this->basePath . 'font2.ttf';

        $options->highlightFont = $fontOptions;
        $this->assertSame(
            $fontOptions,
            $options->highlightFont,
            'Setting property value did not work for property highlightFont in class RadarChartOptions'
        );

        try
        {
            $options->highlightFont = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRadarChartOptionsPropertyHighlightSize()
    {
        $options = new RadarChartOptions();

        $this->assertSame(
            14,
            $options->highlightSize,
            'Wrong default value for property highlightSize in class RadarChartOptions'
        );

        $options->highlightSize = 20;
        $this->assertSame(
            20,
            $options->highlightSize,
            'Setting property value did not work for property highlightSize in class RadarChartOptions'
        );

        try
        {
            $options->highlightSize = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRadarChartOptionsPropertyHighlightRadars()
    {
        $options = new RadarChartOptions();

        $this->assertSame(
            false,
            $options->highlightRadars,
            'Wrong default value for property highlightRadars in class RadarChartOptions'
        );

        $options->highlightRadars = true;
        $this->assertSame(
            true,
            $options->highlightRadars,
            'Setting property value did not work for property highlightRadars in class RadarChartOptions'
        );

        try
        {
            $options->highlightRadars = 42;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRadarChartElementAxis()
    {
        $chart = new RadarChart();

        $this->assertSame(
            true,
            $chart->axis instanceof ChartElementNumericAxis,
            'Wrong default value for chart element axis in class RadarChart'
        );

        $chart->axis = new ChartElementLogarithmicalAxis();
        $this->assertSame(
            true,
            $chart->axis instanceof ChartElementLogarithmicalAxis,
            'Setting element value for chart element axis in class RadarChart'
        );

        try
        {
            $chart->axis = 42;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRadarChartElementRotationAxis()
    {
        $chart = new RadarChart();

        $this->assertSame(
            true,
            $chart->rotationAxis instanceof ChartElementLabeledAxis,
            'Wrong default value for chart element axis in class RadarChart'
        );

        $chart->rotationAxis = new ChartElementLogarithmicalAxis();
        $this->assertSame(
            true,
            $chart->rotationAxis instanceof ChartElementLogarithmicalAxis,
            'Setting element value for chart element axis in class RadarChart'
        );

        try
        {
            $chart->rotationAxis = 42;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testRadarSimple()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new RadarChart();
        $chart->palette = new Tango();

        $chart->data['sample'] = new ArrayDataSet( $this->getRandomData( 6 ) );

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRadarSimpleNoDataFailure()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new RadarChart();
        $chart->palette = new Tango();

        try
        {
            $chart->render( 500, 200, $filename );
        }
        catch ( ezcGraphNoDataException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphNoDataException.' );
    }

    public function testRadarMinorAxis()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new RadarChart();
        $chart->palette = new Black();

        $chart->options->fillLines = 210;

        $chart->data['sample'] = new ArrayDataSet( $this->getRandomData( 31 ) );

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderLineChartToOutput()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new RadarChart();
        $chart->palette = new Tango();

        $chart->data['sample'] = new ArrayDataSet( $this->getRandomData( 6 ) );

        ob_start();
        // Suppress header already sent warning
        @$chart->renderToOutput( 500, 200 );
        file_put_contents( $filename, ob_get_clean() );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRadarNumericRotationAxis()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new RadarChart();
        $chart->palette = new Black();

        $chart->options->fillLines = 210;

        $chart->data['sample'] = new ArrayDataSet( $this->getRandomData( 31 ) );
        $chart->rotationAxis = new ChartElementNumericAxis();

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRadarRendererFailure()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new RadarChart();
        $chart->palette = new Tango();

        $chart->data['sample'] = new ArrayDataSet( $this->getRandomData( 6 ) );

        try
        {
            $chart->renderer = new Renderer3d();
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBasePropertyValueException.' );
    }

    public function testRadarMultiple()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new RadarChart();
        $chart->palette = new Black();

        $chart->options->fillLines = 210;

        $chart->data['sample 1'] = new ArrayDataSet( $this->getRandomData( 8 ) );
        $chart->data['sample 2'] = new ArrayDataSet( $this->getRandomData( 8, 250, 1000, 12 ) );
        $chart->data['sample 3'] = new ArrayDataSet( $this->getRandomData( 8, 0, 500, 42 ) );

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRadarLogarithmicalAxis()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new RadarChart();
        $chart->palette = new Black();

        $chart->axis = new ChartElementLogarithmicalAxis();

        $chart->options->fillLines = 210;

        $chart->data['sample 1'] = new ArrayDataSet( $this->getRandomData( 8, 1, 1000000 ) );
        $chart->data['sample 2'] = new ArrayDataSet( $this->getRandomData( 8, 1, 1000000, 42 ) );

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }
}
?>
