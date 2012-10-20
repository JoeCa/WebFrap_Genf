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
class LibGenfTreeNodeResponsibleCheck
  extends LibGenfTreeNode
{ 
/*//////////////////////////////////////////////////////////////////////////////
// methodes
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * Bei Responsibles wird nur ein Key auf eine Datenquelle benötigt
   * @return string
   */
  public function getKey()
  {
    
    return trim( $this->node['key'] );
    
  }//end public function getKey */
  
  /**
   * 
   * Typen:
   * 
   * - role
   * - role_somewhere
   * - profile 
   * - level
   * 
   * @return string
   */
  public function getType()
  {
    
    return trim( $this->node['type'] );
    
  }//end public function getType */

  /**
   * Erfragen zu was der Check in Relation steht,
   * Möglich sind Dataset, Area, Entity oder Global
   * Teils werden nur bestimmte relationen behandelt, es gibt dann einen fallback
   * auf das erste größere oder erst kleinere
   * 
   * @return string
   */
  public function getRelation()
  {

    return isset($this->node['relation'])
      ? strtolower(trim($this->node['relation']))
      : 'dataset';

  }//end public function getRelation */
  
  /**
   * @return string
   */
  public function getLevel()
  {
    
    if( !isset($this->level['min']) )
      return null;
      
    return trim($this->level['min']);
    
  }//end public function getLevel */

  /**
   * @return string
   */
  public function getMaxLevel()
  {
    
    if( !isset($this->level['max']) )
      return null;
      
    return trim($this->level['max']);
    
  }//end public function getMaxLevel */
  
  /**
   * @return array
   */
  public function getRoles(  )
  {
    
    if( !isset( $this->node->roles->role ) )
      return array( );
      
    $roles = array( );
    
    foreach( $this->node->roles->role as $role )
    {
      $roles[] = trim( $role['name'] );
    }
    
    return $roles;


  }//end public function getRoles */
  
  /**
   * @return array
   */
  public function getElse(  )
  {
    
    if( !isset( $this->node->else->check ) )
      return array( );
      
    $checks = array( );
    
    foreach( $this->node->else->check as $check )
    {
      $checks[] = new LibGenfTreeNodeResponsibleCheck( $check );
    }
    
    return $checks;

  }//end public function getElse */

  
}//end class LibGenfTreeNodeResponsibleCheck

