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
class LibGenfTreeNodeDesktopContainer
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// load methodes
//////////////////////////////////////////////////////////////////////////////*/

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
  public function getWidgets()
  {

    $widgets = array();

    if(!isset($this->node->widget))
      return $widgets;

    foreach ( $this->node->widget as $widget )
    {
      $widgets[] = new LibGenfTreeNodeDesktopWidget( $widget );
    }

    return $widgets;

  }//end public function getWidgets */

  /**
   *
   * Enter description here ...
   * @return string
   */
  public function getSize()
  {
    return isset($this->node['size'])
      ?trim($this->node['size'])
      :null;
  }//end public function getSize */

  /**
   *
   * Enter description here ...
   * @return string
   */
  public function getType()
  {
    return isset($this->node['type'])
      ?trim($this->node['type'])
      :null;
  }//end public function getType */


  /**
   *
   * Enter description here ...
   * @return string
   */
  public function getId()
  {
    return isset($this->node['id'])
      ?trim($this->node['id'])
      :null;
  }//end public function getType */

}//end class LibGenfTreeNodeDesktopContainer

