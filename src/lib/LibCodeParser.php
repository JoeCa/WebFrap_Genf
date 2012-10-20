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
 * A Base class for code builder classes
 * @package WebFrap
 * @subpackage GenF
 */
abstract class LibCodeParser
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * the builder class
   *
   * @var LibGenfBuilder
   */
  protected $builder        = null;

  /**
   * the parser tree
   *
   * @var LibGenfTree
   */
  protected $tree           = null;

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


////////////////////////////////////////////////////////////////////////////////
// Magic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param String $xmlFile the Filepath of the Metamodel to parse
   * @param SimpleXMLElement $xml
   */
  public function __construct( $builder, $node  )
  {

    $this->builder    = $builder;
    $this->tree       = $builder->getTree();

    // dirty hack hrhr to get a better naming
    $this->node       = $node;
    $this->i18nPool   = LibCartridgeI18n::getInstance();


  }//end public function __construct */


} // end abstract class LibBuilder
