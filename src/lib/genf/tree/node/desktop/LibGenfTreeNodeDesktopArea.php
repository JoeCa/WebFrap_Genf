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
 * @todo umstellen von der containerlösung auf eine html / layout lösung
 */
class LibGenfTreeNodeDesktopArea
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

    if(!isset($this->node->containers))
    {
      Debug::console('found no containers in the workarea');
      return $containers;
    }

    $classname  = $this->builder->getNodeClass('DesktopContainer');

    foreach( $this->node->containers->container as $container )
    {
      $containers[] = new $classname( $container );
    }

    return $containers;

  }//end public function getContainers */

}//end class LibGenfTreeNodeDesktopArea

