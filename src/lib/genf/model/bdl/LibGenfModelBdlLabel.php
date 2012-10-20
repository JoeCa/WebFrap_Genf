<?php
/*******************************************************************************
*
* @author      : Dominik Bonsch <dominik.bonsch@webfrap.net>
* @date        :
* @copyright   : Webfrap Developer Network <contact@webfrap.net>
* @project     : Webfrap Web Frame Application
* @projectUrl  : http://webfrap.net
*
* @licence     : BSD License see: LICENCE/BSD Licence.txt
* 
* @version: @package_version@  Revision: @package_revision@
*
* Changes:
*
*******************************************************************************/

/**
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfModelBdlLabel
  extends LibGenfModelBdlTexts
{
////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @return string
   */
  public function parse()
  {

    $texts = '';

    if( !$this->value )
    {
      foreach( $this->texts as $text )
      {
        $texts .= $text->parse();
      }
    }
    else
    {
      $texts = $this->value;
    }


    $xml = <<<XMLS
      <label>{$texts}</label>
XMLS;

    return $xml;

  }//end public function parse */

}//end class LibGenfModelBdlLabel

