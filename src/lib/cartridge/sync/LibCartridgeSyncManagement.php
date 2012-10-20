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
 * @subpackage Genf
 *
 * Build files that sync the metadata in the database with the bld
 * model on load
 *
 */
class LibCartridgeSyncManagement
  extends LibCartridgeBdlManagement
{
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * parse all entities
   *
   */
  public function parse()
  {

    $orm = Webfrap::$env->getOrm();
    
    $orm->deleteWhere('BdlManagement', 'access_key is null');
    
    foreach( $this->root as $management )
    {

      $name         = $management->getName();

      $mNode = $orm->getByKey( 'BdlManagement', $management->name->name );
      
      if( !$mNode )
      {
        $mNode = new BdlManagement_Entity();
      }
      
      $mNode->label      = $management->name->label;
      $mNode->access_key = $management->name->name;
      
      $mNode->data_source = $management->name->source;
      $mNode->id_entity  = $orm->getByKey( 'BdlEntity', $management->name->source );
      
      $mNode->module     = strtolower($management->name->module);
      $mNode->id_module  = $orm->getByKey( 'BdlModule', $management->name->module );
      
      $mNode->comment    = $management->description('en');
      
      $orm->save( $mNode );
      
      $this->syncReferences( $management );

    }//end foreach
  
    /*
    // create Category Menus
    $categories     = $this->root->getCategoryManagements();
    $menuCategories = array();

    foreach( $categories as $catName => $managements )
    {

      $catName = strtolower($catName);

      foreach( $managements as $management )
      {

        $modName = $management->name->lower('customModul');

        if(!isset($menuCategories[$modName]))
          $menuCategories[$modName] = array();

        if( isset($menuCategories[$modName][$catName]) )
          continue;

        $folderPath   = $folder.$key.'/'.$name->lower('customModul').'/data/metadata/module_category/';
        $this->writeFile
        ( 
          $this->syncModCategory($modName,$catName), 
          $folderPath, 
          $modName.'-'.$management->name->name.'-'.$catName.'.php' 
        );

      }
      */


  }

  /**
   * (non-PHPdoc)
   * @see lib/LibCartridge::write()
   */
  public function write()
  {

  }//end public function write */


////////////////////////////////////////////////////////////////////////////////
// helper methodes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param $modName
   * @param $catName
   * @return string
   */
  protected function syncModCategory( $modName, $catName )
  {

    $code = <<<CODE
<?php
/*//////////////////////////////////////////////////////////////////////////////
// create the metadata mod {$modName} category {$catName}
//////////////////////////////////////////////////////////////////////////////*/


    // first clean the management
    \$modCategory = null;

    if( !\$modCategory = \$orm->getByKey( 'WbfsysModuleCategory', '{$modName}-{$catName}' ) )
    {
      \$modCategory = new WbfsysModuleCategory_Entity();
      \$modCategory->access_key = '{$modName}-{$catName}';

      \$modCategory->name      = 'Mod: {$modName} Cat: {$catName}';
      \$modCategory->id_module = \$orm->getByKey( 'WbfsysModule', '{$modName}' );
    }
    \$modCategory->revision    = \$deployRevision;
    \$orm->save( \$modCategory );

    \$secArea = null;

    if( !\$secArea = \$orm->getByKey( 'WbfsysSecurityArea', 'mod-{$modName}-cat-{$catName}' ) )
    {
      \$secArea = new WbfsysSecurityArea_Entity();
      \$secArea->access_key = 'mod-{$modName}-cat-{$catName}';


      \$secArea->label      = 'Mod: {$modName} Cat: {$catName}';
      \$secArea->id_level_listing = {$this->accessLevel['listing']};
      \$secArea->id_level_access  = {$this->accessLevel['access']};
      \$secArea->id_level_insert  = {$this->accessLevel['insert']};
      \$secArea->id_level_update  = {$this->accessLevel['update']};
      \$secArea->id_level_delete  = {$this->accessLevel['delete']};
      \$secArea->id_level_admin   = {$this->accessLevel['admin']};

      \$secArea->id_ref_listing = {$this->accessLevel['ref_listing']};
      \$secArea->id_ref_access  = {$this->accessLevel['ref_access']};
      \$secArea->id_ref_insert  = {$this->accessLevel['ref_insert']};
      \$secArea->id_ref_update  = {$this->accessLevel['ref_update']};
      \$secArea->id_ref_delete  = {$this->accessLevel['ref_delete']};
      \$secArea->id_ref_admin   = {$this->accessLevel['ref_admin']};

    }

    // verweist auf die entity als parent
    \$secArea->upgrade( 'm_parent', \$orm->getByKey( 'WbfsysSecurityArea', 'mod-{$modName}' ) );
    \$secArea->upgrade( 'parent_key', 'mod-{$modName}' );
    \$secArea->upgrade( 'vid', \$modCategory );
    \$secArea->upgrade( 'id_vid_entity', \$orm->getByKey( 'WbfsysEntity', 'wbfsys_module_category' ) );
    \$secArea->upgrade( 'id_type', \$orm->getByKey( 'WbfsysSecurityAreaType', 'module_category' ) );
    \$secArea->upgrade( 'type_key', 'module_category' );
    \$secArea->upgrade( 'description', "Module: {$modName} Category: {$catName}" );
    \$secArea->revision    = \$deployRevision;
    \$orm->save( \$secArea );
    
CODE;

    return $code;

  }//end protected function syncModCategory */

  /**
   *
   * @param LibGenfTreeNodeManagement $management
   * @return string
   */
  protected function syncReferences( $management )
  {
    
    $orm = Webfrap::$env->getOrm();
    
    $name       = $management->getName();
    $references = $management->getReferences();

    foreach( $references as $ref )
    {

      if(!$ref->relation(Bdl::MANY))
        continue;

      $refName = $ref->getName();

      if( !$target  = $ref->target() )
        continue;

      if( !$src     = $ref->src() )
        continue;

      if( $ref->relation(Bdl::MANY_TO_MANY) )
      {
        
        /**/
        if( !$connection = $ref->connection() )
          continue;

        $mNode = $orm->getByKey( 'BdlManagementReference', $name->name.'-ref-'.$refName->name );
      
        if( !$mNode )
        {
          $mNode = new BdlManagementReference_Entity();
        }
        
        $mNode->access_key   = $name->name.'-ref-'.$refName->name;     
        $mNode->name         = $refName->name;   
        $mNode->many_to_many = true;
        
        $mNode->connected  = $ref->binding('connected');

        
        $mNode->source_key = $name->name;
        $mNode->id_source  = $orm->getByKey( 'BdlManagement', $name->name );

        $mNode->target_key = $target->name;
        $mNode->id_target  = $orm->getByKey( 'BdlManagement', $target->name );
        
        $mNode->connection_key = $connection->name;
        $mNode->id_connection  = $orm->getByKey( 'BdlManagement', $connection->name );

        $mNode->id_ref  = $orm->getByKey( 'BdlEntityReference', $name->source.'-ref-'.$refName->name );
      
        $mNode->description    = $ref->description('en');
        
        $orm->save( $mNode );
        

      }
      else
      {
      
        /* */
        $mNode = $orm->getByKey( 'BdlManagementReference', $name->name.'-ref-'.$refName->name );
      
        if( !$mNode )
        {
          $mNode = new BdlManagementReference_Entity();
        }
        
        $mNode->access_key   = $name->name.'-ref-'.$refName->name;     
        $mNode->name         = $refName->name;
        $mNode->many_to_many = false;
        
        $mNode->connected  = $ref->binding('connected');
        
        $mNode->source_key = $name->name;
        $mNode->id_source  = $orm->getByKey( 'BdlManagement', $name->name );

        $mNode->target_key = $target->name;
        $mNode->id_target  = $orm->getByKey( 'BdlManagement', $target->name );

        $mNode->id_ref     = $orm->getByKey( 'BdlEntityReference', $name->source.'-ref-'.$refName->name );
      
        $mNode->description    = $ref->description('en');
        
        $orm->save( $mNode );
       
        
      }


    }//end foreach


  }//end protected function syncReferences */

} // end class LibCartridgedWbfWgttable
