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
 * @subpackage Genf
 */
class LibParserWbfSize
  extends LibGenfParser
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var array<string:array<string,float>>
   */
  public $classTypes = array
  (
    '1'   => array('full'       , 1     ),
    '1/2' => array('half'       , 0.5   ),
    '1/3' => array('third'      , 0.33  ),
    '1/4' => array('fourth'     , 0.25  ),
    '1/5' => array('fifth'      , 0.2   ),
    '1/6' => array('sixth'      , 0.16  ),
    '2/3' => array('two_third'  , 0.66  ),
    '3/4' => array('tree_fourth', 0.75  ),
    '2/5' => array('two_fifth'  , 0.4   ),
    '3/5' => array('three_fifth', 0.6   ),
    '4/5' => array('four_fifth' , 0.8   ),
    '4/6' => array('four_sixth' , 0.66  ),
    '5/6' => array('five_sixth' , 0.83  ),
  );
  
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @param string
   * @return string
   */
  public function getClass( $key )
  {
    return isset( $this->classTypes[$key] )
      ? $this->classTypes[$key][0]
      : null;
  }//end public function getClass */
  
  /**
   * @param string
   * @return float
   */
  public function getFactor( $key )
  {
    return isset( $this->classTypes[$key] )
      ? $this->classTypes[$key][1]
      : null;
  }//end public function getFactor */


} // end class LibParserWbfI18ntext
