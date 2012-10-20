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
class LibBdlFilterSqlCriteria
  extends LibBdlFilter
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * Der Type des letzte Pfades im Eintrag
   * @var string
   */
  protected $lastPathNodeType = null;
  
////////////////////////////////////////////////////////////////////////////////
// method
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param LibGenfTreeNode $node
   * @return array
   */
  public function parse( $node = null  )
  {
    
    $this->bdlNode = $node;

    // first reset the lexer iteration pointer
    $this->lexer->reset();

    $code = '';

    while( $token = $this->lexer->next() )
    {
      if( in_array($token[0], array( $this->t_identifier, $this->k_and, $this->c_open_parenthesis ) ) )
      {
        $this->lexer->back();
        $code .= $this->parseCriteriaCondition( );
      }
      else if( $token[0] == $this->k_or )
      {
        $code .= $this->parseCriteriaConditionOr(  );
      }
      else if( $token[0] == $this->k_not )
      {
        $code .= $this->subParserNot(  );
      }
      else
      {
        $this->unexpectedToken( $token, $this->t_identifier,  __METHOD__.'::'.__LINE__ );
      }
    }

    return $code;

  }//end public function parse */
  
  
 /**
   * @return array
   */
  public function subParserNot( )
  {

    $code = '';
    
    $token = $this->lexer->next();

    if( $token[0] == $this->t_identifier )
    {
      $this->lexer->back();
      $code .= $this->parseCriteriaCondition( true );
    }
    else if( $token[0] == $this->k_and )
    {
      $code .= $this->parseCriteriaCondition( true );
    }
    else if( $token[0] == $this->k_or )
    {
      $code .= $this->parseCriteriaConditionOr( true );
    }
    else
    {
      $this->unexpectedToken( $token, null,  __METHOD__.'::'.__LINE__ );
    }

    return $code;

  }//end public function subParserNot */

/*//////////////////////////////////////////////////////////////////////////////
// parse
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param array $token
   * @return string
   */
  public function parseCriteriaCondition( $not = false  )
  {

    if( $this->bdlNode )
    {
      if( $block = $this->bdlNode->getBlock() )
      {
        $code = $this->line( '$criteria->filterBlock( \''.$block.'\',' );
        $code .= $this->line( '"' );
      }
      else 
      {
        $code = $this->line( '$criteria->filter' );
        $code .= $this->line( '("' );
      }
    }
    else
    {
      $code = $this->line( '$criteria->filter' );
      $code .= $this->line( '("' );
    }
    

    $this->wsInc();
    
    if( $not )
      $code .= $this->line( ' NOT ' );
      
    if( $this->preview( $this->c_open_parenthesis )  )
    {
      $code .= '(';
      $this->next();
    }

    $code .= $this->parseIdentifier();

    if( $this->preview( $this->c_close_parenthesis )  )
    {
      $code .= ')';
      $this->next();
    }
    
    $code .= $this->parseCondition( );

    $this->wsDec();
    $code .= $this->line( '");' );

    return $code;

  }//end public function parseCriteriaCondition */

  /**
   * @param array $token
   * @return string
   */
  public function parseCriteriaConditionOr( $not = false )
  {

    if( $this->bdlNode )
    {
      if( $block = $this->bdlNode->getBlock() )
      {
        $code = $this->line( '$criteria->filterBlock( \''.$block.'\',' );
        $code .= $this->line( '"' );
      }
      else 
      {
        $code = $this->line( '$criteria->filter' );
        $code .= $this->line( '("' );
      }
    }
    else
    {
      $code = $this->line( '$criteria->filter' );
      $code .= $this->line( '("' );
    }
    
    
    
    $this->wsInc();

    if( $not )
      $code .= $this->line( ' NOT ' );

    if( $this->preview( $this->c_open_parenthesis )  )
    {
      $code .= '(';
      $this->next();
    }

    $code .= $this->parseIdentifier();

    if( $this->preview( $this->c_close_parenthesis )  )
    {
      $code .= ')';
      $this->next();
    }
    
    $code .= $this->parseCondition( );

    $this->wsDec();
    $code .= $this->cline( '",' );
    $code .= $this->line( '\'OR\'' );
    $this->wsDec();
    $code .= $this->line( ');' );

    return $code;

  }//end public function parseCriteriaConditionOr */

 /**
   * @return array
   */
  protected function parseCondition( )
  {

    $token = $this->lexer->next();
    
    if( !$token || $this->lexer->eol() )
      return '';

    $code = '';

    if( $token[0] == $this->k_and )
    {
      $code .= $this->parseAnd( );
    }
    else if( $token[0] == $this->k_or )
    {
      $code .= $this->parseOr(  );
    }
    else if( $token[0] == $this->c_not )
    {
      $code .= $this->parseNot(  );
    }    
    else if( $token[0] == $this->c_open_parenthesis )
    {
      $code .= '(';
    }    
    else if( $token[0] == $this->c_close_parenthesis )
    {
      $code .= ')';
    }
    else
    {
      $this->unexpectedToken( $token, $token[0],  __METHOD__.'::'.__LINE__ );
    }
    
    $code .= $this->parseCondition( );

    return $code;

  }//end protected function parseCondition */
  
 /**
   * @return array
   */
  protected function parseNot( )
  {

    $code = ' NOT ';
    
    $token = $this->lexer->next();

    if( $token[0] == $this->k_and )
    {
      $code .= $this->parseAnd( );
    }
    else if( $token[0] == $this->k_or )
    {
      $code .= $this->parseOr(  );
    }    
    else if( $token[0] == $this->c_open_parenthesis )
    {
      $code .= '(';
    }    
    else if( $token[0] == $this->c_close_parenthesis )
    {
      $code .= ')';
    }
    else
    {
      $this->unexpectedToken( $token, null,  __METHOD__.'::'.__LINE__ );
    }
    
    $code .= $this->parseCondition( );

    return $code;

  }//end protected function parseNot */

   /**
   * @return array
   */
  protected function parseAnd( )
  {

    $code = ' AND ';
    
    if( $this->preview($this->c_open_parenthesis)  )
    {
      $code .= '(';
      $this->next();
    }
    
    $code .= $this->parseIdentifier();
    
    if( $this->preview($this->c_close_parenthesis)  )
    {
      $code .= ')';
      $this->next();
    }
    
    $code .= $this->parseCondition( );

    return $code;
    
  }//end protected function parseAnd */
  
   /**
   * @return array
   */
  protected function parseOr( )
  {

    $code = ' OR ';
    
    if( $this->preview($this->c_open_parenthesis)  )
    {
      $code .= '(';
      $this->next();
    }
    
    $code .= $this->parseIdentifier();
    
    if( $this->preview($this->c_close_parenthesis)  )
    {
      $code .= ')';
      $this->next();
    }

    $code .= $this->parseCondition( );
    
    return $code;

  }//end protected function parseOr */
  
  
   /**
   * @return array
   */
  protected function parseIdentifier( )
  {
    
    $code = '';

    $token = $this->expectToken( $this->t_identifier,  __METHOD__.'::'.__LINE__ );
    
    $this->expectToken($this->c_dot);
    $attributeToken = $this->expectToken( $this->t_identifier );

    if( $this->lexer->preview($this->c_dot)  )
    {

      $managementName = $token[1];
      if( !$mgmtNode = $this->registry->builder->getManagement( $managementName ) )
      {
        $this->fail( 'Requested noexisting management from: '.$managementName , $token );
      }

      $code .= $this->parseConditionPath( $mgmtNode, $attributeToken );
    }
    else
    {
      $code .= $this->sLine( "{$token[1]}.{$attributeToken[1]}" );
      $code .= $this->parseConditionType();
    }
    
    return $code;

  }//end protected function parseOr */

  /**
   * @return string
   */
  protected function parseConditionType()
  {

    $code = '';
    $next = $this->lexer->next();

    $validCond = array
    (
      $this->c_smaller          => '<',
      $this->c_bigger           => '>',
      $this->c_equals           => '=',
      $this->c_smaller_or_equal => '<=',
      $this->c_bigger_or_equal  => '=>',
    );

    if( isset( $validCond[$next[0]] )   )
    {
      $code .= $this->sLine( " {$validCond[$next[0]]} " );
      $code .= $this->parseConditionRightSide();
    }
    elseif( $this->k_in == $next[0] )
    {
      $this->lexer->expectNext( $this->c_open_parenthesis );

      $code .= ' IN( ';
      $code .= $this->parseConditionIn();
      $code .= $this->cLine( ' )' );
    }
    elseif( $this->k_is == $next[0] )
    {
      $code .= ' IS ';
      $code .= $this->parseConditionIs();
    }
    elseif( $this->k_not == $next[0] )
    {
      $code .= ' NOT ';
      $code .= $this->parseConditionType();
    }
    else
    {
      $this->unexpectedToken( $next, null, __METHOD__.'::'.__LINE__ );
    }

    return $code;

  }//end protected function parseConditionType */



  /**
   * @return string
   */
  protected function parseConditionRightSide()
  {

    $code = '';

    $next = $this->lexer->next();

    if( $next[0] == $this->t_string  )
    {
      $code .= $this->cLine( " '{$next[1]}' " );
    }
    else if( $next[0] == $this->t_identifier  )
    {
      $code .= $this->cLine( " {$next[1]} " );
    }
    else if( $next[0] == $this->t_integer || $next[0] == $this->t_float  )
    {
      $code .= $this->cLine( " {$next[1]} " );
    }
    else if( $next[0] == $this->e_user )
    {
      $gen  = new LibBdlFilterElementUser( $this->registry, $this->lexer );
      $code .= '".'.$gen->build().'."';
    }
    else if( $next[0] == $this->e_var )
    {
      //$gen  = new LibBdlFilterElementUser( $this->registry, $this->lexer );
      $code .= '\'".$'.$next[1].'."\'';
    }
    else if( $next[0] == $this->e_member_var )
    {
      //$gen  = new LibBdlFilterElementUser( $this->registry, $this->lexer );
      $code .= '\'".$this->'.$next[1].'."\'';
    }
    else
    {
      $this->unexpectedToken( $next, null, __METHOD__.'::'.__LINE__ );
    }

    return $code;

  }//end protected function parseConditionRightSide */

  /**
   * @return string
   */
  protected function parseConditionIn()
  {

    $code = '';

    $next = $this->lexer->next();

    if( $next[0] == $this->t_string  )
    {
      $code .=  "'{$next[1]}'";
    }
    else if( $next[0] == $this->t_integer || $next[0] == $this->t_float  )
    {
      $code .=  "{$next[1]}";
    }
    else
    {
      $this->unexpectedToken( $next, null, __METHOD__.'::'.__LINE__ );
    }

    $next = $this->lexer->next();

    if( $next[0] == $this->c_close_parenthesis )
    {
      return $code;
    }
    elseif ( $this->c_comma )
    {
      $code .= ', '.$this->parseConditionIn();
    }
    else
    {
      $this->unexpectedToken( $next, null, __METHOD__.'::'.__LINE__ );
    }

    return $code;

  }//end protected function parseConditionIn */

  /**
   * @return string
   */
  protected function parseConditionIs()
  {

    $code = '';

    $next = $this->lexer->next();

    if( $next[0] == $this->k_null )
    {
      $code .=  " null ";
    }
    else
    {
      $this->unexpectedToken( $next, null, __METHOD__.'::'.__LINE__ );
    }

    return $code;

  }//end protected function parseConditionIs */

  /**
   * @param LibGenfTreeNodeManagement $mgmtNode
   * @param array $attributeToken
   * @param string $prepend
   * @return string
   */
  public function parseConditionPath( $mgmtNode, $attributeToken, $prepend = '' )
  {

    $attrName = $attributeToken[1];
    // append the entity
    if( !$targetAttr = $mgmtNode->entity->getAttribute( $attrName ) )
    {
      /* @var $targetAttr LibGenfTreeNodeAttribute */
      $this->fail( 'requested noexisting attribute from: '.$mgmtNode->name->name , $attributeToken );
    }

    // wenn der nächste token kein path seperator ist, dann sind wir am
    // ende des pfades und können gehen
    // wenn es ein punkt ist wird dieser entfernt
    // damit muss der nächste token der in else erfragt wird ein identifier sein
    if( !$this->lexer->preview( $this->c_dot, true ) )
    {
      
      $this->lastPathNodeType = $targetAttr->dbType();

      $code = $this->sLine( "{$prepend} {$mgmtNode->name->name}.{$attrName} " );
      $code .= $this->parseConditionType();
      return $code;
    }
    else
    {

      if( !$pathManagement = $targetAttr->targetManagement() )
      {
        $this->fail
        (
          'Criteria target '.$mgmtNode.' attribute has no target reference '.$targetAttr->name->name ,
          $attributeToken
        );
      }

      // bei if wurde der pfad seperator soweit vorhanden entfernt
      // hier kann also nur ein idenftier stehen oder der input is invalid
      $pathAttribute = $this->lexer->expectNext($this->t_identifier);
      return $this->parseConditionPath( $pathManagement, $pathAttribute );
    }

  }//end public function parseConditionPath */


} // end class LibBdlFilterSource

