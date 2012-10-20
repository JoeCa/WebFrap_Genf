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
class BdlIndex_Service
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
    $this->dom  = $this->file->getNodeByPath( '/bdl/services/service' );

    $service = $this->orm->getByKey( 'BdlService', $this['name'] );
    if( !$service )
    {
      $service = new BdlService_Entity();
      $service->access_key = $this['name'];
      $service->name       = SParserString::subToName( $this['name'] ) ;
      
      $service->service_type = $this['type'];

      
      if( isset( $this['module'] ) )
      {
        $service->module     = $this['module'];
        $service->id_module  = $this->orm->getByKey( 'BdlModule', $this['name'] );
      }
      else 
      {
        $modName = SParserString::getFirstSub($this['name']);
        $service->module     = $modName;
        $service->id_module  = $this->orm->getByKey( 'BdlModule', $modName );
      }
      
      $management = $this->orm->getByKey( 'BdlManagement', $this['src'] );
    
      if( !$management )
      {
        $entity = new BdlEntity_Entity();
        $entity->access_key = $this['src'];
        $entity->name       = SParserString::subToName( $this['src'] ) ;
        
        $this->orm->insert( $entity );
        
        
        $management = new BdlManagement_Entity();
        $management->access_key = $this['src'];
        $management->name       = SParserString::subToName( $this['src'] ) ;
        
        $management->data_source = $this['src'];
        $management->id_entity   = $entity;
        
        $this->orm->insert( $management );

      }
      
      $service->data_source = $this['src'];
      $service->id_management   = $management;
      
      $this->orm->insert( $service );
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


}//end class BdlIndex_Service
