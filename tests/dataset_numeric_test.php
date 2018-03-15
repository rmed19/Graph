<?php
/**
 * NumericDataSetTest 
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
use Ezc\Graph\Axis\ChartElementNumericAxis;
use Ezc\Graph\Datasets\NumericDataSet;



/**
 * Tests for ezcGraph class.
 * 
 * @package Graph
 * @subpackage Tests
 */
class NumericDataSetTest extends ezcGraphTestCase
{
    protected $basePath;

    protected $tempDir;

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "NumericDataSetTest" );
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

    public function testNumericDataSetPropertyResolution()
    {
        $dataset = new NumericDataSet();

        $this->assertSame(
            100,
            $dataset->resolution,
            'Wrong default value for property resolution in class NumericDataSet'
        );

        $dataset->resolution = 5;
        $this->assertSame(
            5,
            $dataset->resolution,
            'Setting property value did not work for property resolution in class NumericDataSet'
        );

        $this->assertSame(
            6,
            count( $dataset ),
            'Setting property value did not work for property resolution in class NumericDataSet'
        );

        try
        {
            $dataset->resolution = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testNumericDataSetPropertyStart()
    {
        $dataset = new NumericDataSet();

        $this->assertSame(
            null,
            $dataset->start,
            'Wrong default value for property start in class NumericDataSet'
        );

        $dataset->start = -32.4;
        $this->assertSame(
            -32.4,
            $dataset->start,
            'Setting property value did not work for property start in class NumericDataSet'
        );

        try
        {
            $dataset->start = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testNumericDataSetPropertyEnd()
    {
        $dataset = new NumericDataSet();

        $this->assertSame(
            null,
            $dataset->end,
            'Wrong default value for property end in class NumericDataSet'
        );

        $dataset->end = -32.4;
        $this->assertSame(
            -32.4,
            $dataset->end,
            'Setting property value did not work for property end in class NumericDataSet'
        );

        try
        {
            $dataset->end = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testNumericDataSetPropertyCallback()
    {
        $dataset = new NumericDataSet();

        $this->assertSame(
            null,
            $dataset->callback,
            'Wrong default value for property callback in class NumericDataSet'
        );

        $dataset->callback = 'sin';
        $this->assertSame(
            'sin',
            $dataset->callback,
            'Setting property value did not work for property callback in class NumericDataSet'
        );

        // Use random default enabled public static method
        $dataset->callback = array( 'Reflection', 'export' );
        $this->assertSame(
            array( 'Reflection', 'export' ),
            $dataset->callback,
            'Setting property value did not work for property callback in class NumericDataSet'
        );

        // Use random default enabled public method
        $reflection = new ReflectionClass( 'Exception' );
        $dataset->callback = array( $reflection, 'isInternal' );
        $this->assertSame(
            array( $reflection, 'isInternal' ),
            $dataset->callback,
            'Setting property value did not work for property callback in class NumericDataSet'
        );

        try
        {
            $dataset->callback = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testIterateOverAverageDataset()
    {
        $numericDataSet = new NumericDataSet( -1, 1, 'sin' );

        $stepSize = 2 / 100;
        $start = -1 - $stepSize;

        foreach ( $numericDataSet as $key => $value )
        {
            $expectedKey = $start += $stepSize;
            $expectedValue = sin( $expectedKey );

            $this->assertEquals( $expectedKey, $key, 'Wrong key value.', .01 );
            $this->assertEquals( $expectedValue, $value, 'Wrong value.', .01 );
        }
    }

    public function testIterateOverAverageDataset2()
    {
        $numericDataSet = new NumericDataSet( 
            -90, 
            90, 
            create_function( 
                '$x',
                'return 10 * sin( deg2rad( $x ) );'
            )
        );
        $numericDataSet->resolution = 180;

        $stepSize = 1;
        $start = -91;

        foreach ( $numericDataSet as $key => $value )
        {
            $expectedKey = $start += $stepSize;
            $expectedValue = sin( deg2rad( $expectedKey ) ) * 10;

            $this->assertEquals( $expectedKey, $key, 'Wrong key value.', .01 );
            $this->assertEquals( $expectedValue, $value, 'Wrong value.', .01 );
        }
    }

    public function testRenderCompleteLineChart()
    {
        $filename = $this->tempDir . __FUNCTION__ . '.svg';

        $chart = new LineChart();
        $chart->data['Sinus'] = new NumericDataSet( 
            -180, 
            180, 
            create_function( 
                '$x',
                'return 10 * sin( deg2rad( $x ) );'
            )
        );
        $chart->data['Cosinus'] = new NumericDataSet( 
            -180, 
            180, 
            create_function( 
                '$x',
                'return 5 * cos( deg2rad( $x ) );'
            )
        );
        $chart->xAxis = new ChartElementNumericAxis();

        $chart->render( 500, 200, $filename );

        $this->compare(
            $filename,
            $this->basePath . 'compare/' . __CLASS__ . '_' . __FUNCTION__ . '.svg'
        );
    }
}

?>
