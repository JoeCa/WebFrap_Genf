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
class BdlSubNode
  extends BdlBaseNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @var DOMElement
   */
  public $parent = null;

////////////////////////////////////////////////////////////////////////////////
// Construct
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param BdlFile $file
   * @param DOMElement $node
   * @param DOMElement $parent
   */
  public function __construct( $file, $node, $parent )
  {
    
    $this->file    = $file;
    $this->dom     = $node;
    $this->parent  = $parent;
    
  }//end public function __construct */
  

}//end class BdlSubNode
