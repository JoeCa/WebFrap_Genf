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
class LibBdlCodeParser
  extends LibBdlParser
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibBdlLexer
   */
  protected $lexer        = null;


  /**
   * the parsed code
   * @var string
   */
  protected $parsed       = null;

////////////////////////////////////////////////////////////////////////////////
// init methodes
////////////////////////////////////////////////////////////////////////////////


  /**
   * load the lexer
   */
  public function loadLexer()
  {

    $this->lexer = new LibBdlLexer( );

  }//end public function loadLexer */

  /**
   *
   */
  public function loadRegistry()
  {
    $this->registry = new LibBdlRegistry(  'LibBdl' , $this->lexer );

  }//end public function loadRegistry */

////////////////////////////////////////////////////////////////////////////////
// parser method
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param string $rawCode
   */
  public function parse( $rawCode )
  {

    $this->lexer->split( $rawCode );

    $parser       = $this->registry->getSubParser('Code');
    $this->parsed = $parser->parse();

    // reset context if was given
    $this->registry->context = null;

    return $this->parsed;

  }//end public function parse */

  /**
   * @param string $rawCode
   */
  public function split( $rawCode )
  {

    $this->lexer->split( $rawCode );

  }//end public function split */


  /**
   * @param LibGenfEnvManagement
   */
  public function setEnv( $env )
  {

    $this->clean();

    $this->registry->env      = $env;

    $this->registry->name     = $env->name;
    $this->registry->context  = $env->context;
    $this->registry->node     = $env->management;

  }//end public function setEnv */

  public function setName( $name )
  {
    $this->registry->name = $name;
  }//end public function setName */

  public function setContext( $context )
  {
    $this->registry->context = $context;
  }//end public function setContext */

  public function setNode( $node )
  {
    $this->registry->node = $node;
  }//end public function setNode */

  /**
   *
   */
  public function clean()
  {
    $this->registry->name     = null;
    $this->registry->context  = null;
    $this->registry->node     = null;
  }//end public function clean */

} // end class LibBdlCodeParser







