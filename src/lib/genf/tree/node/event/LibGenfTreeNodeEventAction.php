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
class LibGenfTreeNodeEventAction
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var LibGenfNameActionTrigger
   */
  public $name = null;
  
  /**
   * @var LibGenfEventInterface
   */
  public $interface = null;
  
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////
  
  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameActionTrigger( $this->node );
    
    if( isset( $this->node->interface ) )
    {
      
      $type = trim($this->node->interface['type']);
      $infClass = 'LibGenfTreeNodeEventInterface'.SParserString::subToCamelCase( $type );
    
      if( Webfrap::classLoadable( $infClass ) )
      {
        $this->interface = new $infClass( $this->node );
      }
      else 
      {
        $this->builder->error( "Requested non implemented interface type: ".$type );
      }
    
    }

  }//end protected function loadChilds */

  /**
   * Es gibt eine reihe verschiedener Interface Implementierungen
   * Je nach Action machen jeweils andere Parameter sinn
   * 
   * Deshalb gib es die Möglichkeit einen eigenen Interface Type zu implementieren
   * 
   * Je nach Location können auch unterschiedliche Interface Typen nötig sein
   * Der Generator wird den jeweils passenden wählen, es sei denn es wird expliziet
   * eine Abweichung vom Standard übergeben
   * 
   * Vorsicht, die wahl des Falschen Interfaces führt zu nicht lauffähigem Code
   * 
   * @return string
   */
  public function getIntefaceType()
  {
    
    if( isset( $this->node->interface['type'] ) )
    {
      return ucfirst(trim( $this->node->interface['type'] ));
    }
    else 
    {
      null;
    }
    
  }//end public function getIntefaceType */
  
  /**
   * Mit der Call Action wird 
   * 
   * @return string
   */
  public function getCallAction()
  {
    
    if( isset( $this->node->call['method'] ) )
    {
      return trim( $this->node->call['method'] );
    }
    else 
    {
      null;
    }
    
  }//end public function getCallAction */
  
  /**
   * Die Möglichkeit Code direkt in actions zu packen
   * 
   * @return string
   */
  public function getCode()
  {
    
    if( isset( $this->node->code ) )
    {
      return trim( $this->node->code );
    }
    else 
    {
      null;
    }
    
  }//end public function getCode */

}//end class LibGenfTreeNodeEventAction

