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
 * <procedure type="break" >
 * 
 *   <message name="need_more_information" />
 *   
 * </procedure>
 * 
 */
class LibGenfTreeNodeProcedureBreak
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
  
 

}//end class LibGenfTreeNodeProcedureBreak

