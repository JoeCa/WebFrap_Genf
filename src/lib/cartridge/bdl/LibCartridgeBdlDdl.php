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
abstract class LibCartridgeBdlDdl
  extends LibCartridgeBdlEntity
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @var string
   */
  protected $type       = null;

  /**
   * owner of the table
   * @var string
   */
  protected $owner      = null;

  /**
   * the database schema name
   * @var string
   */
  protected $schema     = null;

  /**
   * The Name of the DBMS
   * @var String
   */
  protected $dbmsName   = null;

  /**
   * should the dbstructur use one single sequence or one sequence
   * for every entity
   * @var boolean
   */
  public $useOid        = false;

  /**
   * @var string
   */
  protected $appendDump = null;

  /**
   *
   * @var array
   */
  protected $tables       = array();

  /**
   * @var array
   */
  protected $parsedTables = array();

  /**
   *
   * @var unknown_type
   */
  protected $multiple = array();

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $path
   */
  public function appendDump( $path )
  {

    $this->appendDump = $path;

  }//end public function appendDump */

  /**
   * @param string $owner
   */
  public function setOwner( $owner )
  {
    $this->owner = $owner;
  }//end public function setOwner */

  /**
   * @param string $schema
   */
  public function setSchema( $schema )
  {
    $this->schema = $schema;
  }//end public function setSchema */


}// end abstract class LibCartridgeBdlDdl
