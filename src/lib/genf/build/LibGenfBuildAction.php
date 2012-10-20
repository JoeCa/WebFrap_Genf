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
class LibGenfBuildAction
  extends LibGenfBuildAbstract
{
////////////////////////////////////////////////////////////////////////////////
// Methode
////////////////////////////////////////////////////////////////////////////////

  /**
   * Enter description here...
   *
   * @return SimpleXmlElement
   */
  public function build( $cartridge )
  {

    if( !$class = $this->getCartridgeClass( $cartridge ) )
    {
      Error::addError( 'Requested nonexisting Action Cartridge: '.$cartridge['class'] );
      return null;
    }
    
    $start = time();
    
    //Message::addMessage( 'Using Action Cartridge: '.$class );
    //Log::info( 'Using Action Cartridge: '.$class.' '.date('H:i:s') );

    $parser = new $class( $this->builder, $cartridge );
    $parser->setOutputFolder( $this->outputPath );
    $parser->render();
    //$parser->write();
    
    Log::info( 'Build Action Cartridge: '.$class.' in '. (time()-$start).' seconds' );

    return $parser;

  }//end public function build */

} // end class LibGenfBuildAction

