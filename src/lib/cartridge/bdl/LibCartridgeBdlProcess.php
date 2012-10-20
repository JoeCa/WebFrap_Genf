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
abstract class LibCartridgeBdlProcess
  extends LibCartridge
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var string
   */
  protected $nodeType     = 'Process';
  
  
  /**
   * @param LibGenfTreeNodeProcess $process
   * @return LibGenfEnvProcess
   */
  public function createEnvironment( $process )
  {

    $environment = new LibGenfEnvProcess( $this->builder, $process );

    return $environment;

  }//end public function createEnvironment */


} // end abstract class LibCartridgeBdlProcess
