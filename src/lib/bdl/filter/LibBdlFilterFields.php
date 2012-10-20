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
class LibBdlFilterSources
  extends LibBdlFilter
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var array
   */
  public $srcIndex = array();

  /**
   *
   * @var boolean
   */
  public $catch = false;

////////////////////////////////////////////////////////////////////////////////
// method
////////////////////////////////////////////////////////////////////////////////

 /**
   * @return array
   */
  public function parse(  )
  {

    $this->srcIndex = array();
    $this->catch    = true;

    // first reset the lexer iteration pointer
    $this->lexer->reset();


    while( $token = $this->lexer->next() )
    {
      if( $this->catch && $token[0] == $this->t_identifier )
      {
        $this->parseEntity( $token );
      }

      if( $token[0] == $this->e_user  )
      {
        $this->lexer->next();
        $this->lexer->next();
      }

    }

    return $this->srcIndex;

  }//end public function parse */

/*//////////////////////////////////////////////////////////////////////////////
// parse
//////////////////////////////////////////////////////////////////////////////*/


 /**
   * @return array
   */
  public function parseEntity( $token  )
  {

    $activManagement = $token[1];

    // append the entity
    $this->srcIndex[$token[1]] = true;



    if(!$mgmtNode = $this->registry->builder->getManagement( $activManagement ))
    {
      $this->fail( 'Invalid Filter Fields, used noexisting source: '.$activManagement.' '. __METHOD__ , $token );
    }

    $this->expectToken( $this->c_dot,  __METHOD__.'::'.__LINE__ );

    $attributeToken =  $this->expectToken( $this->t_identifier,  __METHOD__.'::'.__LINE__ );

    if( $this->lexer->preview($this->c_dot)  )
    {
      $this->parseEntityPath(  $mgmtNode, $attributeToken );
    }

  }//end public function parseEntity */


  /**
   * @param LibGenfTreeNodeManagement $mgmtNode
   * @param array $attributeToken
   * @return array
   */
  public function parseEntityPath( $mgmtNode, $attributeToken  )
  {

    // wenn der nächste token kein path seperator ist, dann sind wir am
    // ende des pfades und können gehen
    if( !$this->lexer->preview( $this->c_dot, true ) )
      return;

    $attrName = $attributeToken[1];

    // append the entity
    if(!$targetAttr = $mgmtNode->entity->getAttribute( $attrName ))
    {
      $this->fail( 'requested noexisting attribute from: '.$mgmtNode->name->name , $attributeToken );
    }


    if(!$pathManagement = $targetAttr->targetManagement( $activManagement ))
    {
      $this->fail( 'Fields target '.$mgmtNode.' attribute has no target reference '.$targetAttr->name->name , $attributeToken );
    }

    $this->srcIndex[$targetAttr->targetKey()] = true;

    // zu beginn wurde geprüft ob wir schon am pfadende sind... wir sind es
    // noch nicht also weiter
    $attribute = $this->lexer->expectNext($this->t_identifier);
    $this->parseEntityPath( $attribute );

  }//end public function parseEntity */


} // end class LibBdlFilterSource

