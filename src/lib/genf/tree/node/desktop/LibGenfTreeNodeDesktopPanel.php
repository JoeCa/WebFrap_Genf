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
class LibGenfTreeNodeDesktopPanel
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
   * @return array
   */
  public function getContainers()
  {

    $containers = array();

    if(!isset($this->node->body->container))
    {
      Debug::console('found no cells in the navigation');
      return $containers;
    }

    $classname  = $this->builder->getNodeClass('DesktopPanelContainer');

    foreach( $this->node->body->container as $container )
    {
      $containers[] = new $classname( $container );
    }

    return $containers;

  }//end public function getContainers */



}//end class LibGenfTreeNodeDesktopPanel

