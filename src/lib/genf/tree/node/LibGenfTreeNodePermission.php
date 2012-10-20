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
class LibGenfTreeNodePermission
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @var array
   */
  public $areas = null;
  
  /**
   * @var array
   */
  public $backpath = null;
  
/*//////////////////////////////////////////////////////////////////////////////
// Methodes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @return [LibGenfTreeNodePermissionArea]
   */
  public function getAreas( ) 
  {
    
    if( !is_null( $this->areas ) )
      return $this->areas;
      
    $this->areas = array();
      
    if( !isset( $this->node->areas->area ) )
      return $this->areas;

    foreach( $this->node->areas->area as $area )
    {
      $this->areas[] = new LibGenfTreeNodePermissionArea($area);
    }
    
    return $this->areas;
    
  }//end public function getAreas */
  
  
}//end class LibGenfTreeNodePermission

