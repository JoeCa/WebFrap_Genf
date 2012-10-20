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
class LibBdlAccessContainer
  extends LibBdlFilter
{
////////////////////////////////////////////////////////////////////////////////
// method
////////////////////////////////////////////////////////////////////////////////

 /**
   * @return array
   */
  public function parse(  )
  {

    // first reset the lexer iteration pointer
    $this->lexer->reset();

    $code = '';


    while( $token = $this->lexer->next() )
    {
      if( $token[0] == $this->k_check )
      {
        $token = $this->expectToken( $this->t_identifier,  __METHOD__.'::'.__LINE__ );
        $code .= $this->compile_check( $token );
      }
      else
      {
        $this->unexpectedToken( $token, $this->t_identifier,  __METHOD__.'::'.__LINE__ );
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
  public function compile_check( $token  )
  {

    $code = '';

    $this->expectToken( $this->c_dot );
    $attributeToken = $this->expectToken( $this->t_identifier );

    if( $this->lexer->preview( $this->c_dot )  )
    {

      $managementName = $token[1];

      if( !$mgmtNode = $this->registry->builder->getManagement( $managementName ) )
      {
        $this->fail( 'requested noexisting management from: '.$managementName , $token );
      }

      $code .= $this->compile_checkPath( $mgmtNode, $attributeToken );

    }
    else
    {
      $code .= $this->compile_checkPathType( );
    }


    return $code;

  }//end public function parseNewCondition */


  /**
   * @return string
   */
  protected function compile_checkPathType()
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
   * @param LibGenfTreeNodeManagement $mgmtNode
   * @param array $attributeToken
   * @return string
   */
  public function compile_checkPath( $mgmtNode, $attributeToken )
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
      $code .= $this->compile_checkPathType();
      return $code;
    }
    else
    {

      if(!$pathManagement = $targetAttr->targetManagement(  ))
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
      return $this->compile_checkPath( $pathManagement, $pathAttribute );
    }

  }//end public function parseConditionPath */


} // end class LibBdlAccessContainer

