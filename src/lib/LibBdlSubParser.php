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
class LibBdlSubParser
  extends LibSubParser
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  protected function parseConditionBraces( $first = null  )
  {

    // if exists we need no check
    if(!$first)
    {
      $first = $this->lexer->next();

      if( $first[0] != $this->c_open_parenthesis )
      {
        $this->unexpectedToken( $first, null, __METHOD__.'::'.__LINE__ );
      }
    }

    $code = $this->line('(');
    $this->registry->wsInc();

    $nextDec = false;

    while( $token = $this->lexer->until(  $this->c_close_parenthesis ) )
    {

      if( $this->isElementToken($token) )
      {
        $code .= $this->parseElement( $token );
      }
      elseif( $token[0] == $this->c_not  )
      {
        $code .= $this->parseNot();
      }
      elseif( $token[0] == $this->c_and  )
      {
        $this->registry->wsInc();

        $code .= $this->parseAnd();

        $nextDec = true;
        continue;

      }
      elseif( $token[0] == $this->c_or  )
      {

        $this->registry->wsInc();
        $code .= $this->parseOr();

        $nextDec = true;
        continue;

      }
      elseif( $token[0] == $this->c_open_curly_braces )
      {
        $code .= $this->parseConditionBraces( $token );
      }
      else if( $this->t_comment == $token[0] )
      {
        $code .= $this->line( '// '.$token[1] );
      }
      else
      {
        $this->unexpectedToken( $token,null,__METHOD__.'::'.__LINE__ );
      }

      if( $nextDec )
      {
        $this->registry->wsDec();
        $nextDec = false;
      }

    }//end while( $token = $this->lexer->until(  $this->c_close_curly_braces ) )


    $this->registry->wsDec();
    $code .=  $this->line(')');

    return $code;

  }//end protected function parseConditionBraces */

  /**
   * @return string
   */
  protected function parseBody(   )
  {

    $level    = 0;
    $first = $this->lexer->next();

    if( $first[0] != $this->c_open_curly_braces )
    {
      $this->unexpectedToken( $first, null, __METHOD__.'::'.__LINE__ );
    }

    $code = $this->line('{');
    $this->registry->wsInc();

    while( $token = $this->lexer->until( $this->c_close_curly_braces )  )
    {

      if( $token[0] == $this->k_if )
      {
        $code .= $this->parseIf( );
      }
      else if( $this->isElementToken( $token ) )
      {
        $code .= $this->parseElement( $token );
      }
      else if( $this->t_comment == $token[0] )
      {
        $code .= $this->parseComment( $token[1] );
      }
      else
      {
        $this->unexpectedToken( $token, null, __METHOD__.'::'.__LINE__ );
      }
    }


    $this->registry->wsDec();
    $code .= $this->line('}');

    return $code;

  }//end protected function parseCodeBodyTokens */

  /**
   * @param $token
   */
  protected function parseElement( $token  )
  {

    if( !$parser = $this->registry->getTokenParser( $token ) )
      $this->fail( 'Tried to request noexisting element parser for token type: '.$token[0] );

    return $parser->parse();


  }//end protected function parseElement */

  /**
   *
   */
  protected function parseIf( )
  {

    $code =  $this->line('if');
    // erst mal alle token bis zum ersten { holen

    // must start with "(" fetch till closing (not first) ")"
    $code .= $this->parseConditionBraces(  );
    $code .= $this->parseBody(  );

    $conditionActiv = true;

    while( $conditionActiv )
    {

      $nextToken = $this->lexer->preview();

      if( $nextToken[0] == $this->k_elseif  )
      {
        $this->lexer->next(); // elseif
        $code .=  $this->line('else if');
        $code .= $this->parseConditionBraces(  );
        $code .= $this->parseBody(  );
      }
      elseif( $nextToken[0] == $this->k_else )
      {
        $this->lexer->next(); // else
        $code .=  $this->line('else');
        $code .= $this->parseBody(  );

        // stop here
        $conditionActiv = false;
      }
      elseif( $nextToken[0] == $this->t_comment )
      {
        $code .= $this->parseComment( $this->lexer->next()  );
      }
      else
      {
        // stop here
        $conditionActiv = false;
      }

    }

    return $code;

  }//end protected function parseIf */

 /**
   * @return string
   */
  protected function parseNot( )
  {
    return '!';
  }//end protected function parseNot */

 /**
   * @return string
   */
  protected function parseAnd( )
  {
    return $this->sLine( '&&' );
  }//end protected function parseAnd */

 /**
   * @return string
   */
  protected function parseOr( )
  {
    return $this->sLine( '||' );
  }//end protected function parseOr */

  /**
   * @param array $token
   */
  protected function isElementToken( $token )
  {
    return ( $token[0] >= 1000 );
  }//end protected function isElementToken


 /**
   * @return string
   */
  protected function parseComment( $comment  )
  {

    if( is_array($comment) )
      $comment = $comment[1];

    return $this->line('//'.$comment);

  }//end protected function parseOr */

  /**
   * @return array
   */
  public function fail( $message , $token = null )
  {

    Debug::console( $message.' '.LibGenfBuild::getDefault()->dumpEnv() , $token );
    throw new LibParser_Exception( $message );

  }//end public function getBodyTokens */

  /**
   * @param array $token
   * @param array $expected
   * @return array
   */
  public function unexpectedToken( $token, $expected = null, $addInfo = null )
  {

    $message = 'Unexpected '.$this->registry->tokenName($token[0],true).' '.$token[0].' in line '.$token[2];

    if( $expected )
      $message .= ' expected '.$this->registry->tokenName($expected,true).' instead';

    if( $addInfo )
      $message .= ' '.$addInfo;
      
    $builder = LibGenfBuild::getDefault();

    $message .= $builder->dumpEnv();
    
    throw new LibParser_Exception($message);

  }//end public function unexpectedToken */

  /**
   * @param array $token
   * @param array $expected
   * @return array
   */
  public function unexpectedIdentifier( $token, $expected = array() )
  {

    $message = 'Unexpected '.$this->registry->tokenName($token[0],true).' in line '.$token[2];

    if( $expected )
      $message .= 'valid identifier would be: '.implode(', ',$expected).' ';

    throw new LibParser_Exception( $message );

  }//end public function unexpectedIdentifier */

  /**
   * @param $token
   * @param $expected
   * @return array
   */
  public function expectToken( $expected, $append = null )
  {

    $token        = $this->lexer->next();

    if( $token[0] != $expected )
    {
      $this->unexpectedToken( $token, $expected, $append );
    }

    return $token;

  }//end public function expectToken */
  
  /**
   * @param $token
   * @param $expected
   * @return array
   */
  public function preview( $expected, $append = null )
  {

    $token        = $this->lexer->next();
    $this->lexer->back();

    if( $token[0] == $expected )
    {
      return true;
    }
    else 
    {
      return false;
    }

  }//end public function preview */
  
  /**
   * @return array
   */
  public function next( )
  {
    return $this->lexer->next();
  }//end public function next */


  /**
   * @return array
   */
  public function getHeadTokens()
  {
    return $this->lexer->getSurrounded
    (
      $this->c_open_parenthesis ,  // (
      $this->c_close_parenthesis   // )
    );
  }//end public function getHeadTokens */

  /**
   * @return array
   */
  public function getBodyTokens()
  {
    return $this->lexer->getSurrounded
    (
      $this->c_open_curly_braces , // {
      $this->c_close_curly_braces  // }
    );
  }//end public function getBodyTokens */

  /**
   * @param boolean $force force an error if the token is not the expected line end token
   * @return array
   */
  protected function lineEnd( $force = true )
  {

    $token = $this->lexer->next();

    if( $force && ( $token[0] !=  $this->c_semicolon ) )
    {
      $this->unexpectedToken( $token , $this->c_semicolon, __METHOD__.'::'.__LINE__ );
    }
    else
    {
      return ( $token[0] ==  $this->c_semicolon );
    }

    return true;


  }//end public function getBodyTokens */

  /**
   *
   * Enter description here ...
   */
  public function getContext()
  {
    return $this->registry->parser->context;
  }//end public function getContext */

  /**
   *
   * Enter description here ...
   * @param unknown_type $message
   */
  public function notYetImpemented( $message )
  {
    Debug::console($message);
  }//end public function notYetImpemented */

  /**
   *
   * Enter description here ...
   */
  public function wsInc(  )
  {
    $this->registry->wsInc();
  }//emd public function wsInc */

  /**
   *
   * Enter description here ...
   */
  public function wsDec(  )
  {
    $this->registry->wsDec();
  }//end public function wsDec */

} // end class LibBdlSubParser







