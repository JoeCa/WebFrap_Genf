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
class LibGenfNameNode
  extends LibGenfName
{
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
   * @see src/lib/genf/LibGenfName#parse($node, $params)
   */
  public function parse( $node , $params = array() )
  {

    $this->name            = trim($node['name']);
    $this->source          = $this->name;

    $this->label           = LibGenfBuild::getInstance()->interpreter->getLabel( $node );

    if( isset($node['type'])  )
    {
      $this->type            = trim($node['type']);
      $this->typeClass       = SParserString::subToCamelCase($this->type);
    }

    if( isset($node['key'])  )
    {
      $this->key            = trim($node['key']);
      $this->keyClass       = SParserString::subToCamelCase($this->key);
    }

    if( isset($node['resource'])  )
    {
      $this->resource            = trim($node['resource']);
      $this->resourceClass       = SParserString::subToCamelCase($this->resource);
    }

    if( isset($node['class'])  )
    {
      $this->classKey        = trim($node['class']);
      $this->class           = SParserString::subToCamelCase($this->classKey);
    }
    else
    {
      $this->class           = SParserString::subToCamelCase($this->name);
    }

    $this->module          = SParserString::getDomainName($this->name);
    $this->model           = SParserString::subToCamelCase( SParserString::removeFirstSub($this->name) ) ;

    $tmp = explode('_',$this->name);
    array_shift($tmp);

    $this->classPath       = $this->lower('module').'/'.implode('_',$tmp);
    $this->classUrl       = $this->module.'.'.$this->model;

    $this->i18nKey         = $this->lower('module').'.'.SParserString::subBody($this->name).'.';
    $this->i18nText        = $this->lower('module').'.'.SParserString::subBody($this->name).'.label';
    $this->i18nMsg         = $this->lower('module').'.'.SParserString::subBody($this->name).'.message';

  }//end public function parse */


}//end class LibGenfNameNode

