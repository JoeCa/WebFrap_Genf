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
class LibGenfBuildDoku
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

    $class = $this->getClassName( $cartridge );

    //Message::addMessage('Using Doku Cartridge: '.$class);
    //Log::info( 'Using Doku Cartridge: '.$class.' '.date('H:i:s') );
    $start = time();

    $parser = new $class( $cartridge );

    $parser->loadSqlParsers( $cartridge );

    if( isset($cartridge->path) )
    {
      $path = $this->replaceVars((string)$cartridge->path);
      $parser->setOutputFolder($path);
    }
    else
    {
      $parser->setOutputFolder( $this->outputPath );
    }

    $parser->parse();
    $parser->write();

    Log::info( 'Build Doku Cartridge: '.$class.' in '. (time()-$start).' seconds' );
    
    return $parser;


  } // end public function build */

} // end class LibGenfBuildDoku

