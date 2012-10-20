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
class LibGenfNameEnum
  extends LibGenfName
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @var string
   */
  public $name     = null;
  
  /**
   * @var string
   */
  public $class    = null;
  
  /**
   * @var string
   */
  public $label    = null;
  
  /**
   * @var string
   */
  public $module   = null;  
  
  /**
   * @var string
   */
  public $customModul = null;
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
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

    $name                 = trim( $node['name'] );
    $this->name           = $name;
    $this->class          = SParserString::subToCamelCase($name);
    
    $this->module         = SParserString::getDomainName($name);
    $this->label          = LibGenfBuild::getInstance()->interpreter->getLabel( $node, 'en', true );
    
    if( isset( $node['module'] ) )
    {
      $this->customModul    = ucfirst(trim($node['module']));
    }
    else 
    {
      $this->customModul    = $this->module;
    }

    $this->i18nKey        = $this->lower('module').'.'.SParserString::subBody($name).'.';
    $this->i18nText       = $this->lower('module').'.'.SParserString::subBody($name).'.label';
    $this->i18nMsg        = $this->lower('module').'.'.SParserString::subBody($name).'.message';


  }//end public function parse */



}//end class LibGenfNameEnum

