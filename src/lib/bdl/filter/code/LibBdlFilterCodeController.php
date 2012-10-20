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
class LibBdlFilterCodeController
  extends LibBdlFilter
{
////////////////////////////////////////////////////////////////////////////////
// method
////////////////////////////////////////////////////////////////////////////////


  public function parse()
  {
    return $this->compile();
  }

 /**
   * @return array
   */
  public function compile(  )
  {

    // first reset the lexer iteration pointer
    $this->lexer->reset();

    // reset flags
    $this->flag = new TArray();

    $code = '';

    while( $token = $this->lexer->next() )
    {
      if( $token[0] == $this->t_identifier )
      {
        $code .= $this->parseCondition( $token );
      }
      else if( $token[0] == $this->k_and )
      {
        $token = $this->expectToken($this->t_identifier,  __METHOD__.'::'.__LINE__);
        $code .= $this->parseCondition( $token );
      }
      else if( $token[0] == $this->k_or )
      {
        $token = $this->expectToken($this->t_identifier,  __METHOD__.'::'.__LINE__);
        $code .= $this->parseConditionOr( $token );
      }
      else
      {
        $this->unexpectedToken( $token, $this->t_identifier, __METHOD__.'::'.__LINE__ );
      }
    }

    return $code;

  }//end public function parse */

/*//////////////////////////////////////////////////////////////////////////////
// parse
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * @param array $token
   * @return string
   */
  public function parseCondition( $token  )
  {


    $code = $this->line( '$criteria->where' );
    $code .= $this->line( '(' );
    $this->wsInc();


    $this->expectToken($this->c_dot,  __METHOD__.'::'.__LINE__);
    $attributeToken = $this->expectToken( $this->t_identifier,  __METHOD__.'::'.__LINE__ );

    if( $this->lexer->preview($this->c_dot)  )
    {

      $managementName = $token[1];
      if( !$mgmtNode = $this->registry->builder->getManagement( $managementName ) )
      {
        $this->fail( 'requested noexisting management from: '.$managementName , $token );
      }

      $code .= $this->parseConditionPath( $mgmtNode, $attributeToken );
    }
    else
    {
      $code .= $this->sLine( "\" {$token[1]}.{$attributeToken[1]}" );
      $code .= $this->parseConditionType();
    }

    $this->wsDec();
    $code .= $this->line( '");' );

    return $code;

  }//end public function parseNewCondition */

  /**
   * @param array $token
   * @return string
   */
  public function parseConditionOr( $token  )
  {


    $code = $this->line( '$criteria->orWhere' );
    $code .= $this->line( '(' );
    $this->wsInc();


    $this->expectToken($this->c_dot,  __METHOD__.'::'.__LINE__);
    $attributeToken = $this->expectToken( $this->t_identifier,  __METHOD__.'::'.__LINE__ );

    if( $this->lexer->preview($this->c_dot)  )
    {

      $managementName = $token[1];
      if( !$mgmtNode = $this->registry->builder->getManagement( $managementName ) )
      {
        $this->fail( 'requested noexisting management from: '.$managementName , $token );
      }

      $code .= $this->parseConditionPath( $mgmtNode, $attributeToken );
    }
    else
    {
      $code .= $this->sLine( "\" {$token[1]}.{$attributeToken[1]}" );
      $code .= $this->parseConditionType();
    }

    $this->wsDec();
    $code .= $this->line( ');' );

    return $code;

  }//end public function parseConditionOr */




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

      $code .= 'IN( ';
      $code .= $this->parseConditionIn();
      $code .= $this->cLine( ' )"' );
    }
    else
    {
      $this->unexpectedToken( $next, $this->t_identifier, __METHOD__.'::'.__LINE__ );
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
    else if( $next[0] == $this->t_integer || $next[0] == $this->t_float  )
    {
      $code .= $this->cLine( " {$next[1]} " );
    }
    else if( $next[0] == $this->e_user )
    {
      $gen  = new LibBdlFilterElementUser( $this->registry, $this->lexer );
      $code .= '".'.$gen->build().'."';
    }
    else
    {
      $this->unexpectedToken( $next, $this->t_identifier, __METHOD__.'::'.__LINE__ );
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
      $this->unexpectedToken( $next, $this->t_identifier, __METHOD__.'::'.__LINE__ );
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
      $this->unexpectedToken( $next, $this->t_identifier, __METHOD__.'::'.__LINE__ );
    }

    return $code;

  }//end protected function parseConditionIn */

  /**
   * @param LibGenfTreeNodeManagement $mgmtNode
   * @param array $attributeToken
   * @return string
   */
  public function parseConditionPath( $mgmtNode, $attributeToken )
  {

    $attrName = $attributeToken[1];
    // append the entity
    if(!$targetAttr = $mgmtNode->entity->getAttribute( $attrName ))
    {
      $this->fail( 'requested noexisting attribute from: '.$mgmtNode->name->name , $attributeToken );
    }

    // wenn der nächste token kein path seperator ist, dann sind wir am
    // ende des pfades und können gehen
    // wenn es ein punkt ist wird dieser entfernt
    // damit muss der nächste token der in else erfragt wird ein identifier sein
    if( !$this->lexer->preview( $this->c_dot, true ) )
    {

      $code = $this->sLine( "\" {$mgmtNode->name->name}.{$attrName} " );
      $code .= $this->parseConditionType();
      return $code;
    }
    else
    {

      if(!$pathManagement = $targetAttr->targetManagement(  ))
      {
        $this->fail( 'Code target '.$mgmtNode.' attribute has no target reference '.$targetAttr->name->name , $attributeToken );
      }

      // bei if wurde der pfad seperator soweit vorhanden entfernt
      // hier kann also nur ein idenftier stehen oder der input is invalid
      $pathAttribute = $this->lexer->expectNext($this->t_identifier);
      return $this->parseConditionPath( $pathManagement, $pathAttribute );
    }

  }//end public function parseConditionPath */


} // end class LibBdlFilterSource

