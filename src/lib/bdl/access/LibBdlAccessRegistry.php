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
class LibBdlAccessRegistry
  extends LibParserRegistry
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * the parsed code
   * @var string
   */
  public $name          = null;

  /**
   *
   * @var string
   */
  public $context       = null;

  /**
   *
   * @var LibGenfTreeNodeManagement
   */
  public $node          = null;

////////////////////////////////////////////////////////////////////////////////
// method
////////////////////////////////////////////////////////////////////////////////

  /**
   * init method
   */
  protected function init()
  {

    $this->tokenParserClass = array
    (
      $this->e_view     => 'ElementView',
    );

  }//end public function init

} // end class LibBdlFilterRegistry







