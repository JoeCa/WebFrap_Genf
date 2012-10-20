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
 * Alle relevanten Namenselemente zum benamen der Action Trigger relevanten
 * Architekturelemente im Code
 * 
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfNameActionTrigger
  extends LibGenfName
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var string
   */
  public $name  = null;
  
  /**
   * @var string
   */
  public $key   = null;
  
  /**
   * @var string
   */
  public $class = null;
  
////////////////////////////////////////////////////////////////////////////////
// methodes
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

    if( isset($name['name']) )
    {
       $this->name   = trim($name['name']);
    }
    
    $this->class  = SParserString::subToCamelCase(trim($name['class']));
    
    if( isset($name['key']) )
    {
      $this->key   = SParserString::subToCamelCase( trim($name['key']) );
    }
    else 
    {
      $this->key   = SParserString::subToCamelCase( $this->name );
    }

    

  }//end public function parse */


}//end class LibGenfNameActionTrigger

