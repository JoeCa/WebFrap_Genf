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
class LibGenfTreeNodeManagementDataProfile
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// Attribute
////////////////////////////////////////////////////////////////////////////////

  public $attrName = null;
  
////////////////////////////////////////////////////////////////////////////////
//
////////////////////////////////////////////////////////////////////////////////

  /**
   * Prüfen ob dieser Profilknoten auf den übergebenen Namen verweist
   * 
   * @param string $name
   * @return boolean
   */
  public function checkTarget( $name )
  {
    
    if( trim($this->node['entity']) == $name )
      return true;
    else
      return false;
    
  }//end public function checkTarget */
  
  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {
    
    $this->attrName = trim( $this->node['field'] );
    
  }//end protected function loadChilds */
  
  /**
   *
   * @return void
   */
  protected function getTarget( )
  {
    
    return trim($this->node['entity']);
    
  }//end protected function loadChilds */


}//end class LibGenfTreeNodeManagementDataProfile

