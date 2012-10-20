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
class LibBdlRegistry
  extends LibParserRegistry
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @var LibGenfBuild
   */
  public $builder   = null;

  /**
   * @var LibGenfTreeNode
   */
  public $node      = null;

  /**
   * @var string
   */
  public $context   = null;

  /**
   * @var LibGenfEnvManagement
   */
  public $env       = null;

} // end class LibBdlRegistry



