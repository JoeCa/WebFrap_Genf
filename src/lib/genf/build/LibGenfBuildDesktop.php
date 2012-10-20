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
class LibGenfBuildDesktop
  extends LibGenfBuildAbstract
{
////////////////////////////////////////////////////////////////////////////////
// Methode
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param SimpleXmlElement $cartridge
   * @return LibCartridge
   */
  public function build( $cartridge )
  {

    if( !$class = $this->getCartridgeClass( $cartridge ) )
    {
      Error::addError( 'Requested nonexisting Desktop Cartridge: '.$cartridge['class'] );
      return null;
    }

    //Message::addMessage( 'Using Desktop Cartridge: '.$class );
    //Log::info( 'Using Desktop Cartridge: '.$class.' '.date('H:i:s') );
    $start = time();

    $parser = new $class( $this->builder, $cartridge );
    $parser->setOutputFolder( $this->outputPath );
    $parser->render();
    //$parser->write();
    
    Log::info( 'Build Desktop Cartridge: '.$class.' in '. (time()-$start).' seconds' );

    return $parser;

  }//end public function build */

} // end class LibGenfBuildDesktop

