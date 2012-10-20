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
 * TreeNode for Fks
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeNodeTriggerAction
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// Attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var string
   */
  public $type = null;
  
  /**
   *
   * @var string
   */
  public $mode = null;

////////////////////////////////////////////////////////////////////////////////
// Method
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @param SimpleXmlElement $node
   * @param string $action
   */
  public function __construct( $node )
  {
    parent::__construct( $node );
    $this->name = new LibGenfNameNode( $this->node );

  }//end public function __construct */

////////////////////////////////////////////////////////////////////////////////
// getter + setter
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @return string
   */
  public function getName()
  {

    return $this->name;

  }//end public function getName */
  
  /**
   * @return string
   */
  public function getTitle()
  {
    
    if( !isset( $this->node->title ) )
      return null;

    return $this->i18nValue( $this->node->title, 'en', '"' );

  }//end public function getTitle */
  
  /**
   * @return string
   */
  public function getTooltip()
  {
    
    if( !isset( $this->node->tooltip ) )
      return null;

    return $this->i18nValue( $this->node->tooltip, 'en', '"' );

  }//end public function getTooltip */

  /**
   * @return string
   */
  public function getIconName()
  {

    if( !isset( $this->node->icon ) )
      return null;

    return ucfirst( trim( $this->node->icon['name'] ) );
    
  }//end public function getIconName */
  
  /**
   * @return string
   */
  public function getIconSrc()
  {

    if( !isset($this->node->icon) )
      return null;
      
    if( isset( $this->node->icon['src'] ) )
      return trim( $this->node->icon['src'] );

    return trim( $this->node->icon );
    
  }//end public function getIconSrc */

  /**
   * @return string
   */
  public function getIcon()
  {

    if( !isset( $this->node->icon ) )
      return null;
      
    if( isset( $this->node->icon['src'] ) )
      return trim( $this->node->icon['src'] );

    return trim( $this->node->icon );
    
  }//end public function getIcon */

  /**
   * @return string
   */
  public function getIconAlt()
  {
    if( !isset( $this->node->icon['alt'] ) )
      return trim( $this->node->icon['name'] );

    return trim( $this->node->icon['alt'] );

  }//end public function getIconAlt */

  /**
   * @return string
   */
  public function getIconData()
  {
    if( !isset( $this->node->icon ) )
      return null;

    return array( trim($this->node->icon), trim($this->node->icon['alt']) );

  }//end public function getIconData */

  /**
   * @return string
   */
  public function getType()
  {

    if( !isset($this->node['type']) )
      return 'append';

    return trim($this->node['type']);
    
  }//end public function getType */
  
  /**
   * @return string
   */
  public function getTriggerType()
  {

    if( !isset($this->node->trigger['type']) )
      return 'link';

    return trim($this->node['type']);
    
  }//end public function getTriggerType */

  /**
   * @return string
   */
  public function getClass()
  {

    if( !isset($this->node['class']) )
      return null;

    return trim( $this->node['class'] );

  }//end public function getClass */


  /**
   * @return string
   */
  public function getPosition()
  {

    if( !isset( $this->node['position'] ) )
      return 'default';

    return trim($this->node['position']);

  }//end public function getPosition */


  /**
   * @return string
   */
  public function getAction()
  {

    if( !isset($this->node->action) )
      return null;

    return trim($this->node->action);

  }//end public function getAction */

  /**
   * @return string
   */
  public function getActionCall()
  {

    if( !isset($this->node->action['call']) )
      return null;

    return trim($this->node->action['call']);

  }//end public function getActionCall */

  /**
   * @return string
   */
  public function getActionType()
  {

    if( !isset($this->node->action['type']) )
      return 'win';

    $type = trim($this->node->action['type']);

    if('window'== $type)
      return 'win';
    else
      return $type;

  }//end public function getActionType */

  /**
   * @return string
   */
  public function getActionContext()
  {

    if( !isset( $this->node->action['context'] ) )
      return 'reference';

    return trim( $this->node->action['context'] );

  }//end public function getActionContext

  /**
   * @return LibGeneratorWbfTriggerAction
   */
  public function getTriggerGenerator( $env )
  {
    
    $className = 'Trigger_'.SParserString::subToCamelCase($this->type);
    
    if( Webfrap::classLoadable( 'LibGeneratorWbf'.$className ) )
    {
      return $this->builder->getGenerator( $className, $env );
    }
    
    return null;
    
  }//end public function getTriggerGenerator */

}//end class LibGenfTreeNodeUiAction

