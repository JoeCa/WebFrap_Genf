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
 * @TODO check if this class is needed
 * @package WebFrap
 * @subpackage Genf
 */
class LibCartridgePool
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var String
   */
  protected $refTablePool   = array();

  /**
   * instance for singleton
   *
   * @var LibCartridgePool
   */
  private static $instance  = null;

////////////////////////////////////////////////////////////////////////////////
// Getter and Setter
////////////////////////////////////////////////////////////////////////////////

  /**
   * get instance for singleton
   *
   * @return LibCartridgePool
   */
  public static function getInstance()
  {
    if( is_null( self::$instance ) )
    {
      self::$instance = new LibCartridgePool();
    }

    return self::$instance;

  }//end public static function getInstance */

  /**
   * add a reference table to create a many to many connetion table
   *
   * @param SimpleXMLElement $table
   */
  public function addRefTable( $tableName , $table )
  {
    $this->refTablePool[$tableName][] = $table;
  }//end public function addRefTable */

  /**
   * getter for $refTablePool
   * @param string $tableName
   * @return array
   */
  public function getRefTables( $tableName )
  {

    if( $tableName )
    {
      return isset($this->refTablePool[$tableName])?$this->refTablePool[$tableName]:array();
    }
    else
    {
      return $this->refTablePool;
    }

  }//end public function getRefTables */

}//end class LibCartridgePool
