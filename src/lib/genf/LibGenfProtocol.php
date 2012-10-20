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
 * Eine Name Lib für die Namings
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfProtocol
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var array
   */
  public $createdTemplate = array();

  /**
   * @var array
   */
  public $createdClass = array();

  /**
   * @var array
   */
  public $missingCartridge = array();

  /**
   * @var array
   */
  public $foundCartridge = array();

  /**
   * @var array
   */
  public $missingGenerator = array();

  /**
   * @var array
   */
  public $foundGenerator = array();

  /**
   * @var array
   */
  public $logicError = array();

  /**
   * @var LibTemplate
   */
  public $view = null;

/*//////////////////////////////////////////////////////////////////////////////
// methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @param string $name
   */
  public function createdTemplate( $name )
  {
    $this->createdTemplate[$name] = $name;
  }

  /**
   *
   * @param string $name
   */
  public function createdClass( $name )
  {
    $this->createdClass[$name] = $name;
  }

  /**
   *
   * @param string $name
   */
  public function missingCartridge( $name )
  {
    $this->missingCartridge[$name] = $name;
  }

  /**
   *
   * @param string $name
   */
  public function foundCartridge( $name )
  {
    $this->foundCartridge[$name] = $name;
  }

  /**
   *
   * @param string $name
   */
  public function missingGenerator( $name )
  {

    if(DEBUG)
      Debug::console( "missing Generator $name" );

    $this->missingGenerator[$name] = $name;
  }

  /**
   *
   * @param string $name
   */
  public function foundGenerator( $name )
  {
    $this->foundGenerator[$name] = $name;
  }

  /**
   *
   * @param string $name
   */
  public function logicError( $message )
  {

    if(DEBUG)
      Debug::console( $message );

    $this->logicError[] = $message;

  }//end public function logicError */

  /**
   *
   * Enter description here ...
   * @param string $content
   */
  public function writeLn( $content )
  {

    if($this->view)
      $this->view->writeLn( $content );

  }//end public function writeLn */

  /**
   *
   * Enter description here ...
   * @param string $content
   */
  public function logLine( $content )
  {

    if($this->view && Log::$levelDebug )
      $this->view->writeLn( $content );

  }//end public function writeLn */


}//end class LibGenfName

