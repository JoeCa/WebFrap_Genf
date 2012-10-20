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
 * Eine Name Lib für die Namings
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfNameProcessPhase
  extends LibGenfName
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  public $original;

  public $name;

  public $key;

  public $module;

  public $label;

////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $name
   * @param array $params
   */
  public function __construct( $name, $params = array()  )
  {
    $this->parse($name, $params);
  }//end public function __construct */


  /**
   * (non-PHPdoc)
   * @see src/lib/genf/LibGenfName#parse($name, $params)
   */
  public function parse( $node , $params = array() )
  {

    $name                 = trim($node['name']);

    $this->original       = $name;
    $this->name           = $name;
    $this->key            = $name;
    $this->module         = SParserString::getDomainName( $name );

    $this->label          = LibGenfBuild::getInstance()->interpreter->getLabel( $node, 'en', false );

    $tmp = explode('_',$name);
    array_shift($tmp);

    $this->model          = SParserString::subToCamelCase( SParserString::removeFirstSub($name) ) ;

    $this->class          = SParserString::subToCamelCase($name);
    $this->classPath      = $this->lower('module').'/'.implode('_',$tmp);
    $this->classUrl       = $this->module.'.'.$this->model;

    $this->package        = SParserString::subToPackage($name);

    $this->modelSub       = implode('_',$tmp);
    $this->i18nKey        = $this->lower('module').'.'.SParserString::subBody($name).'.';

  }//end public function parse */



}//end class LibGenfNameProcessPhase

