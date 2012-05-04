<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go -=b
*
* Filename: "index.php"
*
* Project: Programming Tools - Spectrum Generator.
*
* Purpose: The main file for the project.
*
* Author: Tom McDonnell 2009-10-23.
*
\**************************************************************************************************/

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/SpectrumGenerator.php';
require_once dirname(__FILE__) . '/configurations_rectangular.php';
require_once dirname(__FILE__) . '/converter_html/SpectrumRowsHtmlConverter.php';
require_once dirname(__FILE__) . '/mapper_circle/SpectrumRowsToCircleMapper.php';

// Settings. ///////////////////////////////////////////////////////////////////////////////////////

error_reporting(-1);

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   $requestedId     = (array_key_exists('id', $_GET))? $_GET['id']: '0';
   $nConfigurations = count($configurations);
   $index           = ($requestedId == 'random')? rand(0, $nConfigurations - 1): $requestedId;

   if (!array_key_exists($index, $configurations)) {throw new Exception('Index out of range.');}

   $phpSelf     = $_SERVER['PHP_SELF'];
   $prevHrefStr = ($index ==                    0)? '': "href='$phpSelf?id=" . ($index - 1) . "'";
   $nextHrefStr = ($index == $nConfigurations - 1)? '': "href='$phpSelf?id=" . ($index + 1) . "'";
   $randHrefStr =                                        "href='$phpSelf?id=random'";

   $cellDimensions  = $cellDimensionsByConfigurationIndex[$index];
   $getString       = createConfigurationGetString($configurations[$index], $cellDimensions);
   $imageUrlRect    = "converter_png/spectrum_image_png.php?$getString&viewMode=rect";
   $imageUrlCirc    = "converter_png/spectrum_image_png.php?$getString&viewMode=circ";
   $imageAltMessage = 'Generating image...';

   $filesJs  = array();
   $filesCss = array('style.css');
}
catch (Exception $e)
{
   echo $e->getMessage();
   exit(0);
}

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function createConfigurationGetString($configurationValuesByKey, $cellDimensions)
{
   $getSubstrings = array();

   foreach ($configurationValuesByKey as $key => $value)
   {
      if (is_bool($value))
      {
         $value = ($value)? '1': '0';
      }

      $getSubstrings[] = "$key=$value";
   }

   $getSubstrings[] = "cellWidth={$cellDimensions['width']}";
   $getSubstrings[] = "cellHeight={$cellDimensions['height']}";

   return implode('&', $getSubstrings);
}

// HTML code. //////////////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html>
<html>
 <head>
<?php
 $unixTime = time();
 foreach ($filesJs  as $file) {echo "  <script src='$file?$unixTime'></script>\n"        ;}
 foreach ($filesCss as $file) {echo "  <link rel='stylesheet' href='$file?$unixTime'/>\n";}
?>
  <title>tomcdonnell.net - Color Spectrum Art</title>
 </head>
 <body>
  <a class='backLink' href='../../../index.php'>Back to tomcdonnell.net</a>
  <h1>Color Spectrum Art</h1>
  <a class='navLink' <?php echo $prevHrefStr; ?>>Prev</a>
  <a class='navLink' <?php echo $randHrefStr; ?>>Random</a>
  <a class='navLink' <?php echo $nextHrefStr; ?>>Next</a>
<?php
/*
   $cellDimensions = $cellDimensionsByConfigurationIndex[$index];
   $spectrumRows   = SpectrumGenerator::getSpectrumRows($configurations[$index]);
   $black          = array('r' => 0, 'g' => 0, 'b' => 0);

   echo SpectrumRowsHtmlConverter::toHtml
   (
      $spectrumRows,
//      SpectrumRowsToCircleMapper::map($spectrumRows, 200, $black),
      $cellDimensions['width'], $cellDimensions['height']
   );
*/
?>
  <div><img src='<?php echo $imageUrlRect; ?>' alt='<?php echo $imageAltMessage; ?>'/></div>
  <div><img src='<?php echo $imageUrlCirc; ?>' alt='<?php echo $imageAltMessage; ?>'/></div>
<?php
?>
 </body>
</html>
<?php
/*******************************************END*OF*FILE********************************************/
?>
