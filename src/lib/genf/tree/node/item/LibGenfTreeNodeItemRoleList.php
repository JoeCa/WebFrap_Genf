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
 * TreeNode for Fks
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeNodeItemRoleList
  extends LibGenfTreeNodeItem
{

  /**
   * 
   */
  public function getCatridgeClass()
  {
    return 'ItemRoleList';
  }//end public function getCatridgeClass */
  

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameItem( $this->node );
    
    if( isset($this->node->access) )
    {
      $this->access = new LibGenfTreeNodeElementAccess($this->node->access);
    }

  }//end protected function loadChilds */
  
  /**
   * @return LibGenfTreeNodeElementAccess
   */
  public function getAccess()
  {

    return $this->access;

  }//end public function getAccess */
  
  /**
   * @return LibGenfNameItem
   */
  public function getName()
  {

    return $this->name;

  }//end public function getName */
  
  /**
   * 
   */
  public function getAreaKey()
  {
    
    if( isset( $this->area['name'] ) )
      return trim( $this->area['name'] );
    else 
      return $this->name->source;
    
  }//end public function getAreaKey */
  
  /**
   * @return array<LibGenfTreeNodeItemRoleListRole>
   */
  public function getRoles()
  {
    
    if( !isset($this->node->roles) )
      return array();
    
    $roles = array();
      
    foreach( $this->node->roles->role as $role   )
    {
      $roles[] = new LibGenfTreeNodeItemRoleListRole( $role );
    }
    
    return $roles;
    
  }//end public function getRoles */
  
  /**
   * @return [string]
   */
  public function getGlobalRoles()
  {
    
    if( !isset($this->node->roles) )
      return array();
    
    $roles = array();
      
    foreach( $this->node->roles->role as $role   )
    {
      if( isset( $role['relation'] ) && 'global' === trim( $role['relation'] )  )
        $roles[] = trim( $role['name'] );
    }
    
    return $roles;
    
  }//end public function getGlobalRoles */

  /**
   * Per default sind rollen erst mal local
   * @return [string]
   */
  public function getLocalRoles()
  {
    
    if( !isset($this->node->roles) )
      return array();
    
    $roles = array();
      
    foreach( $this->node->roles->role as $role   )
    {
      if( !isset($role['relation']) || ( isset( $role['relation'] ) && 'local' === trim( $role['relation'] ) )  )
        $roles[] = trim( $role['name'] );
    }
    
    return $roles;
    
  }//end public function getLocalRoles */

}//end class LibGenfTreeNodeItemRoleList

