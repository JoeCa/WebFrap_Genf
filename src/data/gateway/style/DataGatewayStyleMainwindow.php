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
class DataGatewayStyleMainwindow
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
      array('layout/default/layout/core.css','PATH_WGT'),
      array('layout/default/wgt/wgt.core.css','PATH_WGT'),
      array('layout/default/wgt/wgt.desktop.css','PATH_WGT'),
      array('layout/default/wgt/wgt.tab.css','PATH_WGT'),
      array('layout/default/wgt/wgt.table.css','PATH_WGT'),
      array('layout/default/wgt/wgt.blocklist.css','PATH_WGT'),
      array('layout/default/wgt/wgt.form.css','PATH_WGT'),
      array('layout/default/wgt/wgt.menu.css','PATH_WGT'),
      array('layout/default/wgt/wgt.tree.css','PATH_WGT'),
      array('layout/default/wgt/wgt.window.css','PATH_WGT'),
      array('layout/default/wgt/wgt.list.css','PATH_WGT'),
      array('layout/default/wgt/wgt.menuselector.css','PATH_WGT'),
      array('layout/default/jquery_ui/ui.core.css','PATH_WGT'),
      array('layout/default/jquery_ui/ui.accordion.css','PATH_WGT'),
      array('layout/default/jquery_ui/ui.datepicker.css','PATH_WGT'),
      array('layout/default/jquery_ui/ui.dialog.css','PATH_WGT'),
      array('layout/default/jquery_ui/ui.progressbar.css','PATH_WGT'),
      array('layout/default/jquery_ui/ui.resizable.css','PATH_WGT'),
      array('layout/default/jquery_ui/ui.slider.css','PATH_WGT'),
      array('layout/default/jquery_ui/ui.tabs.css','PATH_WGT'),
      array('layout/default/jquery_ui/jquery-ui.css','PATH_WGT'),
      array('layout/default/jquery/jquery.autocomplete.css','PATH_WGT'),
      array('layout/default/jquery/jquery.tooltip.css','PATH_WGT'),
      array('layout/default/jquery/jquery.rating.css','PATH_WGT'),
      array('layout/default/jquery/jquery.dropmenu.css','PATH_WGT'),
      array('layout/default/jquery/jquery.toaster.css','PATH_WGT'),
      array('layout/default/jquery/jquery.fullcalendar.css','PATH_WGT'),
      array('layout/default/jquery/jquery.treeview.css','PATH_WGT'),
      array('layout/default/jquery/jquery.treeTable.css','PATH_WGT'),
    );
  }//end public function get



} // end class LibGeneratorWbfGatewayBootstrap
