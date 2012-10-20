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
class LibGenfTreeNodeUiButtonAction
  extends LibGenfTreeNodeUiAction
{

  /**
   * @param SimpleXmlElement $node
   * @param string $action
   */
  public function __construct( $node )
  {
    parent::__construct( $node );
    $this->name = new LibGenfNameNode( $this->node );

  }//end public function __construct */
  
  /**
   * Visibility node getter
   * @return LibGenfTreeNodeUiVisibility oder null im fehlerfall
   */
  public function getVisibility()
  {

    if( !isset( $this->node->visibility ) )
      return null;

    $className = $this->builder->getNodeClass( 'UiVisibility' );

    return new $className( $this->node->visibility );

  }//end public function getVisibility */
  
  /**
   * @return LibGenfTreeNodeActionAccess
   */
  public function getAccess()
  {

    if( !isset( $this->node->access ) )
      return null;

    $classname   = $this->builder->getNodeClass( 'ActionAccess' );

    return new $classname( $this->node->access );

  }//end public function getAccess */
  
  /**
   * @return string
   */
  public function getType()
  {

    if(!isset($this->node['type']))
      return 'request';

    return trim($this->node['type']);
    
  }//end public function getType */

}//end class LibGenfTreeNodeUiButtonAction

