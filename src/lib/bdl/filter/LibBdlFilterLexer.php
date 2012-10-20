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
class LibBdlFilterLexer
  extends LibLexer
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var array
   */
  protected $catchablePatterns = array
  (
    '[a-z_][a-z0-9_\\\]*',
    '(?:[0-9]+(?:[\.][0-9]+)*)(?:e[+-]?[0-9]+)?',
    //  '"(?:[^"]|"")*"',
  );

  /**
   * @var array
   */
  protected $nonCatchablePatterns = array
  (
    //'\s+',
    //'\*+',
    '(.)'
  );

  /**
   * @var array
   */
  protected $element = array();

  /**
   * @var array
   */
  protected $keyWord = array();

  /**
   * @var array
   */
  protected $operator = array();

  /**
   *
   * @var unknown_type
   */
  protected $opFactor = array
  (
    ':' => true,
//    '@' => true,
    '<' => true,
    '>' => true,
    '=' => true,
    '!' => true,
    '&' => true,
    '|' => true,
    '+' => true,
    '-' => true,
    '*' => true,
    '/' => true,
    '%' => true,
    '.' => true,
    ',' => true,
    ';' => true,
    '(' => true,
    ')' => true,
    '{' => true,
    '}' => true,
    '[' => true,
    ']' => true,
  );


  /**
   *
   * @var array
   */
  public $tokenType = array
  (
    // simple token
    't_none'        => 1,
    't_identifier'  => 2,
    't_integer'     => 3,
    't_string'      => 4,
    't_float'       => 5,
    't_boolean'     => 6,
    't_comment'     => 7,
    
    // keyword
    'k_in'          => 100,
    'k_and'         => 101,
    'k_or'          => 102,
    'k_is'          => 103,
    'k_not'         => 104,
    'k_null'        => 105,
    
    // control element
    'c_double_colon'  => 500,
    'c_at'            => 501,
    'c_smaller'       => 502 ,
    'c_bigger'        => 503 ,
    'c_assign'        => 504 ,
    'c_equals'        => 505 ,
    'c_smaller_or_equal'  => 506 ,
    'c_bigger_or_equal'   => 507 ,
    'c_not'         => 508 ,
    'c_and'         => 509 ,
    'c_or'          => 510 ,
    'c_plus'        => 511 ,
    'c_minus'       => 512 ,
    'c_asterisk'    => 513 ,
    'c_slash'       => 514 ,
    'c_percent'     => 515 ,
    'c_path'        => 516 ,
    'c_dot'         => 517 ,
    'c_comma'       => 518 ,
    'c_semicolon'   => 519 ,
    'c_open_parenthesis'      => 520 ,
    'c_close_parenthesis'     => 521 ,
    'c_open_curly_braces'     => 522 ,
    'c_close_curly_braces'    => 523 ,
    'c_open_square_brackets'  => 524 ,
    'c_close_square_brackets' => 525 ,
    
    // element 
    'e_owner'      => 1000,
    'e_this'       => 1001,
    'e_user'       => 1002,
    'e_my'         => 1003,
    'e_var'        => 1005,
    'e_function'   => 1006,
    'e_member_var' => 1007,
    'e_view'       => 1008,
  );

  /**
   * Die line in der sich der lexer aktuell befindet
   * @var int
   */
  protected $line = null;

////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  
  /**
   * 
   */
  public function loadLexerData()
  {
    
    $this->element = array
    (
      'OWNER'   => array( $this->tokenType['e_owner'] ),
      'THIS'    => array( $this->tokenType['e_this'] ),
      'USER'    => array( $this->tokenType['e_user'] ),
      'MY'      => array( $this->tokenType['e_my'] ),
    );

    $this->keyWord = array
    (
      'IN'     => array( $this->tokenType['k_in'] ),
      'AND'    => array( $this->tokenType['k_and'] ),
      'OR'     => array( $this->tokenType['k_or'] ),
      'IS'     => array( $this->tokenType['k_is'] ),
      'NOT'    => array( $this->tokenType['k_not'] ),
      'NULL'   => array( $this->tokenType['k_null'] )
    );


    $this->operator = array
    (
      '::'      => array( 500 , false ), // ATTRIBUTE
//    '@'       => array( 501 , true ),  // ATTRIBUTE
      '<'       => array( 502 , false ), // SMALLER
      '>'       => array( 503 , false ), // BIGGER
      '='       => array( 504 , false ), // ASIGN OPERATOR
      '=='      => array( 505 , false ), // EQUALS
      '<='      => array( 506 , false ), // SMALER OR EQUAL
      '=>'      => array( 507 , false ), // BIGGER OR EQUAL
      '!'       => array( 508 , true ),  // NOT
      '&&'      => array( 509 , false ), // AND
      '||'      => array( 510 , false ), // OR
      '+'       => array( 511 , true ),
      '-'       => array( 512 , true ),
      '*'       => array( 513 , true ),
      '/'       => array( 514 , true ),
      '%'       => array( 515 , true ),
      '->'      => array( 516 , false ),
      '.'       => array( 517 , true ),
      ','       => array( 518 , true ),
      ';'       => array( 519 , true ),
      '('       => array( $this->tokenType['c_open_parenthesis'], true ),
      ')'       => array( $this->tokenType['c_close_parenthesis'], true ),
      '{'       => array( 522 , true ),
      '}'       => array( 523 , true ),
      '['       => array( 524 , true ),
      ']'       => array( 525 , true ),
    );
    
  }//end public function loadMetaData */

  /**
   * @param array $rawTokens
   *
   */
  public function createTokens(  )
  {

    $this->line = 1;

    while ( $rawToken = next( $this->rawMatches )  )
    {
      $match = $rawToken[0];

      if( "\n" == $match )
      {
        ++ $this->line;
        continue;
      }
      else if( ctype_cntrl($match) )
      {
        continue;
      }
      elseif( ctype_space($match) )
      {
        continue;
      }
      else if( $this->isComment( $match ) )
      {
        $this->tokens[] = $this->token( $this->t_comment, $this->commentToken(), $this->line );
        ++ $this->line;

        continue;
      }
      else if( '"' == $match  )
      {
        $this->tokens[] = $this->token( $this->t_string, $this->stringToken(), $this->line );
        continue;
      }
      else if( $this->isOpFactor( $match ) )
      {

        if( $this->isSingleOperator( $match ) )
        {
          $this->tokens[] = $this->token( $this->operatorType( $match ), $match, $this->line );
          continue;
        }

        $nextToken = next( $this->rawMatches );

        if( $this->isOpFactor( $nextToken[0] ) )
        {
          if($type = $this->operatorType( $match.$nextToken[0] ))
          {
            $this->tokens[] = $this->token( $type, $match.$nextToken[0], $this->line );
            continue;
          }
          else
          {
            throw new LibParser_Exception('Invalid Operator');
          }
        }
        else
        {
          $this->tokens[] = $this->token( $this->operatorType( $match ), $match, $this->line );

          prev($this->rawMatches);
          continue;

        }

      }

      if( '@' == $match  )
      {

        $nextToken = next( $this->rawMatches );
        $match = $nextToken[0];
        
        if( '$' == $match )
        {
          
          $nextToken = next( $this->rawMatches );
          $match = $nextToken[0];
          
          $testVal = str_replace( '_','',$match);
          
          if( ctype_alnum( $testVal ) )
          {
            $this->tokens[] = $this->token
            (
              $this->tokenType['e_member_var'],
              SParserString::subToCamelCase($match,true),
              $this->line
            );
          }
          else
          {
            throw new LibParser_Exception
            (
              'Got invalid member var '.$match.' in line '.$this->line 
            );
          }
          
        }
        else if( $this->isElement( strtoupper( $match ) ) )
        {
          $this->tokens[] = $this->token
          (
            $this->elementType( strtoupper( $match ) ),
            strtoupper( $match ),
            $this->line
          );
        }
        else
        {
          throw new LibParser_Exception
          (
            'Got noexisting element '.strtoupper( $match ).' in line '.$this->line 
          );
        }

        continue;
      }      
      else if( '$' == $match  )
      {

        $nextToken = next( $this->rawMatches );
        $match = $nextToken[0];
        
        $testVal = str_replace( '_','',$match);
        
        if( ctype_alnum( $testVal ) )
        {
          $this->tokens[] = $this->token
          (
            $this->tokenType['e_var'],
            SParserString::subToCamelCase( $match, true ),
            $this->line
          );
        }
        else
        {
          throw new LibParser_Exception
          (
            'Got noexisting element '.strtoupper( $match ).' in line '.$this->line 
          );
        }

        continue;
      }
      else if( $this->isKeyword( strtoupper( $match ) ) )
      {
        $this->tokens[] = $this->token
        ( 
          $this->keywordType( strtoupper( $match ) ), 
          strtoupper( $match ), 
          $this->line 
        );
        continue;
      }
      else
      {
        if( $token = $this->createToken( $match, $this->line ) )
        {
          $this->tokens[] = $token;
        }
      }

    }//end foreach

  }//end public function createTokens */

  /**
   * @param $value
   */
  public function createToken( $value, $line )
  {

    if( ctype_digit( $value ) )
    {
      return array( $this->t_integer , $value, $line );
    }
    else if( is_numeric( $value ) )
    {
      return array( $this->t_float , $value, $line  );
    }
    else
    {
      return array( $this->t_identifier , str_replace( ' ' , '_' , $value ) , $line  );
    }


  }//end public function createToken */


  /**
   * @param string
   */
  public function isOperator( $key )
  {

    return isset( $this->operator[$key] );

  }//end public function isSingleOperator */

  /**
   * @param string
   */
  public function isSingleOperator( $key )
  {

    return $this->operator[$key][1];

  }//end public function isSingleOperator */

  /**
   * @param string
   */
  public function isOpFactor( $key )
  {

    return isset( $this->opFactor[$key] );

  }//end public function isSingleOperator */

  /**
   * @param string
   */
  public function operatorType( $key )
  {
    return $this->operator[$key][0];
  }//end public function operatorType */

  /**
   * @param string
   */
  public function isKeyword( $key )
  {

    return isset( $this->keyWord[$key] );

  }//end public function isKeyword */

  /**
   * @param string
   */
  public function keywordType( $key )
  {
    return $this->keyWord[$key][0];
  }//end public function keywordType */



  /**
   * @param string
   */
  public function isComment( $key )
  {

    if( '/' ==  $key )
    {

      $rawToken = next( $this->rawMatches );

      if( '/' == $rawToken[0] )
      {
        return true;
      }
      else
      {
        // set iterator back
        prev( $this->rawMatches );
        return false;
      }

    }

  }//end public function isElement */

  /**
   * @param string,array $key
   */
  public function isElement( $key )
  {
    return isset( $this->element[$key] );
  }//end public function isElement */

  /**
   * @param string
   */
  public function elementType( $key )
  {
    return $this->element[$key][0];
  }//end public function elementType */

  /**
   * @return string
   */
  public function stringToken()
  {

    $string = '';
    $escape = false;


    while( true )
    {

      $token  = next( $this->rawMatches );

      if( false === $token )
        throw new LibParser_Exception( 'unclosed string '. $string );

      if( !$escape && '\\' == $token[0] )
      {
        $escape   = true;
        $string   .= '\\';
        continue;
      }

      if( !$escape && '"' == $token[0] )
      {
        return $string;
      }
      else
      {
        $string .= $token[0];
        $escape = false;
      }

    }

  }//end public function stringToken */

  /**
   * @return string
   */
  public function commentToken()
  {

    $comment = '';

    while( true )
    {

      $token  = next( $this->rawMatches );

      if( false === $token )
      {
        // comment @ line end, is ok
        return $comment;
      }


      if( "\n" == $token[0] )
      {
        return $comment;
      }
      else
      {
        $comment .= $token[0];
      }

    }

  }//end public function commentToken */


} // end class LibBdlLexer







