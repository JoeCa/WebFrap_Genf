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
class LibGenfTreeNodeServiceReference
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @var LibGenfTreeNodeManagement
   */
  public $management = null;
  
  /**
   * @var LibGenfTreeNodeReference
   */
  public $ref        = null;

/*//////////////////////////////////////////////////////////////////////////////
// Construktor
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @param SimpleXmlElement $node
   * @param LibGenfTreeNodeManagement $management
   */
  public function __construct( $node, $management )
  {

    $this->builder  = LibGenfBuild::getInstance();
    
    $this->management = $management;

    $this->validate( $node );
    $this->loadChilds( );

  }//end public function __construct */
  
/*//////////////////////////////////////////////////////////////////////////////
// methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->ref = $this->management->entity->getReference( trim($this->node['name']) );

  }//end protected function loadChilds */

  /**
   * 
   * @return array<LibGenfTreeNodeAccessProfile>
   */
  public function getTargetFields(  )
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

  }//end public function getTargetFields */
  
  /**
   * 
   * @return array<LibGenfTreeNodeAccessProfile>
   */
  public function getConnectionField(  )
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

  }//end public function getConnectionField */



}//end class LibGenfTreeNodeAccess

