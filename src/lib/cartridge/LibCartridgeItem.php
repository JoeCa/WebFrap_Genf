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
abstract class LibCartridgeItem
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * the xml
   *
   * @var var simpleXmlElement
   */
  protected $registry = null;

////////////////////////////////////////////////////////////////////////////////
// magic methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * constructor for mal object parsers
   * @param string  $name the entity name
   * @param simpleXmlElement $xml the mal xml object
   */
  public function __construct( )
  {
    $this->registry   = LibCartridgeRegistry::getInstance();

  }//end public function __construct */


////////////////////////////////////////////////////////////////////////////////
// Parser
////////////////////////////////////////////////////////////////////////////////

  /**
   * replace all mal constants in a string
   *
   * @param string $code
   * @return string
   */
  protected function replaceConstants( $code )
  {

    $constants = array
    (
      '{LANG}'      => 'I18n::getId()',
      '{USER}'      => 'User::getActive()->getId();',
      '{TIME}'      => 'Controller::getTime()',
      '{TIMESTAMP}' => 'Controller::getTimestamp()',
      '{DATE}'      => 'Controller::getDate()',
      '{NL}'        => 'NL',
    );

    $code = str_replace( array_keys($constants) , array_values($constants) , $code   );
    return $code ;

  }//end protected function replaceConstants */

  /**
   *
   * @param $data
   * @return unknown_type
   */
  protected function replaceThis( $data  )
  {

    $tmp = explode('.',$data,2);

    if( count($tmp) != 2 )
     return $data;

    if( !strtolower($tmp[0]) == 'this')
     return $data;

    return LibCartridgeRegistry::$tableName.'.'.$tmp[1];

  }//end protected function replaceThis */

  /**
   * parse the mal code
   * @param simpleXmlElement
   * @return string
   */
  protected abstract function parseMal( $xml );

  /**
   * parser method
   * @return string
   */
  public abstract function parse();


} // end abstract class LibCartridgeItem
