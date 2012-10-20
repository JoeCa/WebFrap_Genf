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
class LibGenfTreeNodeDesktopWorkarea
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
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameDefault( $this->node );

  }//end protected function loadChilds */

  /**
   * @return array
   */
  public function getAreas()
  {

    $areas = array();

    if(!isset($this->node->area))
    {
      Debug::console('found no areas in the workarea');
      return $areas;
    }

    $classname  = $this->builder->getNodeClass('DesktopArea');

    foreach( $this->node->area as $area )
    {
      $areas[] = new $classname( $area );
    }

    return $areas;

  }//end public function getAreas */

}//end class LibGenfTreeNodeDesktopWorkarea

