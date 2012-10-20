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
class LibGenfEnvSkeleton
  extends LibGenfEnvManagement
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Der Environment type
   * @var string
   */
  public $type      = 'env_skeleton';
  
  /**
   * Der Skeleton Node
   * @var LibGenfTreeNodeSkeleton
   */
  public $skeleton      = null;
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param LibGenfTreeNodeSkeleton $skeleton
   * @param LibGenfName $compName
   */
  public function setData( $skeleton, $compName = null )
  {

    $this->skeleton     = $skeleton;
    $this->name         = $skeleton->name;
    
    if( $management = $skeleton->getManagement() )
    {
      $this->management = $management;
      $this->entity     = $management->entity;
    }

    if( $compName )
      $this->compName   = $compName;
    else
      $this->compName   = $skeleton->name;

  }//end public function setData */
  

}//end class LibGenfEnvSkeleton */
