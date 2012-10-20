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
 * TreeNode for Fks
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeNodeEvent
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  
  /**
   * @return LibGenfTreeNodeEventAction
   */
  public function getAction()
  {

    if( !isset($this->node->action) )
    {
      return null;
    }

    $className  = $this->builder->getNodeClass( 'EventAction' );

    return new $className( $this->node->action );

  }//end public function getAction */

  /**
   * @return array<LibGenfTreeNodeEventAction>
   */
  public function getActions()
  {

    if(!isset($this->node->action))
    {
      return null;
    }

    $className  = $this->builder->getNodeClass('EventAction');
    $actions    = array();

    foreach( $this->node->action as $action )
    {
      $actions[] = new $className($action);
    }

    return $actions;

  }//end public function getActions */

  
  /**
   * Die Möglichkeit Code direkt in actions zu packen
   * 
   * @return string
   */
  public function getCode( $architecture = null )
  {
    
    if( isset( $this->node->code ) )
    {
      return trim( $this->node->code );
    }
    else 
    {
      null;
    }
    
  }//end public function getCode */
  
  
  /**
   * (non-PHPdoc)
   * @see LibGenfTreeNode::debugData()
   */
  public function debugData()
  {
    
    $debugData = 'Event: ';
    
    if( isset($this->node['name']) )
    {
      $debugData .= trim($this->node['name']).' ';
    }
    
    $debugData .= "Class: ".trim($this->node['class'])." Method: ".trim($this->node['method'])." On: ".trim($this->node['on']);
    
    return $debugData;
    
  }//end public function debugData */

}//end class LibGenfTreeNodeEvent

