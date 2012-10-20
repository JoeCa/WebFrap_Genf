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
 *
 * @package WebFrap
 * @subpackage GenF
 */
class Genf_Module
  extends Module
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Die Momentane Version von Genf
   * @var int
   */
  const GENF_VERSION                = 3;


////////////////////////////////////////////////////////////////////////////////
// Logic
////////////////////////////////////////////////////////////////////////////////

  /**
   * Main function
   *
   * @return void
   */
  public function main( )
  {

    $view = $this->getTplEngine();

    if( $view->type == 'html' )
    {
      $view->setTitle('WebFrap GenF');

      $menu = $this->view->newItem ( 'mainMenu' ,'MenuSimplebar'  );
      $menu->setData( DaoMenu::get('gateway/navigation') );
    }

    $this->runController( );

  }//end public function main */


}// end class Genf_Module

