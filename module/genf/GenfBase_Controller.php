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
class GenfBase_Controller
  extends Controller
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var unknown_type
   */
  protected $callAble = array
  (
    'start',
    'menu',
    'cleangeneratedcode',
    'deploygeneratedcode'
  );

  /**
   * ignore accesslist everything is free accessable
   * @var boolean
   */
  protected $fullAccess = true;

////////////////////////////////////////////////////////////////////////////////
//Logic: Meta Model
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return void
   */
  public function start( )
  {

    $this->view->setTemplate( 'genf/start' );

    $modMenu = $this->view->newItem( 'modMenu', 'MenuFolder' );
    $modMenu->setData( DaoMenu::get('genf/modmenu') );


  }//end public function start */


  /**
   * @return void
   */
  public function menu( )
  {

    if( $this->view->isType( View::SUBWINDOW ) )
    {
      $view = $this->view->newWindow('WebfrapMainMenu', 'Default');
      $view->setTitle('Maintenance Menu');
    }
    else
    {
      $view = $this->view;
    }

    $view->setTemplate( 'webfrap/menu/modmenu' );

    $menuName = $this->request->get('menu',Validator::CNAME);

    if( !$menuName )
      $menuName = 'default';

    $modMenu = $view->newItem( 'modMenu', 'MenuFolder' );
    $modMenu->setData( DaoFoldermenu::get( 'daidalos/'.$menuName, true ) );

  }//end public function menu */

  /**
   * Enter description here...
   *
   */
  public function cleanGeneratedCode()
  {

    // now we just to have to clean the code folder :-)
    $toDel = array
    (
      PATH_GW.'data/code_gen_cache/'
    );

    foreach( $toDel as $folder )
    {
      if(SFilesystem::cleanFolder($folder))
      {
        Message::addMessage(I18n::s('wbf.msg.folderSuccessfullyDeleted',array($folder)));
      }
      else
      {
        Message::addError('wbf.msg.folderFailedToDelete',array($folder));
      }
    }

    if( $this->view->isType( View::SUBWINDOW ) || $this->view->isType( View::AJAX ) )
    {
      View::$sendBody = false;
    }
    else
    {
      $this->modMenu();
    }

  }//end public function cleanGeneratedCode */

  /**
   * Enter description here...
   *
   */
  public function deployGeneratedCode()
  {

    $folder = new LibFilesystemFolder(PATH_GW.'data/code_gen_cache/');

    $view = View::getActive();


    foreach( $folder->getFolders() as $path )
    {
      if(SFilesystem::copy($path,PATH_GW))
      {
        Message::addMessage(I18n::s('wbf.msg.successfullyCopiedFolder',array($path,PATH_GW)));
      }
      else
      {
        Message::addError('wbf.msg.failedToCopyFolder',array($path,PATH_GW));
      }
    }

    if( $view->isType( View::SUBWINDOW ) || $view->isType( View::AJAX ) )
    {
      View::$sendBody = false;
    }
    else
    {
      $this->modMenu();
    }

  }//end public function deployGeneratedCode */

} // end class GenfBase_Controller

