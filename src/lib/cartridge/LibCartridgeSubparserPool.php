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
abstract class LibCartridgeSubparserPool
  extends LibCartridge
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var string
   */
  protected $subParserType  = null;

  /**
   * @var array
   */
  protected $subParser      = array();

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @param string $parserName
   * @return
   */
  public function getSubParser( $parserName )
  {

    $type = $this->parserType.ucfirst($parserName);

    if( isset( $this->subParser[$type] ) )
      return $this->subParser[$type];

    if(!$className = $this->builder->getCartridgeClass( $type ))
    {
      return null;
    }

    $this->subParser[$type] = new $className();

    return $this->subParser[$type];

  }//end public function getSubParser */


} // end abstract class LibCartridgeSubparser
