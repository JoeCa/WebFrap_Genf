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
class LibCartridgeSyncService
  extends LibCartridgeBdlService
{

////////////////////////////////////////////////////////////////////////////////
// parser + write
////////////////////////////////////////////////////////////////////////////////

  /**
   * the default parser method
   */
  public function render()
  {

    $orm = Webfrap::$env->getOrm();
    $orm->deleteWhere( 'BdlService', 'access_key is null' );

    foreach( $this->root as $service )
    {

      $name      = $service->getName();

      $env       = $this->root->createEnvironment( $service );
      $this->env = $env;
      $this->builder->activNode = $env;
      
      $env->switchListingContext('service');
      
      
      $mNode = $orm->getByKey( 'BdlService', $service->name->name );
      
      if( !$mNode )
      {
        $mNode = new BdlService_Entity();
      }
      
      $mNode->access_key = $service->name->name;
      $mNode->label      = $service->name->label;
      
      $mNode->service_type   = $name->type;

      $mNode->data_source    = $service->name->source;
      $mNode->id_management  = $orm->getByKey( 'BdlManagement', $service->name->source );
      
      $mNode->module     = strtolower($service->name->module);
      $mNode->id_module  = $orm->getByKey( 'BdlModule', $service->name->module );
      
      $mNode->description  = $service->shortDescription('en');
      
      $orm->save( $mNode );

    }//end foreach

  }//end public function render */


} // end class LibCartridgeSyncService
