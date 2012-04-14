<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "SpectrumRowsToCircleMapper.php"
*
* Project: Art - Spectrum Generator.
*
* Purpose: Convert an array returned by the SpectrumGenerator to an array of equal dimensions
*          whose contents has been modified such that the rectangular image has been mapped to a
*          circle.  The top row of pixels map to the center of the circle, and the bottom row of
*          pixels map to the outer edge of the circle.
*
* Author: Tom McDonnell 2010-02-13.
*
\**************************************************************************************************/

// Class definition. ///////////////////////////////////////////////////////////////////////////////

/*
 *
 */
class SpectrumRowsToCircleMapper
{
   // Public functions. /////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   public function __construct()
   {
      throw new Exception('This class is not intended to be instantiated.');
   }

   /*
    *
    */
   public static function map($spectrumRows, $radius, $backgroundColor)
   {
      if (count($spectrumRows) == 0)
      {
         throw new Exception('Spectrum rows array empty.');
      }

      $mappedSpectrumRows = self::getBlankSpectrumRowsArray($radius, $backgroundColor);

      for ($r = 0; $r < $radius * 2; ++$r)
      {
         for ($c = 0; $c < $radius * 2; ++$c)
         {
            $mappedSpectrumRows[$r][$c] = self::getColorForMappedGridPixel
            (
               $r, $c, $radius, $spectrumRows, $backgroundColor
            );
         }
      }

      return $mappedSpectrumRows;
   }

   // Private functions. ////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   private static function getBlankSpectrumRowsArray($radius, $color)
   {
      $rows = array();

      for ($r = 0; $r < $radius * 2; ++$r)
      {
         $rows[$r] = array();

         for ($c = 0; $c < $radius * 2; ++$c)
         {
            $rows[$r][$c] = $color;
         }
      }

      return $rows;
   }

   /*
    *
    */
   private static function getColorForMappedGridPixel
   (
      $r, $c, $maxRadius, $spectrumRows, $backgroundColor
   )
   {
      $n_rows = count($spectrumRows   );
      $n_cols = count($spectrumRows[0]);

      $rOriginCenter = $r - $maxRadius;
      $cOriginCenter = $c - $maxRadius;

      $radius = sqrt($cOriginCenter * $cOriginCenter + $rOriginCenter * $rOriginCenter);

      if ($radius >= $maxRadius)
      {
         return $backgroundColor;
      }

      $angle = self::PI + atan2($rOriginCenter, $cOriginCenter);

      // Rotate 30 degrees so that images have symmetry about a vertical axis.
      $angle += self::PI / 6;

      if ($angle >= 2 * self::PI)
      {
         $angle -= 2 * self::PI;
      }

      $rowNo = floor(($radius /  $maxRadius   ) * $n_rows);
      $colNo = floor(($angle  / (2 * self::PI)) * $n_cols);

      return $spectrumRows[$rowNo][$colNo];
   }

   // Class constants. /////////////////////////////////////////////////////////////////////////

   const PI = 3.14159265358979323846;
}

/*******************************************END*OF*FILE********************************************/
?>
