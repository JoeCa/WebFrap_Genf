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
class BdlIndex_Enum
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
    $this->dom  = $this->file->getNodeByPath( '/bdl/enums/enum' );

    $enum = $this->orm->getByKey( 'BdlEnum', $this['name'] );
    if( !$enum )
    {
      $enum = new BdlEnum_Entity();
      $enum->access_key = $this['name'];
      $enum->name       = SParserString::subToName( $this['name'] ) ;

      if( isset( $this['module'] ) )
      {
        $enum->module     = $this['module'];
        $enum->id_module  = $this->orm->getByKey( 'BdlModule', $this['name'] );
      }
      else 
      {
        $modName = SParserString::getFirstSub($this['name']);
        $enum->module     = $modName;
        $enum->id_module  = $this->orm->getByKey( 'BdlModule', $modName );
      }

      $this->orm->insert( $enum );
    }
    
    
    $bdlFile = $this->orm->get( 'BdlFile', "file_name='{$this->file->fileName}'" );
    if( !$bdlFile )
    {
      $bdlFile = new BdlFile_Entity();
      $bdlFile->file_name = $this->file->fileName;
      $bdlFile->vid       = $enum;
      
      $this->orm->insert( $bdlFile );
    }
    
  }//end  public function syncIndex */


}//end class BdlIndex_Enum
