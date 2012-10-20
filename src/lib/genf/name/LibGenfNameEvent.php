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
class LibGenfNameEvent
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

    $nameKey = trim($name['name']);

    $label = LibGenfBuild::getInstance()->interpreter->getLabel( $name );

     if( $label )
      $this->label = $label;
    else
      $this->label = SParserString::subToName( $nameKey );


    $this->name          = $nameKey;
    $this->class         = SParserString::subToCamelCase( $nameKey );
    
    $this->module   = SParserString::getDomainName( $nameKey );

    $this->source = trim($name['source']);
      
    if( isset( $name['module'] ) )
      $this->customModul = strtolower(trim($name['module']));
    else 
      $this->customModul = $this->module;

   
    $this->model    = SParserString::subToCamelCase( SParserString::removeFirstSub($nameKey) ) ;
    $this->i18nKey  = $this->lower('module').'.'.SParserString::subBody($nameKey).'.';
    
    $this->i18nText  = $this->lower('module').'.'.SParserString::subBody($nameKey).'.label';
    $this->i18nMsg   = $this->lower('module').'.'.SParserString::subBody($nameKey).'.message';

  }//end public function parse */


}//end class LibGenfNameEvent

