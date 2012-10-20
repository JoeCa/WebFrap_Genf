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
class LibCartridgeSyncEntity
  extends LibCartridgeBdlEntity
{
////////////////////////////////////////////////////////////////////////////////
// P&W
////////////////////////////////////////////////////////////////////////////////

  /**
   * parse the cartridge
   * @return void
   */
  public function parse()
  {


    $orm = Webfrap::$env->getOrm();
    
    $orm->deleteWhere('BdlModule', 'access_key is null');

    foreach( $this->root as $entity )
    {

      $name         = $entity->getName();

      $mNode = $orm->getByKey( 'BdlEntity', $entity->name->name );
      
      if( !$mNode )
      {
        $mNode = new BdlEntity_Entity();
      }
      
      $mNode->access_key = $entity->name->name;
      $mNode->label      = $entity->name->label;
      
      $mNode->module     = strtolower($entity->name->module);
      $mNode->id_module  = $orm->getByKey( 'BdlModule', $entity->name->module );
      
      $mNode->description  = $entity->description('en');
      
      $orm->save( $mNode );
      
      $this->syncReferences( $entity );

    }//end foreach */


  }//end protected function parse */

  /**
   * Enter description here...
   *
   */
  public function write(){}//end public function write */


  /**
   *
   * @param LibGenfTreeNodeEntity $entity
   * @return string
   */
  protected function syncReferences( $entity )
  {
    
    $orm = Webfrap::$env->getOrm();

    $references = $entity->getReferences();
    $name       = $entity->getName();

    foreach( $references as $ref )
    {
      
      /* @var $ref LibGenfTreeNodeReference */
      
      if( !$ref->relation(Bdl::MANY) )
        continue;

      if( !$target = $ref->target() )
        continue;

      $refName  = $ref->getName();
        
      $srcId    = $ref->srcId();
      $targetId = $ref->targetId();


      if( $ref->relation(Bdl::MANY_TO_MANY) )
      {

        if( !$connection   = $ref->connection() )
          continue;

        $srcRefId     = $ref->srcRefId();
        $targetRefId  = $ref->targetRefId();
        
        
        $mNode = $orm->getByKey( 'BdlEntityReference', $name->name.'-ref-'.$refName->name );
      
        if( !$mNode )
        {
          $mNode = new BdlEntityReference_Entity();
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
      
        $mNode->description    = $ref->description('en');
        
        $orm->save( $mNode );
 
      }
      else
      {

        $mNode = $orm->getByKey( 'BdlEntityReference', $name->name.'-ref-'.$refName->name );
      
        if( !$mNode )
        {
          $mNode = new BdlEntityReference_Entity();
        }
        
        $mNode->access_key   = $name->name.'-ref-'.$refName->name;
        $mNode->name         = $refName->name;
        $mNode->many_to_many = false;
        
        $mNode->connected  = $ref->binding('connected');
        
        $mNode->source_key = $name->name;
        $mNode->id_source  = $orm->getByKey( 'BdlEntity', $name->name );

        $mNode->target_key = $target->source;
        $mNode->id_target  = $orm->getByKey( 'BdlEntity', $target->source );

        $mNode->description  = $ref->description('en');
        
        $orm->save( $mNode );
        
      }

    }//end foreach

  }//end protected function buildMetaDataReferences */


} // end class LibCartridgeWbfEntity
