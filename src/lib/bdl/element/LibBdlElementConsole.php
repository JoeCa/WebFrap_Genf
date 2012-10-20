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
class LibBdlElementConsole
  extends LibBdlSubParser
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  public function parse()
  {

    $code   = $this->sLine( 'Debug::console( ' );

    // now this should be the string
    $token  = $this->lexer->next();

    if( $token[0] != $this->t_string )
    {
      $this->unexpetcedToken($token,$this->t_string);
    }

    $code .= $this->string( $token[1] );
    $code .= $this->cLine( ' );' );

    // force check for lineend
    $this->lineEnd( true );

    return $code;

  }//end public function parse */



} // end class LibBdlLexer







