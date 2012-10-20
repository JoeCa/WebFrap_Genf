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
class LibBdlElementView
  extends LibBdlSubParser
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  public function parse( $token )
  {

    $code       = '';
    $nextToken  = $this->lexer->next();

    if( $nextToken[0] == $this->c_assign )
    {
      $code .= $this->parseAssign();
    }
    else if( $nextToken[0] == $this->k_display )
    {
      $code .= $this->parseDisplay();
    }
    else
    {
      $this->unexpectedToken( $nextToken, null, __METHOD__.'::'.__LINE__ );
    }

    return $code;


  }//end public function parse */

/*//////////////////////////////////////////////////////////////////////////////
// call
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   */
  public function parseCall()
  {
    $code = '';

    $nextToken = $this->lexer->next();
    if( !$nextToken[0] == $this->t_identifier  )
    {
      $this->unexpectedToken( $nextToken, $this->t_identifier, __METHOD__.'::'.__LINE__ );
    }

    switch( strtolower($nextToken[1]) )
    {

      case 'display':
      {
        $code .= $this->parseDisplay();
        break;
      }
      case 'closewindow':
      {
        $code .= $this->parseCloseWindow();
        break;
      }

    }

    return $code;

  }//end public function parseCall */



/*//////////////////////////////////////////////////////////////////////////////
// assign
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return string
   */
  public function parseAssign()
  {

    $code = $this->sLine('$view  = ');

    $nextToken = $this->lexer->next();
    if( $nextToken[0] == $this->k_window  )
    {
      $code .= $this->parseAssignWindow().$this->nl();
      $code .= $this->line('$view->setModel( $this->model );');
    }
    else
    {
      $this->unexpectedToken( $nextToken, null, __METHOD__.'::'.__LINE__ );
    }

    return $code;

  }//end public function parseAssign */

  /**
   *
   */
  public function parseAssignWindow()
  {

    $nextToken = $this->lexer->next();
    if( !$nextToken[0] == $this->t_identifier  )
    {
      $this->unexpectedToken( $nextToken, $this->t_identifier, __METHOD__.'::'.__LINE__);
    }

    $className  = SParserString::subToCamelcase( $nextToken[1] );
    $name       = $this->getName();

    $code = <<<CODE
 \$this->tplEngine->newWindow('form_{$name->name}', '{$className}' );
CODE;

    $this->expectToken( $this->c_semicolon );

    return $code;

  }//end public function parseAssignWindow */


/*//////////////////////////////////////////////////////////////////////////////
// display
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return string
   */
  public function parseDisplay()
  {

    $code       = '';
    $nextToken  = $this->lexer->next();

    if( !$nextToken[0] == $this->t_identifier )
    {
      $this->unexpectedToken( $nextToken, null, __METHOD__.'::'.__LINE__ );
    }

    $prevToken = $this->lexer->preview();

    if( $prevToken[0] == $this->k_context )
    {
      $this->lexer->next();
      $contextToken = $this->lexer->next();

      if( $contextToken[0] != $this->t_identifier )
      {
        $this->unexpectedToken( $contextToken, null, __METHOD__.'::'.__LINE__ );
      }

      $context = $contextToken[1];
    }
    else
    {
      $context = $this->getContext();
    }

    switch( $context )
    {
      case 'create':
      {

        $code .= $this->Line( 'if( $view->display'.$nextToken[1].'( $this->getRequest(), $params ) )' );
        $code .= $this->line( '{' );
        $this->wsInc();
        $code .= $this->line( '$this->errorPage' );
        $code .= $this->line( '(' );
        $this->wsInc();
        $code .= $this->line( '$this->tplEngine->i18n->l' );
        $code .= $this->line( '(' );
        $this->wsInc();
        $code .= $this->line( "'Request Failed'," );
        $code .= $this->line( "'wbf.message.requestFailed'" );
        $this->wsDec();
        $code .= $this->line( ')' );
        $this->wsDec();
        $code .= $this->line( ');' );
        $code .= $this->line( 'return false;' );
        $this->wsDec();
        $code .= $this->line( '}' );

        break;
      }
      case 'edit':
      {
        $code .= $this->Line( 'if( $view->display'.$nextToken[1].'( $objid, $this->getRequest(), $params ))' );
        $code .= $this->line( '{' );
        $this->wsInc();
        $code .= $this->line( '$this->errorPage' );
        $code .= $this->line( '(' );
        $this->wsInc();
        $code .= $this->line( '$this->tplEngine->i18n->l' );
        $code .= $this->line( '(' );
        $this->wsInc();
        $code .= $this->line( "'Request Failed'," );
        $code .= $this->line( "'wbf.message.requestFailed'" );
        $this->wsDec();
        $code .= $this->line( ')' );
        $this->wsDec();
        $code .= $this->line( ');' );
        $code .= $this->line( 'return false;' );
        $this->wsDec();
        $code .= $this->line( '}' );

        break;
      }
      case 'table':
      {
        break;
      }
      default:
      {
        $this->notYetImplemented('Implement a context parser for context '.$context.' in element view!');
      }

    }//end switch


    $this->lineEnd( true );

    return $code;

  }//end public function parseAssign */

} // end class LibBdlLexer







