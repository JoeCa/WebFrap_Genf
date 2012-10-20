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
class DataGatewayJavascriptDefault
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
      // extend javascript
      array('js_src/ext/ext.js.js', 'PATH_WGT'),

      // jquery and jquery extensions
      array('js_src/jquery.js', 'PATH_WGT'),
      array('js_src/ext/ext.jquery.js', 'PATH_WGT'),

      // wgt base class
      array('js_src/wgt.js', 'PATH_WGT'),

      // add i18n data
      array('js_src/wgt/wgt.i18n.js', 'PATH_WGT'),
      array('js_src/i18n/i18n.de.js', 'PATH_WGT'),

      // jquery plugins
      array('js_src/jquery/jquery.sizes.js', 'PATH_WGT'),
      array('js_src/jquery/jquery.toaster.js', 'PATH_WGT'),
      array('js_src/jquery/jquery.dropmenu.js', 'PATH_WGT'),
      array('js_src/jquery/jquery.fullcalendar.js', 'PATH_WGT'),
      array('js_src/jquery/jquery.treeview.js', 'PATH_WGT'),
      array('js_src/jquery/jquery.treeTable.js', 'PATH_WGT'),
      array('js_src/jquery/jquery.tooltip.js', 'PATH_WGT'),
      array('js_src/jquery/jquery.monthpicker.js', 'PATH_WGT'),

      // add jquery ui components
      array('js_src/jquery/ui/ui.core.js', 'PATH_WGT'),
      array('js_src/jquery/ui/ui.draggable.js', 'PATH_WGT'),
      array('js_src/jquery/ui/ui.droppable.js', 'PATH_WGT'),
      array('js_src/jquery/ui/ui.dialog.js', 'PATH_WGT'),
      array('js_src/jquery/ui/ui.progressbar.js', 'PATH_WGT'),
      array('js_src/jquery/ui/ui.selectable.js', 'PATH_WGT'),
      array('js_src/jquery/ui/ui.datepicker.js', 'PATH_WGT'),
      array('js_src/jquery/ui/ui.accordion.js', 'PATH_WGT'),

      // add wgt jquery plugins
      array('js_src/jquery/wgt/jquery.wgt.menuselector.js', 'PATH_WGT'),
      array('js_src/jquery/wgt/jquery.wgt.miniMenu.js', 'PATH_WGT'),
      array('js_src/jquery/wgt/jquery.wgt.ajaxfileupload.js', 'PATH_WGT'),

      // window implementation & extensions
      array('js_src/wgt/wgt.window.js', 'PATH_WGT'),
      array('js_src/wgt/window/wgt.window.bookmark.js', 'PATH_WGT'),

      // request handling & extensions
      array('js_src/wgt/wgt.request.js', 'PATH_WGT'),
      array('js_src/wgt/request/wgt.request.req_ajax.js', 'PATH_WGT'),
      array('js_src/wgt/request/wgt.request.req_conf.js', 'PATH_WGT'),
      array('js_src/wgt/request/wgt.request.req_del.js', 'PATH_WGT'),
      array('js_src/wgt/request/wgt.request.req_mainwin.js', 'PATH_WGT'),
      array('js_src/wgt/request/wgt.request.req_page_size.js', 'PATH_WGT'),
      array('js_src/wgt/request/wgt.request.req_page.js', 'PATH_WGT'),
      array('js_src/wgt/request/wgt.request.req_win.js', 'PATH_WGT'),
      array('js_src/wgt/request/wgt.request.req_search.js', 'PATH_WGT'),

      array('js_src/wgt/request/wgt.request.ui_callendar.js', 'PATH_WGT'),
      array('js_src/wgt/request/wgt.request.ui_info.js', 'PATH_WGT'),
      array('js_src/wgt/request/wgt.request.ui_link_info.js', 'PATH_WGT'),
      array('js_src/wgt/request/wgt.request.ui_menu_table.js', 'PATH_WGT'),
      array('js_src/wgt/request/wgt.request.ui_progress.js', 'PATH_WGT'),
      array('js_src/wgt/request/wgt.request.ui_tab.js', 'PATH_WGT'),
      array('js_src/wgt/request/wgt.request.ui_tooltip.js', 'PATH_WGT'),
      array('js_src/wgt/request/wgt.request.ui_accordion.js', 'PATH_WGT'),
      array('js_src/wgt/request/wgt.request.ui_dropmenu.js', 'PATH_WGT'),

      array('js_src/wgt/request/wgt.request.inp_date.js', 'PATH_WGT'),
      array('js_src/wgt/request/wgt.request.inp_date_month.js', 'PATH_WGT'),
      //array('js_src/wgt/request/wgt.request.inp_time.js', 'PATH_WGT'),
      array('js_src/wgt/request/wgt.request.inp_wysiwyg.js', 'PATH_WGT'),

      // desktop
      array('js_src/wgt/wgt.desktop.js', 'PATH_WGT'),

      // add ui
      array('js_src/wgt/wgt.ui.js', 'PATH_WGT'),
      array('js_src/wgt/ui/wgt.ui.activInput.js', 'PATH_WGT'),
      array('js_src/wgt/ui/wgt.ui.table.js', 'PATH_WGT'),
      array('js_src/wgt/ui/wgt.ui.tab.js', 'PATH_WGT'),

      // add init components
      array('js_src/wgt/wgt/wgt.wgt.ini_request.js', 'PATH_WGT'),
      array('js_src/wgt/wgt/wgt.wgt.ini_windowtabs.js', 'PATH_WGT'),
    );
  }//end public function get


} // end class LibGeneratorWbfGatewayBootstrap
