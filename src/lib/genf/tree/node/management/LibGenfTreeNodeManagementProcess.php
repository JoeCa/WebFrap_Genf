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
class LibGenfTreeNodeManagementProcess
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
//
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   */
  protected function prepareNode( $params = array() )
  {

    $this->name = new LibGenfNameManagementProcess( $this->node );

  }//end protected function prepareNode */

  /**
   * Der Prozess könnte auch auf eine Embedede Reference verweisen
   * @return string
   */
  public function getReference()
  {
    
    if( isset( $this->node['reference'] ) )
      return trim( $this->node['reference'] );
    else 
      return null;
    
  }//end public function getReference */
  
}//end class LibGenfTreeNodeManagementProcess

