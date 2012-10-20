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
class LibGenfTreeNodeConcept
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var boolean
   */
  protected $global = false;
  
  /**
   *
   * @var LibGenfTreeNodeManagement
   */
  public $management = null;

////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param SimpleXmlElement $node
   * @param LibGenfName $name
   * @param $params
   */
  public function __construct( $node , $name = null, $params = array(), $global = false )
  {

    $this->builder  = LibGenfBuild::getInstance();

    if( $this->rootType )
      $this->root   = $this->builder->getRoot($this->rootType);

    $this->global   = $global;
    $this->name     = $name;

    $this->validate( $node );
    $this->prepareNode( $params );
    $this->loadChilds( );

  }//end public function __construct */

  /**
   *
   * @return string the name of the entity
   */
  public function interpreted()
  {
    return isset($this->node['interpreted']);
  }//end public function interpreted */
  
  /**
   * @param string $message
   */
  public function reportError( $message )
  {
    
    $this->builder->error
    ( 
      "Invalid Concept ".get_class($this)." ".$message." ".$this->builder->dumpEnv() 
    );
    
  }//end public function reportError */


}//end class LibGenfTreeNodeConcept

