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
class LibParserWbfCondition
  extends LibBdlRuleParser
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////


  public function parse( $rawCode )
  {
    
    // reset parsed string
    $this->parsed = '';
    
    $this->lexer->split( $rawCode );
    $this->parseTokens();
    
    return $this->parsed();
    
  }
  
  
  public function parseTokens()
  {
    
    
    
    
  }
  
  
  

} // end class LibParserWbfI18ntext
