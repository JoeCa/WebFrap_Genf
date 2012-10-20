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
class LibGenfTreeNodeGateway
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * Enter description here ...
   * @var unknown_type
   */
  protected $path = null;

  /**
   *
   * @return string the name of the entity
   */
  public function name()
  {
    return trim( $this->node['name'] );
  }//end public function name

  /**
   *
   * @return string the name of the entity
   */
  public function getKey()
  {
    return trim( $this->node['key'] );
  }//end public function getKey */

  /**
   * @return string
   */
  public function getPath()
  {

    if(!$this->path)
      $this->path = $this->builder->replaceVars( $this->node->path ).'/';

    return $this->path;

  }//end public function getPath */

  public function getConstants()
  {

    /*
      <constants>
        <debug>true</debug>
        <debug_console>true</debug_console>
        <buffer_output>false</buffer_output>
        <wbf_no_login>true</wbf_no_login>
        <wbf_no_acl>true</wbf_no_acl>

        <wbf_controller>Apachemod</wbf_controller>
        <wbf_db_key>rowid</wbf_db_key>
      </constants>
     */

    $constants = array
    (
      'debug'           => 'false',
      'debug_console'   => 'false',
      'buffer_output'   => 'true',
      'wbf_no_login'    => 'false',
      'wbf_no_acl'      => 'false',

      'wbf_controller'  => 'Apachemod',
      'wbf_db_key'      => 'rowid',
    );

    if(!isset($this->node->constants))
      return $constants;

    $cNode = $this->node->constants;

    if( isset($cNode->debug) )
      $constants['debug'] = trim($cNode->debug);

    if( isset($cNode->debug_console) )
      $constants['debug_console'] = trim($cNode->debug_console);

    if( isset($cNode->buffer_output) )
      $constants['buffer_output'] = trim($cNode->buffer_output);

    if( isset($cNode->wbf_no_login) )
      $constants['wbf_no_login'] = trim($cNode->wbf_no_login);

    if( isset($cNode->wbf_no_acl) )
      $constants['wbf_no_acl'] = trim($cNode->wbf_no_acl);

    if( isset($cNode->wbf_controller) )
      $constants['wbf_controller'] = trim($cNode->wbf_controller);

    if( isset($cNode->wbf_db_key) )
      $constants['wbf_db_key'] = trim($cNode->wbf_db_key);

    return $constants;

  }//end public function getConstants */

  /**
   * @return array
   */
  public function getGwInterfaces()
  {

    $interfaces = array
    (
      'index',
      'ajax',
      'css',
      'document',
      'graph',
      'html',
      'js',
      'window',
      'image',
      'modal',
    );

    if( isset($this->node->interfaces['type']) )
    {

      $source = 'DataGatewayInterface'.ucfirst($this->node->interfaces['type']);

      $dataSource = new $source();
      $interfaces = $dataSource->get();

    }
    else if( isset( $this->node->interfaces->interface ) )
    {

      $interfaces = array();

      foreach( $this->node->interfaces->interface as $interface )
      {
        $interfaces[] = trim($interface);
      }

    }

    return $interfaces;


  }//end public function getGwInterfaces */

  /**
   * @return array
   */
  public function getStatus()
  {

    // default status
    $status = array
    (
      'sys.name'        => 'WebFrap',
      'sys.version'     => '0.6',
      'sys.generator'   => 'WebFrap 0.6',
      'sys.admin.name'  => 'admin of the day',
      'sys.admin.mail'  => 'admin@webfrap.net',
      'sys.licence'     => 'BSD',
      'sys.copyright'   => 'WebFrap Net http://webfrap.net',

      'gateway.name'      => 'Unnamed Gateway',
      'gateway.version'   => '0.1',
      'gateway.licence'   => 'BSD',

      'default.country'   => 'de',
      'default.language'  => 'de',
      'default.timezone'  => 'Europe/Berlin',
      'default.encoding'  => 'utf-8',
      'default.theme'     => 'default',

      'activ.country'     => 'de',
      'activ.language'    => 'de',
      'activ.timezone'    => 'Europe/Berlin',
      'activ.encoding'    => 'utf-8',
      'activ.theme'       => 'default',

      'default.title'         => 'WebFrap',

      'default.action.annon'  => 'Webfrap.Page.Start',
      'default.action.user'   => 'webfrap.netsktop.display',
      'default.action.login'  => 'Webfrap.Login.Form',

      'menu.top.annon'        => 'topmenu/annon',
      'menu.top.user'         => 'topmenu/user',

      'ui.listing.numEntries' => array(10,25,50,100,250,500),

      'enable.firephp'    => false,
    );


    if( isset( $this->node->conf->status->var ) )
    {
      //<var name="" ></var>
      foreach( $this->node->conf->status->var as $var )
      {
        if( isset( $var['type'] )  )
        {

          $type = trim($var['type']);

          if( 'int' == $type || 'numeric' == $type || 'boolean' == $type  )
          {
            $status[trim($var['name'])] = trim($var);
          }
          elseif( 'array' == $type )
          {
            $varData = array();

            foreach( $var->value as $value )
            {
              $varData[] = trim($value);
            }

            $status[trim($var['name'])] = $varData;

          }
          else
          {
            $status[trim($var['name'])] = "'".trim($var)."'";
          }
        }
        else
        {
          $status[trim($var['name'])] = "'".trim($var)."'";
        }
      }
    }

    return $status;


  }//end public function getStatus */

  /**
   * @return array
   */
  public function getInitClasses()
  {


    if( isset( $this->node->conf->init_classes->class )  )
    {
      $classes  = array();
      $cNode    = $this->node->conf->init_classes;

      foreach( $cNode->class as $class )
      {
        $classes[] = trim($class);
      }

    }
    else
    {
      // default init classes
      $classes = array
      (
        'Log'       ,
        'I18n'      ,
        'Message'   ,
        'Registry'  ,
        'Request'   ,
        'Session'   ,
        'User'      ,
        'View'      ,
      );//end class
    }

    return $classes;

  }//end public function getInitClasses */

  /**
   *
   */
  public function getConfModules()
  {
    $modules = array();

    if( isset( $this->node->conf->modules->module ) )
    {
      foreach( $this->node->conf->modules->module as $mod )
      {
        $modules[] = $mod;
      }
    }

    return $modules;

  }//end public function getConfModules */


  /**
   * @return array
   */
  public function getIncludeGmods()
  {

    /*
      <include>
        <gmods>
          <mod>name</mod>
     */

    $mods = array();

    if( isset( $this->node->include->gmods->mod ) )
    {
      foreach( $this->node->include->gmods->mod as $mod )
      {
        $mods[] = trim($mod['name']);
      }
    }

    return $mods;

  }//end public function getIncludeGmods */

  /**
   * @return array
   */
  public function getIncludeModules()
  {

    /*
      <include>
        <modules>
          <mod>name</mod>
     */

    $mods = array();

    if( isset( $this->node->include->modules->mod ) )
    {
      foreach( $this->node->include->modules->mod as $mod )
      {
        $mods[] = trim($mod['name']);
      }
    }

    return $mods;

  }//end public function getIncludeModules */

  /**
   * @return array
   */
  public function getIncludeCss()
  {

    /*
      <include>
        <styles name="" >
          <style base="" >name</style>
     */

    $stylesMap = array();

    if( isset( $this->node->include->styles ) )
    {

      foreach( $this->node->include->styles as $styles )
      {

        $tmp = array();

        foreach( $styles->style as $path )
        {
          if( isset($path['base']) )
          {
            $tmp[] = trim($path['base']).".'{$path}'";
          }
          else
          {
            $tmp[] = "'{$path}'";
          }
        }

        $stylesMap[trim($styles['name'])] = $tmp;

      }
    }

    return $stylesMap;

  }//end public function getIncludeCss */

  /**
   * @return array
   */
  public function getIncludeJavascript()
  {

    /*
      <include>
        <scripts name="" >
          <script base="" >name</script>
     */

    $scriptsMap = array();

    if( isset( $this->node->include->scripts ) )
    {

      foreach( $this->node->include->scripts as $scripts )
      {

        $tmp = array();

        foreach( $scripts->script as $path )
        {
          if( isset($path['base']) )
          {
            $tmp[] = trim($path['base']).".'{$path}'";
          }
          else
          {
            $tmp[] = "'{$path}'";
          }
        }

        $scriptsMap[trim($scripts['name'])] = $tmp;

      }
    }

    return $scriptsMap;

  }//end public function getIncludeJavascript */

  /**
   * @return array
   */
  public function getGwPaths()
  {

    // required, must be there
    /*
      <paths>
        <path>
          <root></root>
          <gw></gw>
          <file></file>
          <theme></theme>
          <style></style>
        </path>
        <web>
          <root></root>
          <gw></gw>
          <file></file>
          <theme></theme>
          <style></style>
        </web>
      </paths>
     */

    $pnode = $this->node->paths;

    $paths = array();

    if( isset( $pnode->path->root['BASE'] ) )
      $paths['PATH_ROOT']     = trim($pnode->path->root['BASE'])."'".trim($pnode->path->root)."'";
    else
      $paths['PATH_ROOT']     = "'".trim($pnode->path->root)."'";

    if( isset( $pnode->path->gw['BASE'] ) )
      $paths['PATH_GW']     = trim($pnode->path->gw['BASE'])."'".trim($pnode->path->gw)."'";
    else
      $paths['PATH_GW']     = "'".trim($pnode->path->gw)."'";

    if( isset( $pnode->path->fw['BASE'] ) )
      $paths['PATH_FW']     = trim($pnode->path->fw['BASE'])."'".trim($pnode->path->fw)."'";
    else
      $paths['PATH_FW']     = "'".trim($pnode->path->fw)."'";

    if( isset( $pnode->path->files['BASE'] ) )
      $paths['PATH_FILES']     = trim($pnode->path->files['BASE'])."'".trim($pnode->path->files)."'";
    else
      $paths['PATH_FILES']     = "'".trim($pnode->path->files)."'";

    if( isset( $pnode->path->wgt['BASE'] ) )
      $paths['PATH_WGT']     = trim($pnode->path->wgt['BASE'])."'".trim($pnode->path->wgt)."'";
    else
      $paths['PATH_WGT']     = "'".trim($pnode->path->wgt)."'";


    if( isset( $pnode->web->root['BASE'] ) )
      $paths['WEB_ROOT']     = trim($pnode->web->root['BASE'])."'".trim($pnode->web->root)."'";
    else
      $paths['WEB_ROOT']     = "'".trim($pnode->web->root)."'";

    if( isset( $pnode->web->gw['BASE'] ) )
      $paths['WEB_GW']     = trim($pnode->web->gw['BASE'])."'".trim($pnode->web->gw)."'";
    else
      $paths['WEB_GW']     = "'".trim($pnode->web->gw)."'";

    if( isset( $pnode->web->fw['BASE'] ) )
      $paths['WEB_FW']     = trim($pnode->web->fw['BASE'])."'".trim($pnode->web->fw)."'";
    else
      $paths['WEB_FW']     = "'".trim($pnode->web->fw)."'";

    if( isset( $pnode->web->files['BASE'] ) )
      $paths['WEB_FILES']     = trim($pnode->web->files['BASE'])."'".trim($pnode->web->files)."'";
    else
      $paths['WEB_FILES']     = "'".trim($pnode->web->files)."'";

    if( isset( $pnode->web->wgt['BASE'] ) )
      $paths['WEB_WGT']     = trim($pnode->web->wgt['BASE'])."'".trim($pnode->web->wgt)."'";
    else
      $paths['WEB_WGT']     = "'".trim($pnode->web->wgt)."'";


    return $paths;

  }//end public function getClass */


}//end class LibGenfTreeNodeGateway

