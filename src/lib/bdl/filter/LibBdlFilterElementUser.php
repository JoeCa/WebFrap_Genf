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
class LibBdlFilterElementUser
  extends LibBdlFilter
{
////////////////////////////////////////////////////////////////////////////////
// method
////////////////////////////////////////////////////////////////////////////////

 /**
   * @return array
   */
  public function build(   )
  {

    //Debug::console('in user');

    $code = '';

    $code .= "\$this->getUser()";

    $this->expectToken( $this->c_dot,  __METHOD__.'::'.__LINE__ );

    $next = $this->expectToken( $this->t_identifier,  __METHOD__.'::'.__LINE__ );

    switch( $next['1'] )
    {
      case 'id':
      {
        $code .= $this->buildId();
        break;
      }
      default:
      {
        $this->unexpectedIdentifier($next,array('in'));
        break;
      }
    }

    return $code;

  }//end public function parse */

/*//////////////////////////////////////////////////////////////////////////////
// parse
//////////////////////////////////////////////////////////////////////////////*/



  /**
   * @return string
   */
  protected function buildId()
  {

    $code = '->getId()';

    return $code;

  }//end protected function parseConditionRightSide */



} // end class LibBdlFilterSource

