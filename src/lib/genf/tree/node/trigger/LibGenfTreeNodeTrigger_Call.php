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
class LibGenfTreeNodeTrigger_Call
  extends LibGenfTreeNodeTriggerAction
{
////////////////////////////////////////////////////////////////////////////////
// Attribute
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @var string
   */
  public $type = 'Call';
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function getTargetRefType()
  {
    
    return trim($this->node->target['ref_type']);
    
  }//end public function getTargetRefType */
  
  /**
   * @return string
   */
  public function getService()
  {
    
    return trim($this->node->target['service']);
    
  }//end public function getService */
  
  /**
   * @return string
   */
  public function getTargetType()
  {
    
    return isset($this->node->target['type'])
      ? trim($this->node->target['type'])
      : 'mgmt';
    
  }//end public function getTargetMask */
  
  /**
   * @return string
   */
  public function getTargetMask()
  {
    
    return isset($this->node->target['mgmt'])
      ? trim($this->node->target['mgmt'])
      : null;
    
  }//end public function getTargetMask */

  /**
   * @return string
   */
  public function getTargetSrcField()
  {
    
    return isset($this->node->target['id'])
      ? trim($this->node->target['id'])
      : 'rowid';
    
  }//end public function getTargetSrcField */
  
  /**
   * @return string
   */
  public function getServiceInf()
  {
    
    return isset($this->node->target['service_inf'])
      ? trim($this->node->target['service_inf']).'.php'
      : 'maintab.php';
    
  }//end public function getServiceInf */

  /**
   * @return string
   */
  public function getTargetSourceName( $env )
  {
    
    if( isset( $env->ref ) )
    {
      if( Bdl::MANY_TO_MANY )
      {
        
        if( isset($this->node->target['ref_type']) && 'target' == trim($this->node->target['ref_type']) )
        {
          $targetMgmt = $env->ref->targetManagement();
          return $targetMgmt->name->name;
        }
        else 
        {
          $conMgmt = $env->ref->connectionManagement();
          return $conMgmt->name->name;
        }
        
      }
      else 
      {
        $targetMgmt = $env->ref->targetManagement();
        return $targetMgmt->name->name;
      }
    }
    else 
    {
      return $env->management->name->name;
    }
    
  }//end public function getTargetRefType */
  
  /**
   * @param LibGenfEnvReference $env
   * @return string
   */
  public function getServiceUrl( $env )
  {

    $targetType = $this->getTargetType();
    
    switch ( $targetType )
    {
      case 'mgmt':
      {
        return $this->getServiceUrlMgmt( $env );
      }
      case 'action':
      {
        return $this->getServiceUrlAction( $env );
      }
      default:
      {
        throw LibGenf_Exception( 'got invalid trigger type '.$targetType );
      }
    }

  }//end public function getServiceUrl */
  
  /**
   * @param LibGenfEnvReference $env
   * @return string
   */
  protected function getServiceUrlMgmt( $env )
  {
    
    if( isset( $this->node->target['service'] ) )
      $service = trim($this->node->target['service']);
    else 
      $service = 'show';

    if( isset( $this->node->target['mgmt'] ) )
    {
      $mgmt = $this->builder->getManagement(trim($this->node->target['mgmt']));
      return $mgmt->name->ucfirst('module').'.'
        .$mgmt->name->ucfirst('model').'.'.$service;
    }
    
    if( isset( $env->ref ) )
    {
      if( Bdl::MANY_TO_MANY )
      {
        
        if( isset($this->node->target['ref_type']) && 'target' == trim($this->node->target['ref_type']) )
        {
          $targetMgmt = $env->ref->targetManagement();
          
          return $targetMgmt->name->ucfirst('module').'.'
            .$targetMgmt->name->ucfirst('model').'.'.$service;
        }
        else 
        {
          $conMgmt = $env->ref->connectionManagement();
          
          return $conMgmt->name->ucfirst('module').'.'
            .$conMgmt->name->ucfirst('model').'.'.$service;
        }
        
      }
      else 
      {
        
        $targetMgmt = $env->ref->targetManagement();
        
        return $targetMgmt->name->ucfirst('module').'.'
          .$targetMgmt->name->ucfirst('model').'.'.$service;
        
      }
      
    }
    else 
    {
      return $env->management->name->ucfirst('module').'.'
        .$env->management->name->ucfirst('model').'.'.$service;
    }
    
  }//end protected function getServiceUrlMgmt */
  
  /**
   * @param LibGenfEnvReference $env
   * @return string
   */
  protected function getServiceUrlAction( $env )
  {
    
    if( isset( $this->node->target['service'] ) )
      $service = trim($this->node->target['service']);
    else 
      $service = 'call';

    if( isset( $this->node->target['action'] ) )
    {
      $actionNode = $this->builder->getAction( trim($this->node->target['action']) );

      return $actionNode->name->module.'.'
        .$actionNode->name->model.'_Action.'.$service;
    }
    
   
    
  }//end protected function getServiceUrlAction */
  
////////////////////////////////////////////////////////////////////////////////
// Generator getter
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @return LibGeneratorWbfTrigger_Call
   */
  public function getTriggerGenerator( $env )
  {
    
    return $this->builder->getGenerator( 'Trigger_Call', $env );
    
  }//end public function getTriggerGenerator */

}//end class LibGenfTreeNodeTrigger_Call

