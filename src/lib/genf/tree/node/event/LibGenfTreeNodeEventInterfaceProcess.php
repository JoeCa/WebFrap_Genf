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
 * 
 * Event Interface bei dem ein bestimmter Prozess mit übergeben wird
 *
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeNodeEventInterfaceProcess
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var string
   */
  public $processKey = null;
  
  
////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return void
   */
  protected function loadChilds( )
  {

    $this->processKey = SParserString::subToName(  $this->node['key'] );

  }//end protected function loadChilds */

}//end class LibGenfTreeNodeEventInterfaceProcess

