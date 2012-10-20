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
class LibGenfNameMin
  extends LibGenfName
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var string
   */
  public $name = null;

  /**
   *
   * @var string
   */
  public $module = null;

  public $model  = null;

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $name
   * @param array $params
   */
  public function __construct( $name, $params = array()  )
  {
    $this->parse( $name, $params );
  }//end public function __construct */


  /**
   * (non-PHPdoc)
   * @see src/lib/genf/LibGenfName#parse($name, $params)
   */
  public function parse( $node , $params = array() )
  {

    if( is_string($node) )
      $name             = $node;
    else
      $name             = trim($node['name']);

    $this->name       = $name;
    
    $builder = LibGenfBuild::getInstance();

    $this->label          = $builder->interpreter->getLabel( $node, $builder->langDefault );
    $this->pLabel         = $builder->interpreter->getPlabel( $node, $builder->langDefault );

    
    $this->module     = SParserString::getDomainName($name);

    $tmp = explode('_',$name);
    array_shift($tmp);

    $this->model      = SParserString::subToCamelCase( SParserString::removeFirstSub($name) ) ;
    $this->class      = SParserString::subToCamelCase($name);
    $this->key        = SParserString::subToCamelCase($name,true);

    $this->classPath  = $this->lower('module').'/'.implode('_',$tmp);
    $this->classUrl   = $this->module.'.'.$this->model;

    $this->modelSub   = implode('_',$tmp);

    $this->i18nKey    = $this->lower('module').'.'.SParserString::subBody($name).'.';

    $this->i18nText    = $this->lower('module').'.'.SParserString::subBody($name).'.label';
    $this->i18nMsg    = $this->lower('module').'.'.SParserString::subBody($name).'.message';


  }//end public function parse */



}//end class LibGenfNameMin

