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
class LibGenfModelBdlProcess
  extends LibGenfModelBdl
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////


  // attr attributes

  /**
   *
   * @var string
   */
  protected $attrName   = null;

////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param SimpleXmlElement $node
   */
  public function import( $node )
  {

    // import attributes
    if( isset($node['name']) )
      $this->attrName = trim($node['name']);

  }//end public function import */


  /**
   * @return string
   */
  public function parse()
  {

    $attr = '';
    if($this->attrName)
      $attr .= ' name="'.$this->attrName.'" ';

    $xml = <<<XMLS
      <process {$attr} />
XMLS;

    return $xml;

  }//end public function parse */


}//end class LibGenfModelBdlProcess

