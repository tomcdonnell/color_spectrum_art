<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "SpectrumRowsHtmlConverter.php"
*
* Project: Art - Spectrum Generator.
*
* Purpose: Convert an array returned by the SpectrumGenerator to HTML text for display purposes.
*
* Author: Tom McDonnell 2010-01-16.
*
\**************************************************************************************************/

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/../../library/tom/php/utils/Utils_validator.php';

// Class definition. ///////////////////////////////////////////////////////////////////////////////

/*
 *
 */
class SpectrumRowsHtmlConverter
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
   public static function toHtml($spectrumRows, $cellWidth, $cellHeight)
   {
      $html = '<table>';

      foreach ($spectrumRows as $row)
      {
         $html .= '<tr>';

         foreach ($row as $colorValues)
         {
            $r = (int)($colorValues['r'] * 256);
            $g = (int)($colorValues['g'] * 256);
            $b = (int)($colorValues['b'] * 256);

            $html .=
            (
               "<td style='width: {$cellWidth}px; height: {$cellHeight}px;" .
               " background: rgb($r,$g,$b);'></td>"
            );
         }

         $html .= '</tr>';
      }

      $html .= '</table>';

      return $html;
   }
}

/*******************************************END*OF*FILE********************************************/
?>
