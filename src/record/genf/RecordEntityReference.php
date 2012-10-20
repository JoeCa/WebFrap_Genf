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
 *
 */
class RecordEntityReference
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * the name of the mod in CamelCase
   *
   * @var string
   */
  public $modName = null;

  /**
   * the name of the module in lowercase
   *
   * @var string
   */
  public $lowModName = null;

  /**
   * the full modul name in camel Case
   *
   * @var string
   */
  public $entityName = null;

  /**
   * the full modul name in camel Case
   *
   * @var string
   */
  public $entityLabel = null;

  /**
   * the full modul name in camel Case
   *
   * @var string
   */
  public $entityText = null;

  /**
   * the full modul name in lowercase
   *
   * @var string
   */
  public $lowEntityName = null;

  /**
   * name of the table
   *
   * @var string
   */
  public $tableName = null;

  /**
   * the name of the mex in CamelCase
   *
   * @var string
   */
  public $mexName = null;

  /**
   * the name of the mex in CamelCase
   *
   * @var string
   */
  public $mexPath = null;

  /**
   * the name of the mex in lowercase
   *
   * @var string
   */
  public $lowMexName = null;

  /**
   * the name of the mex in lowercase
   *
   * @var string
   */
  public $i18nKey = null;

  /**
   * the name of the mex in lowercase
   *
   * @var string
   */
  public $entityPath = null;

  /**
   *
   * @var array
   */
  public $embededTree = array();

  /**
   *
   * @var array
   */
  public $embededDependencies = array();

////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Enter description here...
   *
   * @param string $name
   * @return unknown
   */
  public function getReference($name)
  {

    if( isset($this->embededTree[trim($name)])  )
      return  $this->embededTree[trim($name)][0];

    Log::warn(__file__,__line__,'requested nonexisting reference: '.$name.' in '.Debug::getCallerPosition() );

    return null;

  }//end public function getReference

  public function getKeyReferences($name)
  {

    if( isset($this->embededTree[trim($name)])  )
      return  $this->embededTree[trim($name)];

    Log::warn(__file__,__line__,'requested nonexisting reference: '.$name.' in '.Debug::getCallerPosition() );

    return null;

  }//end public function getKeyReferences

}//end class RecordEntityReference

