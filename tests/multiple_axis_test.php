<?php
/**
 * LineChartTest 
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


use Ezc\Graph\Charts\LineChart;

use Ezc\Graph\Axis\AxisContainer;
use Ezc\Graph\Axis\ChartElementLabeledAxis;
use Ezc\Graph\Datasets\ArrayDataSet;
use Ezc\Graph\Renderer\AxisNoLabelRenderer;

/**
 * Tests for ezcGraph class.
 * 
 * @package Graph
 * @subpackage Tests
 */
class ezcGraphMultipleAxisTest extends ezcGraphTestCase
{
    protected $basePath;

    protected $tempDir;

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "ezcGraphMultipleAxisTest" );
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

    public function testAxisPropertyChartPosition()
    {
        $options = new ChartElementNumericAxis();

        $this->assertEquals(
            null,
            $options->chartPosition,
            'Wrong default value for property chartPosition in class ChartElementNumericAxis'
        );

        $options->chartPosition = .3;
        $this->assertSame(
            .3,
            $options->chartPosition,
            'Setting property value did not work for property chartPosition in class ChartElementNumericAxis'
        );

        try
        {
            $options->chartPosition = 15;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testAxisContainerIterator()
    {
        $options = new LineChart();

        $axis = array();

        $options->additionalAxis[] = $axis[] = new ChartElementNumericAxis();
        $options->additionalAxis[] = $axis[] = new ChartElementNumericAxis();
        $options->additionalAxis['foo'] = $axis['foo'] = new ChartElementLabeledAxis();

        foreach ( $options->additionalAxis as $key => $value )
        {
            $this->assertTrue(
                array_key_exists( $key, $axis ),
                "Expecteded key '$key' in both arrays."
            );

            $this->assertSame(
                $axis[$key],
                $value,
                "Value should be the same for key '$key'."
            );
        }
    }

    public function testAddAdditionalAxisToChart()
    {
        $chart = new LineChart();

        $this->assertTrue(
            $chart->additionalAxis instanceof AxisContainer,
            'Line chart option additionalAxis should be of AxisContainer.'
        );

        $this->assertSame(
            count( $chart->additionalAxis ),
            0,
            'The initial count of additional axis should be zero.'
        );

        $chart->additionalAxis[] = new ChartElementNumericAxis();

        $this->assertSame(
            count( $chart->additionalAxis ),
            1,
            'The count of additional axis should be one.'
        );

        $chart->additionalAxis[] = new ChartElementLabeledAxis();

        $this->assertSame(
            count( $chart->additionalAxis ),
            2,
            'The count of additional axis should be two.'
        );

        try
        {
            $chart->additionalAxis[] = $chart;
        }
        catch( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testDatasetAxisAssignement()
    {
        $chart = new LineChart();

        $chart->additionalAxis['marker'] = new ChartElementNumericAxis();
        $chart->additionalAxis['new base'] = new ChartElementLabeledAxis();

        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => -21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['sampleData']->yAxis = $chart->additionalAxis['marker'];
        $chart->data['sampleData']->xAxis = $chart->additionalAxis['new base'];
        
        $this->assertTrue(
            $chart->data['sampleData']->yAxis->default instanceof ChartElementNumericAxis,
            'yAxis property should point to a ChartElementNumericAxis.'
        );

        $this->assertTrue(
            $chart->data['sampleData']->xAxis->default instanceof ChartElementLabeledAxis,
            'xAxis property should point to a ChartElementLabeledAxis.'
        );

        try
        {
            $chart->data['sampleData']->yAxis['sample 1'] = $chart->additionalAxis['marker'];
        }
        catch ( ezcGraphInvalidAssignementException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphInvalidAssignementException.' );
    }

    public function testDatasetAxisAssignementWithoutRegistration()
    {
        $chart = new LineChart();

        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => -21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['sampleData']->yAxis = new ChartElementNumericAxis();
        $chart->data['sampleData']->xAxis = new ChartElementLabeledAxis();
        
        $this->assertEquals(
            new ChartElementNumericAxis(),
            $chart->data['sampleData']->yAxis->default,
            'yAxis property should point to a ChartElementNumericAxis.'
        );

        $this->assertEquals(
            new ChartElementLabeledAxis(),
            $chart->data['sampleData']->xAxis->default,
            'xAxis property should point to a ChartElementLabeledAxis.'
        );

        try
        {
            $chart->data['sampleData']->xAxis[100] = new ChartElementLabeledAxis();
        }
        catch ( ezcGraphInvalidAssignementException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphInvalidAssignementException.' );
    }

    public function testRenderNoMainAxisAssignement()
    {
        $chart = new LineChart();

        $chart->additionalAxis['marker'] = new ChartElementNumericAxis();
        $chart->additionalAxis['new base'] = new ChartElementLabeledAxis();

        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => -21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1) );
        $chart->data['sampleData']->yAxis = $chart->additionalAxis['marker'];
        $chart->data['sampleData']->xAxis = $chart->additionalAxis['new base'];
        
        try
        {
            $chart->render( 400, 200 );
        }
        catch ( ezcGraphNoDataException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphNoDataException.' );
    }

    public function testRenderNoLabelRendererFallBack()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new LineChart();

        $chart->additionalAxis['marker'] = $marker = new ChartElementNumericAxis();
        $chart->additionalAxis['empty'] = $empty = new ChartElementNumericAxis();

        $marker->position = ezcGraph::BOTTOM;
        $marker->chartPosition = 1;

        $empty->position =  ezcGraph::BOTTOM;
        $empty->chartPosition = .5;
        $empty->label = 'Marker';

        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => -21, 'sample 3' => 324, 'sample 4' => 620, 'sample 5' => 1) );
        $chart->data['sampleData']->yAxis = $chart->additionalAxis['marker'];
        
        $chart->data['moreData'] = new ArrayDataSet( array( 'sample 1' => 112, 'sample 2' => 54, 'sample 3' => 12, 'sample 4' => -167, 'sample 5' => 329) );
        $chart->data['Even more data'] = new ArrayDataSet( array( 'sample 1' => 300, 'sample 2' => -30, 'sample 3' => 220, 'sample 4' => 67, 'sample 5' => 450) );

        $chart->render( 500, 200, $filename );

        $this->compare( 
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderNoLabelRendererFallBackXAxis()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new LineChart();

        $chart->additionalAxis['marker'] = $marker = new ChartElementLabeledAxis();
        $chart->additionalAxis['empty'] = $empty = new ChartElementLabeledAxis();

        $marker->position = ezcGraph::LEFT;
        $marker->chartPosition = 1;

        $empty->position =  ezcGraph::RIGHT;
        $empty->chartPosition = .0;
        $empty->label = 'Marker';

        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => -21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1, 'sample 6' => 74) );
        $chart->data['sampleData']->xAxis = $chart->additionalAxis['marker'];
        
        $chart->data['moreData'] = new ArrayDataSet( array( 'sample 1' => 112, 'sample 2' => 54, 'sample 3' => 12, 'sample 4' => -167, 'sample 5' => 329) );
        $chart->data['Even more data'] = new ArrayDataSet( array( 'sample 1' => 300, 'sample 2' => -30, 'sample 3' => 220, 'sample 4' => 67, 'sample 5' => 450) );

        $chart->render( 500, 200, $filename );

        $this->compare( 
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderNoLabelRendererDifferentAxisSpace()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new LineChart();

        $chart->additionalAxis['marker'] = $marker = new ChartElementLabeledAxis();
        $chart->additionalAxis['empty'] = $empty = new ChartElementLabeledAxis();

        $chart->xAxis->axisSpace = 0.1;
        $chart->yAxis->axisSpace = 0.05;

        $marker->position = ezcGraph::LEFT;
        $marker->axisSpace = .1;
        $marker->chartPosition = 1;

        $empty->position =  ezcGraph::BOTTOM;
        $empty->chartPosition = .5;
        $empty->label = 'Marker';

        $chart->data['sampleData'] = new ArrayDataSet( array( 'sample 1' => 234, 'sample 2' => -21, 'sample 3' => 324, 'sample 4' => 120, 'sample 5' => 1, 'sample 6' => 74) );
        $chart->data['sampleData']->xAxis = $chart->additionalAxis['marker'];
        
        $chart->data['moreData'] = new ArrayDataSet( array( 'sample 1' => 112, 'sample 2' => 54, 'sample 3' => 12, 'sample 4' => -167, 'sample 5' => 329) );
        $chart->data['Even more data'] = new ArrayDataSet( array( 'sample 1' => 300, 'sample 2' => -30, 'sample 3' => 220, 'sample 4' => 67, 'sample 5' => 450) );

        $chart->render( 500, 200, $filename );

        $this->compare( 
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderNoLabelRendererZeroAxisSpace()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new LineChart();

        $chart->additionalAxis['marker'] = $marker = new ChartElementLabeledAxis();
        $chart->additionalAxis['empty'] = $empty = new ChartElementLabeledAxis();

        $chart->xAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->xAxis->axisSpace = 0;

        $chart->yAxis->axisLabelRenderer = new AxisNoLabelRenderer();
        $chart->yAxis->axisSpace = 0;

        $marker->position = ezcGraph::LEFT;
        $marker->axisSpace = 0;
        $marker->chartPosition = 1;

        $empty->position =  ezcGraph::RIGHT;
        $empty->chartPosition = .0;
        $empty->axisSpace = 0;
        $empty->label = 'Marker';

        $chart->data['moreData'] = new ArrayDataSet( array( 'sample 1' => 112, 'sample 2' => 54, 'sample 3' => 12, 'sample 4' => -167, 'sample 5' => 329) );
        $chart->data['Even more data'] = new ArrayDataSet( array( 'sample 1' => 300, 'sample 2' => -30, 'sample 3' => 220, 'sample 4' => 67, 'sample 5' => 450) );

        $chart->render( 500, 200, $filename );

        $this->compare( 
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }
}
?>
