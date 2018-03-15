<?php

use Ezc\Graph\Axis\ChartElementNumericAxis;
use Ezc\Graph\Options\FontOptions;
use Ezc\Graph\Element\ChartElementBackground;
use Ezc\Graph\Element\ChartElementLegend;
use Ezc\Graph\Element\ChartElementText;
use Ezc\Graph\Colors\Color;
use Ezc\Graph\Renderer\AxisBoxedLabelRenderer;

/**
 * ezcGraphElementOptionsTest 
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

/**
 * Tests for ezcGraph class.
 * 
 * @package Graph
 * @subpackage Tests
 */
class ezcGraphElementOptionsTest extends ezcTestImageCase
{

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "ezcGraphElementOptionsTest" );
	}


    public function testChartElementPropertyTitle()
    {
        $options = new ChartElementBackground();

        $this->assertSame(
            false,
            $options->title,
            'Wrong default value for property title in class ChartElementBackground'
        );

        $options->title = 'Title';
        $this->assertSame(
            'Title',
            $options->title,
            'Setting property value did not work for property title in class ChartElementBackground'
        );
    }

    public function testChartElementPropertyBackground()
    {
        $options = new ChartElementBackground();

        $this->assertSame(
            false,
            $options->background,
            'Wrong default value for property background in class ChartElementBackground'
        );

        $options->background = $color = Color::fromHex( '#FFFFFF' );
        $this->assertSame(
            $color,
            $options->background,
            'Setting property value did not work for property background in class ChartElementBackground'
        );

        try
        {
            $options->background = false;
        }
        catch ( ezcGraphUnknownColorDefinitionException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphUnknownColorDefinitionException.' );
    }

    public function testChartElementPropertyBoundings()
    {
        $options = new ChartElementBackground();

        $this->assertSame(
            'ezcGraphBoundings',
            get_class( $options->boundings ),
            'Wrong default value for property boundings in class ChartElementBackground'
        );
    }

    public function testChartElementPropertyBorder()
    {
        $options = new ChartElementBackground();

        $this->assertSame(
            false,
            $options->border,
            'Wrong default value for property border in class ChartElementBackground'
        );

        $options->border = $color = Color::fromHex( '#FFFFFF' );
        $this->assertSame(
            $color,
            $options->border,
            'Setting property value did not work for property border in class ChartElementBackground'
        );

        try
        {
            $options->border = false;
        }
        catch ( ezcGraphUnknownColorDefinitionException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphUnknownColorDefinitionException.' );
    }

    public function testChartElementPropertyPadding()
    {
        $options = new ChartElementBackground();

        $this->assertSame(
            0,
            $options->padding,
            'Wrong default value for property padding in class ChartElementBackground'
        );

        $options->padding = 1;
        $this->assertSame(
            1,
            $options->padding,
            'Setting property value did not work for property padding in class ChartElementBackground'
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

    public function testChartElementPropertyMargin()
    {
        $options = new ChartElementBackground();

        $this->assertSame(
            0,
            $options->margin,
            'Wrong default value for property margin in class ChartElementBackground'
        );

        $options->margin = 1;
        $this->assertSame(
            1,
            $options->margin,
            'Setting property value did not work for property margin in class ChartElementBackground'
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

    public function testChartElementPropertyBorderWidth()
    {
        $options = new ChartElementBackground();

        $this->assertSame(
            0,
            $options->borderWidth,
            'Wrong default value for property borderWidth in class ChartElementBackground'
        );

        $options->borderWidth = 1;
        $this->assertSame(
            1,
            $options->borderWidth,
            'Setting property value did not work for property borderWidth in class ChartElementBackground'
        );

        try
        {
            $options->borderWidth = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartElementPropertyPosition()
    {
        $options = new ChartElementBackground();

        $this->assertSame(
            ezcGraph::LEFT,
            $options->position,
            'Wrong default value for property position in class ChartElementBackground'
        );

        $options->position = ezcGraph::RIGHT;
        $this->assertSame(
            ezcGraph::RIGHT,
            $options->position,
            'Setting property value did not work for property position in class ChartElementBackground'
        );

        try
        {
            $options->position = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartElementPropertyMaxTitleHeight()
    {
        $options = new ChartElementBackground();

        $this->assertSame(
            16,
            $options->maxTitleHeight,
            'Wrong default value for property maxTitleHeight in class ChartElementBackground'
        );

        $options->maxTitleHeight = 20;
        $this->assertSame(
            20,
            $options->maxTitleHeight,
            'Setting property value did not work for property maxTitleHeight in class ChartElementBackground'
        );

        try
        {
            $options->maxTitleHeight = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartElementPropertyPortraitTitleSize()
    {
        $options = new ChartElementBackground();

        $this->assertSame(
            .15,
            $options->portraitTitleSize,
            'Wrong default value for property portraitTitleSize in class ChartElementBackground'
        );

        $options->portraitTitleSize = .5;
        $this->assertSame(
            .5,
            $options->portraitTitleSize,
            'Setting property value did not work for property portraitTitleSize in class ChartElementBackground'
        );

        try
        {
            $options->portraitTitleSize = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartElementPropertyLandscapeTitleSize()
    {
        $options = new ChartElementBackground();

        $this->assertSame(
            .2,
            $options->landscapeTitleSize,
            'Wrong default value for property landscapeTitleSize in class ChartElementBackground'
        );

        $options->landscapeTitleSize = .5;
        $this->assertSame(
            .5,
            $options->landscapeTitleSize,
            'Setting property value did not work for property landscapeTitleSize in class ChartElementBackground'
        );

        try
        {
            $options->landscapeTitleSize = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartElementPropertyFont()
    {
        $options = new ChartElementBackground();

        $this->assertSame(
            'FontOptions',
            get_class( $options->font ),
            'Wrong default value for property font in class ChartElementBackground'
        );

        $fontOptions = new FontOptions();
        $fontOptions->path = dirname( __FILE__ ) . '/data/font2.ttf';

        $options->font = $fontOptions;
        $this->assertSame(
            $fontOptions,
            $options->font,
            'Setting property value did not work for property font in class ChartElementBackground'
        );

        try
        {
            $options->font = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartElementPropertyFontCloned()
    {
        $options = new ChartElementBackground();

        $this->assertSame(
            false,
            $options->fontCloned,
            'Wrong default value for property fontCloned in class ChartElementBackground'
        );
    }

    public function testChartElementBackgroundPropertyImage()
    {
        $options = new ChartElementBackground();

        $this->assertSame(
            false,
            $options->image,
            'Wrong default value for property image in class ChartElementBackground'
        );

        $options->image = $file = dirname( __FILE__ ) . '/data/gif.gif';
        $this->assertSame(
            $file,
            $options->image,
            'Setting property value did not work for property image in class ChartElementBackground'
        );

        try
        {
            $options->image = false;
        }
        catch ( ezcBaseFileNotFoundException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseFileNotFoundException.' );
    }

    public function testChartElementBackgroundPropertyRepeat()
    {
        $options = new ChartElementBackground();

        $this->assertSame(
            ezcGraph::NO_REPEAT,
            $options->repeat,
            'Wrong default value for property repeat in class ChartElementBackground'
        );

        $options->repeat = ezcGraph::VERTICAL;
        $this->assertSame(
            ezcGraph::VERTICAL,
            $options->repeat,
            'Setting property value did not work for property repeat in class ChartElementBackground'
        );

        try
        {
            $options->repeat = 42;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartElementLegendPropertyPadding()
    {
        $options = new ChartElementLegend();

        $this->assertSame(
            0,
            $options->padding,
            'Wrong default value for property padding in class ChartElementLegend'
        );

        $options->padding = 1;
        $this->assertSame(
            1,
            $options->padding,
            'Setting property value did not work for property padding in class ChartElementLegend'
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

    public function testChartElementLegendPropertyPortraitSize()
    {
        $options = new ChartElementLegend();

        $this->assertSame(
            .2,
            $options->portraitSize,
            'Wrong default value for property portraitSize in class ChartElementLegend'
        );

        $options->portraitSize = .5;
        $this->assertSame(
            .5,
            $options->portraitSize,
            'Setting property value did not work for property portraitSize in class ChartElementLegend'
        );

        try
        {
            $options->portraitSize = 42;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartElementLegendPropertyLandscapeSize()
    {
        $options = new ChartElementLegend();

        $this->assertSame(
            .1,
            $options->landscapeSize,
            'Wrong default value for property landscapeSize in class ChartElementLegend'
        );

        $options->landscapeSize = .5;
        $this->assertSame(
            .5,
            $options->landscapeSize,
            'Setting property value did not work for property landscapeSize in class ChartElementLegend'
        );

        try
        {
            $options->landscapeSize = 42;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartElementLegendPropertySymbolSize()
    {
        $options = new ChartElementLegend();

        $this->assertSame(
            14,
            $options->symbolSize,
            'Wrong default value for property symbolSize in class ChartElementLegend'
        );

        $options->symbolSize = 20;
        $this->assertSame(
            20,
            $options->symbolSize,
            'Setting property value did not work for property symbolSize in class ChartElementLegend'
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

    public function testChartElementLegendPropertyMinimumSymbolSize()
    {
        $options = new ChartElementLegend();

        $this->assertSame(
            .05,
            $options->minimumSymbolSize,
            'Wrong default value for property minimumSymbolSize in class ChartElementLegend'
        );

        $options->minimumSymbolSize = .1;
        $this->assertSame(
            .1,
            $options->minimumSymbolSize,
            'Setting property value did not work for property minimumSymbolSize in class ChartElementLegend'
        );

        try
        {
            $options->minimumSymbolSize = 42;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartElementLegendPropertySpacing()
    {
        $options = new ChartElementLegend();

        $this->assertSame(
            2,
            $options->spacing,
            'Wrong default value for property spacing in class ChartElementLegend'
        );

        $options->spacing = 5;
        $this->assertSame(
            5,
            $options->spacing,
            'Setting property value did not work for property spacing in class ChartElementLegend'
        );

        try
        {
            $options->spacing = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartElementAxisPropertyNullPosition()
    {
        $options = new ChartElementNumericAxis();

        $this->assertSame(
            false,
            $options->nullPosition,
            'Wrong default value for property nullPosition in class ChartElementNumericAxis'
        );

        $options->nullPosition = .5;
        $this->assertSame(
            .5,
            $options->nullPosition,
            'Setting property value did not work for property nullPosition in class ChartElementNumericAxis'
        );
    }

    public function testChartElementAxisPropertyAxisSpace()
    {
        $options = new ChartElementNumericAxis();

        $this->assertSame(
            .1,
            $options->axisSpace,
            'Wrong default value for property axisSpace in class ChartElementNumericAxis'
        );

        $options->axisSpace = .2;
        $this->assertSame(
            .2,
            $options->axisSpace,
            'Setting property value did not work for property axisSpace in class ChartElementNumericAxis'
        );

        try
        {
            $options->axisSpace = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    /* Disabled for now.
    public function testChartElementAxisPropertyOuterAxisSpace()
    {
        $options = new \Ezc\Graph\Axis\ChartElementNumericAxis();

        $this->assertSame(
            null,
            $options->outerAxisSpace,
            'Wrong default value for property outerAxisSpace in class Ezc\Graph\Axis\ChartElementNumericAxis'
        );

        $options->outerAxisSpace = .2;
        $this->assertSame(
            .2,
            $options->outerAxisSpace,
            'Setting property value did not work for property outerAxisSpace in class Ezc\Graph\Axis\ChartElementNumericAxis'
        );

        $options->outerAxisSpace = null;
        $this->assertSame(
            null,
            $options->outerAxisSpace,
            'Setting property value did not work for property outerAxisSpace in class 
    Ezc\Graph\Axis\ChartElementNumericAxis'
        );

        try
        {
            $options->outerAxisSpace = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    } // */

    public function testChartElementAxisPropertyMajorGrid()
    {
        $options = new ChartElementNumericAxis();

        $this->assertSame(
            false,
            $options->majorGrid,
            'Wrong default value for property majorGrid in class ChartElementNumericAxis'
        );

        $options->majorGrid = $color = Color::fromHex( '#FFFFFF' );
        $this->assertSame(
            $color,
            $options->majorGrid,
            'Setting property value did not work for property majorGrid in class ChartElementNumericAxis'
        );

        try
        {
            $options->majorGrid = false;
        }
        catch ( ezcGraphUnknownColorDefinitionException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphUnknownColorDefinitionException.' );
    }

    public function testChartElementAxisPropertyMinorGrid()
    {
        $options = new ChartElementNumericAxis();

        $this->assertSame(
            false,
            $options->minorGrid,
            'Wrong default value for property minorGrid in class ChartElementNumericAxis'
        );

        $options->minorGrid = $color = Color::fromHex( '#FFFFFF' );
        $this->assertSame(
            $color,
            $options->minorGrid,
            'Setting property value did not work for property minorGrid in class ChartElementNumericAxis'
        );

        try
        {
            $options->minorGrid = false;
        }
        catch ( ezcGraphUnknownColorDefinitionException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcGraphUnknownColorDefinitionException.' );
    }

    public function testChartElementAxisPropertyMajorStep()
    {
        $options = new ChartElementNumericAxis();

        $this->assertSame(
            null,
            $options->majorStep,
            'Wrong default value for property majorStep in class ChartElementNumericAxis'
        );

        $options->majorStep = 1.;
        $this->assertSame(
            1.,
            $options->majorStep,
            'Setting property value did not work for property majorStep in class ChartElementNumericAxis'
        );

        try
        {
            $options->majorStep = -1.;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartElementAxisPropertyMinorStep()
    {
        $options = new ChartElementNumericAxis();

        $this->assertSame(
            null,
            $options->minorStep,
            'Wrong default value for property minorStep in class ChartElementNumericAxis'
        );

        $options->minorStep = 1.;
        $this->assertSame(
            1.,
            $options->minorStep,
            'Setting property value did not work for property minorStep in class ChartElementNumericAxis'
        );

        try
        {
            $options->minorStep = -1.;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartElementAxisPropertyFormatString()
    {
        $options = new ChartElementNumericAxis();

        $this->assertSame(
            '%s',
            $options->formatString,
            'Wrong default value for property formatString in class ChartElementNumericAxis'
        );

        $options->formatString = '[%s]';
        $this->assertSame(
            '[%s]',
            $options->formatString,
            'Setting property value did not work for property formatString in class ChartElementNumericAxis'
        );
    }

    public function testChartElementAxisPropertyLabel()
    {
        $options = new ChartElementNumericAxis();

        $this->assertSame(
            false,
            $options->label,
            'Wrong default value for property label in class ChartElementNumericAxis'
        );

        $options->label = 'Axis';
        $this->assertSame(
            'Axis',
            $options->label,
            'Setting property value did not work for property label in class ChartElementNumericAxis'
        );
    }

    public function testChartElementAxisPropertyLabelSize()
    {
        $options = new ChartElementNumericAxis();

        $this->assertSame(
            14,
            $options->labelSize,
            'Wrong default value for property labelSize in class ChartElementNumericAxis'
        );

        $options->labelSize = 20;
        $this->assertSame(
            20,
            $options->labelSize,
            'Setting property value did not work for property labelSize in class ChartElementNumericAxis'
        );

        try
        {
            $options->labelSize = 2;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartElementAxisPropertyLabelMargin()
    {
        $options = new ChartElementNumericAxis();

        $this->assertSame(
            2,
            $options->labelMargin,
            'Wrong default value for property labelMargin in class ChartElementNumericAxis'
        );

        $options->labelMargin = 1;
        $this->assertSame(
            1,
            $options->labelMargin,
            'Setting property value did not work for property labelMargin in class ChartElementNumericAxis'
        );

        try
        {
            $options->labelMargin = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartElementAxisPropertyMinArrowHeadSize()
    {
        $options = new ChartElementNumericAxis();

        $this->assertSame(
            4,
            $options->minArrowHeadSize,
            'Wrong default value for property minArrowHeadSize in class ChartElementNumericAxis'
        );

        $options->minArrowHeadSize = 10;
        $this->assertSame(
            10,
            $options->minArrowHeadSize,
            'Setting property value did not work for property minArrowHeadSize in class ChartElementNumericAxis'
        );

        try
        {
            $options->labelMargin = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartElementAxisPropertyMaxArrowHeadSize()
    {
        $options = new ChartElementNumericAxis();

        $this->assertSame(
            8,
            $options->maxArrowHeadSize,
            'Wrong default value for property maxArrowHeadSize in class ChartElementNumericAxis'
        );

        $options->maxArrowHeadSize = 10;
        $this->assertSame(
            10,
            $options->maxArrowHeadSize,
            'Setting property value did not work for property maxArrowHeadSize in class ChartElementNumericAxis'
        );

        try
        {
            $options->labelMargin = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartElementAxisPropertyAxisLabelRenderer()
    {
        $options = new ChartElementNumericAxis();

        $this->assertSame(
            'ezcGraphAxisExactLabelRenderer',
            get_class( $options->axisLabelRenderer ),
            'Wrong default value for property axisLabelRenderer in class ChartElementNumericAxis'
        );

        $options->axisLabelRenderer = $axisLabelRenderer = new AxisBoxedLabelRenderer();
        $this->assertSame(
            $axisLabelRenderer,
            $options->axisLabelRenderer,
            'Setting property value did not work for property axisLabelRenderer in class ChartElementNumericAxis'
        );

        try
        {
            $options->axisLabelRenderer = false;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }

    public function testChartElementAxisPropertyLabelCallback()
    {
        $options = new ChartElementNumericAxis();

        $this->assertSame(
            null,
            $options->labelCallback,
            'Wrong default value for property labelCallback in class ChartElementNumericAxis'
        );

        $options->labelCallback = 'printf';
        $this->assertSame(
            'printf',
            $options->labelCallback,
            'Setting property value did not work for property labelCallback in class ChartElementNumericAxis'
        );

        $options->labelCallback = array( $this, __METHOD__ );
        $this->assertSame(
            array( $this, __METHOD__ ),
            $options->labelCallback,
            'Setting property value did not work for property labelCallback in class ChartElementNumericAxis'
        );

        try
        {
            $options->labelCallback = 'undefined_function';
        }
        catch ( ezcBasevalueException $e )
        {
            return true;
        }

        $this->fail( 'ezcBasevalueException expected.' );
    }

    public function testChartElementAxisPropertyLabelRotation()
    {
        $options = new ChartElementNumericAxis();

        $this->assertSame(
            0.,
            $options->labelRotation,
            'Wrong default value for property labelRotation in class ChartElementNumericAxis'
        );

        $options->labelRotation = 450;
        $this->assertSame(
            90.,
            $options->labelRotation,
            'Setting property value did not work for property labelRotation in class ChartElementNumericAxis'
        );

        try
        {
            $options->labelRotation = 'foo';
            $this->fail( 'ezcBasevalueException expected.' );
        }
        catch ( ezcBasevalueException $e )
        { /* Expected */ }
    }

    public function testChartElementTextPropertyMaxHeight()
    {
        $options = new ChartElementText();

        $this->assertSame(
            0.1,
            $options->maxHeight,
            'Wrong default value for property maxHeight in class ChartElementText'
        );

        $options->maxHeight = .2;
        $this->assertSame(
            .2,
            $options->maxHeight,
            'Setting property value did not work for property maxHeight in class ChartElementText'
        );

        try
        {
            $options->maxHeight = 2;
        }
        catch ( ezcBaseValueException $e )
        {
            return true;
        }

        $this->fail( 'Expected ezcBaseValueException.' );
    }
}
?>
