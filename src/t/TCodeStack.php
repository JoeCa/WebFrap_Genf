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
 * Context Envelop to be able to sourround something with a context
 *
 * @package WebFrap
 * @subpackage WebFrap
 */
class TCodeStack
  extends TArray
{

  /**
   * @param string $key
   * @param int $idention
   */
  public function getCode( $key, $idention )
  {
    
    if( isset($this->pool[$key]) )
      return SParserString::setIndentinon($this->pool[$key], $idention);
    else
      return '';
      
  }//end public function getCode */
  
  /**
   * @param string $position
   * @param string $code
   */
  public function appendCode( $position, $code )
  {

    if( isset($this->pool[$position]) )
    {
      $this->pool[$position] .= NL.$code;
    }
    else
    {
      $this->pool[$position] = $code;
    }

  }//end public function appendCode */

  /**
   * @param string $position
   * @param string $code
   */
  public function prependCode( $position, $code )
  {

    if( isset($this->pool[$position]) )
    {
      $this->pool[$position] = $code.NL.$this->pool[$position];
    }
    else
    {
      $this->pool[$position] = $code;
    }

  }//end public function prependCode */

}//end class TCodeStack

