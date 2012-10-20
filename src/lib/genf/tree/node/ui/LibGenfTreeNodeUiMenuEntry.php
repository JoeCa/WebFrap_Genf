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
class LibGenfTreeNodeUiMenuEntry
  extends LibGenfTreeNode
{

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameNode( $this->node );

  }//end protected function loadChilds */

  /**
   *
   */
  public function __toString()
  {
    return 'UiMenuEntry: '.$this->name;
  }//end public function __toString */

  /**
   *
   * @return string
   */
  public function getType()
  {

    if(!isset($this->node['type']))
      return 'append';

    return trim($this->node['type']);
  }//end public function getType */

  /**
   *
   * @return string
   */
  public function getContext()
  {

    if(!isset($this->node['context']))
      return null;

    return trim($this->node['context']);

  }//end public function getContext */

  /**
   *
   * @return string
   */
  public function getPosition()
  {

    if(!isset($this->node['position']))
      return 'default';

    return trim($this->node['position']);
  }//end public function getPosition */

  /**
   * @return LibGenfTreeNodeAccess
   */
  public function getAccess()
  {

    if(!isset($this->node->access) )
      return null;

    $classname   = $this->builder->getNodeClass('Access');

    return new $classname( $this->node->access );

  }//end public function getAccess */

  /**
   * get the nodes in the entry
   * @return array
   */
  public function getNodes()
  {

    if( !isset( $this->node->body ) )
      return array();

    if(!$children = $this->node->body->children())
      return array();

    $entries = array();

    $classEntry   = $this->builder->getNodeClass('TreeSubtreeNode');
    $classSubmenu = $this->builder->getNodeClass('TreeSubtree');

    foreach( $children as $keyName => $entry )
    {

      switch ( $keyName )
      {
        case 'node':
        {
          $entries[] = new $classEntry($entry);
          break;
        }
        case 'subtree':
        {
          $entries[] = new $classSubmenu($entry);
          break;
        }
        default:
        {
          Debug::console('got nonexisting menuentry type: '. $keyName);
        }
      }

    }

    return $entries;

  }//end public function getNodes */


}//end class LibGenfTreeNodeUiMenu

