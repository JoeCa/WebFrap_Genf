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
 *
 * @stateless
 */
class LibGenfTreeSearchLayout
{

  /**
   *
   * Enter description here ...
   * @var unknown_type
   */
  private static $instance;

  /**
   * @return LibGenfTreeSearchLayout
   */
  public static function get()
  {
    if(!self::$instance)
      self::$instance = new LibGenfTreeSearchLayout();

    return self::$instance;

  }//end public static function get */


  /**
   * @param array $fields
   * @param SimpleXmlElement $node
   */
  public function extractLayoutReferences( $references, $node )
  {

    if( !$children = $node->children() )
      return $references;

    $tagNames = array( 'reference' );

    foreach( $children as $child )
    {

      if( in_array( $child->getName(), $tagNames )  )
      {
        $references[] = $child;
      }
      else
      {
        $references = $this->extractLayoutReferences( $references, $child );
      }
    }

    return $references;

  }//end public function extractLayoutReferences */



} // end class LibGenfTreeBdl
