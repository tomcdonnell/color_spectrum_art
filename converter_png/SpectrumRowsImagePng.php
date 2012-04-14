<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "SpectrumRowsImagePng.php"
*
* Project: Art - Spectrum Generator.
*
* Purpose: Convert an array returned by the SpectrumGenerator to a PNG image for display purposes.
*
* Author: Tom McDonnell 2010-02-13.
*
\**************************************************************************************************/

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/../../library/tom/php/utils/Utils_validator.php';
require_once dirname(__FILE__) . '/../mapper_circle/SpectrumRowsToCircleMapper.php';

// Class definition. ///////////////////////////////////////////////////////////////////////////////

/*
 *
 */
class SpectrumRowsImagePng
{
   // Public functions. /////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   public function __construct($spectrumRows, $cellWidth, $cellHeight, $viewMode, $backgroundColor)
   {
      if (count($spectrumRows) == 0)
      {
         throw new Exception('Spectrum rows array empty.');
      }

      switch ($viewMode)
      {
       case 'circ':
         $spectrumRows = SpectrumRowsToCircleMapper::map($spectrumRows, 125, $backgroundColor);
         $cellWidth    = 1;
         $cellHeight   = 1;
         break;
       case 'rect':
         // Do nothing.
         break;
       default:
         throw new Exception("Unknown viewMode '$viewMode'.");
      }

      $n_rows = count($spectrumRows   );
      $n_cols = count($spectrumRows[0]);

      $imageMinX = 0;
      $imageMinY = 0;
      $imageMaxX = $n_cols * $cellWidth;
      $imageMaxY = $n_rows * $cellHeight;

      header('Content-type: image/png');
      $image = imagecreatetruecolor($imageMaxX, $imageMaxY);

      foreach ($spectrumRows as $rowNo => $row)
      {
         foreach ($row as $colNo => $colorValues)
         {
            $color = self::getColor
            (
               $image                        ,
               (int)($colorValues['r'] * 255),
               (int)($colorValues['g'] * 255),
               (int)($colorValues['b'] * 255)
            );

            $minX = $imageMinX + $colNo * $cellWidth;
            $minY = $imageMinY + $rowNo * $cellHeight;
            $maxX = $minX + $cellWidth;
            $maxY = $minY + $cellHeight;

            imagefilledrectangle($image, $minX, $minY, $maxX, $maxY, $color);
         }
      }

      imagepng($image);
      imagedestroy($image);
   }

   // Private functions. ////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   private static function getColor($image, $r, $g, $b)
   {
      static $colorsByIndex = array();

      $index = 1000000 * $r + 1000 * $g + $b;

      if (!array_key_exists($index, $colorsByIndex))
      {
         $colorsByIndex[$index] = imagecolorallocate($image, $r, $g, $b);
      }

      return $colorsByIndex[$index];
   }
}

/*******************************************END*OF*FILE********************************************/
?>
