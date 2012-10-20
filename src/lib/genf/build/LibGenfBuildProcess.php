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
class LibGenfBuildProcess
  extends LibGenfBuildAbstract
{
////////////////////////////////////////////////////////////////////////////////
// Methode
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $cartridge
   * @return SimpleXmlElement
   */
  public function build( $cartridge )
  {

    if(!$class = $this->getCartridgeClass( $cartridge ))
    {
      Error::addError('Requested nonexisting Cartridge: '.$cartridge['class'] );
      return null;
    }

    //Message::addMessage('Using Process Cartridge: '.$class);
    //Log::info( 'Using Process Cartridge: '.$class.' '.date('H:i:s') );
    $start = time();

    $cartridgeObj = new $class( $this->builder, $cartridge );
    $cartridgeObj->setOutputFolder( $this->outputPath );
    $cartridgeObj->render();
    //$parser->write();
    
    Log::info( 'Build Process Cartridge: '.$class.' in '. (time()-$start).' seconds' );

    return $cartridgeObj;

  }//end public function build */

} // end class LibGenfBuildProcess

