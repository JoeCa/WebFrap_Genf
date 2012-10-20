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
class LibGenfBuildBackup
  extends LibGenfBuildAbstract
{
////////////////////////////////////////////////////////////////////////////////
// Methode
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param LimpleXmlElement $cartridge
   *
   * @return LibCartridgeBdlService
   */
  public function build( $cartridge )
  {

    if( !$class = $this->getCartridgeClass( $cartridge ) )
    {
      Error::addError( 'Requested nonexisting Backup Cartridge: '.$cartridge['class'] );
      return null;
    }

    //Message::addMessage( 'Using Backup Cartridge: '.$class );
    //Log::info( 'Using Backup Cartridge: '.$class.' '.date('H:i:s') );
    $start = time();

    $parser = new $class( $this->builder, $cartridge );
    $parser->setOutputFolder( $this->outputPath );
    $parser->render();
    
    Log::info( 'Build Backup Cartridge: '.$class.' in '. (time()-$start).' seconds' );

    return $parser;

  }//end public function build */

} // end class LibGenfBuildBackup

