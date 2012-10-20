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
class LibGenfTreeNodeDesktopNavigation
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * Enter description here ...
   * @var LibGenfTreeNodeNavigationTree
   */
  protected $tree = null;

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameDefault( $this->node );


  }//end protected function loadChilds */

  /**
   * @return array
   */
  public function getCells()
  {

    $cells = array();

    if(!isset($this->node->body->cell))
    {
      Debug::console('found no cells in the navigation');
      return $cells;
    }

    $classname  = $this->builder->getNodeClass('DesktopNavigationCell');

    foreach( $this->node->body->cell as $cell )
    {
      $cells[] = new $classname( $cell );
    }

    return $cells;

  }//end public function getCells */


  /**
   * @return LibGenfTreeNodeNavigationTree
   */
  public function getTree()
  {

    if(!isset($this->node->tree))
    {
      Debug::console('found no tree in navigation '.$this->name->name );
      return null;
    }

    if( $this->tree )
      return $this->tree;

    $classname  = $this->builder->getNodeClass('Tree');
    $this->tree = new $classname( $this->node->tree );

    return $this->tree;

  }//end public function getTree */

}//end class LibGenfTreeNodeDesktopNavigation

