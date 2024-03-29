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
class LibBdlParser
  extends LibParser
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibBdlLexer
   */
  protected $lexer      = null;

  /**
   *
   * @var LibBdlSst
   */
  protected $sst      = null;

  /**
   * the parsed code
   * @var string
   */
  protected $parsed     = null;

////////////////////////////////////////////////////////////////////////////////
// init methodes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param LibGenfBuild $builder
   */
  public function __construct( $builder )
  {

    $this->loadLexer();
    $this->loadSst();
    $this->loadRegistry();

    $this->registry->builder = $builder;

  }//end public function __construct */


  /**
   * load the lexer
   */
  public function loadLexer()
  {

  }//end public function loadLexer */

  /**
   *
   */
  public function loadRegistry()
  {

  }//end public function loadRegistry */

  /**
   *
   */
  public function loadSst()
  {

  }//end public function loadSst */

  /**
   * @param LibGenfTreeNode $env
   */
  public function setEnv( $env )
  {
    $this->registry->env      = $env;
    $this->registry->node     = $env->management;
    $this->registry->context  = $env->context;

  }//end public function setEnv */

  /**
   *
   * @param string $context
   */
  public function setContext( $context )
  {
    $this->registry->context = $context;
  }//end public function setContext

  /**
   *
   * @param LibGenfBuild $kb
   */
  public function injectKnowledge( $kb )
  {

    $this->registry->build = $kb;

  }//end public function injectKnowledge */

  /**
   *
   */
  public function cleanWorkspace()
  {

    $this->registry->name     = null;
    $this->registry->context  = null;
    $this->registry->node     = null;

  }//end public function clean */

  /**
   *
   */
  public function clean()
  {

    $this->registry->name     = null;
    $this->registry->context  = null;
    $this->registry->node     = null;

  }//end public function clean */

////////////////////////////////////////////////////////////////////////////////
// parser method
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param string $rawCode
   */
  public function parse( $rawCode ){ return ''; }


} // end class LibBdlParser







