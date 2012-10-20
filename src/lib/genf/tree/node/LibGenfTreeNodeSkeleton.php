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
class LibGenfTreeNodeSkeleton
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @var LibGenfTreeNodeUi
   */
  public $management   = null;

  /**
   *
   * @var LibGenfTreeNodeSkeletonService
   */
  public $services   = array();
  
/*//////////////////////////////////////////////////////////////////////////////
// load methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name       = new LibGenfNameSkeleton( $this->node );

    if( $this->name->mgmt )
      $this->management = $this->builder->getManagement( $this->name->mgmt );

    $this->name = new LibGenfNameSkeletonService( $this->node );

    if( isset( $this->node->services->service ) )
    {
      foreach(  $this->node->services->service  as $service  )
      {
        $this->services[] = new LibGenfTreeNodeSkeletonService( $service );
      }
    }
    

  }//end protected function loadChilds */

/*//////////////////////////////////////////////////////////////////////////////
// ui getter
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @return LibGenfTreeNodeManagement
   */
  public function getManagement()
  {
    return null;
  }//end public function getManagement */
  
  /**
   * Den Zugriffstype abfragen
   * 
   * public | auth
   * 
   * @return string
   */
  public function getAccessType()
  {
    
    if( isset( $this->node->access['type'] ) )
      return trim( $this->node->access['type']  );
    else 
      return 'auth';

  }//end public function getAccessType */

  /**
   * 
   * @param string $type
   * @return boolean
   */
  public function hasViewType( $type )
  {
    
    $vTypes = $this->node->xpath( './services/service/views/view[@type="'.$type.'"]' );
    
    return (boolean)count($vTypes);
    
  }//end public function hasViewType */
  
  /**
   * @return [LibGenfTreeNodeService]
   */
  public function getServices()
  {
    
    return $this->services;
    
  }//end public function getServices */

}//end class LibGenfTreeNodeSkeleton

