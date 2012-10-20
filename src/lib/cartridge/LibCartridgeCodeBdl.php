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
 * @subpackage ModGenf
 */
abstract class LibCartridgeCodeBdl
  extends LibCartridgeCode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var unknown_type
   */
  protected $cartridge        = null;

  /**
   *
   * @var unknown_type
   */
  protected $entityCategory   = array();

  /**
   *
   * @var CartridgeRegistryBdl
   */
  protected $registry         = null;

////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param SimpleXMLElement $xml
   */
  public function __construct(  $cartridge = null )
  {

    $this->cartridge  = $cartridge;
    $this->langParser = LibCartridgeI18n::getInstance();

    $this->date = SDate::getDate();

    $this->init();

  }//end public function __construct

////////////////////////////////////////////////////////////////////////////////
// Check Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   */
  protected function cleanCategories()
  {

    $this->entityCategory = array();
    $this->entityCategory['default'] = new TCategoryNode( 'General Information' );

  }//end protected function cleanCategories */

  /**
   *
   * @param $eEntity
   * @return unknown_type
   */
  protected function importCategories( $eEntity )
  {

    if( isset($eEntity->attributes->categories) )
    {
      foreach( $eEntity->attributes->categories->category as $category )
      {
        $name   = (string)$category['name'];
        $label  = (string)$category['label'];
        $type   = isset($category['type']) ? (string)$category['type']:'2';

        if( !isset($this->entityCategory[$name]) )
        {
          $this->entityCategory[$name] = new TCategoryNode( $label , $type );
        }
        else
        {
          // if the groupname has the same
          if($this->entityCategory[$name]->name == $name )
            $this->entityCategory[$name]->name = $label;
        }
      }
    }

  }//end protected function importCategories */

  /**
   *
   * @param $attribute
   * @return unknown_type
   */
  protected function checkAttrGroup( $attribute )
  {

    if( isset( $attribute->groups) && isset( $attribute->groups['name']  ) )
    {

       $category = (string)$attribute->groups['name'];

      if( !isset( $this->entityCategory[$category] ) )
        $this->entityCategory[$category] = new TGroupNode( $category );

      return $category;
    }
    else
    {
      return 'default';
    }

  }//end protected function checkAttrGroup */

  /**
   *
   * @return unknown_type
   */
  public function isRefTable( $entityName = null )
  {

    $doubleBlock = array();

    $r = $this->registry;

    if( !$entityName )
      $entityName =  $r->tableName;

    $isRef = array();

    foreach( $r->entities as $entity )
    {
      if( !isset($entity->references) )
        continue;

      $embTab = $entity->references;

      foreach( $embTab->ref as $refTab  )
      {

        if( strtolower($refTab['relation']) == Bdl::MANY_TO_MANY && (string)$refTab['connection']  == $entityName )
        {

          ///TODO check if to use the alias of the target here
          if( isset( $refTab['target_alias'] ) )
            $key = (string)$refTab['target_alias'];
          else
            $key = (string)$refTab['target'];

          // check if don't have any references till now for that
          if( !in_array( $key,  $doubleBlock ) )
          {
            $doubleBlock[] = $key;
            $isRef[] = $refTab;
          }

        }
      }

    }


    return $isRef;

  }//end public function isRefTable

  /**
   * Enter description here...
   *
   */
  public function write()
  {

    $r = $this->registry;

    // den Genf Code Wegschreiben
    foreach( $this->genfCode as $mod => $elements )
    {
      foreach( $elements as $name => $code )
      {
        $fileName = $this->classType.SParserString::subToCamelCase($name);
        $classPath = SParserString::getClassPath($fileName,false);
        $folderPath = $this->outputFolder.'genf/'.$mod.'/src/'.$classPath;
        $this->writeFile($code , $folderPath , $fileName.'.php' );
      }
    }//end foreach

    // den Genf Code Wegschreiben
    foreach( $this->handCode as $mod => $elements )
    {
      foreach( $elements as $name => $code )
      {
        $fileName = $this->classType.SParserString::subToCamelCase($name);
        $classPath = SParserString::getClassPath($fileName,false);
        $folderPath = $this->outputFolder.'hand/'.$mod.'/src/'.$classPath;
        $this->writeFile($code , $folderPath , $fileName.'.php' );
      }
    }//end foreach

  }//end public function write

} // end abstract class LibCartridgeAbstract
