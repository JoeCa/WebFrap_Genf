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
class LibGenfBuildSkeleton
  extends LibGenfBuildAbstract
{
////////////////////////////////////////////////////////////////////////////////
// Methode
////////////////////////////////////////////////////////////////////////////////

  /**
   * 
   * @return SimpleXmlElement Catridgenode aus der Project Bdl
   */
  public function build( $cartridge )
  {

    if( !$class = $this->getCartridgeClass( $cartridge ) )
    {
      Error::addError('Requested nonexisting Skeleton Cartridge: '.$cartridge['class'] );
      return null;
    }

    //Message::addMessage('Using Skeleton Cartridge: '.$class);
    //Log::info( 'Using Skeleton Cartridge: '.$class.' '.date('H:i:s') );
    $start = time();

    $generator = new $class( $this->builder, $cartridge );
    $generator->setOutputFolder( $this->outputPath );
    $generator->render();
    
    
    Log::info( 'Build Skeleton Cartridge: '.$class.' in '. (time()-$start).' seconds' );

    return $generator;

  }//end public function build */

} // end class LibGenfBuildSkeleton

