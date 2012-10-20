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
class LibBdlElementUser
  extends LibBdlSubParser
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   */
  public function parse()
  {

    $code   = $this->sLine( '$this->user' );
    $token  = $this->lexer->next();

    if( $token[0] == $this->k_has )
    {
      $code .= $this->parseHas();
    }
    elseif( $token[0] == $this->k_in )
    {
      $code .= $this->parseIn();
    }
    elseif( $token[0] == $this->k_role )
    {
      // short notation to ignore has
      $code .= $this->parseHasRole();
    }


    return $code.$this->nl();

  }//end public function parse */

  /**
   *
   */
  protected function parseIn()
  {

    // $USER has role ADMIN
    // rolename mapps on access key of wbfsys_role_group

  }//end protected function parseHasRole */


  /**
   *
   */
  protected function parseHas()
  {

    // $USER has ???
    // active user has whatever

    $code   = '';
    $token  = $this->lexer->next();

    if( $token[0] == $this->k_role )
    {
      $code .= $this->parseHasRole();
    }
    else
    {
      $this->unexpectedToken( $token, null, __METHOD__.'::'.__LINE__ );
    }

    return $code;

  }//end protected function parseHasRole */

  /**
   *
   */
  protected function parseHasRole()
  {

    // $USER has role ADMIN
    // rolename mapps on access key of wbfsys_role_group

    $code   = '';
    $token  = $this->lexer->next();

    // can be identifier or string
    if( $token[0] == $this->t_identifier )
    {
      $code .= "->hasRole( '{$token[1]}' )";
    }
    elseif( $token[0] == $this->t_string )
    {
      $code .= "->hasRole( '{$token[1]}' )";
    }
    else
    {
      $this->unexpectedToken( $token, null, __METHOD__.'::'.__LINE__ );
    }

    return $code;

  }//end protected function parseHasRole */


  /**
   *
   */
  protected function parseHasLevel()
  {

    // $USER has level ADMIN |
    // rolename mapps on access key of wbfsys_role_group or is int

    // $USER has role ADMIN
    // rolename mapps on access key of wbfsys_role_group

    $code   = '';
    $token  = $this->lexer->next();

    // can be identifier or string
    if( $token[0] == $this->t_int )
    {
      $code .= "->getLevel() >= {$token[1]} ";
    }
    else
    {
      $this->unexpectedToken( $token, null, __METHOD__.'::'.__LINE__ );
    }

    return $code;

  }//end protected function parseHasLevel */


} // end class LibBdlLexer







