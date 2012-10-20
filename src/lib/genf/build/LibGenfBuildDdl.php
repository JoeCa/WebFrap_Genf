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
class LibGenfBuildDdl
  extends LibGenfBuildAbstract
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   * default name for the pk property
   * @var string
   */
  public $rowidKey = 'rowid';

////////////////////////////////////////////////////////////////////////////////
// methode
////////////////////////////////////////////////////////////////////////////////

  /**
   * Enter description here...
   *
   * @return SimpleXmlElement
   */
  public function build( $cartridge )
  {

    if(!$class = $this->getCartridgeClass( $cartridge ))
    {
      Error::addError('Requested nonexisting Ddl Cartridge: '.$cartridge['class'] );
      return null;
    }

    //Message::addMessage('Using DDL Cartridge: '.$class);
    //Log::info( 'Using DDL Cartridge: '.$class.' '.date('H:i:s') );
    $start = time();

    $parser = new $class( $this->builder, $cartridge );

    if( isset($cartridge->appendDump) )
      $parser->appendDump( $this->builder->replaceVars(trim($cartridge->appendDump)) );

    if( isset($cartridge->useOid) && trim( $cartridge->useOid ) == 'false'  )
    {
      $parser->useOid = false;
    }
    else // just to make shure if the default value changes maybe, should never happen but who knows...
    {
      $parser->useOid = true;
    }

    $parser->setOwner( trim($cartridge->owner) );
    $parser->setSchema( trim($cartridge->schema) );

    $parser->setOutputFolder( $this->outputPath );
    $parser->parse();
    $parser->write();
    
    Log::info( 'Build DDL Cartridge: '.$class.' in '. (time()-$start).' seconds' );

    return $parser;


  } // end public function build */

} // end class LibGenfBuildEntity

