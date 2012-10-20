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
class LibGenfTreeNodeAccess
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @var LibGenfTreeNodeAccess
   */
  public $entityAccess  = null;
  
  /**
   * @var LibGenfTreeNodeEntity
   */
  public $entity        = null;
  
  /**
   * @var string
   */
  public $userLevel     = null;

  /**
   * @var string
   */
  public $accessLevel   = null;
  
  /**
   * @var string
   */
  public $maxLevel   = null;
  
  /**
   * @var string
   */
  public $accessPath   = null;
  
/*//////////////////////////////////////////////////////////////////////////////
// Construktor
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @param SimpleXmlElement $node
   * @param LibGenfTreeNodeEntity $entity
   */
  public function __construct( $node, $entity = null )
  {

    $this->builder  = LibGenfBuild::getInstance();
    
    if( $entity )
    {
      $this->entity       = $entity;
      $this->entityAccess = $entity->getAccess();
    }    

    $this->validate( $node );
    $this->loadChilds( );

  }//end public function __construct */
  
/*//////////////////////////////////////////////////////////////////////////////
// methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return void
   */
  protected function loadChilds( )
  {

    if( isset( $this->node['user_level'] ) )
      $this->userLevel = trim($this->node['user_level']);

    if( isset( $this->node['access_level'] ) )
      $this->accessLevel = trim( $this->node['access_level'] );
      
    if( isset( $this->node['max_level'] ) )
      $this->maxLevel = trim( $this->node['max_level'] );
      
    if( isset( $this->node['access_path'] ) )
      $this->accessPath = trim( $this->node['access_path'] );

  }//end protected function loadChilds */
  
  /**
   * @return string
   */
  public function getLevel()
  {
    return $this->accessLevel;
  }//end public function getLevel */
  
  /**
   * @return string
   */
  public function getMaxLevel()
  {
    return $this->maxLevel;
  }//end public function getMaxLevel */
  
  /**
   * @return string
   */
  public function getMinLevel()
  {
    return $this->accessLevel;
  }//end public function getMinLevel */
  
  /**
   * @return string
   */
  public function getUserLevel()
  {
    return $this->userLevel;
  }//end public function getUserLevel */


  /**
   * @return string
   */
  public function getActionRoles( $action )
  {

    $roles = array();

    if(!$tmp = $this->node->xpath( './roles/role['.$action.']' ))
    {
      if( $this->entityAccess )
        return $this->entityAccess->getActionRoles( $action );
      else 
        return $roles;
    }

    foreach( $tmp as $role )
    {
      $roles[trim($role['name'])] = $role;
    }

    return $roles;

  }//end public function getActionRoles */

  /**
   *
   * @return [LibGenfTreeNodeAccessRole]
   */
  public function getAccessRoles(  )
  {
    
    if( !isset( $this->node->roles->role ) )
    {
      if( $this->entityAccess )
        return $this->entityAccess->getAccessRoles();
      else 
        return array();
    }

    $className = $this->builder->getNodeClass( 'AccessRole' );

    $roles = array();

    if( isset( $this->node->roles->role ) )
    {
      foreach( $this->node->roles->role as $role )
      {
        $roles[] = new $className( $role );
      }
    }

    return $roles;

  }//end public function getAccessRoles */

  /**
   * 
   * @return array<LibGenfTreeNodeAccessProfile>
   */
  public function getProfiles(  )
  {
    
    if( !isset($this->node->profiles->profile) )
    {
      if( $this->entityAccess )
        return $this->entityAccess->getProfiles();
      else 
        return array();
    }

    $className  = $this->builder->getNodeClass( 'AccessProfile' );
    $profiles   = array();

    if( isset( $this->node->profiles->profile ) )
    {

      foreach( $this->node->profiles->profile as $profile )
      {
        $profiles[] = new $className( $profile );
      }

    }

    return $profiles;

  }//end public function getProfiles */
  
  /**
   * @return [LibGenfTreeNodeAccessCheck]
   */
  public function getChecks( )
  {

    $className  = $this->builder->getNodeClass( 'AccessCheck' );
    $checks     = array();

    if( isset( $this->node->checks->check ) )
    {
      foreach( $this->node->checks->check as $check )
      {
        $checks[] = new $className( $check );
      }
    }

    return $checks;

  }//end public function getChecks */
  
  /**
   * @return LibGenfTreeNodeAccessPathRoot
   */
  public function getPaths( )
  {
    
    if( !isset( $this->node->paths ) )
      return null;

    $className  = $this->builder->getNodeClass( 'AccessPathRoot' );

    return new $className( $this->node->paths );

  }//end public function getChecks */
  
  /**
   * @return string
   */
  public function getInherit()
  {
    return isset( $this->node['inherit'] )
      ? trim($this->node['inherit'])
      : null;
  }//end public function getInherit */
  
  /**
   * @return string
   */
  public function getExtend()
  {
    return isset( $this->node['extend'] )
      ? trim($this->node['extend'])
      : null;
  }//end public function getExtend */
  
  /**
   * Type der Berechtigungen
   * 
   * - full
   * - core_data
   * 
   * @return string
   */
  public function getType()
  {
    
    return isset( $this->node['type'] )
      ? trim( $this->node['type'] )
      : 'full';
      
  }//end public function getType */
  
////////////////////////////////////////////////////////////////////////////////
// 
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @return array<string>
   */
  public function getRoles(  )
  {
    
    if( !isset( $this->node->roles->role ) )
    {
      if( $this->entityAccess )
        return $this->entityAccess->getRoles();
      else 
        return array();
    }

    //$className = $this->builder->getNodeClass( 'AccessRole' );

    $roles = array();

    if( isset( $this->node->roles->role ) )
    {
      foreach( $this->node->roles->role as $role )
      {
        //$roles[] = new $className( $role );
        $roles[] = trim( $role['name'] );
      }
    }

    return $roles;

  }//end public function getRoles */

  /**
   *
   * @return array<string>
   */
  public function getRolesSomewhere(  )
  {
    
    if( !isset( $this->node->roles_somewhere->role ) )
    {
      if( $this->entityAccess )
        return $this->entityAccess->getRolesSomewhere();
      else 
        return array();
    }

    //$className = $this->builder->getNodeClass( 'AccessRole' );

    $roles = array();

    if( isset( $this->node->roles_somewhere->role ) )
    {
      foreach( $this->node->roles_somewhere->role as $role )
      {
        //$roles[] = new $className( $role );
        $roles[] = trim( $role['name'] );
      }
    }

    return $roles;

  }//end public function getRolesSomewhere */
  
  /**
   *
   * @return array<string>
   */
  public function getRolesExplicit(  )
  {
    
    if( !isset( $this->node->roles_explicit->role ) )
    {
      if( $this->entityAccess )
        return $this->entityAccess->getRolesExplicit();
      else 
        return array();
    }

    //$className = $this->builder->getNodeClass( 'AccessRole' );

    $roles = array();

    if( isset( $this->node->roles_explicit->role ) )
    {
      foreach( $this->node->roles_explicit->role as $role )
      {
        //$roles[] = new $className( $role );
        $roles[] = trim( $role['name'] );
      }
    }

    return $roles;

  }//end public function getRolesExplicit */

  /**
   * Wir zb verwendet um die adopt action auszublenden
   * wenn der user bereits die Rolle hat mit welcher er den datensatz adoptieren
   * würde
   * @return array<string>
   */
  public function getNoRolesExplicit(  )
  {
    
    if( !isset( $this->node->no_roles_explicit->role ) )
    {
      if( $this->entityAccess )
        return $this->entityAccess->getRolesExplicit();
      else 
        return array();
    }

    //$className = $this->builder->getNodeClass( 'AccessRole' );

    $roles = array();

    if( isset( $this->node->no_roles_explicit->role ) )
    {
      foreach( $this->node->no_roles_explicit->role as $role )
      {
        //$roles[] = new $className( $role );
        $roles[] = trim( $role['name'] );
      }
    }

    return $roles;

  }//end public function getNoRolesExplicit */

}//end class LibGenfTreeNodeAccess

