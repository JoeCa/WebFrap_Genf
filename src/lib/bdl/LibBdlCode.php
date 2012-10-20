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
 * @subpackage GenF
 */
class LibBdlCode
  extends LibBdlSubParser
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

 /**
   * @return string
   */
  public function parse(  )
  {

    $code = '';

    while( $token = $this->lexer->next() )
    {
      if( $token[0] == $this->k_if )
      {
        $code .= $this->parseIf( );
      }
      elseif( $token[0] >= 1000 )
      {
        $code .= $this->parseElement( $token );
      }
      else if( $this->t_comment == $token[0] )
      {
        $code .= $this->line( '// '.$token[1] );
      }
      else
      {
        $this->unexpectedToken( $token, null, __METHOD__.'::'.__LINE__ );
      }
    }

    return $code;

  }//end public function parse */


} // end class LibBdlLexer

