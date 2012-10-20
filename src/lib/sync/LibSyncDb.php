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
 * @subpackage ModGenf
 */
class LibSyncDb
{

  protected $registry = null;

  protected $db = null;

  protected $infoDb = null;

  /**
   * @param $registry
   * @param $db
   * @param $infoDb
   */
  public function __construct( $registry , $db , $infoDb )
  {

    $this->registry = $registry;

    $this->db = $db;

    $this->infoDb = $infoDb;

  }//end public function __construct */

  /**
   *
   * Enter description here...
   * @return unknown_type
   */
  public function sync()
  {

    foreach( $this->registry->entities as $entity )
    {
      $entityName = trim($entity['name']);

    }

  }//end public function sync */


} // end class LibSyncDb
