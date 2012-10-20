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
class LibGenfTreeNodeResponsible
  extends LibGenfTreeNode
{ 
/*//////////////////////////////////////////////////////////////////////////////
// methodes
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * @return string
   */
  public function name()
  {
    
    return trim( $this->node['name'] );
    
  }//end public function name */
  

  /**
   * @return array<LibGenfTreeNodeResponsibleCheck>
   */
  public function getChecks(  )
  {
    
    if( !isset( $this->node->check ) )
      return array( );
      
    $checks = array( );
    
    foreach( $this->node->check as $check )
    {
      $checks[] = new LibGenfTreeNodeResponsibleCheck( $check );
    }
    
    return $checks;

  }//end public function getChecks */

  
}//end class LibGenfTreeNodeResponsible

