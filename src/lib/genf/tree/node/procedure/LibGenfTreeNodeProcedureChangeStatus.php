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
 * 
 * @example Tag Beispiel
 * 
 * <procedure type="change_status" >
 *   <status key="some_status" />
 * </procedure>
 * 
 * Die Prozedur funktioniert nur mit Prozessen
 * 
 */
class LibGenfTreeNodeProcedureChangeStatus
  extends LibGenfTreeNodeProcedure
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name       = new LibGenfNameProcedure( $this->node );
    $this->loadConditions();

  }//end protected function loadChilds */


  /**
   * @return string
   */
  public function getStatus()
  {
    
    if( !isset( $this->node->status['key'] ) )
    {
      $this->builder->dumpError( "Missing the status status->\"key\" attribute for change status. How should that work?!" );
      return null;
    }
    
    return trim( $this->node->status['key'] );

  }//end public function getStatus */


  
}//end class LibGenfTreeNodeProcedureChangeStatus

