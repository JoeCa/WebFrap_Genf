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
class LibGenfTreeNodeRole
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var LibGenfTreeNodePermission
   */
  public $permission = null;
  
/*//////////////////////////////////////////////////////////////////////////////
// load methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameRole( $this->node );

  }//end protected function loadChilds */


  /**
   * @return string
   */
  public function level()
  {

    return isset( $this->node['level'] )
      ? strtoupper( trim( $this->node['level'] ) )
      : 'GUEST';

  }//end public function level */
  
  /**
   * @return LibGenfTreeNodePermission
   */
  public function getPermission()
  {
    
    if( $this->permission )
      return $this->permission;
      
    if( !isset( $this->node->permission ) )
      return null;
      
    $this->permission = new LibGenfTreeNodePermission( $this->node->permission );
    
    return $this->permission;
    
  }//end public function getPermission */

  /**
   * Prüfen ob diese Rolle eine System Rolle ist, 
   * also für den Betrieb des Systems essentiell benötigt wird und nicht
   * gelöscht werden kann
   * 
   * Das Gegenteil davon sind vom Benutzer angelegte Standard Rollen welche dynamisch
   * konfiguriert werden können, und auch jederzeit wieder gelöscht werden können
   * 
   * @return boolean
   */
  public function isSystemRole()
  {
    return isset( $this->node->system );
  }//end public function isSystemRole */

}//end class LibGenfTreeNodeRole

