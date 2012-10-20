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
abstract class LibCartridgeBdlShare
  extends LibCartridge
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  
  /**
   * the parser tree
   *
   * @var LibGenfTreeRootShare
   */
  protected $root           = null;
  
  /**
   * @var string
   */
  protected $nodeType     = 'Share';


} // end abstract class LibCartridgeBdlShare
