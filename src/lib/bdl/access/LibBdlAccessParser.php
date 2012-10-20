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
class LibBdlAccessParser
  extends LibBdlParser
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibBdlAccessLexer
   */
  protected $lexer    = null;

  /**
   *
   * @var LibBdlAccessSst
   */
  protected $sst      = null;


////////////////////////////////////////////////////////////////////////////////
// init methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * load the lexer
   */
  public function loadSst()
  {

    $this->sst = new LibBdlAccessSst( );

  }//end public function loadSst */

  /**
   *
   */
  public function loadRegistry()
  {

    $this->registry = new LibBdlAccessRegistry(  'LibBdlAccess' , $this->lexer );

  }//end public function loadRegistry */

////////////////////////////////////////////////////////////////////////////////
// parser method
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param LibBdlAccessLexer $lexer
   */
  public function createSst( $lexer )
  {

  }//end public function createSst */

  /**
   * @param string $rawCode
   */
  public function compile( $rawCode  )
  {


    $this->lexer->split( $rawCode );

    $parser       = $this->registry->getSubParser('Container');
    $this->sst = $parser->parse( );

    // reset context if was given
    $this->registry->context = null;

    return $this->parsed;

  }//end public function compile */



} // end class LibBdlAccessParser

