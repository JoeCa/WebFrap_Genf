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
class LibGenfTreeNodeAccessProfileCheck
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * @return string
   */
  public function getType()
  {

    if( isset($this->node['type']) )
      return strtolower( trim( $this->node['type'] ) ) ;
    else
      return 'bdl';

  }//end public function getType */

  /**
   * @return string
   */
  public function getMaxLevel()
  {

    if( isset($this->node['max_level']) )
      return strtoupper(trim( $this->node['max_level'] ));
    else
      return 'ADMIN';

  }//end public function getMaxLevel */


  /**
   * @return string
   */
  public function getFieldName()
  {

    ///TODO error handling
    return trim( $this->node['field'] );

  }//end public function getFieldName */
  
  /**
   * prüfen ob ein pfad existiert
   * @return boolean
   */
  public function hasPath()
  {

    return isset( $this->node->path );

  }//end public function hasPath */

  /**
   * @param LibGenfTreeNodeManagement $management
   * @return array<string,Entity>
   */
  public function getPath( $management )
  {

    if( isset( $this->node->path ) )
    {
      return $this->buildEntityPath
      ( 
        $management->entity, 
        explode( '.', trim( $this->node->path ) ) 
      );
    }
    else
    {
      return null;
    }

  }//end public function getPath */


  /**
   * @return string
   */
  public function getPathStart()
  {

    $tmp = explode( '.', trim( $this->node->path ) );

    return $tmp[0];

  }//end public function getPathStart */

  /**
   * @param LibGenfTreeNodeEntity $entity
   * @return LibGenfNameManagement
   */
  public function getFieldTarget( $entity, $type = true )
  {

    return $entity->getAttrTarget( $this->getFieldName(), $type );

  }//end public function getFieldTarget */


  /**
   * @return string
   */
  public function getRoles()
  {

    $roles = array();

    if( !isset($this->node->roles->role) )
      return $roles;

    foreach( $this->node->roles->role as $role )
    {
      $roles[] = trim( $role['name'] );
    }

    return $roles;

  }//end public function getRoles */

  /**
   * @return string
   */
  public function getNewAccesLevel()
  {

    return strtoupper(trim($this->set['access_level']));

  }//end public function getNewAccesLevel */

  /**
   * @return string
   */
  public function getNewRefLevel()
  {

    return strtoupper(trim($this->set['ref_level']));

  }//end public function getNewRefLevel */

  /**
   * @return string
   * @return array<string role:string level.upper()>
   */
  public function getNewRefLevels()
  {

    $levels = array();

    if( !isset($this->node->set->level) )
      return $levels;

    foreach( $this->node->set->level as $level )
    {
      $levels[trim($level['name'])] = strtoupper(trim( $level['level'] ));
    }

    return $levels;

  }//end public function getNewRefLevels */
  
  /**
   * 
   * Gibt eine Liste mit Rollenzugehörigkeiten zurück
   *  
   * @return array<string rolename: string rolename>
   */
  public function getNewRoles()
  {

    $roles = array();

    if( !isset($this->node->set->roles->role) )
      return $roles;

    foreach( $this->node->set->roles->role as $role )
    {
      $roles[trim($role['name'])] = trim( $role['name'] );
    }

    return $roles;

  }//end public function getNewRoles */

  /**
   * @return string
   */
  public function getCode()
  {

    return trim( $this->node );

  }//end public function getCode */


}//end class LibGenfTreeNodeAccessProfileCheck

