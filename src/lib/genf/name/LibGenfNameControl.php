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
class LibGenfNameControl
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
    $this->parse( $name, $params );
  }//end public function __construct */


  /**
   * (non-PHPdoc)
   * @see src/lib/genf/LibGenfName#parse($name, $params)
   */
  public function parse( $name , $params = array() )
  {

    $label = LibGenfBuild::getInstance()->interpreter->getLabel( $name );

    $parent = $name->parentNode->parentNode;

     if( $label )
      $this->label = $label;
    else
      $this->label = SParserString::subToName( trim($parent['name']) );

    $name = trim($parent['name']);

    $this->name            = $name;
    $this->source          = $name;

    $this->entity          = SParserString::subToCamelCase($name);
    $this->class           = SParserString::subToCamelCase($name);
    $this->key             = SParserString::subToCamelCase($name);
    $this->module          = SParserString::getDomainName($name);
    $this->model           = SParserString::subToCamelCase( SParserString::removeFirstSub($name) ) ;

    if( isset($name['module']) && trim($name['module']) != '' )
      $this->customModul = strtolower( trim($name['module']) );
    else
      $this->customModul = $this->module;

    $tmp = explode('_',$name);
    array_shift($tmp);

    $this->modelSub        = implode('_',$tmp);
    $this->entityPath      = $this->lower('module').'/'.implode('_',$tmp);
    $this->entityUrl       = $this->module.'.'.$this->model;

    $this->classPath       = $this->lower('module').'/'.implode('_',$tmp);
    $this->classUrl       = $this->module.'.'.$this->model;

    $this->i18nKey         = $this->lower('module').'.'.SParserString::subBody($name).'.';
    $this->i18nText        = $this->i18nKey.'label';
    $this->i18nMsg         = $this->i18nKey.'message';

  }//end public function parse */


}//end class LibGenfName

