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
class LibGenfModelBdlPosition
  extends LibGenfModelBdl
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  // attr attributes
  protected $attrPriority   = null;
  protected $attrValign     = null;
  protected $attrAlign      = null;

  protected $attrRelation   = null;
  protected $attrTarget   = null;

////////////////////////////////////////////////////////////////////////////////
// method
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param SimpleXmlElement $node
   */
  public function import( $node )
  {

    // import attributes
    if( isset($node['priority']) )
      $this->attrPriority = trim($node['priority']);

    if( isset($node['valign']) )
      $this->attrValign   = trim($node['valign']);

    if( isset($node['align']) )
      $this->attrAlign    = trim($node['align']);

    if( isset($node['relation']) )
      $this->attrRelation    = trim($node['relation']);

    if( isset($node['target']) )
      $this->attrTarget    = trim($node['target']);

  }//end public function import */


  /**
   * @return string
   */
  public function parse()
  {

    $attr = '';

    if($this->attrPriority)
      $attr .= ' priority="'.$this->attrPriority.'" ';

    if($this->attrValign)
      $attr .= ' valign="'.$this->attrValign.'" ';

    if($this->attrAlign)
      $attr .= ' align="'.$this->attrAlign.'" ';

    if($this->attrRelation)
      $attr .= ' relation="'.$this->attrRelation.'" ';

    if($this->attrTarget)
      $attr .= ' target="'.$this->attrTarget.'" ';


    $xml = <<<XMLS
      <position {$attr} ></position>
XMLS;

    return $xml;

  }//end public function parse */

}//end class LibGenfModelBdlPosition

