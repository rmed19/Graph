<?php
/**
 * ezcGraphChartTest 
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

require_once dirname( __FILE__ ) . '/test_case.php';
require_once dirname( __FILE__ ) . '/custom_chart.php';

use Ezc\Graph\Charts\BarChart;
use Ezc\Graph\Charts\LineChart;
use Ezc\Graph\Charts\PieChart;
use Ezc\Graph\Element\ChartElementText;
use Ezc\Graph\Axis\ChartElementNumericAxis;
use Ezc\Graph\Datasets\ArrayDataSet;
use Ezc\Graph\Renderer\Renderer2d;


/**
 * Tests for ezcGraph class.
 * 
 * @package Graph
 * @subpackage Tests
 */
class ezcGraphChartTest extends ezcGraphTestCase
{
    protected $testFiles = array(
        'jpeg'          => 'jpeg.jpg',
        'nonexistant'   => 'nonexisting.jpg',
        'invalid'       => 'text.txt',
    );

    protected $tempDir;
    protected $basePath;

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "ezcGraphChartTest" );
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

    public function testSetTitle()
    {
        $pieChart = new PieChart();
        $pieChart->title = 'Test title';

        $this->assertSame(
            'Test title',
            $pieChart->title->title
        );

        $this->assertTrue(
            $pieChart->title instanceof ChartElementText
        );
    }

    public function testSetOptionsUnknown()
    {
        try
        {
            $pieChart = new PieChart();
            $pieChart->options->unknown = 'unknown';
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBasePropertyNotFoundException' );
    }

    public function testSetRenderer()
    {
        $pieChart = new PieChart();
        $renderer = $pieChart->renderer = new Renderer2d();

        $this->assertSame(
            $renderer,
            $pieChart->renderer
        );
    }

    public function testSetInvalidRenderer()
    {
        try
        {
            $pieChart = new PieChart();
            $pieChart->renderer = 'invalid';
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException' );
    }

    public function testAccessUnknownElement()
    {
        try
        {
            $pieChart = new PieChart();
            //Read
            $pieChart->unknownElement;
        }
        catch ( ezcGraphNoSuchElementException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphNoSuchElementException' );
    }

    public function testSetDriver()
    {
        if ( !ezcBaseFeatures::hasExtensionSupport( 'gd' ) && 
             ( ezcBaseFeatures::hasFunction( 'imagefttext' ) || ezcBaseFeatures::hasFunction( 'imagettftext' ) ) )
        {
            $this->markTestSkipped( 'This test needs ext/gd with native ttf support or FreeType 2 support.' );
        }

        $pieChart = new PieChart();
        $driver = $pieChart->driver = new ezcGraphGdDriver();

        $this->assertSame(
            $driver,
            $pieChart->driver
        );
    }

    public function testSetInvalidDriver()
    {
        try
        {
            $pieChart = new PieChart();
            $pieChart->driver = 'invalid';
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphInvalidDriverException' );
    }

    public function testPieChartWithoutData()
    {
        try
        {
            $pieChart = new PieChart();
            $pieChart->render( 400, 200 );
        }
        catch ( ezcGraphNoDataException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphNoDataException.' );
    }

    public function testBarChartWithoutData()
    {
        try
        {
            $barChart = new BarChart();
            $barChart->render( 400, 200 );
        }
        catch ( ezcGraphNoDataException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphNoDataException.' );
    }

    public function testBarChartWithSingleDataPoint()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $barChart = new BarChart();
        $barChart->data['test'] = new ArrayDataSet(
            array( 23 )
        );
        $barChart->render( 400, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testBarChartWithTwoSingleDataPoint()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $barChart = new BarChart();
        $barChart->data['test'] = new ArrayDataSet(
            array( 23 )
        );
        $barChart->data['test 2'] = new ArrayDataSet(
            array( 5 )
        );
        $barChart->render( 400, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testBarChartWithSingleDataPointNumericAxis()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $barChart = new BarChart();
        $barChart->xAxis = new ChartElementNumericAxis();

        $barChart->data['test'] = new ArrayDataSet(
            array( 23 )
        );
        $barChart->render( 400, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testReRenderChart()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $barChart = new LineChart();

        $barChart->data['test'] = new ArrayDataSet(
            array( 5, 23, 42 )
        );
        $color = $barChart->data['test']->color->default;
        $barChart->render( 400, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );

        // Render a second time with a new dataset, and expect the same result
        $barChart->data['test'] = new ArrayDataSet(
            array( 5, 23, 42 )
        );
        $barChart->data['test']->color = $color;
        $barChart->render( 400, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testCustomChartClass()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new ezcCustomTestChart();
        $chart->render( 400, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }
}
?>
