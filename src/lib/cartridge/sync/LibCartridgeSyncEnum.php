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
class LibCartridgeSyncEnum
  extends LibCartridgeBdlEnum
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
    $orm->deleteWhere('BdlEnum', 'access_key is null');
    
    foreach( $this->root as $enum )
    {

      $name      = $enum->getName();

      $mNode = $orm->getByKey( 'BdlEnum', $enum->name->name );
      
      if( !$mNode )
      {
        $mNode = new BdlEnum_Entity();
      }
      
      $mNode->access_key = $enum->name->name;
      $mNode->label      = $enum->name->label;

      $mNode->module     = strtolower($enum->name->module);
      $mNode->id_module  = $orm->getByKey( 'BdlModule', $enum->name->module );
      
      $mNode->description  = $enum->shortDescription('en');
      
      $orm->save( $mNode );

    }//end foreach

  }//end public function render */


} // end class LibCartridgeSyncEnum
