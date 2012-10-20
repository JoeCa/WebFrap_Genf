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
class LibGenfInterpreterParser
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var LibGenfInterpreter
   */
  protected $interpreter     = null;

  /**
   * @var LibGenfBuild
   */
  protected $builder         = null;


////////////////////////////////////////////////////////////////////////////////
// constructor
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param LibGenfInterpreter $interpreter
   * @param LibGenfBuild $builder
   */
  public function __construct( $interpreter, $builder  )
  {

    $this->interpreter  = $interpreter;
    $this->builder      = $builder;

    $this->init();

  }//end public function __construct */

  /**
   *
   */
  public function init()
  {

  }//end public function init */


}//end class LibGenfInterpreterParser
