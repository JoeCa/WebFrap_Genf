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
class LibGenfParser
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * the builder class
   *
   * @var LibGenfBuild
   */
  protected $builder        = null;

  /**
   * the parser tree
   *
   * @var LibGenfTree
   */
  protected $tree           = null;

  /**
   * activ tree root
   *
   * @var LibGenfTreeRoot
   */
  protected $root           = null;

  /**
   * te activ node
   *
   * @var LibGenfTreeNode
   */
  protected $node           = null;

  /**
   * collecting all used lang entries
   *
   * @var LibCartridgeWbfI18n
   */
  protected $i18nPool       = null;

  /**
   * collecting all used lang entries
   *
   * @var LibGenfName
   */
  protected $name           = null;

  /**
   *
   * @var LibGenfName
   */
  protected $compName         = null;

  /**
   * a context to define diffrent points of views
   * @var string
   */
  protected $context         = 'table';


////////////////////////////////////////////////////////////////////////////////
// Magic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param LibGenfBuild      $builder
   * @param SimpleXmlElement  $root
   */
  public function __construct( $builder, $root = null  )
  {

    if($root)
      $this->root     = $root;

    $this->builder    = $builder;
    $this->tree       = $builder->getTree();
    $this->i18nPool   = LibCartridgeI18n::getInstance();

    // init method
    $this->init();

  }//end public function __construct */


  /**
   * @param sting $key
   */
  public function getName( $key = null )
  {
    return $this->name;
  }//end public function getName */

  /**
   * @param sting $key
   */
  public function setName( $name  )
  {
    $this->name = $name;
  }//end public function setName */

  /**
   * @param sting $key
   */
  public function setRoot( $root  )
  {
    $this->root = $root;
  }//end public function setRoot */

  /**
   * @param sting $key
   */
  public function setNode( $node  )
  {
    $this->node = $node;
  }//end public function setNode */

  /**
   *
   * @param string $type
   * @return LibGenfParser
   */
  public function getParser( $type )
  {
    return $this->builder->getParser( $type );
  }//end public function getParser */

  /**
   *
   * @param string $type
   * @return LibGenfGenerator
   */
  public function getGenerator( $type, $env = null )
  {

    if(!$env)
      $env = $this->env;

    return $this->builder->getGenerator( $type, $env );

  }//end public function getGenerator */

  /**
   * @param LibGenfName $compName
   */
  public function setComponentName( $compName )
  {

    $this->compName = $compName;

  }//end public function setComponentName */

  /**
   * @param string $context
   */
  public function setContext( $context )
  {

    $this->context = $context;

  }//end public function setContext */

  /**
   * @overwrite
   */
  public function init(){}

  /**
   *
   * @param unknown_type $num
   * @return unknown_type
   */
  public function wsp( $num )
  {
    return str_pad('', ($num * $this->builder->indentation) );
  }//end public function wsp */

}//end class LibGenfParser
