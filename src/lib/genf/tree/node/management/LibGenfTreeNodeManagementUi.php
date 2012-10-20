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
class LibGenfTreeNodeManagementUi
  extends LibGenfTreeNodeUi
{

  /**
   * Fallback vom Management UI auf das Entity UI
   * 
   * @var LibGenfTreeNodeUi
   */
  public $fallback = null;
  
  /**
   *
   * @param LibGenfTreeNodeUi $fallback
   */
  public function setFallback( $fallback )
  {
    
    if( $this === $fallback )
    {
      $this->error( "Tried to assign a node as it's fallback, that's not possible an would run in an endless loop ".$this->debugData() );
      return null;
    }
    
    $this->builder->dumpEnv('GOT FALLBACK');
    
    $this->fallback = $fallback;
    
  }//end public function setFallback */

}//end class LibGenfTreeNodeManagementUi

