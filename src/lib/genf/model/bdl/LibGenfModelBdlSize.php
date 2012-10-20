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
class LibGenfModelBdlSize
  extends LibGenfModelBdl
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  // attr attributes
  protected $attrWidth  = null;
  protected $attrHeight = null;

////////////////////////////////////////////////////////////////////////////////
// method
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param SimpleXmlElement $node
   */
  public function import( $node )
  {

    // import attributes
    if( isset($node['width']) )
      $this->attrWidth = trim($node['width']);

    if( isset($node['height']) )
      $this->attrHeight = trim($node['height']);

  }//end public function import */


  /**
   * @return string
   */
  public function parse()
  {

    $width = '';
    if($this->attrWidth)
      $width = ' width="'.$this->attrWidth.'" ';

    $height = '';
    if($this->attrHeight)
      $height = ' height="'.$this->attrHeight.'" ';

    $xml = <<<XMLS
      <size {$width} {$height} ></size>
XMLS;

    return $xml;

  }//end public function parse */

}//end class LibGenfModelBdlSize

