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
abstract class LibCartridgeBdlEntity
  extends LibCartridge
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var array
   */
  protected $categories   = array();

  /**
   * @var string
   */
  protected $nodeType     = 'Entity';

  /**
   * @var LibGenfTreeRootEntity
   */
  protected $root         = null;

////////////////////////////////////////////////////////////////////////////////
// Category methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   */
  protected function cleanCategories()
  {

    $this->categories = array();
    $this->categories['default'] = new TCategoryNode( 'General Information' );

  }//end protected function cleanCategories */

  /**
   * @param $eEntity
   * @return unknown_type
   */
  protected function importCategories( $eEntity )
  {

    if( isset( $eEntity->attributes->categories ) )
    {
      foreach( $eEntity->attributes->categories->category as $category )
      {
        $name   = (string)$category['name'];
        $label  = (string)$category['label'];
        $type   = isset($category['type']) ? (string)$category['type']:'2';

        if( !isset($this->categories[$name]) )
        {
          $this->categories[$name] = new TCategoryNode( $label , $type );
        }
        else
        {
          // if the groupname has the same
          if($this->categories[$name]->name == $name )
            $this->categories[$name]->name = $label;
        }
      }
    }

  }//end protected function importCategories */

  /**
   *
   * @param string $attribute
   * @return string the attribute category
   */
  protected function getDefaultCategory( $attribute )
  {

    if( isset($attribute->categories) && isset($attribute->categories['default']) )
    {

      $category = (string)$attribute->categories['default'];

      if( !isset( $this->categories[$category] ) )
        $this->categories[$category] = new TCategoryNode( $category );

      return $category;
    }
    else
    {
      return 'default';
    }

  }//end protected function getDefaultCategory */

} // end abstract class LibCartridgeBdlEntity
