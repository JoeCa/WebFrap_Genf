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
class LibGenfTreeNodeLayoutCol
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var int
   */
  public $weight      = 0;

  /**
   * @var array
   */
  public $elements    = array();

  /**
   *
   * @var string
   */
  public $type        = null;

  /**
   *
   * @var string
   */
  public $class       = null;

  /**
   *
   * @var float
   */
  public $size        = null;

  /**
   *
   * @var string
   */
  public $align       = null;

  /**
   *
   * @var string
   */
  public $fill        = null;

  /**
   *
   * @var unknown_type
   */
  public $name        = null;


////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   * calculate the weight of the layout
   * @return int
   */
  public function weight()
  {
    $weight = 0;

    foreach( $this->elements as $attr )
    {
      $weight +=  $attr->weight();
    }

    return $weight;
  }//end public function weight */

}//end class LibGenfTreeNodeLayout

