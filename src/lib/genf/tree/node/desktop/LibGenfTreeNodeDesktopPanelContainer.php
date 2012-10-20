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
class LibGenfTreeNodeDesktopPanelContainer
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
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
   * @return string
   */
  public function getType()
  {

    return isset($this->node['type'])
      ? ucfirst($this->node['type'])
      : null;

  }//end public function getType */


  /**
   * @return array
   */
  public function getElements()
  {

    $elements = array();

    if(!isset($this->node->element))
    {
      Debug::console('found no cells in the navigation');
      return $elements;
    }

    $classname  = $this->builder->getNodeClass('DesktopPanelElement');

    foreach( $this->node->element as $element )
    {
      $elements[] = new $classname( $element );
    }

    return $elements;

  }//end public function getElements */



}//end class LibGenfTreeNodeDesktopPanelContainer

