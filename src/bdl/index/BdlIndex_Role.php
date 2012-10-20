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
class BdlIndex_Role
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
    $this->dom  = $this->file->getNodeByPath( '/bdl/roles/role' );
    
    $role = $this->orm->getByKey( 'BdlRole', $this['name'] );
    
    if( !$role )
    {
      $role = new BdlRole_Entity();
      $role->access_key = $this['name'];
      $role->name = SParserString::subToName( $this['name']) ;
      
      $this->orm->insert( $role );
    }
    
    $bdlFile = $this->orm->get( 'BdlFile', "file_name='{$this->file->fileName}'" );
    if( !$bdlFile )
    {
      $bdlFile = new BdlFile_Entity();
      $bdlFile->file_name = $this->file->fileName;
      $bdlFile->vid = $role;
      
      $this->orm->insert( $bdlFile );
    }
    
  }//end  public function syncIndex */


}//end class BdlIndex_Role 
