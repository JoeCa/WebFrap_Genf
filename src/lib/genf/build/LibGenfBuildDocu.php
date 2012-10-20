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
class LibGenfBuildDocu
  extends LibGenfBuildAbstract
{
////////////////////////////////////////////////////////////////////////////////
// Methode
////////////////////////////////////////////////////////////////////////////////

  /**
   * Enter description here...
   *
   * @return void
   */
  public function build( $cartridge )
  {

    if(!$class = $this->getCartridgeClass( $cartridge ))
    {
      Error::addError('Requested nonexisting Cartridge: '.$cartridge['class'] );
      return null;
    }

    //Message::addMessage('Using Docu Cartridge: '.$class);
    //Log::info( 'Using Docu Cartridge: '.$class.' '.date('H:i:s') );
    $start = time();

    $parser = new $class( $this->builder, $cartridge );
    $parser->setOutputFolder( $this->outputPath );
    $parser->render();
    
    Log::info( 'Build Docu Cartridge: '.$class.' in '. (time()-$start).' seconds' );

    return $parser;


  } // end public function build */

} // end class LibGenfBuildDocu

