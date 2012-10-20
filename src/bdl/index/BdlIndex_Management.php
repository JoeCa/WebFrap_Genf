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
class BdlIndex_Management
  extends BdlIndexer
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param BdlFiled $bdlFile
   * @return string
   */
  public function syncIndex( $bdlFile )
  {
    
    $this->file = $bdlFile;
    $this->dom  = $this->file->getNodeByPath( '/bdl/managements/management' );

    $management = $this->orm->getByKey( 'BdlManagement', $this['name'] );
    if( !$management )
    {
      $management = new BdlManagement_Entity();
      $management->access_key = $this['name'];
      $management->name       = SParserString::subToName( $this['name'] ) ;

      
      if( isset( $this['module'] ) )
      {
        $management->module     = $this['module'];
        $management->id_module  = $this->orm->getByKey( 'BdlModule', $this['name'] );
      }
      else 
      {
        $modName = SParserString::getFirstSub($this['name']);
        $management->module     = $modName;
        $management->id_module  = $this->orm->getByKey( 'BdlModule', $modName );
      }
      
      $entity = $this->orm->getByKey( 'BdlEntity', $this['src'] );
    
      if( !$entity )
      {
        $entity = new BdlEntity_Entity();
        $entity->access_key = $this['src'];
        $entity->name       = SParserString::subToName( $this['src'] ) ;
        
        $this->orm->insert( $entity );
      }
      
      $management->data_source = $this['src'];
      $management->id_entity   = $entity;
      
      $this->orm->insert( $management );
    }
    
    
    $bdlFile = $this->orm->get( 'BdlFile', "file_name='{$this->file->fileName}'" );
    if( !$bdlFile )
    {
      $bdlFile = new BdlFile_Entity();
      $bdlFile->file_name = $this->file->fileName;
      $bdlFile->vid       = $entity;
      
      $this->orm->insert( $bdlFile );
    }
    
  }//end  public function syncIndex */


}//end class BdlIndex_Management
