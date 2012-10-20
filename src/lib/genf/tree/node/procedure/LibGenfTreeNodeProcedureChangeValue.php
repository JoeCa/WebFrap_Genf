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
 * <procedure type="change_value" >
 * 
 *   <values>
 *     <value key="" data="" />
 *   </values>
 *   
 *   
 * </procedure>
 * 
 */
class LibGenfTreeNodeProcedureChangeValue
  extends LibGenfTreeNodeProcedure
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * 
   * @var array
   */
  public $values = array();
  
  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name       = new LibGenfNameProcedure( $this->node );
    
    foreach( $this->node->values->value as $value )
    {
      $this->values[] = new LibGenfTreeNodeValue( $value );
    }
    
    $this->loadConditions();

  }//end protected function loadChilds */


  /**
   * @return string
   */
  public function getKey()
  {
    
    if( !isset( $this->node->entity['key'] ) )
      return null;
    
    return SParserString::subToCamelCase( trim( $this->node->entity['key'] ) ) ;

  }//end public function getEntityKey */

  /**
   * @return LibGenfTreeNodeEntity
   */
  public function getEntity()
  {
    
    if( !isset( $this->node->entity['name'] ) )
      return null;
    
    $mgmtName = trim($this->node->entity['name']);
    
    $mgmt = $this->builder->getManagement( $mgmtName );

    if( !$mgmt )
    {
      $this->builder->error( "Missing area name in procedure ".$this->builder->dumpEnv() );
      return null;
    }
    
    return $mgmt->entity;

  }//end public function getEntity */
  
  /**
   * @return array<LibGenfTreeNodeValue>
   */
  public function getValues()
  {
    
    return $this->values;

  }//end public function getValues */
  
}//end class LibGenfTreeNodeProcedureChangeValue

