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
abstract class LibCartridgeBdlMessage
  extends LibCartridge
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var string
   */
  protected $nodeType     = 'Message';
  
  
  /**
   * @param LibGenfTreeNodeMessage $message
   * @return LibGenfEnvMessage
   */
  public function createEnvironment( $message )
  {

    $environment = new LibGenfEnvMessage( $this->builder, $message );

    return $environment;

  }//end public function createEnvironment */


} // end abstract class LibCartridgeBdlMessage
