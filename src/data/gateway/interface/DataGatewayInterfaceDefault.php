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
class DataGatewayInterfaceDefault
  extends TDataContainer
{

  /**
   * (non-PHPdoc)
   * @see src/t/TDataContainer::get()
   */
  public function get()
  {
    return array
    (
      'Admin',
      'Ajax',
      'Css',
      'Document',
      'File',
      'Graph',
      'Html',
      'Image',
      'Index',
      'Js',
      'Json',
      'Login',
      'Setup',
      'Subwindow',
      'Thumb',
      'Window',
    );
  }//end public function get


} // end class DataGatewayInterfaceDefault
