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
 * <procedure type="send_mail" >
 * 
 *   <message name="need_more_information" />
 *   
 *   <messages>
 *     <success>
 *       <text lang="en" >Notified Assignment Creator</text>
 *     </success>
 *   </messages>
 *   
 *   <receivers>
 *     <receiver type="responsible" name="responsible" />
 *   </receivers>
 *   
 * </procedure>
 * 
 */
class LibGenfTreeNodeProcedureRemoveRole
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
  public function getRoleName()
  {
    
    return trim( $this->node->role['name'] );

  }//end public function getRoleName */
  

  /**
   * @return LibGenfNameManagement
   */
  public function getArea()
  {
    
    if( !isset( $this->node->area['name'] ) )
      return null;
    
    $mgmtName = trim($this->node->area['name']);
    
    $mgmt = $this->builder->getManagement( $mgmtName );

    if( !$mgmt )
    {
      $this->builder->error( "Missing area name in procedure ".$this->builder->dumpEnv() );
      return null;
    }
    
    return $mgmt->name;
    
  }//end public function getArea */
  
  /**
   * @return string
   */
  public function getAreaKey()
  {
    
    $key = null;
    
    if( isset( $this->node->area['key'] ) )
    {
      $key = trim($this->node->area['key']);
    }
    else if( isset( $this->node->area['name'] ) )
    {
      $key = trim($this->node->area['name']);
    }    
    else
    { 
      return null;
    }
    
    return SParserString::subToCamelCase( trim($key) ) ;

  }//end public function getAreaKey */

}//end class LibGenfTreeNodeProcedureRemoveRole

