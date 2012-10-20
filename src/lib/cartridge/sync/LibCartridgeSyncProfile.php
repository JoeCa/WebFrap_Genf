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
class LibCartridgeSyncProfile
  extends LibCartridgeBdlProfile
{

  /**
   *
   * @var LibGeneratorWbfModel
   */
  protected $generator    = null;

////////////////////////////////////////////////////////////////////////////////
// Parser
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   */
  public function render()
  {
    
    $orm = Webfrap::$env->getOrm();
    $orm->deleteWhere('BdlProfile', 'access_key is null');

    foreach( $this->node as $profile )
    {
      
      /* @var LibGenfTreeNodeProfile $profile */
      
      $mNode = $orm->getByKey( 'BdlProfile', $profile->name->name );
      
      if( !$mNode )
      {
        $mNode = new BdlProfile_Entity();
      }
      
      $mNode->access_key = $profile->name->name;
      $mNode->label      = $profile->name->label;
      
      $mNode->module     = strtolower($profile->name->module);
      $mNode->id_module  = $orm->getByKey( 'BdlModule', $profile->name->module );
      
      $mNode->description  = $profile->shortDescription('en');
      
      $orm->save( $mNode );
      
      
      $rNode = $orm->getByKey( 'BdlRole', $profile->name->name );
      
      if( !$rNode )
      {
        $rNode = new BdlRole_Entity();
      }
      
      $rNode->access_key = $profile->name->name;
      $rNode->label      = $profile->name->label;
      
      $rNode->flag_implicit  = true;
      $rNode->profile_name   = $profile->name->name;

  
      $rNode->module     = strtolower($profile->name->module);
      $rNode->id_module  = $orm->getByKey( 'BdlModule', $profile->name->module );
      
      $rNode->description  = $profile->shortDescription('en');
      
      $orm->save( $rNode );

    }//end foreach

  }//end protected function render */


} // end class LibCartridgeSyncProfile
