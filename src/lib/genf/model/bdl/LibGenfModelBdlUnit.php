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
class LibGenfModelBdlUnit
  extends LibGenfModelBdl
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  // attr attributes
  protected $attrType   = null;
  protected $attrValue  = null;


////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function parse()
  {

    // parse attributes
    $type   = '';
    if($this->attrType)
      $type = ' type="'.$this->attrType.'" ';

    $value    = '';
    if($this->attrValue)
      $value  = ' value="'.$this->attrValue.'" ';

    $xml = <<<XMLS
      <unit {$type} {$value} ></unit>
XMLS;

    return $xml;

  }//end public function parse */


}//end class LibGenfModelBdlUnit

