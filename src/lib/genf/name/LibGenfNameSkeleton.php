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
 * Alle relevanten Namenselemente zum benamen der Widget relevanten
 * Architekturelemente im Code
 *
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfNameSkeleton
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
   * @see src/lib/genf/LibGenfName#parse($name, $params)
   */
  public function parse( $name , $params = array() )
  {

    $nameKey = trim( $name['name'] );

    if( isset( $name['source'] ) )
      $this->source = trim( $name['source'] );

    $label = LibGenfBuild::getInstance()->interpreter->getLabel( $name );

     if( $label )
      $this->label = $label;
    else
      $this->label = SParserString::subToName( $nameKey );

    $this->name          = $nameKey;
    $this->module        = SParserString::getDomainName( $nameKey );

    if( isset( $name['module'] ) )
    {
      $this->customModul         = ucfirst( trim( $name['module'] ) );
    }
    else
    {
      $this->customModul         = $this->module;
    }
    
    if( isset( $name['type'] ) )
      $this->type = trim( $name['type'] );
    else 
      $this->type = 'data';


    $tmp = explode('_',$nameKey);
    array_shift($tmp);

    $this->model          = SParserString::subToCamelCase( SParserString::removeFirstSub($nameKey) ) ;
    $this->modelSub       = implode('_',$tmp);

    $this->package        = SParserString::subToPackage($nameKey);

    $this->class          = SParserString::subToCamelCase($nameKey);
    $this->classPath      = $this->lower('module').'/'.implode('_',$tmp);
    $this->classUrl       = $this->module.'.'.$this->model;

    $this->i18nKey   = $this->lower('module').'.'.SParserString::subBody($nameKey).'.';
    $this->i18nText  = $this->lower('module').'.'.SParserString::subBody($nameKey).'.label';
    $this->i18nMsg   = $this->lower('module').'.'.SParserString::subBody($nameKey).'.message';

  }//end public function parse */


}//end class LibGenfNameSkeleton

