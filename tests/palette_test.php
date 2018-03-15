<?php
/**
 * ezcGraphPaletteTest 
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
use Ezc\Graph\Palette\Black;
use Ezc\Graph\Palette\Tango;

/**
 * Tests for ezcGraph class.
 * 
 * @package Graph
 * @subpackage Tests
 */
class ezcGraphPaletteTest extends ezcTestCase
{
	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "ezcGraphPaletteTest" );
	}

    public function testDefaultPalette()
    {
        $chart = new LineChart();

        $this->assertTrue(
            $chart->palette instanceof ezcGraphPalette,
            'No default palette was set.'
        );
        
        $this->assertTrue(
            $chart->palette instanceof Tango,
            'Default pallete should be tango.'
        );
    }

    public function testChangePalette()
    {
        $chart = new LineChart();
        $chart->palette = new Black();

        $this->assertTrue(
            $chart->palette instanceof ezcGraphPalette,
            'No default palette was set.'
        );
        
        $this->assertTrue(
            $chart->palette instanceof Black,
            'Default pallete should be tango.'
        );
    }

    public function testInvalidPalette()
    {
        try
        {
            $chart = new LineChart();
            // Silenced, because this throws an E_WARNING in devel mode,
            // caused by the non existing class ezcGraphPaletteUndefined
            @$chart->palette = 'Undefined';
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphUnknownPaletteException.' );
    }

    public function testChartBackgroundColor()
    {
        $chart = new LineChart();

        $this->assertEquals(
            Color::fromHex( '#EEEEEC' ),
            $chart->palette->chartBackground,
            'Background color not properly set.'
        );
    }

    public function testElementBackgroundColor()
    {
        $chart = new LineChart();

        $this->assertEquals(
            Color::fromHex( '#000000FF' ),
            $chart->palette->elementBackground,
            'Background color not properly set.'
        );
    }

    public function testAxisColor()
    {
        $chart = new LineChart();

        $this->assertEquals(
            Color::fromHex( '#2E3436' ),
            $chart->palette->axisColor,
            'Axis color not properly set.'
        );
    }

    public function testDataSetColor()
    {
        $chart = new LineChart();

        $this->assertEquals(
            Color::fromHex( '#3465A4' ),
            $chart->palette->dataSetColor,
            'DataSet color not properly set.'
        );

        $this->assertEquals(
            Color::fromHex( '#4E9A06' ),
            $chart->palette->dataSetColor,
            'DataSet color not properly set.'
        );

        $this->assertEquals(
            Color::fromHex( '#CC0000' ),
            $chart->palette->dataSetColor,
            'DataSet color not properly set.'
        );

        $this->assertEquals(
            Color::fromHex( '#EDD400' ),
            $chart->palette->dataSetColor,
            'DataSet color not properly set.'
        );

        $this->assertEquals(
            Color::fromHex( '#75505B' ),
            $chart->palette->dataSetColor,
            'DataSet color not properly set.'
        );

        $this->assertEquals(
            Color::fromHex( '#F57900' ),
            $chart->palette->dataSetColor,
            'DataSet color not properly set.'
        );

        $this->assertEquals(
            Color::fromHex( '#204A87' ),
            $chart->palette->dataSetColor,
            'DataSet color not properly set.'
        );

        $this->assertEquals(
            Color::fromHex( '#C17D11' ),
            $chart->palette->dataSetColor,
            'DataSet color not properly set.'
        );

        $this->assertEquals(
            Color::fromHex( '#3465A4' ),
            $chart->palette->dataSetColor,
            'DataSet color not properly set.'
        );
    }

    public function testDataSetSymbol()
    {
        $chart = new LineChart();

        $this->assertEquals(
            ezcGraph::NO_SYMBOL,
            $chart->palette->dataSetSymbol,
            'Symbol for datasets not properly set.'
        );

        $this->assertEquals(
            ezcGraph::NO_SYMBOL,
            $chart->palette->dataSetSymbol,
            'Symbol for datasets not properly set.'
        );
    }

    public function testFontName()
    {
        $chart = new LineChart();

        $this->assertEquals(
            'sans-serif',
            $chart->palette->fontName,
            'Font face not properly set.'
        );
    }

    public function testFontColor()
    {
        $chart = new LineChart();

        $this->assertEquals(
            Color::fromHex( '#2E3436' ),
            $chart->palette->fontColor,
            'Font color not properly set.'
        );
    }

    public function testChartBorderColor()
    {
        $chart = new LineChart();

        $this->assertEquals(
            Color::fromHex( '#000000FF' ),
            $chart->palette->chartBorderColor,
            'Border color not properly set.'
        );
    }

    public function testChartBorderWidth()
    {
        $chart = new LineChart();

        $this->assertEquals(
            0,
            $chart->palette->chartBorderWidth,
            'Border width not properly set.'
        );
    }

    public function testElementBorderColor()
    {
        $chart = new LineChart();

        $this->assertEquals(
            Color::fromHex( '#000000FF' ),
            $chart->palette->elementBorderColor,
            'Border color not properly set.'
        );
    }

    public function testElementBorderWidth()
    {
        $chart = new LineChart();

        $this->assertEquals(
            0,
            $chart->palette->elementBorderWidth,
            'Border width not properly set.'
        );
    }

    public function testPadding()
    {
        $chart = new LineChart();

        $this->assertEquals(
            1,
            $chart->palette->padding,
            'Padding not properly set.'
        );
    }

    public function testMargin()
    {
        $chart = new LineChart();

        $this->assertEquals(
            0,
            $chart->palette->margin,
            'Margin not properly set.'
        );
    }

    public function testDataSetAutomaticColorization()
    {
        $chart = new LineChart();
        $chart->data['income'] = new ArrayDataSet( array( 2000 => 2345.2, 2456.3, 2567.4 ) );
        $chart->data['spending'] = new ArrayDataSet( array( 2000 => 2347.2, 2458.3, 2569.4 ) );

        $this->assertEquals(
            Color::fromHex( '#3465A4' ),
            $chart->data['income']->color->default,
            'Wrong automatic color set.'
        );

        $this->assertEquals(
            Color::fromHex( '#4E9A06' ),
            $chart->data['spending']->color->default,
            'Wrong automatic color set.'
        );
    }

    public function testChartBackground()
    {
        $chart = new LineChart();
        $chart->data['income'] = new ArrayDataSet( array( 2000 => 2345.2, 2456.3, 2567.4 ) );
        $chart->data['spending'] = new ArrayDataSet( array( 2000 => 2347.2, 2458.3, 2569.4 ) );

        $this->assertEquals(
            Color::fromHex( '#EEEEEC' ),
            $chart->background->background,
            'Chart background not set from pallet.'
        );
    }

    public function testChartElementBorder()
    {
        $chart = new LineChart();
        $chart->data['income'] = new ArrayDataSet( array( 2000 => 2345.2, 2456.3, 2567.4 ) );
        $chart->data['spending'] = new ArrayDataSet( array( 2000 => 2347.2, 2458.3, 2569.4 ) );

        $this->assertEquals(
            Color::fromHex( '#000000FF' ),
            $chart->legend->border,
            'Chart background not set from pallet.'
        );
    }

    public function testChartElementBorderWidth()
    {
        $chart = new LineChart();
        $chart->data['income'] = new ArrayDataSet( array( 2000 => 2345.2, 2456.3, 2567.4 ) );
        $chart->data['spending'] = new ArrayDataSet( array( 2000 => 2347.2, 2458.3, 2569.4 ) );

        $this->assertEquals(
            0,
            $chart->legend->borderWidth,
            'Chart background not set from pallet.'
        );
    }

    public function testChartElementAxisColor()
    {
        $chart = new LineChart();
        $chart->data['income'] = new ArrayDataSet( array( 2000 => 2345.2, 2456.3, 2567.4 ) );
        $chart->data['spending'] = new ArrayDataSet( array( 2000 => 2347.2, 2458.3, 2569.4 ) );

        $this->assertEquals(
            Color::fromHex( '#2E3436' ),
            $chart->xAxis->border,
            'Chart background not set from pallet.'
        );
    }

    public function testModifyPaletteColorProperty()
    {
        $palette = new Tango();

        $palette->axisColor = '#FFFFFF';
        $palette->majorGridColor = array( 255, 255, 255, 255 );
        $palette->minorGridColor = Color::fromHex( '#00000000' );

        $this->assertEquals(
            $palette->axisColor,
            Color::fromHex( '#FFFFFF' )
        );

        try
        {
            $palette->axisColor = false;
        }
        catch ( ezcGraphUnknownColorDefinitionException $e )
        {
            return true;
        }

        $this->fail( 'expected ezcGraphUnknownColorDefinitionException.' );
    }

    public function testModifyPaletteDatasetColorArray()
    {
        $palette = new Tango();

        $palette->dataSetColor = array(
            '#ABCDEF',
            array( 255, 255, 255 ),
        );

        $this->assertEquals(
            $palette->dataSetColor,
            Color::fromHex( '#ABCDEF' )
        );

        try
        {
            $palette->dataSetColor = '#FFFFFF';
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'expected ezcBaseValueException.' );
    }

    public function testModifyPaletteDatasetSymbolArray()
    {
        $palette = new Tango();

        $palette->dataSetSymbol = array(
            ezcGraph::BULLET,
            ezcGraph::CIRCLE
        );

        $this->assertSame(
            $palette->dataSetSymbol,
            ezcGraph::BULLET
        );

        try
        {
            $palette->dataSetSymbol = ezcGraph::BULLET;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'expected ezcBaseValueException.' );
    }

    public function testPalettePropertyChartBorderWidth()
    {
        $options = new Tango();

        $this->assertSame(
            0,
            $options->chartBorderWidth,
            'Wrong default value for property chartBorderWidth in class ezcGraphPalette'
        );

        $options->chartBorderWidth = 1;
        $this->assertSame(
            1,
            $options->chartBorderWidth,
            'Setting property value did not work for property chartBorderWidth in class ezcGraphPalette'
        );

        try
        {
            $options->chartBorderWidth = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testPalettePropertyElementBorderWidth()
    {
        $options = new Tango();

        $this->assertSame(
            0,
            $options->elementBorderWidth,
            'Wrong default value for property elementBorderWidth in class ezcGraphPalette'
        );

        $options->elementBorderWidth = 1;
        $this->assertSame(
            1,
            $options->elementBorderWidth,
            'Setting property value did not work for property elementBorderWidth in class ezcGraphPalette'
        );

        try
        {
            $options->elementBorderWidth = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testPalettePropertyPadding()
    {
        $options = new Tango();

        $this->assertSame(
            1,
            $options->padding,
            'Wrong default value for property padding in class ezcGraphPalette'
        );

        $options->padding = 2;
        $this->assertSame(
            2,
            $options->padding,
            'Setting property value did not work for property padding in class ezcGraphPalette'
        );

        try
        {
            $options->padding = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testPalettePropertyMargin()
    {
        $options = new Tango();

        $this->assertSame(
            0,
            $options->margin,
            'Wrong default value for property margin in class ezcGraphPalette'
        );

        $options->margin = 1;
        $this->assertSame(
            1,
            $options->margin,
            'Setting property value did not work for property margin in class ezcGraphPalette'
        );

        try
        {
            $options->margin = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }
}
?>
