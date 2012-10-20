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
class LibBdlFilter
  extends LibBdlSubParser
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibBdlFilterRegistry
   */
  protected $registry   = null;

  /**
   *
   * @var LibBdlFilterLexer
   */
  protected $lexer      = null;
  
  /**
   * Der Node dessen Code Analysiert wurde
   * @var LibGenftreeNode 
   */
  protected $bdlNode      = null;


}//end class LibBdlFilter

