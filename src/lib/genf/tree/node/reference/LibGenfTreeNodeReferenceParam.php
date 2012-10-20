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
class LibGenfTreeNodeReferenceParam
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * 
   * @var string
   */
  public $type = null;
  
  /**
   * 
   * @var LibGenfTreeNodeReference
   */
  public $reference = null;
  
/*//////////////////////////////////////////////////////////////////////////////
// magic
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   *
   * @param SimpleXmlElement $node
   * @param LibGenfTreeNodeReference $reference
   */
  public function __construct( $node, $reference )
  {

    $this->builder    = LibGenfBuild::getInstance();
    
    $this->reference  = $reference; 

    $this->validate( $node );
    $this->loadChilds( );

  }//end public function __construct */
  
/*//////////////////////////////////////////////////////////////////////////////
// methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->type = trim($this->node['type']);

  }//end protected function loadChilds */
  
 
  /**
   * @return string
   */
  public function getAttrName()
  {
    
    return trim($this->node['attribute']);
    
  }//end public function getAttrName */
  

}//end class LibGenfTreeNodeAccess

