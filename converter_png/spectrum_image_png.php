<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et co=100 go-=b
*
* Filename: "spectrum_image_png.php"
*
* Project: Spectrum Color Art.
*
* Purpose: This file should be used as an image.
*
* Author: Tom McDonnell 2009-11-08.
*
\**************************************************************************************************/

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/../lib_tom/php/utils/UtilsValidator.php';
require_once dirname(__FILE__) . '/../SpectrumGenerator.php';
require_once dirname(__FILE__) . '/SpectrumRowsImagePng.php';

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   UtilsValidator::checkArray
   (
      $_GET, array
      (
         'includeReflection' => 'numeric',
         'filter'            => 'string' ,
         'dual'              => 'numeric',
         'n_subrows'         => 'numeric',
         'cellWidth'         => 'numeric',
         'cellHeight'        => 'numeric',
         'viewMode'          => 'string'
      )
   );

   $configuration = array
   (
      'filter'            => ($_GET['filter'           ] == '' )? null: $_GET['filter'],
      'includeReflection' => ($_GET['includeReflection'] == '1')                       ,
      'dual'              => ($_GET['dual'             ] == '1')                       ,
      'n_subrows'         => (int)$_GET['n_subrows']
   );

   $white = array('r' => 1, 'g' => 1, 'b' => 1);
   $black = array('r' => 0, 'g' => 0, 'b' => 0);

   $backgroundColor =
   (
      ($configuration['dual'] == '0')?
      (
         ($configuration['includeReflection'] == '0')? $white: $black
      ):
      (
         ($configuration['includeReflection'] == '0')? $black: $white
      )
   );

   $spectrumGeneratorPng = new SpectrumRowsImagePng
   (
      SpectrumGenerator::getSpectrumRows($configuration),
      (int)$_GET['cellWidth' ]                          ,
      (int)$_GET['cellHeight']                          ,
      $_GET['viewMode']                                 ,
      $backgroundColor
   );
}
catch (Exception $e)
{
   echo $e->getMessage();
}

/*******************************************END*OF*FILE********************************************/
?>
