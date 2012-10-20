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
class BdlIndex_Process
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
    $this->dom  = $this->file->getNodeByPath( '/bdl/processes/process' );

    $process = $this->orm->getByKey( 'BdlProcess', $this['name'] );
    if( !$process )
    {
      $process = new BdlProcess_Entity();
      $process->access_key = $this['name'];
      $process->name       = SParserString::subToName( $this['name'] ) ;

      
      if( isset( $this['module'] ) )
      {
        $process->module     = $this['module'];
        $process->id_module  = $this->orm->getByKey( 'BdlModule', $this['name'] );
      }
      else 
      {
        $modName = SParserString::getFirstSub($this['name']);
        $process->module     = $modName;
        $process->id_module  = $this->orm->getByKey( 'BdlModule', $modName );
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
      
      $process->data_source = $this['src'];
      $process->id_management   = $management;
      
      $this->orm->insert( $process );
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


}//end class BdlIndex_Process
