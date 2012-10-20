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
class LibCartridgeSyncRole
  extends LibCartridgeBdlRole
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * parse all entities
   *
   */
  public function parse()
  {
    
    $orm = Webfrap::$env->getOrm();
    $orm->deleteWhere('BdlRole', 'access_key is null');

    foreach( $this->root as $role )
    {
      
      $mNode = $orm->getByKey( 'BdlRole', $role->name->name );
      
      if( !$mNode )
      {
        $mNode = new BdlRole_Entity();
      }
      
      $mNode->access_key = $role->name->name;
      $mNode->label      = $role->name->label;
      
      $mNode->module     = strtolower($role->name->module);
      $mNode->id_module  = $orm->getByKey( 'BdlModule', $role->name->module );
      
      $mNode->description  = $role->shortDescription('en');
      
      $orm->save( $mNode );

    }//end foreach

  }//end public function parse */

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





} // end class LibCartridgeSyncRole
