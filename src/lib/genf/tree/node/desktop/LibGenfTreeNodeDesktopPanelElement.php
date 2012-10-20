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
class LibGenfTreeNodeDesktopPanelElement
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
      ? SParserString::subToCamelCase(trim($this->node['type']))
      : null;

  }//end public function getType */



}//end class LibGenfTreeNodeDesktopPanelElement

