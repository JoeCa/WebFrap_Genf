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
class LibGeneratorInjector
  extends LibGenfGenerator
{
////////////////////////////////////////////////////////////////////////////////
// Constances
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param string $classType
   * @param string $method
   * @param string $position
   * @param TCodeStack $codeStack
   * @param LibGenfTreeNode $node
   * @param int $ident Einrückung
   * 
   */
  public function inject( $classType, $method, $position, $codeStack, $node, $ident = 0 )
  {

    $classType  = SParserString::subToCamelCase( $classType );
    $method     = SParserString::subToCamelCase( $method );
    $position   = SParserString::subToCamelCase( $position );

    $methodName = 'method_'.$classType.'_'.$method.'_'.$position;

    if( $node instanceof LibGenfEnv )
    {
      $management = $node->management;
    }
    else 
    {
      $management = $node;
    }
    
    if( method_exists( $this, $methodName) )
    {
      $codeStack->appendCode
      ( 
        'code'.$position, 
        SParserString::setIndentinon( $this->$methodName( $management ), $ident)  );
    }

  }//end public function inject */

} // end class LibGeneratorInjector
