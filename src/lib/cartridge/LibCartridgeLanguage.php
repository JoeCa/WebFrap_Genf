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
 * @subpackage Genf
 */
abstract class LibCartridgeLanguage
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
  *
  * @var LibGenfTreeRootManagement
  */
  protected $node = null ;

  /**
   *
   * @var LibCartridgeI18n
   */
  protected $i18nPool = null ;

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

 /**
  * @param LibGenfTreeRootManagement $node
  */
  public function __construct( $node )
  {

    $this->builder    = $node;
    $this->node       = $node;
    $this->i18nPool   = LibCartridgeI18n::getInstance();

  }//end public function __construct */

////////////////////////////////////////////////////////////////////////////////
// some logic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $key
   * @return string
   */
  public function cleanKey( $key )
  {
    return str_replace("'", "\'", $key);
  }//end public function cleanKey */

////////////////////////////////////////////////////////////////////////////////
// abstract Methodes
////////////////////////////////////////////////////////////////////////////////

  
    /**
   * @param LibGenfEnvManagement $env
   * @return [lang=>[repoKey=>[key=>value]]]
   */
  public function render( $env )
  {
    return array();
  }//end public function render */
  
  
  /**
   * @param LibGenfEnvManagement $env
   * @return [lang=>[repoKey=>[key=>value]]]
   */
  public function renderCreateForm( $env )
  {
    return array();
  }//end public function renderCreateForm */
  
  
  /**
   * @param LibGenfEnvManagement $env
   * @return [lang=>[repoKey=>[key=>value]]]
   */
  public function renderEditForm( $env )
  {
    return array();
  }//end public function renderEditForm */
  
  
  /**
   * @param LibGenfEnvManagement $env
   * @return [lang=>[repoKey=>[key=>value]]]
   */
  public function renderShowForm( $env )
  {
    return array();
  }//end public function renderShowForm */
  
  
  /**
   * @param LibGenfEnvManagement $env
   * @return [lang=>[repoKey=>[key=>value]]]
   */
  public function renderTableList( $env )
  {
    return array();
  }//end public function renderTableList */
  
  
  /**
   * @param LibGenfEnvManagement $env
   * @return [lang=>[repoKey=>[key=>value]]]
   */
  public function renderTreetableList( $env )
  {
    return array();
  }//end public function renderTreetableList */

  /**
   *
   * @param string $name
   * @return string
   */
  //public abstract function build( $name );


}//end abstract class LibCartridgeLanguage
